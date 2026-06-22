<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>Aksara - Elegant Publishing Platform</title>
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700|lora:400,500,600,700&display=swap" rel="stylesheet" />
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased text-gray-900 bg-[#FAFAFA]">
        <!-- Top Header -->
        <header class="bg-[#FAFAFA] border-b border-gray-200 sticky top-0 z-50 transition-all duration-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center h-16">
                    <!-- Left: Logo & Search -->
                    <div class="flex items-center space-x-6">
                        <a href="{{ route('home') }}" class="font-bold text-3xl tracking-tighter text-indigo-950 font-serif">
                            Aksara.
                        </a>
                        <div class="hidden sm:block">
                            <form action="{{ route('search') }}" method="GET" class="relative">
                                <input type="text" name="q" value="{{ request('q') }}" placeholder="Search stories..." class="rounded-full bg-white border border-gray-200 focus:ring-2 focus:ring-indigo-100 focus:border-indigo-300 px-5 py-2 text-sm w-64 transition-all duration-300 placeholder-gray-400">
                                <button type="submit" class="absolute right-3 top-2.5 text-gray-400 hover:text-indigo-600">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- Right: Actions -->
                    <div class="flex items-center space-x-6">
                        <a href="{{ auth()->check() ? (in_array(auth()->user()->role, ['author', 'admin', 'editor']) ? route('author.create') : route('profile.edit', ['tab' => 'author-request'])) : route('login') }}" class="text-gray-600 hover:text-indigo-600 flex items-center space-x-1.5 transition font-medium">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                            <span class="text-sm">Write</span>
                        </a>
                        @auth
                            <div class="relative" x-data="{ open: false }">
                                <button @click="open = !open" @click.away="open = false" class="h-9 w-9 rounded-full bg-emerald-600 hover:bg-emerald-700 transition flex items-center justify-center text-white font-semibold text-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 shadow-sm">
                                    {{ substr(auth()->user()->name, 0, 1) }}
                                </button>
                                
                                <div x-show="open" x-transition.opacity style="display: none;" class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-lg py-2 ring-1 ring-black ring-opacity-5 z-50 border border-gray-100">
                                    <div class="px-4 py-2 mb-1 border-b border-gray-50">
                                        <p class="text-sm font-medium text-gray-900 truncate">{{ auth()->user()->name }}</p>
                                        <p class="text-xs text-gray-500 truncate">{{ auth()->user()->email }}</p>
                                    </div>
                                    <a href="{{ auth()->user()->role === 'author' ? route('author.index') : route('library') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-indigo-50 hover:text-indigo-700 transition">Library / Dashboard</a>
                                    <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-indigo-50 hover:text-indigo-700 transition">Profile Settings</a>
                                    
                                    <form method="POST" action="{{ route('logout') }}" class="mt-1 border-t border-gray-50">
                                        @csrf
                                        <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition">
                                            Log out
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @else
                            <a href="{{ route('login') }}" class="px-4 py-2 rounded-full bg-gray-900 text-white text-sm font-medium hover:bg-indigo-600 transition shadow-sm">Sign in</a>
                        @endauth
                    </div>
                </div>
            </div>
        </header>

        <!-- Main Content (Full Width) -->
        <main class="min-h-screen">
            @yield('content')
        </main>
        
        <!-- Minimal Footer -->
        <footer class="bg-white border-t border-gray-200 mt-20 py-10 text-center text-gray-500 text-sm">
            <div class="max-w-7xl mx-auto px-4">
                <p>&copy; {{ date('Y') }} Aksara. Elegant Publishing.</p>
                <div class="flex justify-center space-x-6 mt-4">
                    <a href="#" class="hover:text-indigo-600 transition">About</a>
                    <a href="#" class="hover:text-indigo-600 transition">Terms</a>
                    <a href="#" class="hover:text-indigo-600 transition">Privacy</a>
                </div>
            </div>
        </footer>

        @stack('scripts')
    </body>
</html>
