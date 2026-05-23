<?php

use App\Http\Controllers\EditorController;
use App\Http\Controllers\JournalistController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PublicController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PublicController::class, 'index'])->name('home');
Route::get('/news/{article:slug}', [PublicController::class, 'show'])->name('article.show');
Route::get('/category/{category:slug}', [PublicController::class, 'category'])->name('category.show');
Route::get('/search', [PublicController::class, 'search'])->name('search');

Route::get('/dashboard', function () {
    if (auth()->user()->role === 'editor') {
        return redirect()->route('editor.index');
    }
    return redirect()->route('journalist.index');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Journalist Routes
    Route::middleware('role:journalist')->prefix('journalist')->name('journalist.')->group(function () {
        Route::get('/articles', [JournalistController::class, 'index'])->name('index');
        Route::get('/articles/create', [JournalistController::class, 'create'])->name('create');
        Route::post('/articles', [JournalistController::class, 'store'])->name('store');
        Route::get('/articles/{article}/edit', [JournalistController::class, 'edit'])->name('edit');
        Route::put('/articles/{article}', [JournalistController::class, 'update'])->name('update');
        Route::delete('/articles/{article}', [JournalistController::class, 'destroy'])->name('destroy');
    });

    // Editor Routes
    Route::middleware('role:editor')->prefix('editor')->name('editor.')->group(function () {
        Route::get('/review', [EditorController::class, 'index'])->name('index');
        Route::post('/articles/{article}/publish', [EditorController::class, 'publish'])->name('publish');
        Route::post('/articles/{article}/revise', [EditorController::class, 'revise'])->name('revise');
    });
});

require __DIR__.'/auth.php';
