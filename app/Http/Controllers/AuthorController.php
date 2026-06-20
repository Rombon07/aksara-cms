<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class AuthorController extends Controller
{
    public function index()
    {
        $articles = auth()->user()->articles()->latest()->paginate(10);
        return view('author.index', compact('articles'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('author.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|in:article,ebook',
            'title' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'body' => 'nullable|string',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'file_upload' => 'nullable|mimes:pdf|max:10240',
            'action' => 'required|in:draft,pending',
        ]);

        $article = new Article($validated);
        $article->body = $validated['body'] ?? '<p></p>';
        $article->user_id = auth()->id();
        $article->slug = Str::slug($validated['title']) . '-' . uniqid();
        $article->status = $validated['action'];
        $article->type = $validated['type'];

        if ($request->hasFile('file_upload') && $validated['type'] === 'ebook') {
            $pdf = $request->file('file_upload');
            $pdfName = time() . '_' . Str::slug($pdf->getClientOriginalName()) . '.' . $pdf->getClientOriginalExtension();
            $pdf->storeAs('ebooks', $pdfName, 'public');
            $article->file_path = 'ebooks/' . $pdfName;
        }

        if ($request->hasFile('thumbnail')) {
            $image = $request->file('thumbnail');
            $filename = time() . '.' . $image->getClientOriginalExtension();
            $path = storage_path('app/public/thumbnails/' . $filename);
            
            // Only run heavy image processing/resizing if the image size exceeds 500KB
            if (extension_loaded('gd') && $image->getSize() > 500 * 1024) {
                if (!file_exists(storage_path('app/public/thumbnails'))) {
                    mkdir(storage_path('app/public/thumbnails'), 0755, true);
                }
                $manager = new ImageManager(new Driver());
                $img = $manager->read($image);
                $img->scale(width: 800); // Scale is much faster than coverDown + crop
                $img->save($path, 75); // Fast compression at 75 quality
            } else {
                $image->storeAs('thumbnails', $filename, 'public');
            }
            
            $article->thumbnail = 'thumbnails/' . $filename;
        }

        $article->save();

        return redirect()->route('author.index')->with('success', 'Article saved successfully.');
    }

    public function edit(Article $article)
    {
        if ($article->user_id !== auth()->id()) abort(403);
        $categories = Category::all();
        return view('author.edit', compact('article', 'categories'));
    }

    public function update(Request $request, Article $article)
    {
        if ($article->user_id !== auth()->id()) abort(403);

        $validated = $request->validate([
            'type' => 'required|in:article,ebook',
            'title' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'body' => 'nullable|string',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'file_upload' => 'nullable|mimes:pdf|max:10240',
            'action' => 'required|in:draft,pending',
        ]);

        $article->fill([
            'type' => $validated['type'],
            'title' => $validated['title'],
            'category_id' => $validated['category_id'],
            'body' => $validated['body'] ?? '<p></p>',
            'status' => $validated['action'],
        ]);

        if ($request->hasFile('file_upload') && $validated['type'] === 'ebook') {
            $pdf = $request->file('file_upload');
            $pdfName = time() . '_' . Str::slug($pdf->getClientOriginalName()) . '.' . $pdf->getClientOriginalExtension();
            $pdf->storeAs('ebooks', $pdfName, 'public');
            $article->file_path = 'ebooks/' . $pdfName;
        }

        if ($request->hasFile('thumbnail')) {
            $image = $request->file('thumbnail');
            $filename = time() . '.' . $image->getClientOriginalExtension();
            $path = storage_path('app/public/thumbnails/' . $filename);
            
            // Only run heavy image processing/resizing if the image size exceeds 500KB
            if (extension_loaded('gd') && $image->getSize() > 500 * 1024) {
                if (!file_exists(storage_path('app/public/thumbnails'))) {
                    mkdir(storage_path('app/public/thumbnails'), 0755, true);
                }
                $manager = new ImageManager(new Driver());
                $img = $manager->read($image);
                $img->scale(width: 800); // Scale is much faster than coverDown + crop
                $img->save($path, 75); // Fast compression at 75 quality
            } else {
                $image->storeAs('thumbnails', $filename, 'public');
            }
            
            $article->thumbnail = 'thumbnails/' . $filename;
        }

        $article->save();

        return redirect()->route('author.index')->with('success', 'Article updated successfully.');
    }

    public function destroy(Article $article)
    {
        if ($article->user_id !== auth()->id()) abort(403);
        $article->delete();
        return redirect()->route('author.index')->with('success', 'Article deleted.');
    }
}
