<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Review Queue') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($articles as $article)
                    <article class="flex flex-col h-full bg-white rounded-2xl shadow-sm hover:shadow-lg transition-shadow duration-300 overflow-hidden border border-gray-100 group">
                        <div class="relative h-48 overflow-hidden bg-gray-200 shrink-0">
                            <img src="{{ $article->image_url }}" alt="{{ $article->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        </div>
                        
                        <div class="p-6 flex flex-col flex-1">
                            <div class="flex-1">
                                <div class="text-xs font-semibold text-indigo-600 uppercase tracking-wide mb-1">{{ $article->category->name }}</div>
                                <a href="{{ route('article.show', $article->slug) }}" target="_blank">
                                    <h3 class="text-lg font-bold text-gray-900 mb-2 group-hover:text-indigo-600 transition duration-200">{{ $article->title }}</h3>
                                </a>
                                <p class="text-sm text-gray-500 mb-4 line-clamp-3">{{ strip_tags($article->body) }}</p>
                            </div>
                            <div class="text-xs text-gray-400 mb-6 shrink-0">
                                By <span class="font-semibold text-gray-700">{{ $article->user->name }}</span> on {{ $article->created_at->format('M d, Y') }}
                            </div>

                            <div class="flex space-x-3 pt-4 border-t border-gray-100 mt-auto shrink-0">
                                <form action="{{ route('editor.publish', $article) }}" method="POST" class="flex-1">
                                    @csrf
                                    <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-4 rounded-lg text-sm transition focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 shadow-sm">
                                        Publish
                                    </button>
                                </form>
                                <button type="button" onclick="document.getElementById('reviseModal-{{ $article->id }}').classList.remove('hidden')" class="flex-1 bg-yellow-500 hover:bg-yellow-600 text-white font-semibold py-2 px-4 rounded-lg text-sm transition focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500 shadow-sm">
                                    Revise
                                </button>
                            </div>
                        </div>
                    </article>

                    <!-- Revise Modal -->
                    <div id="reviseModal-{{ $article->id }}" class="hidden fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
                        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" onclick="document.getElementById('reviseModal-{{ $article->id }}').classList.add('hidden')"></div>
                            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                                <form action="{{ route('editor.revise', $article) }}" method="POST">
                                    @csrf
                                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                                        <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                            Revise Article
                                        </h3>
                                        <div class="mt-2">
                                            <p class="text-sm text-gray-500 mb-4">Provide notes to the journalist on what needs to be changed.</p>
                                            <textarea name="editor_notes" rows="4" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 mt-1 block w-full sm:text-sm border border-gray-300 rounded-md" required></textarea>
                                        </div>
                                    </div>
                                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                                        <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-yellow-600 text-base font-medium text-white hover:bg-yellow-700 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm">
                                            Send to Draft
                                        </button>
                                        <button type="button" onclick="document.getElementById('reviseModal-{{ $article->id }}').classList.add('hidden')" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                                            Cancel
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-1 md:col-span-2 lg:col-span-3 bg-white p-12 rounded-lg shadow-sm border border-gray-200 text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">No articles pending review</h3>
                        <p class="mt-1 text-sm text-gray-500">The review queue is completely empty.</p>
                    </div>
                @endforelse
            </div>
            
            <div class="mt-6">
                {{ $articles->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
