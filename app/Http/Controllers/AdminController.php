<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AdminController extends Controller
{
    public function dashboard()
    {
        $stats = [
            'total_articles' => Article::where('type', 'article')->count(),
            'total_ebooks' => Article::where('type', 'ebook')->count(),
            'total_users' => User::count(),
            'total_likes' => \App\Models\Like::count(),
            'total_comments' => \App\Models\Comment::count(),
        ];

        $recentArticles = Article::with(['user', 'category'])
            ->latest()
            ->limit(5)
            ->get();

        $recentUsers = User::latest()
            ->limit(5)
            ->get();

        // Get category count for articles
        $categoriesStats = \App\Models\Category::withCount(['articles' => function($q) {
            $q->where('type', 'article');
        }])->get();

        return view('admin.dashboard', compact('stats', 'recentArticles', 'recentUsers', 'categoriesStats'));
    }

    public function index(Request $request)
    {
        $query = User::query();

        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
        }

        $users = $query->latest()->paginate(10)->withQueryString();

        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'role' => ['required', Rule::in(['reader', 'author', 'editor', 'admin'])],
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        return redirect()->route('admin.users.index')->with('success', 'User created successfully.');
    }

    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique(User::class)->ignore($user->id)],
            'role' => ['required', Rule::in(['reader', 'author', 'editor', 'admin'])],
        ];

        if ($request->filled('password')) {
            $rules['password'] = ['required', 'string', 'min:8', 'confirmed'];
        }

        $request->validate($rules);

        $user->name = $request->name;
        $user->email = $request->email;
        $user->role = $request->role;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect()->route('admin.users.index')->with('success', 'User updated successfully.');
    }

    public function destroy(User $user)
    {
        // Optional: Check if admin is trying to delete themselves
        if (auth()->id() === $user->id) {
            return redirect()->route('admin.users.index')->with('error', 'You cannot delete your own account.');
        }

        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'User deleted successfully.');
    }

    /**
     * Handle the author request approval/rejection.
     */
    public function handleRequest(User $user, $action)
    {
        if ($action === 'approve') {
            $user->update([
                'role' => 'author',
                'author_request_status' => 'approved',
            ]);
            return redirect()->route('admin.users.index')->with('success', "Akses User {$user->name} berhasil dinaikkan menjadi Author.");
        } elseif ($action === 'reject') {
            $user->update([
                'author_request_status' => 'rejected',
            ]);
            return redirect()->route('admin.users.index')->with('success', "Permintaan Author dari {$user->name} telah ditolak.");
        }

        return redirect()->back();
    }
}
