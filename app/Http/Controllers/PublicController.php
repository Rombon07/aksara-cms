<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Category;
use Illuminate\Http\Request;

class PublicController extends Controller
{
    public function index(Request $request)
    {
        $query = Article::with(['user', 'category'])
            ->withCount(['likes', 'comments'])
            ->where('status', 'published');

        if (auth()->check()) {
            $query->withExists(['bookmarks as is_bookmarked' => function($q) {
                $q->where('user_id', auth()->id());
            }]);
        }

        if ($request->has('categories') && is_array($request->categories)) {
            $query->whereIn('category_id', $request->categories);
        }

        if ($request->has('types') && is_array($request->types)) {
            $query->whereIn('type', $request->types);
        }

        $latestArticles = $query->latest('published_at')->paginate(15)->withQueryString();

        $categories = Category::all();

        return view('welcome', compact('latestArticles', 'categories'));
    }

    public function show(Article $article)
    {
        // Izinkan jika artikel sudah di-publish
        // ATAU jika user yang login adalah Editor
        // ATAU jika user yang login adalah Author dari artikel tersebut
        $isPublished = $article->status === 'published';
        $isEditor = auth()->check() && auth()->user()->role === 'editor';
        $isAuthor = auth()->check() && auth()->user()->id === $article->user_id;

        if (!$isPublished && !$isEditor && !$isAuthor) {
            abort(404);
        }

        $article->load(['user', 'category', 'comments.user']);
        
        return view('article.show', compact('article'));
    }

    public function category(Category $category)
    {
        $query = $category->articles()
            ->with(['user'])
            ->withCount(['likes', 'comments'])
            ->where('status', 'published');

        if (auth()->check()) {
            $query->withExists(['likes as is_liked' => function($q) {
                $q->where('user_id', auth()->id());
            }])->withExists(['bookmarks as is_bookmarked' => function($q) {
                $q->where('user_id', auth()->id());
            }]);
        }

        $articles = $query->latest('published_at')->paginate(12);

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
