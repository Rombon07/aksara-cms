<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class JournalistController extends Controller
{
    public function index()
    {
        $articles = auth()->user()->articles()->latest()->paginate(10);
        return view('journalist.index', compact('articles'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('journalist.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'body' => 'required|string',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'action' => 'required|in:draft,pending',
        ]);

        $article = new Article($validated);
        $article->user_id = auth()->id();
        $article->slug = Str::slug($validated['title']) . '-' . uniqid();
        $article->status = $validated['action'];

        if ($request->hasFile('thumbnail')) {
            $image = $request->file('thumbnail');
            $filename = time() . '.' . $image->getClientOriginalExtension();
            $path = storage_path('app/public/thumbnails/' . $filename);
            
            if (!file_exists(storage_path('app/public/thumbnails'))) {
                mkdir(storage_path('app/public/thumbnails'), 0755, true);
            }

            if (extension_loaded('gd')) {
                $manager = new ImageManager(new Driver());
                $img = $manager->read($image);
                $img->coverDown(800, 600);
                $img->save($path, 80);
            } else {
                $image->move(storage_path('app/public/thumbnails'), $filename);
            }
            
            $article->thumbnail = 'thumbnails/' . $filename;
        }

        $article->save();

        return redirect()->route('journalist.index')->with('success', 'Article saved successfully.');
    }

    public function edit(Article $article)
    {
        if ($article->user_id !== auth()->id()) abort(403);
        $categories = Category::all();
        return view('journalist.edit', compact('article', 'categories'));
    }

    public function update(Request $request, Article $article)
    {
        if ($article->user_id !== auth()->id()) abort(403);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'body' => 'required|string',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'action' => 'required|in:draft,pending',
        ]);

        $article->fill([
            'title' => $validated['title'],
            'category_id' => $validated['category_id'],
            'body' => $validated['body'],
            'status' => $validated['action'],
        ]);

        if ($request->hasFile('thumbnail')) {
            $image = $request->file('thumbnail');
            $filename = time() . '.' . $image->getClientOriginalExtension();
            $path = storage_path('app/public/thumbnails/' . $filename);
            
            if (!file_exists(storage_path('app/public/thumbnails'))) {
                mkdir(storage_path('app/public/thumbnails'), 0755, true);
            }

            if (extension_loaded('gd')) {
                $manager = new ImageManager(new Driver());
                $img = $manager->read($image);
                $img->coverDown(800, 600);
                $img->save($path, 80);
            } else {
                $image->move(storage_path('app/public/thumbnails'), $filename);
            }
            
            $article->thumbnail = 'thumbnails/' . $filename;
        }

        $article->save();

        return redirect()->route('journalist.index')->with('success', 'Article updated successfully.');
    }

    public function destroy(Article $article)
    {
        if ($article->user_id !== auth()->id()) abort(403);
        $article->delete();
        return redirect()->route('journalist.index')->with('success', 'Article deleted.');
    }
}
