<?php

use App\Http\Controllers\CitationController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\FolderController;
use App\Http\Controllers\ImportController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ProjectCollaborationController;
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
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

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
    Route::post('workspace', [WorkspaceController::class, 'store'])->name('workspace.store');
    Route::patch('workspace/{manuscript}', [WorkspaceController::class, 'update'])->name('workspace.update');
    Route::delete('workspace/{manuscript}', [WorkspaceController::class, 'destroy'])->name('workspace.destroy');
    Route::get('workspace/{manuscript}/export/pdf', [WorkspaceController::class, 'exportPdf'])->name('workspace.export.pdf');
    Route::get('workspace/{manuscript}/export/word', [WorkspaceController::class, 'exportWord'])->name('workspace.export.word');

    // AI Research Tools
    Route::get('paraphraser', [App\Http\Controllers\AiToolController::class, 'paraphraser'])->name('ai.paraphraser');
    Route::post('paraphraser/action', [App\Http\Controllers\AiToolController::class, 'paraphraseAction'])->name('ai.paraphrase-action');
    Route::post('ai/paraphrase', [App\Http\Controllers\AiToolController::class, 'paraphraseAction']); // For Workspace Editor
    Route::get('literature-map', [App\Http\Controllers\AiToolController::class, 'literatureMap'])->name('ai.literature-map');
    Route::get('research-templates', [App\Http\Controllers\AiToolController::class, 'templates'])->name('ai.templates');
    Route::get('ai/chat', [App\Http\Controllers\AiToolController::class, 'chat'])->name('ai.chat');
    Route::post('ai/chat', [App\Http\Controllers\AiToolController::class, 'chatAction'])->name('ai.chat-action');

    // Project Management (ClickUp style)
    // Project Management (ClickUp style)
    Route::post('projects/{project}/tasks', [App\Http\Controllers\TaskController::class, 'store'])->name('projects.tasks.store');
    Route::patch('projects/tasks/{task}', [App\Http\Controllers\TaskController::class, 'update'])->name('projects.tasks.update');
    Route::delete('projects/tasks/{task}', [App\Http\Controllers\TaskController::class, 'destroy'])->name('projects.tasks.destroy');
    Route::post('projects/{project}/tasks/reorder', [App\Http\Controllers\TaskController::class, 'reorder'])->name('projects.tasks.reorder');

    // Task Details & Extras
    Route::get('projects/tasks/{task}', [App\Http\Controllers\TaskController::class, 'show'])->name('tasks.show');
    Route::post('projects/tasks/{task}/checklists', [App\Http\Controllers\TaskController::class, 'storeChecklist'])->name('tasks.checklists.store');
    Route::patch('projects/tasks/checklists/{checklist}', [App\Http\Controllers\TaskController::class, 'updateChecklist'])->name('tasks.checklists.update');
    Route::delete('projects/tasks/checklists/{checklist}', [App\Http\Controllers\TaskController::class, 'destroyChecklist'])->name('tasks.checklists.destroy');
    Route::post('projects/tasks/{task}/files', [App\Http\Controllers\TaskController::class, 'uploadFile'])->name('tasks.files.upload');

    // Project Collaboration
    Route::post('projects/{project}/members', [App\Http\Controllers\ProjectCollaborationController::class, 'addMember'])->name('projects.members.add');
    Route::delete('projects/{project}/members/{member}', [App\Http\Controllers\ProjectCollaborationController::class, 'removeMember'])->name('projects.members.remove');
    Route::post('projects/{project}/files', [App\Http\Controllers\ProjectCollaborationController::class, 'uploadFile'])->name('projects.files.upload');
    Route::delete('projects/files/{file}', [App\Http\Controllers\ProjectCollaborationController::class, 'deleteFile'])->name('projects.files.delete');
    Route::post('projects/comments', [App\Http\Controllers\ProjectCollaborationController::class, 'addComment'])->name('projects.comments.add');
    Route::patch('projects/comments/{comment}', [App\Http\Controllers\ProjectCollaborationController::class, 'updateComment'])->name('projects.comments.update');
    Route::delete('projects/comments/{comment}', [App\Http\Controllers\ProjectCollaborationController::class, 'deleteComment'])->name('projects.comments.delete');
    Route::get('notifications', [App\Http\Controllers\NotificationController::class, 'index'])->name('notifications.index');
    Route::post('notifications/{id}/mark-as-read', [App\Http\Controllers\NotificationController::class, 'markAsRead'])->name('notifications.mark-as-read');
    Route::post('notifications/mark-all-as-read', [App\Http\Controllers\NotificationController::class, 'markAllAsRead'])->name('notifications.mark-all-as-read');
    Route::delete('notifications/{id}', [App\Http\Controllers\NotificationController::class, 'destroy'])->name('notifications.destroy');

    Route::get('users/search', [App\Http\Controllers\ProjectCollaborationController::class, 'searchUsers'])->name('users.search');
    // Invitations
    Route::get('projects/join/{token}', [ProjectCollaborationController::class, 'joinByInviteToken'])->name('projects.join');
    Route::post('projects/{project}/invite-settings', [ProjectCollaborationController::class, 'updateInviteSettings'])->name('projects.invite-settings');

    // Feedback
    Route::resource('feedback', App\Http\Controllers\FeedbackController::class)->only(['index', 'store']);

    Route::get('team-spaces', [ProjectController::class, 'teamSpaces'])->name('team-spaces.index');
    Route::post('projects/{project}/accept', [ProjectController::class, 'acceptInvitation'])->name('projects.accept');
    Route::post('projects/{project}/decline', [ProjectController::class, 'declineInvitation'])->name('projects.decline');
});

require __DIR__ . '/settings.php';
