<?php

namespace App\Http\Controllers;

use App\Models\Reference;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class ReferenceController extends Controller
{
    /**
     * Display a listing of the user's references.
     */
    public function index(): Response
    {
        $references = Reference::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return Inertia::render('references/index', [
            'references' => $references,
        ]);
    }

    /**
     * Show the form for creating a new reference.
     */
    public function create(): Response
    {
        return Inertia::render('references/create');
    }

    /**
     * Store a newly created reference in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:500',
            'authors' => 'nullable|array',
            'authors.*' => 'string|max:255',
            'type' => 'required|in:book,journal,website,conference,thesis,report,other',
            'year' => 'nullable|string|max:10',
            'doi' => 'nullable|string|max:255',
            'isbn' => 'nullable|string|max:30',
            'url' => 'nullable|url|max:500',
            'publisher' => 'nullable|string|max:255',
            'journal_name' => 'nullable|string|max:255',
            'volume' => 'nullable|string|max:50',
            'issue' => 'nullable|string|max:50',
            'pages' => 'nullable|string|max:50',
            'edition' => 'nullable|string|max:50',
            'abstract' => 'nullable|string|max:5000',
            'notes' => 'nullable|string|max:2000',
            'tags' => 'nullable|array',
        ]);

        $validated['user_id'] = Auth::id();

        Reference::create($validated);

        return redirect()->route('references.index')
            ->with('success', 'Reference created successfully.');
    }

    /**
     * Display the specified reference.
     */
    public function show(Reference $reference): Response
    {
        $this->authorize('view', $reference);

        return Inertia::render('references/show', [
            'reference' => $reference,
        ]);
    }

    /**
     * Show the form for editing the specified reference.
     */
    public function edit(Reference $reference): Response
    {
        $this->authorize('update', $reference);

        return Inertia::render('references/edit', [
            'reference' => $reference,
        ]);
    }

    /**
     * Update the specified reference in storage.
     */
    public function update(Request $request, Reference $reference)
    {
        $this->authorize('update', $reference);

        $validated = $request->validate([
            'title' => 'required|string|max:500',
            'authors' => 'nullable|array',
            'authors.*' => 'string|max:255',
            'type' => 'required|in:book,journal,website,conference,thesis,report,other',
            'year' => 'nullable|string|max:10',
            'doi' => 'nullable|string|max:255',
            'isbn' => 'nullable|string|max:30',
            'url' => 'nullable|url|max:500',
            'publisher' => 'nullable|string|max:255',
            'journal_name' => 'nullable|string|max:255',
            'volume' => 'nullable|string|max:50',
            'issue' => 'nullable|string|max:50',
            'pages' => 'nullable|string|max:50',
            'edition' => 'nullable|string|max:50',
            'abstract' => 'nullable|string|max:5000',
            'notes' => 'nullable|string|max:2000',
            'tags' => 'nullable|array',
        ]);

        $reference->update($validated);

        return redirect()->route('references.show', $reference)
            ->with('success', 'Reference updated successfully.');
    }

    /**
     * Remove the specified reference from storage.
     */
    public function destroy(Reference $reference)
    {
        $this->authorize('delete', $reference);

        $reference->delete();

        return redirect()->route('references.index')
            ->with('success', 'Reference deleted successfully.');
    }
}
