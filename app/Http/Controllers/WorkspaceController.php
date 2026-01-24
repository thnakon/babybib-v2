<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Inertia\Inertia;
use Inertia\Response;

use App\Models\Manuscript;
use App\Models\Reference;
use Illuminate\Support\Facades\Auth;

class WorkspaceController extends Controller
{
    /**
     * Display the integrated workspace.
     */
    public function index(Request $request): Response
    {
        $manuscriptId = $request->query('manuscript_id');

        if ($manuscriptId) {
            $manuscript = Manuscript::where('user_id', Auth::id())->findOrFail($manuscriptId);
        } else {
            // Get most recent or create a default one
            $manuscript = Manuscript::where('user_id', Auth::id())->latest()->first();

            if (!$manuscript) {
                $manuscript = Manuscript::create([
                    'user_id' => Auth::id(),
                    'title' => 'Untitled Manuscript',
                    'content' => '<h1>Untitled Manuscript</h1><p>Start writing your research paper here...</p>',
                ]);
            }
        }

        $references = Reference::where('user_id', Auth::id())->get();
        $manuscripts = Manuscript::where('user_id', Auth::id())->latest()->get();

        return Inertia::render('workspace/index', [
            'manuscript' => $manuscript,
            'manuscripts' => $manuscripts,
            'references' => $references,
        ]);
    }

    /**
     * Create a new manuscript.
     */
    public function store(Request $request)
    {
        $manuscript = Manuscript::create([
            'user_id' => Auth::id(),
            'title' => $request->title ?: 'Untitled Manuscript',
            'content' => $request->content ?: '<h1>' . ($request->title ?: 'Untitled Manuscript') . '</h1><p>Start writing your research paper here...</p>',
        ]);

        return redirect()->route('workspace.index', ['manuscript_id' => $manuscript->id])
            ->with('success', 'New manuscript created');
    }

    /**
     * Delete a manuscript.
     */
    public function destroy(Manuscript $manuscript)
    {
        if ($manuscript->user_id !== Auth::id()) {
            abort(403);
        }

        $manuscript->delete();

        return redirect()->route('workspace.index')->with('success', 'Manuscript deleted');
    }

    /**
     * Autosave manuscript content.
     */
    public function update(Request $request, Manuscript $manuscript)
    {
        if ($manuscript->user_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'title' => 'nullable|string|max:255',
            'content' => 'nullable|string',
        ]);

        $manuscript->update($validated);

        return response()->json([
            'success' => true,
            'updated_at' => $manuscript->updated_at->toDateTimeString(),
        ]);
    }

    /**
     * Export manuscript as PDF.
     */
    public function exportPdf(Manuscript $manuscript)
    {
        if ($manuscript->user_id !== Auth::id()) abort(403);

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadHTML("
            <style>
                @import url('https://fonts.googleapis.com/css2?family=Sarabun&display=swap');
                body { font-family: 'Sarabun', sans-serif; padding: 40px; line-height: 1.6; }
                h1 { text-align: center; }
                hr { border: none; page-break-after: always; height: 0; margin: 0; }
            </style>
            {$manuscript->content}
        ")->setOption('isRemoteEnabled', true);

        return $pdf->download(str_replace(' ', '_', $manuscript->title) . '.pdf');
    }

    /**
     * Export manuscript as Word (.docx).
     */
    public function exportWord(Manuscript $manuscript)
    {
        if ($manuscript->user_id !== Auth::id()) abort(403);

        $phpWord = new \PhpOffice\PhpWord\PhpWord();
        $section = $phpWord->addSection();

        // Convert HTML content to Word
        \PhpOffice\PhpWord\Shared\Html::addHtml($section, $manuscript->content, false, false);

        $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');

        $fileName = str_replace(' ', '_', $manuscript->title) . '.docx';
        $tempFile = tempnam(sys_get_temp_dir(), 'word_');
        $objWriter->save($tempFile);

        return response()->download($tempFile, $fileName)->deleteFileAfterSend(true);
    }
}
