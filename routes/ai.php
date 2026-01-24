<?php

use Illuminate\Support\Facades\Route;

Route::get('/paraphraser', function () {
    return redirect()->route('workspace.paraphraser');
})->name('paraphraser');

Route::get('/literature-map', function () {
    return redirect()->route('workspace.literature-map');
})->name('literature-map');
