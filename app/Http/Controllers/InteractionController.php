<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;

class InteractionController extends Controller
{
    // Fungsi ini digunakan untuk memberikan 'Like' atau membatalkan 'Like' pada suatu artikel.
    public function toggleLike(Article $article)
    {
        $user = auth()->user(); // Mengambil data user yang sedang login
        // Mengecek apakah user sudah memberikan like pada artikel ini sebelumnya
        $like = $user->likes()->where('article_id', $article->id)->first();

        if ($like) {
            $like->delete(); // Jika sudah di-like, maka hapus like tersebut (Unlike)
            $status = 'unliked';
        } else {
            $user->likes()->create(['article_id' => $article->id]); // Jika belum, tambahkan like baru
            $status = 'liked';
        }

        if (request()->wantsJson() || request()->ajax()) {
            // Mengembalikan response JSON untuk diolah oleh AJAX/Alpine.js di frontend
            return response()->json([
                'status' => $status,
                'likes_count' => $article->likes()->count()
            ]);
        }

        return back();
    }

    // Fungsi ini digunakan untuk menyimpan artikel ke daftar bacaan user (Bookmark) atau menghapusnya.
    public function toggleBookmark(Article $article)
    {
        $user = auth()->user();
        // Mengecek apakah artikel ini sudah ada di daftar simpanan (bookmark) user
        $bookmark = $user->bookmarks()->where('article_id', $article->id)->first();

        if ($bookmark) {
            $bookmark->delete(); // Jika sudah disimpan, hapus dari daftar bacaan
            $status = 'removed';
        } else {
            $user->bookmarks()->create(['article_id' => $article->id]); // Jika belum, simpan artikel
            $status = 'saved';
        }

        if (request()->wantsJson() || request()->ajax()) {
            // Mengembalikan status terbaru untuk mengupdate UI
            return response()->json([
                'status' => $status,
                'bookmarks_count' => $article->bookmarks()->count()
            ]);
        }

        return back();
    }

    // Fungsi ini digunakan untuk menyimpan komentar baru dari user pada sebuah artikel.
    public function storeComment(Request $request, Article $article)
    {
        // Validasi input: Komentar wajib diisi, berupa teks, maksimal 1000 karakter
        $validated = $request->validate([
            'body' => 'required|string|max:1000',
        ]);

        // Menyimpan data komentar ke database yang berelasi dengan artikel terkait
        $comment = $article->comments()->create([
            'user_id' => auth()->id(),
            'body' => $validated['body'],
        ]);

        // Load user data for the response so frontend can append it
        $comment->load('user');

        return response()->json([
            'status' => 'success',
            'comment' => $comment,
            'comments_count' => $article->comments()->count()
        ]);
    }
}
