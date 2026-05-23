@extends('layouts.public')

@section('title', $category->name . ' News')

@section('content')
<div class="bg-indigo-700 pb-24">
    <div class="max-w-7xl mx-auto py-16 px-4 sm:py-24 sm:px-6 lg:px-8">
        <div class="text-center">
            <h1 class="text-4xl font-extrabold text-white sm:text-5xl sm:tracking-tight lg:text-6xl">{{ $category->name }} News</h1>
            <p class="max-w-xl mt-5 mx-auto text-xl text-indigo-200">Latest updates and stories from the {{ $category->name }} category.</p>
        </div>
    </div>
</div>

<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-24 pb-12">
    @if($articles->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($articles as $article)
                <article class="flex flex-col bg-white rounded-2xl overflow-hidden shadow-lg transition-shadow duration-300 border border-gray-100">
                    <a href="{{ route('article.show', $article->slug) }}" class="shrink-0 relative h-48 bg-gray-200 overflow-hidden group block">
                        <img class="h-full w-full object-cover group-hover:scale-105 transition-transform duration-500" src="{{ $article->image_url }}" alt="{{ $article->title }}">
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
            {{ $articles->links() }}
        </div>
    @else
        <div class="text-center py-20 bg-white rounded-2xl border border-gray-100 shadow">
            <h3 class="mt-2 text-sm font-medium text-gray-900">No articles found in this category</h3>
        </div>
    @endif
</section>
@endsection
