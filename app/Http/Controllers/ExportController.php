<?php

namespace App\Http\Controllers;

use App\Models\Reference;
use App\Services\BibtexExporter;
use App\Services\RisExporter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ExportController extends Controller
{
    protected BibtexExporter $bibtexExporter;
    protected RisExporter $risExporter;

    public function __construct(BibtexExporter $bibtexExporter, RisExporter $risExporter)
    {
        $this->bibtexExporter = $bibtexExporter;
        $this->risExporter = $risExporter;
    }

    /**
     * Export selected references to BibTeX.
     */
    public function bibtex(Request $request)
    {
        $request->validate([
            'reference_ids' => 'required|array',
            'reference_ids.*' => 'integer|exists:references,id',
        ]);

        $references = Reference::whereIn('id', $request->reference_ids)
            ->where('user_id', Auth::id())
            ->get();

        if ($references->isEmpty()) {
            return response()->json(['error' => 'No references found'], 404);
        }

        $export = $this->bibtexExporter->exportToFile($references);

        return response($export['content'])
            ->header('Content-Type', $export['mime'])
            ->header('Content-Disposition', 'attachment; filename="' . $export['filename'] . '"');
    }

    /**
     * Export selected references to RIS.
     */
    public function ris(Request $request)
    {
        $request->validate([
            'reference_ids' => 'required|array',
            'reference_ids.*' => 'integer|exists:references,id',
        ]);

        $references = Reference::whereIn('id', $request->reference_ids)
            ->where('user_id', Auth::id())
            ->get();

        if ($references->isEmpty()) {
            return response()->json(['error' => 'No references found'], 404);
        }

        $export = $this->risExporter->exportToFile($references);

        return response($export['content'])
            ->header('Content-Type', $export['mime'])
            ->header('Content-Disposition', 'attachment; filename="' . $export['filename'] . '"');
    }

    /**
     * Export all user's references to BibTeX.
     */
    public function allBibtex()
    {
        $references = Reference::where('user_id', Auth::id())->get();

        if ($references->isEmpty()) {
            return response()->json(['error' => 'No references found'], 404);
        }

        $export = $this->bibtexExporter->exportToFile($references);

        return response($export['content'])
            ->header('Content-Type', $export['mime'])
            ->header('Content-Disposition', 'attachment; filename="' . $export['filename'] . '"');
    }

    /**
     * Export all user's references to RIS.
     */
    public function allRis()
    {
        $references = Reference::where('user_id', Auth::id())->get();

        if ($references->isEmpty()) {
            return response()->json(['error' => 'No references found'], 404);
        }

        $export = $this->risExporter->exportToFile($references);

        return response($export['content'])
            ->header('Content-Type', $export['mime'])
            ->header('Content-Disposition', 'attachment; filename="' . $export['filename'] . '"');
    }

    /**
     * Preview BibTeX export (returns content without download).
     */
    public function previewBibtex(Request $request)
    {
        $request->validate([
            'reference_ids' => 'required|array',
            'reference_ids.*' => 'integer|exists:references,id',
        ]);

        $references = Reference::whereIn('id', $request->reference_ids)
            ->where('user_id', Auth::id())
            ->get();

        $content = $this->bibtexExporter->exportCollection($references);

        return response()->json([
            'success' => true,
            'content' => $content,
            'format' => 'bibtex',
            'count' => $references->count(),
        ]);
    }

    /**
     * Preview RIS export (returns content without download).
     */
    public function previewRis(Request $request)
    {
        $request->validate([
            'reference_ids' => 'required|array',
            'reference_ids.*' => 'integer|exists:references,id',
        ]);

        $references = Reference::whereIn('id', $request->reference_ids)
            ->where('user_id', Auth::id())
            ->get();

        $content = $this->risExporter->exportCollection($references);

        return response()->json([
            'success' => true,
            'content' => $content,
            'format' => 'ris',
            'count' => $references->count(),
        ]);
    }
}
