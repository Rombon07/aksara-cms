<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;

class EditorController extends Controller
{
    public function index()
    {
        $articles = Article::with(['user', 'category'])
            ->where('status', 'pending')
            ->latest()
            ->paginate(12);
            
        return view('editor.index', compact('articles'));
    }

    public function publish(Article $article)
    {
        $article->update([
            'status' => 'published',
            'published_at' => now(),
            'editor_notes' => null,
        ]);

        return redirect()->route('editor.index')->with('success', 'Article published successfully.');
    }

    public function revise(Request $request, Article $article)
    {
        $request->validate([
            'editor_notes' => 'required|string|max:1000'
        ]);

        $article->update([
            'status' => 'draft',
            'editor_notes' => $request->editor_notes,
        ]);

        return redirect()->route('editor.index')->with('success', 'Article sent back for revision.');
    }
}
