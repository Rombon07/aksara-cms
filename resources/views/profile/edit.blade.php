@extends('layouts.public')

@section('title', 'Profile')

@section('content')
<div class="border-b border-gray-200 mb-6 sticky top-[64px] bg-white z-40 pt-6">
    <div class="flex items-center space-x-2 pb-4">
        <h1 class="text-3xl font-bold text-gray-900 font-serif">Profile Settings</h1>
    </div>
    <nav class="-mb-px flex space-x-8">
        <a href="{{ route('profile.edit') }}" class="{{ request('tab') !== 'author-request' ? 'border-gray-900 text-gray-900 border-b-2' : 'border-transparent text-gray-500 hover:text-gray-700' }} whitespace-nowrap pb-4 px-1 font-medium text-sm transition">Account</a>
        
        @if(auth()->user()->role === 'reader')
        <a href="{{ route('profile.edit', ['tab' => 'author-request']) }}" class="{{ request('tab') === 'author-request' ? 'border-gray-900 text-gray-900 border-b-2' : 'border-transparent text-gray-500 hover:text-gray-700' }} whitespace-nowrap pb-4 px-1 font-medium text-sm transition">Become an Author</a>
        @endif
    </nav>
</div>

<div class="py-4">
    @if(session('success'))
        <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl text-sm font-medium">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-800 rounded-xl text-sm font-medium">
            {{ session('error') }}
        </div>
    @endif

    @if(request('tab') === 'author-request' && auth()->user()->role === 'reader')
        <div class="p-6 sm:p-8 bg-white border border-gray-100 shadow-sm sm:rounded-2xl">
            <div class="max-w-xl">
                @include('profile.partials.author-request-form')
            </div>
        </div>
    @else
        <div class="space-y-10">
            <div class="p-6 sm:p-8 bg-white border border-gray-100 shadow-sm sm:rounded-2xl">
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <div class="p-6 sm:p-8 bg-white border border-gray-100 shadow-sm sm:rounded-2xl">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <div class="p-6 sm:p-8 bg-white border border-gray-100 shadow-sm sm:rounded-2xl">
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    @endif
</div>
@endsection
