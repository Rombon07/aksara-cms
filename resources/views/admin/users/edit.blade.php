<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit User') }}: {{ $user->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if($user->author_request_status === 'pending')
                        <div class="mb-8 p-6 bg-amber-50 border border-amber-200 rounded-xl">
                            <h3 class="text-sm font-bold text-amber-900 mb-2">Author Request Details</h3>
                            <p class="text-xs text-amber-800 leading-relaxed mb-4">
                                User ini mengajukan permohonan menjadi <strong>Author</strong> pada {{ $user->author_request_at ? \Carbon\Carbon::parse($user->author_request_at)->format('M d, Y H:i') : '-' }}.
                            </p>
                            <div class="bg-white p-4 rounded-lg border border-amber-100 text-sm text-gray-700 mb-4">
                                <span class="font-semibold block text-xs text-gray-500 mb-1">BIO MENULIS:</span>
                                <p class="italic">"{{ $user->author_request_bio }}"</p>
                                @if($user->author_request_portfolio)
                                    <div class="mt-3 pt-3 border-t border-gray-100">
                                        <span class="font-semibold block text-xs text-gray-500 mb-1">PORTFOLIO:</span>
                                        <a href="{{ $user->author_request_portfolio }}" target="_blank" class="text-indigo-600 hover:underline break-all text-xs">{{ $user->author_request_portfolio }}</a>
                                    </div>
                                @endif
                            </div>
                            <div class="flex space-x-3">
                                <form action="{{ route('admin.users.handle-request', [$user, 'approve']) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-md text-xs font-semibold transition">Setujui (Upgrade to Author)</button>
                                </form>
                                <form action="{{ route('admin.users.handle-request', [$user, 'reject']) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-md text-xs font-semibold transition">Tolak Permintaan</button>
                                </form>
                            </div>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('admin.users.update', $user) }}">
                        @csrf
                        @method('PUT')

                        <!-- Name -->
                        <div class="mb-4">
                            <x-input-label for="name" :value="__('Name')" />
                            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name', $user->name)" required autofocus autocomplete="name" />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        <!-- Email Address -->
                        <div class="mb-4">
                            <x-input-label for="email" :value="__('Email')" />
                            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email', $user->email)" required autocomplete="username" />
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>

                        <!-- Role -->
                        <div class="mb-4">
                            <x-input-label for="role" :value="__('Role')" />
                            <select id="role" name="role" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                <option value="reader" {{ old('role', $user->role) === 'reader' ? 'selected' : '' }}>Reader</option>
                                <option value="author" {{ old('role', $user->role) === 'author' ? 'selected' : '' }}>Author</option>
                                <option value="editor" {{ old('role', $user->role) === 'editor' ? 'selected' : '' }}>Editor</option>
                                <option value="admin" {{ old('role', $user->role) === 'admin' ? 'selected' : '' }}>Admin</option>
                            </select>
                            <x-input-error :messages="$errors->get('role')" class="mt-2" />
                        </div>

                        <div class="my-6 border-t border-gray-200"></div>
                        <h4 class="text-sm font-medium text-gray-900 mb-4">Change Password (leave blank if you don't want to change it)</h4>

                        <!-- Password -->
                        <div class="mb-4">
                            <x-input-label for="password" :value="__('New Password')" />
                            <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" autocomplete="new-password" />
                            <x-input-error :messages="$errors->get('password')" class="mt-2" />
                        </div>

                        <!-- Confirm Password -->
                        <div class="mb-4">
                            <x-input-label for="password_confirmation" :value="__('Confirm New Password')" />
                            <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" autocomplete="new-password" />
                            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <a href="{{ route('admin.users.index') }}" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 mr-4">
                                Cancel
                            </a>
                            <x-primary-button>
                                {{ __('Update User') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
