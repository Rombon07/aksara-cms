@extends('layouts.public')

@section('title', $article->title)

@section('content')
<article class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="mb-8 text-center">
        <a href="{{ route('category.show', $article->category->slug) }}" class="inline-block px-3 py-1 bg-indigo-100 text-indigo-800 rounded-full text-sm font-semibold mb-4 hover:bg-indigo-200 transition">{{ $article->category->name }}</a>
        <h1 class="text-4xl font-extrabold text-gray-900 tracking-tight sm:text-5xl mb-6">{{ $article->title }}</h1>
        <div class="flex items-center justify-center text-gray-500 space-x-2 text-sm">
            <span>By <span class="font-bold text-gray-900">{{ $article->user->name }}</span></span>
            <span>&bull;</span>
            <time datetime="{{ $article->published_at }}">{{ $article->published_at->format('F d, Y') }}</time>
        </div>
    </div>

    @if($article->image_url)
        <div class="mb-12 rounded-2xl overflow-hidden shadow-lg">
            <img src="{{ $article->image_url }}" alt="{{ $article->title }}" class="w-full h-auto object-cover max-h-[600px]">
        </div>
    @endif

    <div class="prose prose-indigo prose-lg mx-auto text-gray-800 leading-relaxed max-w-2xl">
        {!! $article->body !!}
    </div>
</article>
@endsection
