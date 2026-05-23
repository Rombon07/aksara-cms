@extends('layouts.public')

@section('content')
    <!-- Hero Section -->
    @if($heroArticle)
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-8 mb-12">
        <div class="relative rounded-3xl overflow-hidden group shadow-xl block">
            <div class="absolute inset-0 bg-gray-900">
                <img src="{{ $heroArticle->image_url }}" alt="{{ $heroArticle->title }}" class="w-full h-full object-cover opacity-60 group-hover:scale-105 transition-transform duration-700">
            </div>
            <div class="absolute inset-0 bg-gradient-to-t from-gray-900 via-gray-900/40 to-transparent"></div>
            <div class="relative px-8 pt-32 pb-12 sm:px-12 sm:pt-48 sm:pb-16 flex flex-col items-start">
                <a href="{{ route('category.show', $heroArticle->category->slug) }}" class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold tracking-wide uppercase bg-indigo-600 text-white mb-4 hover:bg-indigo-500 transition">
                    {{ $heroArticle->category->name }}
                </a>
                <a href="{{ route('article.show', $heroArticle->slug) }}" class="hover:underline text-white">
                    <h1 class="mt-2 text-4xl sm:text-5xl font-extrabold tracking-tight leading-tight max-w-3xl">
                        {{ $heroArticle->title }}
                    </h1>
                </a>
                <div class="mt-6 flex items-center text-sm text-gray-300">
                    <span class="font-medium text-white">{{ $heroArticle->user->name }}</span>
                    <span class="mx-2">&middot;</span>
                    <time datetime="{{ $heroArticle->published_at }}">{{ $heroArticle->published_at->format('M d, Y') }}</time>
                </div>
                <p class="mt-4 text-lg text-gray-200 max-w-2xl line-clamp-2">
                    {{ strip_tags($heroArticle->body) }}
                </p>
                <a href="{{ route('article.show', $heroArticle->slug) }}" class="mt-8 inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-full shadow-sm text-indigo-600 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition">
                    Read Full Story
                </a>
            </div>
        </div>
    </section>
    @endif

    <!-- Latest News Grid -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 border-t border-gray-200">
        <div class="flex justify-between items-end mb-8">
            <h2 class="text-3xl font-bold tracking-tight text-gray-900">Latest Updates</h2>
        </div>
        
        @if($latestArticles->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($latestArticles as $article)
                    <article class="flex flex-col bg-white rounded-2xl overflow-hidden shadow-sm hover:shadow-lg transition-shadow duration-300 border border-gray-100">
                        <a href="{{ route('article.show', $article->slug) }}" class="shrink-0 relative h-48 bg-gray-200 overflow-hidden group block">
                            <img class="h-full w-full object-cover group-hover:scale-105 transition-transform duration-500" src="{{ $article->image_url }}" alt="{{ $article->title }}">
                            <div class="absolute top-4 left-4">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-xs font-medium bg-white text-indigo-800 shadow-sm hover:bg-gray-50">
                                    {{ $article->category->name }}
                                </span>
                            </div>
                        </a>
                        <div class="flex-1 p-6 flex flex-col justify-between">
                            <div class="flex-1">
                                <a href="{{ route('article.show', $article->slug) }}" class="block mt-2">
                                    <h3 class="text-xl font-bold text-gray-900 hover:text-indigo-600 transition">{{ $article->title }}</h3>
                                    <p class="mt-3 text-base text-gray-500 line-clamp-3">
                                        {{ strip_tags($article->body) }}
                                    </p>
                                </a>
                            </div>
                            <div class="mt-6 flex items-center">
                                <div class="shrink-0">
                                    <span class="sr-only">{{ $article->user->name }}</span>
                                    <div class="h-10 w-10 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-700 font-bold">
                                        {{ substr($article->user->name, 0, 1) }}
                                    </div>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-gray-900">
                                        {{ $article->user->name }}
                                    </p>
                                    <div class="flex space-x-1 text-sm text-gray-500">
                                        <time datetime="{{ $article->published_at }}">{{ $article->published_at->format('M d, Y') }}</time>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>
            
            <div class="mt-12">
                {{ $latestArticles->links() }}
            </div>
        @else
            <div class="text-center py-20 bg-white rounded-2xl border border-gray-100 border-dashed">
                <svg class="mx-auto h-12 w-12 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9.5L18.5 7H20" />
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">No articles found</h3>
                <p class="mt-1 text-sm text-gray-500">Check back later for more updates.</p>
            </div>
        @endif
    </section>
@endsection
