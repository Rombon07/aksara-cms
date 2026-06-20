@extends('layouts.public')

@section('title', 'Your Library - Aksara')

@section('content')
<div class="border-b border-gray-100 pb-4 mb-6 sticky top-[64px] bg-gray-50 z-40 pt-4">
    <div class="flex items-center space-x-2 pb-4">
        <h1 class="text-3xl font-bold text-gray-900 font-serif">Your Library</h1>
    </div>
    <nav class="-mb-px flex space-x-8">
        <a href="#" class="border-gray-900 text-gray-900 whitespace-nowrap pb-4 px-1 border-b-2 font-medium text-sm transition">Saved reading</a>
    </nav>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 sm:p-8">
    <div class="space-y-8">
        @forelse($bookmarks as $bookmark)
            @php $article = $bookmark->article; @endphp
            <article class="group">
                <div class="flex items-center space-x-2 text-xs mb-3">
                    <div class="w-6 h-6 rounded-full bg-indigo-50 flex items-center justify-center text-indigo-700 font-bold">
                        {{ substr($article->user->name, 0, 1) }}
                    </div>
                    <span class="font-medium text-gray-900">{{ $article->user->name }}</span>
                    <span class="text-gray-300">&bull;</span>
                    <time class="text-gray-500">{{ $article->published_at ? $article->published_at->format('M d, Y') : '' }}</time>
                    
                    @if($article->type === 'ebook')
                        <span class="text-gray-300">&bull;</span>
                        <span class="flex items-center text-gray-500 font-medium">
                            <svg class="w-3.5 h-3.5 mr-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332-.477-4.5-1.253"></path></svg>
                            E-Book
                        </span>
                    @endif
                </div>
                
                <a href="{{ route('article.show', $article->slug) }}" class="block">
                    <h3 class="text-2xl font-bold text-gray-900 mb-2 group-hover:text-indigo-600 transition tracking-tight">{{ $article->title }}</h3>
                    <p class="text-gray-600 text-sm md:text-base line-clamp-3 leading-relaxed mb-4 font-serif">
                        {{ strip_tags($article->body) }}
                    </p>
                </a>
                
                <div class="flex items-center justify-between text-gray-500">
                    <div class="flex items-center space-x-5 text-sm font-medium">
                        <span class="bg-gray-50 text-gray-600 px-3 py-1 rounded-full text-xs border border-gray-100 hover:bg-gray-100 transition cursor-pointer">{{ $article->category->name }}</span>
                        
                        <span class="flex items-center space-x-1.5">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.514"></path></svg>
                            <span>{{ $article->likes_count }}</span>
                        </span>
                        <a href="{{ route('article.show', $article->slug) }}#comments" class="flex items-center space-x-1.5 hover:text-gray-900 transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                            <span>{{ $article->comments_count }}</span>
                        </a>
                    </div>

                    <div class="flex items-center space-x-3">
                        @auth
                            <form action="{{ route('article.bookmark', $article->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="hover:text-gray-900 transition text-indigo-600">
                                    <svg class="w-5 h-5" fill="currentColor" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"></path></svg>
                                </button>
                            </form>
                        @endauth
                    </div>
                </div>
                
                @if(!$loop->last)
                <hr class="my-8 border-gray-100">
                @endif
            </article>
        @empty
            <div class="text-center py-20 text-gray-500">
                <svg class="w-12 h-12 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path></svg>
                <p class="text-lg font-medium text-gray-900">Your library is empty.</p>
                <p class="text-sm mt-1">Articles you save will appear here.</p>
                <a href="{{ route('home') }}" class="inline-block mt-4 text-indigo-600 font-semibold hover:underline">Explore articles</a>
            </div>
        @endforelse
    </div>
</div>

<div class="mt-8 flex justify-center pb-12">
    {{ $bookmarks->links() }}
</div>
@endsection
