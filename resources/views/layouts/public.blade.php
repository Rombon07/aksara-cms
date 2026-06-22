<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>@yield('title') - Aksara</title>
        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700|lora:400,500,600,700&display=swap" rel="stylesheet" />
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased text-gray-900 bg-gray-50">
        <!-- Top Header -->
        <header class="bg-white border-b border-gray-100 sticky top-0 z-50 transition-all duration-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center h-16">
                    <!-- Left: Logo & Search -->
                    <div class="flex items-center space-x-6">
                        <a href="{{ route('home') }}" class="font-bold text-3xl tracking-tighter text-gray-900 font-serif">
                            Aksara.
                        </a>
                        <div class="hidden sm:block">
                            <form action="{{ route('search') }}" method="GET" class="relative">
                                <input type="text" name="q" value="{{ request('q') }}" placeholder="Search" class="rounded-full bg-gray-50 border-none focus:ring-0 px-10 py-2.5 text-sm w-64 transition-all duration-300 placeholder-gray-400" required>
                                <button type="submit" class="absolute left-3 top-2.5 text-gray-400">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- Right: Actions -->
                    <div class="flex items-center space-x-6">
                        <a href="{{ auth()->check() ? (in_array(auth()->user()->role, ['author', 'admin', 'editor']) ? route('author.create') : route('profile.edit', ['tab' => 'author-request'])) : route('login') }}" class="text-gray-500 hover:text-gray-900 flex items-center space-x-1 transition">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                            <span class="text-sm">Write</span>
                        </a>
                        <button class="text-gray-500 hover:text-gray-900 transition">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                        </button>
                        @auth
                            <div class="relative" x-data="{ open: false }">
                                <button @click="open = !open" @click.away="open = false" class="h-9 w-9 rounded-full bg-emerald-600 hover:bg-emerald-700 transition flex items-center justify-center text-white font-semibold text-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500">
                                    {{ substr(auth()->user()->name, 0, 1) }}
                                </button>
                                
                                <div x-show="open" x-transition.opacity style="display: none;" class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-lg py-2 ring-1 ring-black ring-opacity-5 z-50 border border-gray-100">
                                    <div class="px-4 py-2 mb-1 border-b border-gray-50">
                                        <p class="text-sm font-medium text-gray-900 truncate">{{ auth()->user()->name }}</p>
                                        <p class="text-xs text-gray-500 truncate">{{ auth()->user()->email }}</p>
                                    </div>
                                    <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-gray-900 transition">Profile</a>
                                    <a href="{{ route('dashboard') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-gray-900 transition">Dashboard</a>
                                    
                                    <form method="POST" action="{{ route('logout') }}" class="mt-1 border-t border-gray-50">
                                        @csrf
                                        <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition">
                                            Log out
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @else
                            <a href="{{ route('login') }}" class="text-sm font-semibold text-gray-600 hover:text-gray-900">Sign in</a>
                        @endauth
                    </div>
                </div>
            </div>
        </header>

        <div class="max-w-[1400px] mx-auto px-4 sm:px-6 lg:px-8 mt-10">
            <div class="flex flex-col lg:flex-row gap-8 xl:gap-12 justify-center">
                <!-- Left Sidebar -->
                <aside class="hidden lg:block w-48 shrink-0 relative">
                    <div class="sticky top-28 space-y-8">
                        <a href="{{ route('home') }}" class="flex items-center space-x-4 {{ request()->routeIs('home') ? 'text-gray-900' : 'text-gray-500 hover:text-gray-900' }} transition">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                            <span>Home</span>
                        </a>
                        <a href="{{ auth()->check() ? (auth()->user()->role === 'author' ? route('author.index') : route('library')) : route('login') }}" class="flex items-center space-x-4 {{ request()->routeIs('library') || request()->routeIs('author.index') ? 'text-gray-900' : 'text-gray-500 hover:text-gray-900' }} transition">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"></path></svg>
                            <span>Library</span>
                        </a>
                        <a href="{{ route('profile.edit') }}" class="flex items-center space-x-4 text-gray-500 hover:text-gray-900 transition">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                            <span>Profile</span>
                        </a>
                    </div>
                </aside>

                <!-- Center Content -->
                <main class="flex-1 max-w-3xl min-w-0 mx-auto lg:mx-0 w-full pb-20">
                    @yield('content')
                </main>

                <!-- Right Sidebar -->
                @unless(request()->routeIs('profile.edit'))
                <aside class="hidden xl:block w-[300px] shrink-0 relative">
                    <div class="sticky top-28 space-y-10">
                        <!-- Staff Picks -->
                        <div>
                            <h3 class="font-bold text-gray-900 mb-4">Staff Picks</h3>
                            <div class="space-y-5">
                                @foreach(\App\Models\Article::with('user')->where('status', 'published')->inRandomOrder()->limit(3)->get() as $pick)
                                <div class="group cursor-pointer">
                                    <div class="flex items-center space-x-2 text-xs mb-1">
                                        <div class="w-5 h-5 rounded-full bg-gray-200 flex items-center justify-center text-gray-600 font-bold">
                                            {{ substr($pick->user->name, 0, 1) }}
                                        </div>
                                        <span class="font-medium text-gray-900">{{ $pick->user->name }}</span>
                                    </div>
                                    <a href="{{ route('article.show', $pick->slug) }}">
                                        <h4 class="font-bold text-gray-900 leading-snug group-hover:text-indigo-600 transition">{{ $pick->title }}</h4>
                                    </a>
                                </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Recommended Topics -->
                        <div>
                            <h3 class="font-bold text-gray-900 mb-4">Recommended topics</h3>
                            <div class="flex flex-wrap gap-2">
                                @foreach(\App\Models\Category::limit(7)->get() as $cat)
                                    <a href="{{ route('home', ['categories' => [$cat->id]]) }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-full text-sm transition">
                                        {{ $cat->name }}
                                    </a>
                                @endforeach
                            </div>
                        </div>

                        <!-- Footer Links -->
                        <div class="text-xs text-gray-500 flex flex-wrap gap-x-4 gap-y-2">
                            <a href="#" class="hover:text-gray-900">Help</a>
                            <a href="#" class="hover:text-gray-900">Status</a>
                            <a href="#" class="hover:text-gray-900">About</a>
                            <a href="#" class="hover:text-gray-900">Careers</a>
                            <a href="#" class="hover:text-gray-900">Privacy</a>
                            <a href="#" class="hover:text-gray-900">Terms</a>
                        </div>
                    </div>
                </aside>
                @endunless
            </div>
        </div>
        @stack('scripts')
    </body>
</html>
