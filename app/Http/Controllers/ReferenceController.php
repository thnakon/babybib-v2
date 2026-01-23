<?php

namespace App\Http\Controllers;

use App\Models\Reference;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

use App\Models\Project;
use App\Services\CitationFormatter;

class ReferenceController extends Controller
{
    protected CitationFormatter $formatter;

    public function __construct(CitationFormatter $formatter)
    {
        $this->formatter = $formatter;
    }

    /**
     * Display a listing of the user's references.
     */
    public function index(Request $request): Response
    {
        $projectId = $request->query('project_id');
        $folderId = $request->query('folder_id');
        $style = $request->query('style', 'apa7');

        $query = Reference::where('user_id', Auth::id());

        if ($request->filled('folder_id')) {
            $folderId = $request->input('folder_id');
            $query->whereHas('folders', function ($q) use ($folderId) {
                $q->where('folders.id', $folderId);
            });
        } elseif ($request->filled('project_id')) {
            $projectId = $request->input('project_id');
            $query->whereHas('projects', function ($q) use ($projectId) {
                $q->where('projects.id', $projectId);
            })->whereDoesntHave('folders');
        }

        // Log::info($query->toSql()); 
        // Log::info($query->getBindings());

        $references = $query->orderBy('sort_order', 'asc')
            ->orderBy('year', 'asc')
            ->orderBy('year_suffix', 'asc')
            ->orderBy('created_at', 'desc')
            ->get();

        // Group references by author and year to auto-generate suffixes if missing
        $authorYearGroups = [];
        $references->each(function ($ref) use (&$authorYearGroups) {
            $authorKey = is_array($ref->authors) ? implode('|', $ref->authors) : ($ref->authors ?? '');
            $yearKey = $ref->year ?? 'n.d.';
            $groupKey = $authorKey . '_' . $yearKey;

            if (!isset($authorYearGroups[$groupKey])) {
                $authorYearGroups[$groupKey] = [];
            }
            $authorYearGroups[$groupKey][] = $ref;
        });

        // Apply automatic suffixes if multiple references in same group
        $thaiSuffixes = ['ก', 'ข', 'ค', 'ง', 'จ', 'ฉ', 'ช', 'ซ', 'ฌ', 'ญ', 'ฎ', 'ฏ', 'ฐ', 'ฑ', 'ฒ', 'ณ', 'ด', 'ต', 'ถ', 'ท', 'ธ', 'น', 'บ', 'ป', 'ผ', 'ฝ', 'พ', 'ฟ', 'ภ', 'ม', 'ย', 'ร', 'ล', 'ว', 'ศ', 'ษ', 'ส', 'ห', 'ฬ', 'อ', 'ฮ'];
        $engSuffixes = range('a', 'z');

        foreach ($authorYearGroups as $groupKey => $group) {
            if (count($group) > 1) {
                foreach ($group as $index => $ref) {
                    // Only auto-suffix if not manually set
                    if (empty($ref->year_suffix)) {
                        $isThaiRef = $this->isThaiReference($ref);
                        $ref->year_suffix = $isThaiRef
                            ? ($thaiSuffixes[$index] ?? '')
                            : ($engSuffixes[$index] ?? '');
                    }
                }
            }
        }

        // Add formatted citations
        $references->transform(function ($reference) use ($style) {
            $reference->citation = $this->formatter->format($reference, $style);
            $reference->citation_in_text = $this->formatter->formatInText($reference, $style);
            return $reference;
        });

        $projects = Project::where('user_id', Auth::id())
            ->with(['folders' => function ($q) {
                $q->withCount('references');
            }])
            ->withCount(['references' => function ($q) {
                $q->whereDoesntHave('folders');
            }])
            ->orderBy('sort_order', 'asc')
            ->get();

        return Inertia::render('references/index', [
            'references' => $references,
            'projects' => $projects,
            'selectedProjectId' => $projectId ? (int)$projectId : null,
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
            'year_suffix' => 'nullable|string|max:10',
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
            'year_suffix' => 'nullable|string|max:10',
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

        return back()->with('success', 'Reference deleted successfully.');
    }

    /**
     * Reorder references.
     */
    public function reorder(Request $request)
    {
        $validated = $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'required|integer|exists:references,id',
        ]);

        foreach ($validated['ids'] as $index => $id) {
            Reference::where('id', $id)
                ->where('user_id', Auth::id())
                ->update(['sort_order' => $index]);
        }

        return back()->with('success', 'References reordered.');
    }
    /**
     * Check if a reference should be treated as Thai.
     */
    private function isThaiReference(Reference $reference): bool
    {
        // 1. Check if title has Thai characters
        if (preg_match('/[\x{0E00}-\x{0E7F}]/u', $reference->title)) {
            return true;
        }

        // 2. Check if authors have Thai characters
        if ($reference->authors) {
            foreach ($reference->authors as $author) {
                if (preg_match('/[\x{0E00}-\x{0E7F}]/u', $author)) {
                    return true;
                }
            }
        }

        // 3. Check if publisher or journal name has Thai characters
        if (
            preg_match('/[\x{0E00}-\x{0E7F}]/u', $reference->publisher ?? '') ||
            preg_match('/[\x{0E00}-\x{0E7F}]/u', $reference->journal_name ?? '')
        ) {
            return true;
        }

        return false;
    }
}
