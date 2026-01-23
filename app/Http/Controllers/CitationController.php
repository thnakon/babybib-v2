<?php

namespace App\Http\Controllers;

use App\Models\Reference;
use App\Services\CitationFormatter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class CitationController extends Controller
{
    protected CitationFormatter $formatter;

    public function __construct(CitationFormatter $formatter)
    {
        $this->formatter = $formatter;
    }

    /**
     * Show citation generator page.
     */
    public function index(): Response
    {
        $references = Reference::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();

        return Inertia::render('citations/index', [
            'references' => $references,
            'styles' => $this->formatter->getStyles(),
        ]);
    }

    /**
     * Format a single reference.
     */
    public function format(Request $request)
    {
        $request->validate([
            'reference_id' => 'required|integer|exists:references,id',
            'style' => 'required|string|in:apa7,mla9,chicago,ieee,harvard,thai-cu,thai-tu,thai-mu',
        ]);

        $reference = Reference::findOrFail($request->reference_id);
        $this->authorize('view', $reference);

        $citation = $this->formatter->format($reference, $request->style);

        return response()->json([
            'success' => true,
            'citation' => $citation,
            'style' => $request->style,
        ]);
    }

    /**
     * Format multiple references as a bibliography.
     */
    public function bibliography(Request $request)
    {
        $request->validate([
            'reference_ids' => 'required|array',
            'reference_ids.*' => 'integer|exists:references,id',
            'style' => 'required|string|in:apa7,mla9,chicago,ieee,harvard,thai-cu,thai-tu,thai-mu',
        ]);

        $references = Reference::whereIn('id', $request->reference_ids)
            ->where('user_id', Auth::id())
            ->get();

        $citations = [];
        foreach ($references as $reference) {
            $citations[] = [
                'id' => $reference->id,
                'title' => $reference->title,
                'citation' => $this->formatter->format($reference, $request->style),
            ];
        }

        // Sort alphabetically by citation
        usort($citations, fn($a, $b) => strcmp(
            strip_tags($a['citation']),
            strip_tags($b['citation'])
        ));

        return response()->json([
            'success' => true,
            'citations' => $citations,
            'style' => $request->style,
            'count' => count($citations),
        ]);
    }

    /**
     * Get available citation styles.
     */
    public function styles()
    {
        return response()->json([
            'success' => true,
            'styles' => $this->formatter->getStyles(),
        ]);
    }

    /**
     * Format reference data directly (without saving).
     */
    public function preview(Request $request)
    {
        $request->validate([
            'title' => 'required|string',
            'style' => 'required|string|in:apa7,mla9,chicago,ieee,harvard,thai-cu,thai-tu,thai-mu',
        ]);

        $citation = $this->formatter->formatArray($request->all(), $request->style);

        return response()->json([
            'success' => true,
            'citation' => $citation,
            'style' => $request->style,
        ]);
    }
}
