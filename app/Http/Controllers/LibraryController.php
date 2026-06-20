<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LibraryController extends Controller
{
    // Fungsi ini digunakan untuk menampilkan daftar artikel yang telah disimpan (bookmark) oleh user.
    public function index()
    {
        $user = auth()->user();
        
        // Mengambil data bookmark beserta artikel, penulis, dan kategori untuk mengoptimalkan query (Eager Loading).
        $bookmarks = $user->bookmarks()
            ->with([
                'article.user',
                'article.category',
                'article' => function($q) {
                    $q->withCount(['likes', 'comments']);
                }
            ])
            ->latest()
            ->paginate(12);

        return view('reader.library', compact('bookmarks'));
    }
}
