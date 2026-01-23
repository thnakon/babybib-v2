<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Folder;
use App\Models\Project;
use Illuminate\Support\Facades\Auth;

class FolderController extends Controller
{
    /**
     * Store a newly created folder in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'project_id' => 'required|exists:projects,id',
            'name' => 'required|string|max:255',
            'color' => 'nullable|string|max:20',
        ]);

        $project = Project::findOrFail($validated['project_id']);
        $this->authorize('update', $project);

        $folder = new Folder($validated);
        $folder->user_id = Auth::id();
        $folder->save();

        return back()->with('success', 'Folder created successfully.');
    }

    /**
     * Update the specified folder in storage.
     */
    public function update(Request $request, Folder $folder)
    {
        $this->authorize('update', $folder);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $folder->update($validated);

        return back()->with('success', 'Folder updated successfully.');
    }

    /**
     * Remove the specified folder from storage.
     */
    public function destroy(Folder $folder)
    {
        $this->authorize('delete', $folder);
        $folder->delete();

        return back()->with('success', 'Folder deleted successfully.');
    }
}
