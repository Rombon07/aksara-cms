<?php

use App\Http\Controllers\EditorController;
use App\Http\Controllers\AuthorController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PublicController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PublicController::class, 'index'])->name('home');
Route::get('/news/{article:slug}', [PublicController::class, 'show'])->name('article.show');
Route::get('/category/{category:slug}', [PublicController::class, 'category'])->name('category.show');
Route::get('/search', [PublicController::class, 'search'])->name('search');

// Fallback to serve storage images directly (bypassing symlink issues on Windows/XAMPP)
Route::get('/media/{path}', function ($path) {
    $fullPath = storage_path('app/public/' . $path);
    if (!file_exists($fullPath)) {
        abort(404);
    }
    $mimeType = \Illuminate\Support\Facades\File::mimeType($fullPath);
    return response()->file($fullPath, ['Content-Type' => $mimeType]);
})->where('path', '.*');

Route::get('/dashboard', function () {
    $role = request()->user()?->role;
    if ($role === 'editor') {
        return redirect()->route('editor.index');
    } elseif ($role === 'author') {
        return redirect()->route('author.index');
    }

    // Default fallback untuk reader
    return redirect()->route('home');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    // Reader Library (Daftar Bacaan)
    Route::get('/library', [\App\Http\Controllers\LibraryController::class, 'index'])->name('library');

    // Interactions
    Route::post('/articles/{article}/like', [\App\Http\Controllers\InteractionController::class, 'toggleLike'])->name('article.like');
    Route::post('/articles/{article}/bookmark', [\App\Http\Controllers\InteractionController::class, 'toggleBookmark'])->name('article.bookmark');
    Route::post('/articles/{article}/comment', [\App\Http\Controllers\InteractionController::class, 'storeComment'])->name('article.comment');

    // Author Routes
    Route::middleware('role:author')->prefix('author')->name('author.')->group(function () {
        Route::get('/articles', [AuthorController::class, 'index'])->name('index');
        Route::get('/articles/create', [AuthorController::class, 'create'])->name('create');
        Route::post('/articles', [AuthorController::class, 'store'])->name('store');
        Route::get('/articles/{article}/edit', [AuthorController::class, 'edit'])->name('edit');
        Route::put('/articles/{article}', [AuthorController::class, 'update'])->name('update');
        Route::delete('/articles/{article}', [AuthorController::class, 'destroy'])->name('destroy');
    });

    // Editor Routes
    Route::middleware('role:editor')->prefix('editor')->name('editor.')->group(function () {
        Route::get('/review', [EditorController::class, 'index'])->name('index');
        Route::post('/articles/{article}/publish', [EditorController::class, 'publish'])->name('publish');
        Route::post('/articles/{article}/revise', [EditorController::class, 'revise'])->name('revise');
    });
});

require __DIR__.'/auth.php';
