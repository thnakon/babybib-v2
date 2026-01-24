<?php

use App\Http\Controllers\CitationController;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\FolderController;
use App\Http\Controllers\ImportController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ReferenceController;
use App\Http\Controllers\WorkspaceController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Laravel\Fortify\Features;

Route::get('/', function () {
    return Inertia::render('welcome', [
        'canRegister' => Features::enabled(Features::registration()),
    ]);
})->name('home');

Route::get('/help', function () {
    return Inertia::render('help');
})->name('help');

Route::get('/features', function () {
    return Inertia::render('features');
})->name('features');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', function () {
        return Inertia::render('dashboard');
    })->name('dashboard');

    // Reference Management
    Route::resource('references', ReferenceController::class);
    Route::post('references/reorder', [ReferenceController::class, 'reorder'])->name('references.reorder');

    // Project Management
    Route::resource('projects', ProjectController::class);
    Route::post('projects/reorder', [ProjectController::class, 'reorder'])->name('projects.reorder');
    Route::post('projects/{project}/add-reference', [ProjectController::class, 'addReference'])->name('projects.add-reference');
    Route::post('projects/{project}/remove-reference', [ProjectController::class, 'removeReference'])->name('projects.remove-reference');
    // Folder Management
    Route::resource('folders', FolderController::class);

    // Import Features
    Route::get('import', [ImportController::class, 'index'])->name('import.index');
    Route::post('import/lookup', [ImportController::class, 'lookup'])->name('import.lookup');
    Route::post('import/quick-store', [ImportController::class, 'quickStore'])->name('import.quick-store');
    Route::post('import/from-lookup', [ImportController::class, 'importFromLookup'])->name('import.from-lookup');
    Route::post('import/parse-bibtex', [ImportController::class, 'parseBibtex'])->name('import.parse-bibtex');
    Route::post('import/bibtex', [ImportController::class, 'importBibtex'])->name('import.bibtex');

    // Citation Formatting
    Route::get('citations', [CitationController::class, 'index'])->name('citations.index');
    Route::post('citations/format', [CitationController::class, 'format'])->name('citations.format');
    Route::post('citations/bibliography', [CitationController::class, 'bibliography'])->name('citations.bibliography');
    Route::get('citations/styles', [CitationController::class, 'styles'])->name('citations.styles');
    Route::post('citations/preview', [CitationController::class, 'preview'])->name('citations.preview');

    // Export Features
    Route::match(['get', 'post'], 'export/bibtex', [ExportController::class, 'bibtex'])->name('export.bibtex');
    Route::match(['get', 'post'], 'export/ris', [ExportController::class, 'ris'])->name('export.ris');
    Route::get('export/word', [ExportController::class, 'word'])->name('export.word');
    Route::get('export/all/bibtex', [ExportController::class, 'allBibtex'])->name('export.all.bibtex');
    Route::get('export/all/ris', [ExportController::class, 'allRis'])->name('export.all.ris');
    Route::post('export/preview/bibtex', [ExportController::class, 'previewBibtex'])->name('export.preview.bibtex');
    Route::post('export/preview/ris', [ExportController::class, 'previewRis'])->name('export.preview.ris');
    Route::get('export/pdf', [ExportController::class, 'pdf'])->name('export.pdf');

    // Integrated Workspace
    Route::get('workspace', [WorkspaceController::class, 'index'])->name('workspace.index');
});

require __DIR__ . '/settings.php';
