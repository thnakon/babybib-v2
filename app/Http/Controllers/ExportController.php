<?php

namespace App\Http\Controllers;

use App\Models\Reference;
use App\Models\Project;
use App\Models\Folder;
use App\Services\BibtexExporter;
use App\Services\RisExporter;
use App\Services\CitationFormatter;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ExportController extends Controller
{
    protected BibtexExporter $bibtexExporter;
    protected RisExporter $risExporter;
    protected CitationFormatter $citationFormatter;

    public function __construct(
        BibtexExporter $bibtexExporter,
        RisExporter $risExporter,
        CitationFormatter $citationFormatter
    ) {
        $this->bibtexExporter = $bibtexExporter;
        $this->risExporter = $risExporter;
        $this->citationFormatter = $citationFormatter;
    }

    /**
     * Export selected references to BibTeX.
     */
    public function bibtex(Request $request)
    {
        $references = $this->getReferencesFromRequest($request);

        if ($references->isEmpty()) {
            return back()->with('error', 'No references found');
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
        $references = $this->getReferencesFromRequest($request);

        if ($references->isEmpty()) {
            return back()->with('error', 'No references found');
        }

        $export = $this->risExporter->exportToFile($references);

        return response($export['content'])
            ->header('Content-Type', $export['mime'])
            ->header('Content-Disposition', 'attachment; filename="' . $export['filename'] . '"');
    }

    /**
     * Export references as Word document (.doc).
     */
    public function word(Request $request)
    {
        $references = $this->getReferencesFromRequest($request);

        if ($references->isEmpty()) {
            return back()->with('error', 'No references found');
        }

        $style = $request->style ?: 'apa7';
        $lang = $request->lang ?: 'en';

        $processedReferences = $this->processAndSortReferences($references, $style);
        $title = $this->getRequestTitle($request);
        $bibliographyTitle = $lang === 'th' ? 'บรรณานุกรม' : 'References';

        // Render the content
        $content = view('exports.bibliography', [
            'references' => $processedReferences,
            'title' => $title,
            'bibliographyTitle' => $bibliographyTitle,
            'style' => $style,
            'lang' => $lang,
        ])->render();

        $filename = str_replace(' ', '_', $title) . '_bibliography.doc';

        return response($content)
            ->header('Content-Type', 'application/msword')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }

    /**
     * Helper to get references based on request parameters.
     */
    private function getReferencesFromRequest(Request $request)
    {
        $query = Reference::where('user_id', Auth::id());

        if ($request->folder_id) {
            $folder = Folder::findOrFail($request->folder_id);
            return $folder->references()->get();
        } elseif ($request->project_id) {
            $project = Project::findOrFail($request->project_id);
            return $project->references()->get();
        } elseif ($request->reference_ids) {
            $ids = is_array($request->reference_ids) ? $request->reference_ids : explode(',', $request->reference_ids);
            return $query->whereIn('id', $ids)->get();
        }

        return $query->get();
    }

    /**
     * Helper to get title for file based on request.
     */
    private function getRequestTitle(Request $request)
    {
        if ($request->folder_id) {
            return Folder::find($request->folder_id)->name;
        } elseif ($request->project_id) {
            return Project::find($request->project_id)->name;
        }
        return 'All References';
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

    /**
     * Export references as PDF.
     */
    public function pdf(Request $request)
    {
        $request->validate([
            'project_id' => 'nullable|integer|exists:projects,id',
            'folder_id' => 'nullable|integer|exists:folders,id',
            'style' => 'nullable|string',
            'lang' => 'nullable|string|in:en,th',
        ]);

        $query = Reference::where('user_id', Auth::id());

        if ($request->folder_id) {
            $folder = Folder::findOrFail($request->folder_id);
            $references = $folder->references()->get();
            $title = $folder->name;
        } elseif ($request->project_id) {
            $project = Project::findOrFail($request->project_id);
            $references = $project->references()->get();
            $title = $project->name;
        } else {
            $references = $query->get();
            $title = 'All References';
        }

        if ($references->isEmpty()) {
            return back()->with('error', 'No references found to export.');
        }

        $style = $request->style ?: 'apa7';
        $lang = $request->lang ?: 'en';

        $processedReferences = $this->processAndSortReferences($references, $style);

        $bibliographyTitle = $lang === 'th' ? 'บรรณานุกรม' : 'References';

        $pdf = Pdf::loadView('exports.bibliography', [
            'references' => $processedReferences,
            'title' => $title,
            'bibliographyTitle' => $bibliographyTitle,
            'style' => $style,
            'lang' => $lang,
        ])->setOption('isRemoteEnabled', true);

        return $pdf->download(str_replace(' ', '_', $title) . '_bibliography.pdf');
    }

    /**
     * Sort references (Thai then English) and assign suffixes for same year.
     */
    private function processAndSortReferences($references, $style)
    {
        $thaiRefs = [];
        $engRefs = [];

        foreach ($references as $ref) {
            if ($this->isThaiReference($ref)) {
                $thaiRefs[] = $ref;
            } else {
                $engRefs[] = $ref;
            }
        }

        // Sort each group
        $thaiRefs = $this->sortGroup($thaiRefs);
        $engRefs = $this->sortGroup($engRefs);

        // Assign suffixes within each group
        $this->assignSuffixes($thaiRefs, 'thai');
        $this->assignSuffixes($engRefs, 'eng');

        // Combine and format
        $combined = array_merge($thaiRefs, $engRefs);

        foreach ($combined as $ref) {
            $ref->formatted_citation = $this->citationFormatter->format($ref, $style);
        }

        return $combined;
    }

    private function isThaiReference($ref)
    {
        $text = $ref->title . ($ref->authors ? implode(' ', $ref->authors) : '');
        return preg_match('/[\x{0E00}-\x{0E7F}]/u', $text);
    }

    private function sortGroup($group)
    {
        usort($group, function ($a, $b) {
            // Sort by first author name
            $authorA = $a->authors[0] ?? '';
            $authorB = $b->authors[0] ?? '';

            $cmp = strcmp($authorA, $authorB);
            if ($cmp !== 0) return $cmp;

            // Sort by year
            return strcmp($a->year ?? '', $b->year ?? '');
        });

        return $group;
    }

    private function assignSuffixes($group, $langType)
    {
        $suffixes = $langType === 'thai'
            ? ['ก', 'ข', 'ค', 'ง', 'จ', 'ฉ', 'ช', 'ซ', 'ฌ', 'ญ', 'ฎ', 'ฏ', 'ฐ', 'ฑ', 'ฒ', 'ณ', 'ด', 'ต', 'ถ', 'ท', 'ธ', 'น', 'บ', 'ป', 'ผ', 'ฝ', 'พ', 'ฟ', 'ภ', 'ม', 'ย', 'ร', 'ล', 'ว', 'ศ', 'ษ', 'ส', 'ห', 'ฬ', 'อ', 'ฮ']
            : range('a', 'z');

        $counts = [];
        foreach ($group as $ref) {
            $author = $ref->authors[0] ?? 'Unknown';
            $year = $ref->year ?? 'n.d.';
            $key = $author . '|' . $year;

            if (!isset($counts[$key])) {
                $counts[$key] = [];
            }
            $counts[$key][] = $ref;
        }

        foreach ($counts as $key => $refs) {
            if (count($refs) > 1) {
                foreach ($refs as $index => $ref) {
                    $ref->year_suffix = $suffixes[$index] ?? '';
                }
            } else {
                $refs[0]->year_suffix = null;
            }
        }
    }
}
