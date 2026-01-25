<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Reference;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class ProjectController extends Controller
{
    use \App\Traits\NotifiesProjectMembers;
    /**
     * Display a listing of the user's projects.
     */
    public function index(): Response
    {
        $referencesCount = Reference::where('user_id', Auth::id())->count();
        $projects = Project::where('user_id', Auth::id())
            ->withCount(['references', 'tasks', 'members', 'files'])
            ->orderBy('sort_order', 'asc')
            ->get();

        $projectsCount = $projects->count();
        $foldersCount = \App\Models\Folder::whereHas('project', function ($q) {
            $q->where('user_id', Auth::id());
        })->count();

        // Consistent calculation with ReferenceController
        $storageUsed = ($referencesCount * 0.54) + ($projectsCount * 0.5) + ($foldersCount * 0.5);
        $storageLimit = 200;

        return Inertia::render('projects/index', [
            'projects' => $projects,
            'storageUsage' => [
                'used' => round($storageUsed, 2),
                'limit' => $storageLimit,
                'percentage' => min(100, round(($storageUsed / $storageLimit) * 100, 1)),
            ],
        ]);
    }

    /**
     * Show the form for creating a new project.
     */
    public function create(): Response
    {
        return Inertia::render('projects/create');
    }

    /**
     * Store a newly created project.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'citation_style' => 'nullable|string|max:50',
            'color' => 'nullable|string|max:20',
            'icon' => 'nullable|string|max:50',
            'status' => 'nullable|string|in:planning,active,completed,on_hold',
            'priority' => 'nullable|string|in:low,medium,high,urgent',
            'due_date' => 'nullable|date',
            'visibility' => 'nullable|string|in:private,team',
        ]);

        $validated['user_id'] = Auth::id();
        $validated['invite_token'] = \Illuminate\Support\Str::random(32);

        $project = Project::create($validated);

        // Add creator as owner member
        $project->members()->create([
            'user_id' => Auth::id(),
            'role' => 'owner',
            'status' => 'accepted',
        ]);

        return redirect()->route('projects.index')
            ->with('success', 'Project created successfully.');
    }

    /**
     * Display the specified project.
     */
    public function show(Project $project): Response
    {
        // Check if user is owner or member
        $isMember = $project->members()->where('user_id', Auth::id())->exists();
        if ($project->user_id !== Auth::id() && !$isMember) {
            return abort(403);
        }

        $project->load(['references', 'tasks.assignee', 'members.user', 'files.user', 'comments.user']);

        $availableReferences = Reference::where('user_id', Auth::id())
            ->whereNotIn('id', $project->references->pluck('id'))
            ->get();

        return Inertia::render('projects/show', [
            'project' => $project,
            'availableReferences' => $availableReferences,
        ]);
    }

    /**
     * Show the form for editing the specified project.
     */
    public function edit(Project $project): Response
    {
        $this->authorize('update', $project);

        return Inertia::render('projects/edit', [
            'project' => $project,
        ]);
    }

    /**
     * Update the specified project.
     */
    public function update(Request $request, Project $project)
    {
        // $this->authorize('update', $project);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'citation_style' => 'nullable|string|max:50',
            'color' => 'nullable|string|max:20',
            'icon' => 'nullable|string|max:50',
            'status' => 'nullable|string|in:planning,active,completed,on_hold',
            'priority' => 'nullable|string|in:low,medium,high,urgent',
            'due_date' => 'nullable|date',
            'visibility' => 'nullable|string|in:private,team',
            'progress' => 'nullable|integer|min:0|max:100',
        ]);

        $project->update($validated);

        return back()->with('success', 'Project updated successfully.');
    }

    /**
     * Remove the specified project.
     */
    public function destroy(Project $project)
    {
        // $this->authorize('delete', $project);

        $project->references()->detach();
        $project->delete();

        return redirect()->route('projects.index')
            ->with('success', 'Project deleted successfully.');
    }

    /**
     * Add a reference to the project.
     */
    public function addReference(Request $request, Project $project)
    {
        $this->authorize('update', $project);

        $validated = $request->validate([
            'reference_id' => 'required|integer|exists:references,id',
        ]);

        $reference = Reference::where('id', $validated['reference_id'])
            ->where('user_id', Auth::id())
            ->first();

        if ($reference) {
            $project->references()->syncWithoutDetaching([$reference->id]);
            $this->notifyMembers($project, Auth::user(), Auth::user()->name . " added reference: " . $reference->title, 'reference_added');
        }

        return back()->with('success', 'Reference added to project.');
    }

    /**
     * Remove a reference from the project.
     */
    public function removeReference(Request $request, Project $project)
    {
        $this->authorize('update', $project);

        $validated = $request->validate([
            'reference_id' => 'required|integer|exists:references,id',
        ]);

        $reference = Reference::find($validated['reference_id']);
        $project->references()->detach($validated['reference_id']);

        if ($reference) {
            $this->notifyMembers($project, Auth::user(), Auth::user()->name . " removed reference: " . $reference->title, 'reference_removed');
        }

        return back()->with('success', 'Reference removed from project.');
    }

    /**
     * Reorder projects.
     */
    public function reorder(Request $request)
    {
        $validated = $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'required|integer|exists:projects,id',
        ]);

        foreach ($validated['ids'] as $index => $id) {
            Project::where('id', $id)
                ->where('user_id', Auth::id())
                ->update(['sort_order' => $index]);
        }

        return back()->with('success', 'Projects reordered.');
    }

    /**
     * Display projects shared with the user.
     */
    public function teamSpaces(): Response
    {
        $projects = Project::whereHas('members', function ($q) {
            $q->where('user_id', Auth::id())
                ->where('role', '!=', 'owner');
        })
            ->with(['user', 'members' => function ($q) {
                $q->where('user_id', Auth::id());
            }])
            ->withCount(['references', 'tasks', 'members', 'files'])
            ->get()
            ->map(function ($project) {
                $membership = $project->members->first();
                $project->membership_status = $membership ? $membership->status : 'none';
                return $project;
            });

        return Inertia::render('team-spaces/index', [
            'projects' => $projects,
        ]);
    }

    public function acceptInvitation(Project $project)
    {
        $project->members()
            ->where('user_id', Auth::id())
            ->where('status', 'pending')
            ->update(['status' => 'accepted']);

        $this->notifyMembers($project, Auth::user(), Auth::user()->name . " has joined the project!", 'member_joined');

        return back()->with('success', "ยินดีต้อนรับสู่โปรเจกต์ '{$project->name}'! คุณได้เข้าร่วมเป็นส่วนหนึ่งของทีมเรียบร้อยแล้ว");
    }

    public function declineInvitation(Project $project)
    {
        $project->members()
            ->where('user_id', Auth::id())
            ->where('status', 'pending')
            ->delete();

        return back()->with('success', "Invitation declined.");
    }
}
