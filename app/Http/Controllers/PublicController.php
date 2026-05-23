<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Category;
use Illuminate\Http\Request;

class PublicController extends Controller
{
    public function index()
    {
        $heroArticle = Article::with(['user', 'category'])
            ->where('status', 'published')
            ->latest('published_at')
            ->first();

        $latestArticles = Article::with(['user', 'category'])
            ->where('status', 'published')
            ->when($heroArticle, function ($query) use ($heroArticle) {
                return $query->where('id', '!=', $heroArticle->id);
            })
            ->latest('published_at')
            ->paginate(12);

        $categories = Category::all();

        return view('welcome', compact('heroArticle', 'latestArticles', 'categories'));
    }

    public function show(Article $article)
    {
        if ($article->status !== 'published') {
            abort(404);
        }

        $article->load(['user', 'category']);
        
        return view('article.show', compact('article'));
    }

    public function category(Category $category)
    {
        $articles = $category->articles()
            ->with(['user'])
            ->where('status', 'published')
            ->latest('published_at')
            ->paginate(12);

        return view('category.show', compact('category', 'articles'));
    }

    public function search(Request $request)
    {
        $q = $request->query('q');
        $articles = Article::with(['user', 'category'])
            ->where('status', 'published')
            ->where(function($query) use ($q) {
                $query->where('title', 'like', "%{$q}%")
                      ->orWhere('body', 'like', "%{$q}%");
            })
            ->latest('published_at')
            ->paginate(12);

        return view('search', compact('articles', 'q'));
    }
}
