<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('My Articles') }}
            </h2>
            <a href="{{ route('author.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150">
                Write New Article
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    
                    @if(session('success'))
                        <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    @endif

                    <div class="hidden md:block overflow-x-auto rounded-lg shadow-sm border border-gray-200">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Title</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                    <th scope="col" class="relative px-6 py-3"><span class="sr-only">Actions</span></th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($articles as $article)
                                    <tr class="hover:bg-gray-50 transition duration-150">
                                        <td class="px-6 py-4">
                                            <div class="flex items-center space-x-4">
                                                <div class="w-16 h-16 shrink-0 bg-gray-100 rounded-lg overflow-hidden border border-gray-200">
                                                    @if($article->image_url)
                                                        <img src="{{ $article->image_url }}" alt="{{ $article->title }}" class="w-full h-full object-cover">
                                                    @else
                                                        <div class="w-full h-full flex items-center justify-center text-gray-400">
                                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                                        </div>
                                                    @endif
                                                </div>
                                                <div>
                                                    <div class="text-sm font-semibold text-gray-900">{{ $article->title }}</div>
                                                    @if($article->editor_notes && $article->status === 'draft')
                                                        <div class="text-xs text-red-600 mt-2 bg-red-50 p-2 rounded border border-red-100"><strong>Editor Note:</strong> {{ $article->editor_notes }}</div>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-indigo-50 text-indigo-700 border border-indigo-100">
                                                {{ $article->category->name }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($article->status == 'draft')
                                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800 border border-gray-200">Draft</span>
                                            @elseif($article->status == 'pending')
                                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800 border border-yellow-200">Pending Review</span>
                                            @else
                                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800 border border-green-200">Published</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $article->created_at->format('M d, Y') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <a href="{{ route('author.edit', $article) }}" class="text-indigo-600 hover:text-indigo-900 mr-4 transition duration-150">Edit</a>
                                            <form action="{{ route('author.destroy', $article) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900 transition duration-150">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-8 text-center text-gray-500 text-sm">
                                            You haven't written any articles yet.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Mobile stacked cards view -->
                    <div class="block md:hidden space-y-4">
                        @forelse($articles as $article)
                            <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-4 hover:shadow-md transition-shadow duration-200">
                                <div class="flex justify-between items-start mb-2">
                                    <div class="text-base font-bold text-gray-900 pr-2 leading-tight">{{ $article->title }}</div>
                                    <div class="shrink-0">
                                         @if($article->status == 'draft')
                                             <span class="px-2.5 py-0.5 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800 border border-gray-200">Draft</span>
                                         @elseif($article->status == 'pending')
                                             <span class="px-2.5 py-0.5 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800 border border-yellow-200">Pending</span>
                                         @else
                                             <span class="px-2.5 py-0.5 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800 border border-green-200">Published</span>
                                         @endif
                                    </div>
                                </div>
                                <div class="text-sm text-gray-500 mb-3 flex items-center justify-between">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-50 text-indigo-700 border border-indigo-100">{{ $article->category->name }}</span>
                                    <span>{{ $article->created_at->format('M d, Y') }}</span>
                                </div>
                                @if($article->editor_notes && $article->status === 'draft')
                                    <div class="text-xs text-red-600 bg-red-50 p-2.5 rounded-lg mb-4 border border-red-100"><strong>Editor Note:</strong> {{ $article->editor_notes }}</div>
                                @endif
                                <div class="flex justify-end space-x-4 pt-3 border-t border-gray-100">
                                     <a href="{{ route('author.edit', $article) }}" class="text-indigo-600 hover:text-indigo-800 text-sm font-semibold transition duration-200">Edit</a>
                                     <form action="{{ route('author.destroy', $article) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure?');">
                                         @csrf
                                         @method('DELETE')
                                         <button type="submit" class="text-red-600 hover:text-red-800 text-sm font-semibold transition duration-200">Delete</button>
                                     </form>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-8 text-gray-500 bg-gray-50 rounded-xl border border-gray-200 border-dashed">
                                You haven't written any articles yet.
                            </div>
                        @endforelse
                    </div>
                    <div class="mt-4">
                        {{ $articles->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
