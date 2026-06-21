<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Admin Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Stats Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6 mb-8">
                <!-- Stat Card 1 -->
                <div class="bg-gradient-to-br from-indigo-500 to-indigo-600 rounded-2xl shadow-sm text-white p-6 relative overflow-hidden">
                    <div class="absolute right-0 bottom-0 opacity-10 translate-x-2 translate-y-2">
                        <svg class="w-32 h-32" fill="currentColor" viewBox="0 0 24 24"><path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-5 14H7v-2h7v2zm3-4H7v-2h10v2zm0-4H7V7h10v2z"/></svg>
                    </div>
                    <span class="text-xs uppercase tracking-wider font-semibold opacity-75 block mb-1">Total Articles</span>
                    <span class="text-3xl font-extrabold block mb-2">{{ $stats['total_articles'] }}</span>
                    <span class="text-xs bg-white/20 px-2 py-0.5 rounded-full">Writer Uploads</span>
                </div>

                <!-- Stat Card 2 -->
                <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl shadow-sm text-white p-6 relative overflow-hidden">
                    <div class="absolute right-0 bottom-0 opacity-10 translate-x-2 translate-y-2">
                        <svg class="w-32 h-32" fill="currentColor" viewBox="0 0 24 24"><path d="M21 5c-1.11-.35-2.33-.5-3.5-.5-1.95 0-4.05.4-5.5 1.5-1.45-1.1-3.55-1.5-5.5-1.5-1.95 0-4.05.4-5.5 1.5v14.65c0 .25.25.5.5.5.1 0 .15-.05.25-.05C3.1 20.45 5.05 20 6.5 20c1.95 0 4.05.4 5.5 1.5 1.35-.85 3.8-1.5 5.5-1.5 1.65 0 3.35.3 4.75 1.05.1.05.15.1.25.1.25 0 .5-.25.5-.5V6c-.6-.45-1.25-.75-2-1zM20 18.5c-1.2-.75-3-1-4.5-1-1.95 0-4.05.4-5.5 1.5V7.5c1.45-1.1 3.55-1.5 5.5-1.5 1.5 0 3.3.25 4.5 1v11.5z"/></svg>
                    </div>
                    <span class="text-xs uppercase tracking-wider font-semibold opacity-75 block mb-1">Total E-Books</span>
                    <span class="text-3xl font-extrabold block mb-2">{{ $stats['total_ebooks'] }}</span>
                    <span class="text-xs bg-white/20 px-2 py-0.5 rounded-full">PDF Documents</span>
                </div>

                <!-- Stat Card 3 -->
                <div class="bg-gradient-to-br from-rose-500 to-rose-600 rounded-2xl shadow-sm text-white p-6 relative overflow-hidden">
                    <div class="absolute right-0 bottom-0 opacity-10 translate-x-2 translate-y-2">
                        <svg class="w-32 h-32" fill="currentColor" viewBox="0 0 24 24"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>
                    </div>
                    <span class="text-xs uppercase tracking-wider font-semibold opacity-75 block mb-1">Total Likes</span>
                    <span class="text-3xl font-extrabold block mb-2">{{ $stats['total_likes'] }}</span>
                    <span class="text-xs bg-white/20 px-2 py-0.5 rounded-full">Reader Applause</span>
                </div>

                <!-- Stat Card 4 -->
                <div class="bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-2xl shadow-sm text-white p-6 relative overflow-hidden">
                    <div class="absolute right-0 bottom-0 opacity-10 translate-x-2 translate-y-2">
                        <svg class="w-32 h-32" fill="currentColor" viewBox="0 0 24 24"><path d="M21.99 4c0-1.1-.89-2-1.99-2H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h14l4 4-.01-18zM18 14H6v-2h12v2zm0-3H6V9h12v2zm0-3H6V6h12v2z"/></svg>
                    </div>
                    <span class="text-xs uppercase tracking-wider font-semibold opacity-75 block mb-1">Total Comments</span>
                    <span class="text-3xl font-extrabold block mb-2">{{ $stats['total_comments'] }}</span>
                    <span class="text-xs bg-white/20 px-2 py-0.5 rounded-full">Active Responses</span>
                </div>

                <!-- Stat Card 5 -->
                <div class="bg-gradient-to-br from-amber-500 to-amber-600 rounded-2xl shadow-sm text-white p-6 relative overflow-hidden">
                    <div class="absolute right-0 bottom-0 opacity-10 translate-x-2 translate-y-2">
                        <svg class="w-32 h-32" fill="currentColor" viewBox="0 0 24 24"><path d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5c-1.66 0-3 1.34-3 3s1.34 3 3 3zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5C6.34 5 5 6.34 5 8s1.34 3 8 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5c0-2.33-4.67-3.5-7-3.5zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.97 1.97 3.45V19h6v-2.5c0-2.33-4.67-3.5-7-3.5z"/></svg>
                    </div>
                    <span class="text-xs uppercase tracking-wider font-semibold opacity-75 block mb-1">Total Users</span>
                    <span class="text-3xl font-extrabold block mb-2">{{ $stats['total_users'] }}</span>
                    <span class="text-xs bg-white/20 px-2 py-0.5 rounded-full">Registered Accounts</span>
                </div>
            </div>

            <!-- Dashboard Split Row -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Recent Articles -->
                <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="p-6 border-b border-gray-100 flex justify-between items-center">
                        <h3 class="font-bold text-gray-900 text-lg">Recent Content Activity</h3>
                        <span class="text-xs font-semibold text-indigo-600 bg-indigo-50 px-2.5 py-1 rounded-full uppercase">Latest</span>
                    </div>
                    <div class="divide-y divide-gray-100">
                        @forelse($recentArticles as $article)
                            <div class="p-6 flex items-start justify-between hover:bg-gray-50 transition">
                                <div class="flex-1 min-w-0 pr-4">
                                    <div class="flex items-center space-x-2 text-xs text-gray-500 mb-1">
                                        <span class="font-semibold text-indigo-600 uppercase">{{ $article->type }}</span>
                                        <span>&bull;</span>
                                        <span>By {{ $article->user->name }}</span>
                                    </div>
                                    <h4 class="font-bold text-gray-900 leading-snug truncate">
                                        <a href="{{ route('article.show', $article->slug) }}" class="hover:text-indigo-600 transition">{{ $article->title }}</a>
                                    </h4>
                                    <p class="text-xs text-gray-400 mt-1">Uploaded {{ $article->created_at->diffForHumans() }}</p>
                                </div>
                                <div class="shrink-0 flex items-center">
                                    @if($article->status === 'published')
                                        <span class="px-2.5 py-1 text-xs font-semibold rounded-full bg-green-50 text-green-700 uppercase">Published</span>
                                    @elseif($article->status === 'pending')
                                        <span class="px-2.5 py-1 text-xs font-semibold rounded-full bg-yellow-50 text-yellow-700 uppercase">Pending</span>
                                    @else
                                        <span class="px-2.5 py-1 text-xs font-semibold rounded-full bg-gray-50 text-gray-700 uppercase">Draft</span>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <div class="p-8 text-center text-gray-500">No content uploaded yet.</div>
                        @endforelse
                    </div>
                </div>

                <!-- Recent Registrations & Topic distribution -->
                <div class="space-y-8">
                    <!-- Recent Users -->
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                        <div class="p-6 border-b border-gray-100">
                            <h3 class="font-bold text-gray-900 text-lg">New User Registrations</h3>
                        </div>
                        <div class="divide-y divide-gray-100">
                            @forelse($recentUsers as $user)
                                <div class="px-6 py-4 flex items-center justify-between hover:bg-gray-50 transition">
                                    <div class="flex items-center space-x-3 min-w-0">
                                        <div class="w-8 h-8 rounded-full bg-indigo-50 flex items-center justify-center text-indigo-700 font-bold text-xs shrink-0">
                                            {{ substr($user->name, 0, 1) }}
                                        </div>
                                        <div class="min-w-0">
                                            <p class="text-sm font-semibold text-gray-900 truncate">{{ $user->name }}</p>
                                            <p class="text-xs text-gray-400 truncate">{{ $user->email }}</p>
                                        </div>
                                    </div>
                                    <span class="px-2.5 py-0.5 text-[10px] font-bold uppercase rounded-full {{ $user->role === 'admin' ? 'bg-red-50 text-red-700' : ($user->role === 'editor' ? 'bg-yellow-50 text-yellow-700' : ($user->role === 'author' ? 'bg-blue-50 text-blue-700' : 'bg-green-50 text-green-700')) }}">
                                        {{ $user->role }}
                                    </span>
                                </div>
                            @empty
                                <div class="p-6 text-center text-gray-500">No users registered yet.</div>
                            @endforelse
                        </div>
                    </div>

                    <!-- Category stats -->
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                        <div class="p-6 border-b border-gray-100">
                            <h3 class="font-bold text-gray-900 text-lg">Category Distribution</h3>
                        </div>
                        <div class="p-6 space-y-4">
                            @foreach($categoriesStats as $cat)
                                <div>
                                    <div class="flex justify-between items-center text-sm mb-1">
                                        <span class="font-medium text-gray-700">{{ $cat->name }}</span>
                                        <span class="text-gray-500 font-semibold">{{ $cat->articles_count }} articles</span>
                                    </div>
                                    <div class="w-full bg-gray-100 rounded-full h-1.5">
                                        @php
                                            $percent = $stats['total_articles'] > 0 ? ($cat->articles_count / $stats['total_articles']) * 100 : 0;
                                        @endphp
                                        <div class="bg-indigo-600 h-1.5 rounded-full" style="width: {{ $percent }}%"></div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
