<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('home');
Route::view('manual', 'manual')->name('manual');
Route::view('citation-generator', 'citation-generator')->name('citation-generator');
Route::view('privacy', 'privacy')->name('privacy');
Route::view('terms', 'terms')->name('terms');
Route::view('about', 'about')->name('about');

Route::get('change-language/{locale}', function ($locale) {
    if (in_array($locale, ['th', 'en'])) {
        session(['locale' => $locale]);
    }
    return back();
})->name('change-language');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('dashboard', 'dashboard')->name('dashboard');
});

require __DIR__.'/settings.php';
