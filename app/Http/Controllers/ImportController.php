<?php

namespace App\Http\Controllers;

use App\Models\Reference;
use App\Services\MetadataFetcher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class ImportController extends Controller
{
    protected MetadataFetcher $metadataFetcher;

    public function __construct(MetadataFetcher $metadataFetcher)
    {
        $this->metadataFetcher = $metadataFetcher;
    }

    /**
     * Show the import page.
     */
    public function index(): Response
    {
        return Inertia::render('references/import');
    }

    /**
     * Lookup metadata from DOI or ISBN.
     */
    public function lookup(Request $request)
    {
        $request->validate([
            'identifier' => 'required|string|max:255',
        ]);

        $identifier = $request->input('identifier');
        $metadata = $this->metadataFetcher->fetch($identifier);

        if (empty($metadata)) {
            return response()->json([
                'success' => false,
                'message' => 'Could not find metadata for the given identifier.',
            ]);
        }

        return response()->json([
            'success' => true,
            'data' => $metadata,
        ]);
    }

    /**
     * Look up and immediately store a reference (Quick Cite).
     */
    public function quickStore(Request $request)
    {
        $request->validate([
            'identifier' => 'required|string|max:255',
        ]);

        $identifier = $request->input('identifier');
        $metadata = $this->metadataFetcher->fetch($identifier);

        if (!$metadata) {
            return back()->withErrors(['error' => 'Could not find metadata for this source.']);
        }

        // Prepare data for creation
        $data = $metadata;
        $data['user_id'] = Auth::id();

        Reference::create($data);

        return back()->with('success', 'Reference added successfully!');
    }

    /**
     * Import reference from lookup data.
     */
    public function importFromLookup(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:500',
            'authors' => 'nullable|array',
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
            'abstract' => 'nullable|string|max:5000',
            'project_id' => 'nullable|exists:projects,id',
            'folder_id' => 'nullable|exists:folders,id',
        ]);

        $validated['user_id'] = Auth::id();
        $projectId = $validated['project_id'] ?? null;
        $folderId = $validated['folder_id'] ?? null;

        // Strip project_id and folder_id from the data used to create the reference
        $referenceData = $validated;
        unset($referenceData['project_id'], $referenceData['folder_id']);

        $reference = Reference::create($referenceData);

        if ($projectId) {
            $reference->projects()->attach($projectId);
        }

        if ($folderId) {
            $reference->folders()->attach($folderId);
        }

        return back()->with('success', 'Reference added successfully!');
    }

    /**
     * Parse BibTeX file content.
     */
    public function parseBibtex(Request $request)
    {
        $request->validate([
            'content' => 'required|string',
        ]);

        $content = $request->input('content');
        $entries = $this->parseBibtexContent($content);

        return response()->json([
            'success' => true,
            'entries' => $entries,
            'count' => count($entries),
        ]);
    }

    /**
     * Import multiple references from parsed BibTeX.
     */
    public function importBibtex(Request $request)
    {
        $request->validate([
            'entries' => 'required|array',
            'entries.*.title' => 'required|string',
            'project_id' => 'nullable|exists:projects,id',
        ]);

        $entries = $request->input('entries');
        $projectId = $request->input('project_id');
        $imported = 0;

        foreach ($entries as $entry) {
            $entry['user_id'] = Auth::id();
            $entry['type'] = $entry['type'] ?? 'other';
            $reference = Reference::create($entry);

            if ($projectId) {
                $reference->projects()->attach($projectId);
            }

            $imported++;
        }

        return response()->json([
            'success' => true,
            'message' => "Successfully imported {$imported} references.",
            'count' => $imported,
        ]);
    }

    /**
     * Parse BibTeX content into array of entries.
     */
    private function parseBibtexContent(string $content): array
    {
        $entries = [];

        // Match BibTeX entries
        preg_match_all('/@(\w+)\s*\{([^,]+),\s*([^@]+)\}/s', $content, $matches, PREG_SET_ORDER);

        foreach ($matches as $match) {
            $type = strtolower($match[1]);
            $fields = $this->parseBibtexFields($match[3]);

            $entry = [
                'title' => $fields['title'] ?? 'Untitled',
                'authors' => isset($fields['author']) ? $this->parseAuthors($fields['author']) : [],
                'type' => $this->mapBibtexType($type),
                'year' => $fields['year'] ?? null,
                'doi' => $fields['doi'] ?? null,
                'isbn' => $fields['isbn'] ?? null,
                'url' => $fields['url'] ?? null,
                'publisher' => $fields['publisher'] ?? null,
                'journal_name' => $fields['journal'] ?? null,
                'volume' => $fields['volume'] ?? null,
                'issue' => $fields['number'] ?? null,
                'pages' => $fields['pages'] ?? null,
                'abstract' => $fields['abstract'] ?? null,
            ];

            $entries[] = $entry;
        }

        return $entries;
    }

    /**
     * Parse BibTeX field key-value pairs.
     */
    private function parseBibtexFields(string $fieldString): array
    {
        $fields = [];

        // Match field = {value} or field = "value"
        preg_match_all('/(\w+)\s*=\s*[{"]([^}"]+)[}"]/', $fieldString, $matches, PREG_SET_ORDER);

        foreach ($matches as $match) {
            $key = strtolower(trim($match[1]));
            $value = trim($match[2]);
            $fields[$key] = $value;
        }

        return $fields;
    }

    /**
     * Parse author string into array.
     */
    private function parseAuthors(string $authorString): array
    {
        // BibTeX authors are separated by "and"
        $authors = preg_split('/\s+and\s+/i', $authorString);
        return array_map('trim', $authors);
    }

    /**
     * Map BibTeX type to our reference types.
     */
    private function mapBibtexType(string $type): string
    {
        $map = [
            'article' => 'journal',
            'book' => 'book',
            'inbook' => 'book',
            'incollection' => 'book',
            'conference' => 'conference',
            'inproceedings' => 'conference',
            'proceedings' => 'conference',
            'mastersthesis' => 'thesis',
            'phdthesis' => 'thesis',
            'techreport' => 'report',
            'misc' => 'other',
            'unpublished' => 'other',
            'online' => 'website',
        ];

        return $map[$type] ?? 'other';
    }
}
