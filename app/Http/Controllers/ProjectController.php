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
    /**
     * Display a listing of the user's projects.
     */
    public function index(): Response
    {
        $projects = Project::where('user_id', Auth::id())
            ->withCount(['references' => function ($q) {
                $q->whereDoesntHave('folders');
            }])
            ->orderBy('sort_order', 'asc')
            ->get();

        return Inertia::render('projects/index', [
            'projects' => $projects,
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
        ]);

        $validated['user_id'] = Auth::id();

        $project = Project::create($validated);

        return back()
            ->with('success', 'Project created successfully.');
    }

    /**
     * Display the specified project.
     */
    public function show(Project $project): Response
    {
        $this->authorize('view', $project);

        $project->load('references');

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
        $this->authorize('update', $project);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'citation_style' => 'nullable|string|max:50',
            'color' => 'nullable|string|max:20',
            'icon' => 'nullable|string|max:50',
        ]);

        $project->update($validated);

        return back()->with('success', 'Project updated successfully.');
    }

    /**
     * Remove the specified project.
     */
    public function destroy(Project $project)
    {
        $this->authorize('delete', $project);

        $project->references()->detach();
        $project->delete();

        return redirect()->route('references.index')
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

        $project->references()->detach($validated['reference_id']);

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
}
