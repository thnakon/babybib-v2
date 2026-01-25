<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\ProjectMember;
use App\Models\ProjectFile;
use App\Models\ProjectComment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Notifications\ProjectActivityNotification;
use App\Models\User;

class ProjectCollaborationController extends Controller
{
    use \App\Traits\NotifiesProjectMembers;
    // Membership Management
    public function addMember(Request $request, Project $project)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'role' => 'required|string|in:manager,contributor,viewer',
        ]);

        $member = $project->members()->firstOrCreate(
            ['user_id' => $validated['user_id']],
            [
                'role' => $validated['role'],
                'status' => 'pending'
            ]
        );

        // Notify the user
        $user = \App\Models\User::find($validated['user_id']);
        if ($user && $user->id !== Auth::id()) {
            $user->notify(new \App\Notifications\ProjectInvitation($project, Auth::user()));
        }

        return back()->with('success', 'Member added to project.');
    }

    public function removeMember(Request $request, Project $project, ProjectMember $member)
    {
        if ($member->role === 'owner') {
            return back()->with('error', 'Cannot remove owner.');
        }

        $userName = $member->user ? $member->user->name : 'Unknown User';
        $member->delete();

        $this->notifyMembers($project, Auth::user(), Auth::user()->name . " removed {$userName} from the project.", 'member_removed');

        return back()->with('success', 'Member removed.');
    }

    // File Management
    public function uploadFile(Request $request, Project $project)
    {
        $request->validate([
            'file' => 'required|file|max:10240', // 10MB
        ]);

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $path = $file->store("projects/{$project->id}/files", 'public');

            $project->files()->create([
                'user_id' => Auth::id(),
                'name' => $file->getClientOriginalName(),
                'path' => $path,
                'size' => $file->getSize(),
                'extension' => $file->getClientOriginalExtension(),
            ]);

            $this->notifyMembers($project, Auth::user(), Auth::user()->name . " uploaded a file: " . $file->getClientOriginalName(), 'file_upload');
        }

        return back()->with('success', 'File uploaded.');
    }

    public function deleteFile(ProjectFile $file)
    {
        Storage::disk('public')->delete($file->path);
        $file->delete();

        return back()->with('success', 'File deleted.');
    }

    public function addComment(Request $request)
    {
        $validated = $request->validate([
            'commentable_id' => 'required|integer',
            'commentable_type' => 'required|string',
            'content' => 'nullable|string',
            'attachments' => 'nullable|array',
            'attachments.*' => 'file|max:10240', // 10MB each
        ]);

        $storedAttachments = [];
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $path = $file->store('comments/attachments', 'public');
                $storedAttachments[] = [
                    'name' => $file->getClientOriginalName(),
                    'path' => $path,
                    'extension' => $file->getClientOriginalExtension(),
                    'size' => $file->getSize()
                ];
            }
        }

        ProjectComment::create([
            'user_id' => Auth::id(),
            'commentable_id' => $validated['commentable_id'],
            'commentable_type' => $validated['commentable_type'],
            'content' => $validated['content'] ?? '',
            'attachments' => $storedAttachments,
        ]);

        $project = null;
        if ($validated['commentable_type'] === 'App\\Models\\Project') {
            $project = Project::find($validated['commentable_id']);
        } else if ($validated['commentable_type'] === 'App\\Models\\Task') {
            $task = \App\Models\Task::find($validated['commentable_id']);
            $project = $task ? $task->project : null;
        }

        if ($project) {
            $this->notifyMembers($project, Auth::user(), Auth::user()->name . " added a comment: " . mb_substr($validated['content'], 0, 50) . "...", 'comment');
        }

        return back()->with('success', 'Comment added.');
    }

    public function updateComment(Request $request, ProjectComment $comment)
    {
        if ($comment->user_id !== Auth::id()) {
            return abort(403);
        }

        $validated = $request->validate([
            'content' => 'required|string',
        ]);

        $comment->update($validated);

        return back()->with('success', 'Comment updated.');
    }

    public function deleteComment(ProjectComment $comment)
    {
        if ($comment->user_id !== Auth::id()) {
            return abort(403);
        }

        // Optional: delete attachments from storage
        if ($comment->attachments) {
            foreach ($comment->attachments as $att) {
                Storage::disk('public')->delete($att['path']);
            }
        }

        $comment->delete();

        return back()->with('success', 'Comment deleted.');
    }

    public function searchUsers(Request $request)
    {
        $query = $request->input('q');
        if (strlen($query) < 2) return response()->json([]);

        $users = \App\Models\User::where('name', 'like', "%{$query}%")
            ->orWhere('email', 'like', "%{$query}%")
            ->limit(10)
            ->get(['id', 'name', 'email']);

        return response()->json($users);
    }

    public function updateInviteSettings(Request $request, Project $project)
    {
        $validated = $request->validate([
            'invite_role' => 'required|string|in:manager,contributor,viewer',
            'regenerate' => 'nullable|boolean',
        ]);

        $update = ['invite_role' => $validated['invite_role']];

        if ($request->boolean('regenerate') || !$project->invite_token) {
            $update['invite_token'] = \Illuminate\Support\Str::random(32);
        }

        $project->update($update);

        return back()->with('success', 'Invitation settings updated.');
    }

    public function joinByInviteToken(Request $request, $token)
    {
        $project = Project::where('invite_token', $token)->firstOrFail();

        if (!Auth::check()) {
            return redirect()->route('login')->with('url.intended', route('projects.join', $token));
        }

        // Check if user is already a member
        $existingMember = $project->members()->where('user_id', Auth::id())->first();

        if ($existingMember) {
            if ($existingMember->status === 'pending') {
                $existingMember->update(['status' => 'accepted']);
                return redirect()->route('projects.show', $project->id)
                    ->with('success', "You have joined the project '{$project->name}'.");
            }
            return redirect()->route('projects.show', $project->id)
                ->with('info', "You are already a member of this project.");
        }

        // Add user to project
        $project->members()->create([
            'user_id' => Auth::id(),
            'role' => $project->invite_role ?? 'contributor',
            'status' => 'accepted',
        ]);

        return redirect()->route('projects.show', $project->id)
            ->with('success', "You have successfully joined the project '{$project->name}'.");
    }
}
