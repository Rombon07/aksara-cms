@extends('layouts.public')

@section('title', $article->title)

@section('content')

@if(auth()->check() && auth()->user()->role === 'editor')
    <div class="bg-indigo-50 border border-indigo-100 rounded-xl p-6 mb-8 mt-6">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-4">
            <div>
                <h4 class="text-sm font-bold text-indigo-900">Editor Review Mode</h4>
                <p class="text-xs text-indigo-700">You are viewing this article as it will appear to readers. Status: <span class="uppercase font-bold">{{ $article->status }}</span></p>
            </div>
            @if($article->status === 'pending')
                <div class="flex items-center space-x-3 shrink-0">
                    <form action="{{ route('editor.publish', $article) }}" method="POST">
                        @csrf
                        <button type="submit" class="px-6 py-2 bg-green-600 text-white text-sm font-semibold rounded-lg hover:bg-green-700 transition shadow-sm">
                            Publish Article
                        </button>
                    </form>
                </div>
            @endif
        </div>
        
        @if($article->status === 'pending')
            <div class="border-t border-indigo-100 pt-4 mt-2">
                <form action="{{ route('editor.revise', $article) }}" method="POST">
                    @csrf
                    <label class="block text-xs font-bold text-indigo-900 mb-2">Request Revisions (Editor Notes)</label>
                    <div class="flex items-start gap-3">
                        <textarea name="editor_notes" rows="2" class="w-full text-sm border-indigo-200 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 shadow-sm" placeholder="Explain what needs to be changed..." required></textarea>
                        <button type="submit" class="px-4 py-2 h-full bg-yellow-500 text-white text-sm font-semibold rounded-lg hover:bg-yellow-600 transition shadow-sm whitespace-nowrap">
                            Send back to Draft
                        </button>
                    </div>
                </form>
            </div>
        @endif
    </div>
@endif

<article class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12" x-data="articleInteraction({{ $article->id }}, {{ auth()->check() && $article->likes()->where('user_id', auth()->id())->exists() ? 'true' : 'false' }}, {{ auth()->check() && $article->bookmarks()->where('user_id', auth()->id())->exists() ? 'true' : 'false' }}, {{ $article->likes()->count() }}, {{ $article->comments()->count() }})">
    
    <div class="mb-8 text-center">
        <a href="{{ route('category.show', $article->category->slug) }}" class="inline-block px-3 py-1 bg-indigo-100 text-indigo-800 rounded-full text-sm font-semibold mb-4 hover:bg-indigo-200 transition">{{ $article->category->name }}</a>
        <h1 class="text-4xl font-extrabold text-gray-900 tracking-tight sm:text-5xl mb-6">{{ $article->title }}</h1>
        <div class="flex items-center justify-center text-gray-500 space-x-2 text-sm">
            <span>By <span class="font-bold text-gray-900">{{ $article->user->name }}</span></span>
            <span>&bull;</span>
            <time datetime="{{ $article->published_at }}">{{ $article->published_at ? $article->published_at->format('F d, Y') : 'Pending Publication' }}</time>
        </div>
    </div>

    @if($article->image_url)
        <div class="mb-12 rounded-2xl overflow-hidden shadow-sm border border-gray-100">
            <img src="{{ $article->image_url }}" alt="{{ $article->title }}" class="w-full h-auto object-cover max-h-[600px] bg-gray-50" loading="lazy">
        </div>
    @endif

    <div class="prose prose-lg md:prose-xl mx-auto text-gray-800 leading-relaxed max-w-3xl font-serif">
        @if($article->type === 'ebook')
            <h3 class="text-xl font-bold font-sans text-gray-900 border-b border-gray-200 pb-2 mb-4">Synopsis / Description</h3>
        @endif
        {!! $article->body !!}
    </div>

    @if($article->type === 'ebook' && $article->file_url)
        <div class="max-w-3xl mx-auto mt-12 bg-[#FAFAFA] rounded-2xl border border-gray-200 p-6 md:p-8 text-center shadow-sm">
            <h3 class="text-xl font-bold text-gray-900 mb-6 font-sans">Read Full E-Book</h3>
            <div class="w-full h-[600px] mb-6 bg-white rounded-lg overflow-hidden border border-gray-200 shadow-inner">
                <iframe src="{{ $article->file_url }}" width="100%" height="100%" class="border-0"></iframe>
            </div>
            <a href="{{ $article->file_url }}" target="_blank" download class="inline-flex items-center justify-center px-8 py-3 text-base font-semibold text-white bg-indigo-600 rounded-full hover:bg-indigo-700 transition shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                Download PDF
            </a>
        </div>
    @endif

    <!-- Interaksi: Tombol Like dan Bookmark -->
    <div class="max-w-3xl mx-auto mt-12 py-6 border-y border-gray-200 flex items-center justify-between">
        <div class="flex items-center space-x-6 text-gray-500">
            <!-- Tombol Like (Clap) -->
            <button @click="toggleLike" class="flex items-center space-x-2 hover:text-gray-900 transition" :class="isLiked ? 'text-indigo-600' : ''">
                <!-- Icon menggunakan logic kondisional Alpine (x-show) untuk fill atau outline -->
                <svg x-show="!isLiked" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.514"></path></svg>
                <svg x-show="isLiked" class="w-6 h-6" fill="currentColor" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.514"></path></svg>
                <span class="text-lg" x-text="likesCount"></span>
            </button>

            <!-- Tombol Komentar Scroll -->
            <a href="#comments" class="flex items-center space-x-2 hover:text-gray-900 transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                <span class="text-lg" x-text="commentsCount"></span>
            </a>
        </div>
        
        <div class="flex items-center space-x-4">
            <!-- Tombol Bookmark (Save) -->
            <button @click="toggleBookmark" class="text-gray-500 hover:text-gray-900 transition" :class="isBookmarked ? 'text-indigo-600' : ''">
                <!-- Icon berubah saat di-bookmark -->
                <svg x-show="!isBookmarked" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"></path></svg>
                <svg x-show="isBookmarked" class="w-6 h-6" fill="currentColor" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"></path></svg>
            </button>
        </div>
    </div>

    <!-- Bagian Komentar -->
    <div id="comments" class="max-w-3xl mx-auto mt-12 mb-20">
        <h3 class="text-2xl font-bold text-gray-900 mb-8 font-sans">Responses (<span x-text="commentsCount"></span>)</h3>

        <!-- Form Tambah Komentar -->
        <div class="mb-10 p-6 bg-white rounded-xl shadow-sm border border-gray-100">
            @auth
                <form @submit.prevent="submitComment">
                    <textarea x-model="newComment" rows="3" class="w-full border-0 focus:ring-0 p-0 text-gray-900 placeholder-gray-400 text-lg resize-none" placeholder="What are your thoughts?"></textarea>
                    <div class="flex justify-end mt-4">
                        <!-- Tombol ini akan merespon AJAX dengan mengirimkan komentar ke server -->
                        <button type="submit" :disabled="isSubmitting" class="px-5 py-2 bg-indigo-600 text-white font-medium rounded-full hover:bg-indigo-700 disabled:opacity-50 transition">
                            <span x-show="!isSubmitting">Respond</span>
                            <span x-show="isSubmitting">Posting...</span>
                        </button>
                    </div>
                </form>
            @else
                <div class="text-center py-4">
                    <p class="text-gray-600 mb-4">Please log in to leave a response.</p>
                    <a href="{{ route('login') }}" class="px-5 py-2 border border-gray-300 text-gray-700 font-medium rounded-full hover:bg-gray-50 transition">Log in</a>
                </div>
            @endauth
        </div>

        <!-- Daftar Komentar -->
        <div class="space-y-8">
            <template x-for="comment in comments" :key="comment.id">
                <div class="pb-8 border-b border-gray-100 last:border-0">
                    <div class="flex items-center space-x-3 mb-4">
                        <div class="w-10 h-10 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-700 font-bold text-sm" x-text="comment.user.name.charAt(0)"></div>
                        <div>
                            <p class="font-medium text-gray-900" x-text="comment.user.name"></p>
                            <p class="text-xs text-gray-500" x-text="comment.created_at_human"></p>
                        </div>
                    </div>
                    <p class="text-gray-800 leading-relaxed font-serif" x-text="comment.body"></p>
                </div>
            </template>
        </div>
    </div>

</article>

@push('scripts')
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('articleInteraction', (articleId, initialLiked, initialBookmarked, initialLikesCount, initialCommentsCount) => ({
            articleId: articleId,
            isLiked: initialLiked,
            isBookmarked: initialBookmarked,
            likesCount: initialLikesCount,
            commentsCount: initialCommentsCount,
            
            // Logika komentar
            newComment: '',
            isSubmitting: false,
            // Mengambil daftar komentar yang dipassing dari backend dan mengubahnya menjadi format JSON yang dimengerti JS
            @php
                $commentsData = $article->comments->map(function($c) {
                    return [
                        'id' => $c->id,
                        'body' => $c->body,
                        'user' => ['name' => $c->user->name],
                        'created_at_human' => $c->created_at->diffForHumans()
                    ];
                });
            @endphp
            comments: {!! json_encode($commentsData) !!},
            
            // Fungsi untuk AJAX Toggle Like
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
            
            // Fungsi untuk AJAX Toggle Bookmark
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
            },

            // Fungsi untuk AJAX Submit Comment
            async submitComment() {
                if(this.newComment.trim() === '') return;
                
                this.isSubmitting = true;

                try {
                    let response = await fetch(`/articles/${this.articleId}/comment`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify({ body: this.newComment })
                    });
                    let data = await response.json();
                    
                    if(data.status === 'success') {
                        // Memformat data komentar baru agar sesuai dengan template Alpine JS
                        const formattedComment = {
                            id: data.comment.id,
                            body: data.comment.body,
                            user: { name: data.comment.user.name },
                            created_at_human: 'Just now'
                        };
                        // Menambahkan komentar ke list paling atas
                        this.comments.unshift(formattedComment);
                        this.commentsCount = data.comments_count;
                        this.newComment = ''; // Reset form
                    }
                } catch (error) {
                    console.error('Error submitting comment:', error);
                } finally {
                    this.isSubmitting = false;
                }
            }
        }));
    });
</script>
@endpush
@endsection
