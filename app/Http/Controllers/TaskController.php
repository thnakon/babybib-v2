<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Notifications\ProjectActivityNotification;
use App\Models\User;

class TaskController extends Controller
{
    use \App\Traits\NotifiesProjectMembers;
    public function store(Request $request, Project $project)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'nullable|string',
            'priority' => 'nullable|string',
            'due_date' => 'nullable|date',
            'assigned_to' => 'nullable|exists:users,id',
        ]);

        $maxPosition = $project->tasks()->where('status', $validated['status'] ?? 'todo')->max('position');
        $validated['position'] = $maxPosition + 1;

        $task = $project->tasks()->create($validated);

        $this->notifyMembers($project, Auth::user(), Auth::user()->name . " created a new task: " . $task->title, 'task_created');

        if ($task->assigned_to && $task->assigned_to !== Auth::id()) {
            $assignee = User::find($task->assigned_to);
            if ($assignee) {
                $assignee->notify(new ProjectActivityNotification($project, Auth::user(), "You have been assigned to task: " . $task->title, 'task_assigned'));
            }
        }

        return back()->with('success', 'Task created successfully.');
    }

    public function update(Request $request, Task $task)
    {
        $validated = $request->validate([
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'status' => 'nullable|string',
            'priority' => 'nullable|string',
            'due_date' => 'nullable|date',
            'assigned_to' => 'nullable|exists:users,id',
        ]);

        $task->update($validated);

        // Update project progress
        $task->project->update(['progress' => $task->project->calculateProgress()]);

        return back()->with('success', 'Task updated successfully.');
    }

    public function reorder(Request $request, Project $project)
    {
        $validated = $request->validate([
            'tasks' => 'required|array',
            'tasks.*.id' => 'required|exists:tasks,id',
            'tasks.*.position' => 'required|integer',
            'tasks.*.status' => 'required|string',
        ]);

        foreach ($validated['tasks'] as $item) {
            Task::where('id', $item['id'])->update([
                'position' => $item['position'],
                'status' => $item['status']
            ]);
        }

        // Update progress just in case status changed
        $project->update(['progress' => $project->calculateProgress()]);

        return response()->json(['success' => true]);
    }


    public function show(Task $task)
    {
        $task->load([
            'checklists',
            'attachments.user',
            'activities.user',
            'comments.user',
            'assignee'
        ]);
        return response()->json($task);
    }

    public function storeChecklist(Request $request, Task $task)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
        ]);

        $maxPos = $task->checklists()->max('position') ?? 0;

        $task->checklists()->create([
            'title' => $validated['title'],
            'position' => $maxPos + 1,
            'is_completed' => false,
        ]);

        return back()->with('success', 'Checklist item added.');
    }

    public function updateChecklist(Request $request, \App\Models\TaskChecklist $checklist)
    {
        $validated = $request->validate([
            'title' => 'nullable|string',
            'is_completed' => 'nullable|boolean',
        ]);

        $checklist->update($validated);

        return back()->with('success', 'Checklist updated.');
    }

    public function destroyChecklist(\App\Models\TaskChecklist $checklist)
    {
        $checklist->delete();
        return back()->with('success', 'Checklist item deleted.');
    }

    public function uploadFile(Request $request, Task $task)
    {
        $request->validate([
            'file' => 'required|file|max:10240',
        ]);

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $project = $task->project;
            $path = $file->store("projects/{$project->id}/tasks/{$task->id}/files", 'public');

            $project->files()->create([
                'task_id' => $task->id,
                'user_id' => Auth::id(),
                'name' => $file->getClientOriginalName(),
                'path' => $path,
                'size' => $file->getSize(),
                'extension' => $file->getClientOriginalExtension(),
            ]);

            $this->notifyMembers($project, Auth::user(), Auth::user()->name . " attached a file to task: " . $task->title, 'task_file_upload');
        }

        return back()->with('success', 'File attached to task.');
    }

    public function destroy(Task $task)
    {
        $project = $task->project;
        $taskTitle = $task->title;
        $task->delete();

        // Update project progress
        $project->update(['progress' => $project->calculateProgress()]);

        $this->notifyMembers($project, Auth::user(), Auth::user()->name . " deleted task: " . $taskTitle, 'task_deleted');

        return back()->with('success', 'Task deleted successfully.');
    }
}
