@extends('layouts.public')

@section('title', $category->name . ' News')

@section('content')
<div class="border-b border-gray-200 mb-6 sticky top-[64px] bg-white z-40 pt-6">
    <div class="flex items-center space-x-2 pb-4">
        <span class="text-sm text-gray-500 uppercase tracking-widest font-semibold">Category:</span>
        <h1 class="text-2xl font-bold text-gray-900 font-serif">{{ $category->name }}</h1>
    </div>
</div>

<div class="flex flex-col">
    @forelse($articles as $article)
        <article x-data="interaction({{ $article->id }}, {{ ($article->is_liked ?? false) ? 'true' : 'false' }}, {{ ($article->is_bookmarked ?? false) ? 'true' : 'false' }}, {{ $article->likes_count ?? 0 }}, {{ $article->comments_count ?? 0 }})" class="border-b border-gray-100 py-8 last:border-0 group">
            <div class="flex items-center space-x-2 text-sm text-gray-900 mb-3">
                <div class="w-6 h-6 rounded-full bg-gray-200 flex items-center justify-center text-xs font-bold text-gray-600">{{ substr($article->user->name, 0, 1) }}</div>
                <span class="font-medium text-gray-900">{{ $article->user->name }}</span>
                <span class="text-gray-500">in</span>
                <span class="font-medium">{{ $category->name }}</span>
                <span class="text-gray-500">&middot;</span>
                <span class="text-gray-500">{{ $article->published_at->format('M d, Y') }}</span>
            </div>
            
            <div class="flex justify-between gap-6 md:gap-10">
                <div class="flex-1">
                    <a href="{{ route('article.show', $article->slug) }}">
                        <h2 class="text-xl sm:text-[22px] font-bold text-gray-900 leading-tight tracking-tight mb-2 font-sans group-hover:text-indigo-600 transition">{{ $article->title }}</h2>
                        <p class="text-gray-600 line-clamp-2 leading-relaxed font-serif">{{ strip_tags($article->body) }}</p>
                    </a>
                    
                    <div class="flex items-center justify-between mt-6 text-gray-500">
                        <div class="flex items-center space-x-6 text-sm">
                            <button @click="toggleLike" class="flex items-center space-x-1.5 hover:text-gray-900 transition" :class="isLiked ? 'text-indigo-600' : ''">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.514"></path></svg>
                                <span x-text="likesCount"></span>
                            </button>
                            <a href="{{ route('article.show', $article->slug) }}#comments" class="flex items-center space-x-1.5 hover:text-gray-900 transition">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                                <span x-text="commentsCount"></span>
                            </a>
                        </div>
                        <div class="flex items-center space-x-4">
                            <button @click="toggleBookmark" class="hover:text-gray-900 transition" :class="isBookmarked ? 'text-indigo-600' : ''">
                                <svg x-show="!isBookmarked" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"></path></svg>
                                <svg x-show="isBookmarked" class="w-5 h-5" fill="currentColor" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"></path></svg>
                            </button>
                        </div>
                    </div>
                </div>
                
                @if($article->image_url)
                <div class="shrink-0 w-24 h-24 sm:w-32 sm:h-32 pt-2">
                    <a href="{{ route('article.show', $article->slug) }}">
                        <img src="{{ $article->image_url }}" alt="Thumbnail" class="w-full h-full object-cover bg-gray-100" loading="lazy">
                    </a>
                </div>
                @endif
            </div>
        </article>
    @empty
        <div class="text-center py-20 text-gray-500">
            No stories found in this category.
        </div>
    @endforelse
</div>

<div class="mt-8">
    {{ $articles->links() }}
</div>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('interaction', (articleId, initialLiked, initialBookmarked, initialLikesCount, initialCommentsCount) => ({
            articleId: articleId,
            isLiked: initialLiked,
            isBookmarked: initialBookmarked,
            likesCount: initialLikesCount,
            commentsCount: initialCommentsCount,
            
            async toggleLike() {
                @if(!auth()->check())
                    window.location.href = "{{ route('login') }}";
                    return;
                @endif
                
                try {
                    let response = await fetch(`/articles/${this.articleId}/like`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        }
                    });
                    let data = await response.json();
                    this.isLiked = data.status === 'liked';
                    this.likesCount = data.likes_count;
                } catch (error) {
                    console.error('Error toggling like:', error);
                }
            },
            
            async toggleBookmark() {
                @if(!auth()->check())
                    window.location.href = "{{ route('login') }}";
                    return;
                @endif
                
                try {
                    let response = await fetch(`/articles/${this.articleId}/bookmark`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        }
                    });
                    let data = await response.json();
                    this.isBookmarked = data.status === 'saved';
                } catch (error) {
                    console.error('Error toggling bookmark:', error);
                }
            }
        }));
    });
</script>
@endsection
