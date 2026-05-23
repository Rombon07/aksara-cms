<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>@yield('title', config('app.name', 'KabarKini')) - Digital Publishing CMS</title>
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased text-gray-900 bg-gray-50">
        <!-- Navigation -->
        <header class="bg-white border-b border-gray-100 sticky top-0 z-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex">
                        <div class="shrink-0 flex items-center">
                            <a href="{{ route('home') }}" class="text-2xl font-bold text-indigo-600 tracking-tight">Kabar<span class="text-gray-900">Kini</span></a>
                        </div>
                        <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                            @foreach(\App\Models\Category::all() as $navCategory)
                                <a href="{{ route('category.show', $navCategory->slug) }}" class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition duration-150 ease-in-out">
                                    {{ $navCategory->name }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                    <div class="hidden sm:flex sm:items-center sm:ml-6 space-x-4">
                        <form action="{{ route('search') }}" method="GET" class="relative">
                            <input type="text" name="q" value="{{ request('q') }}" placeholder="Search news..." class="w-64 rounded-full border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 px-4 py-2 text-sm" required>
                            <button type="submit" class="absolute right-3 top-2.5 text-gray-400 hover:text-indigo-600">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                            </button>
                        </form>
                        @if (Route::has('login'))
                            @auth
                                <a href="{{ route('dashboard') }}" class="text-sm font-semibold text-gray-600 hover:text-indigo-600 px-4 py-2 border border-gray-200 rounded-full hover:bg-gray-50 transition">Dashboard</a>
                            @else
                                <a href="{{ route('login') }}" class="text-sm font-semibold text-gray-600 hover:text-indigo-600 px-4 py-2 border border-gray-200 rounded-full hover:bg-gray-50 transition">Log in</a>
                            @endauth
                        @endif
                    </div>
                </div>
            </div>
        </header>

        <main>
            @yield('content')
        </main>

        <footer class="bg-white border-t border-gray-200 mt-12 py-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center text-gray-500 text-sm">
                &copy; {{ date('Y') }} KabarKini Digital Publishing CMS. All rights reserved.
            </div>
        </footer>
    </body>
</html>
