@extends('layouts.public')

@section('title', 'Profile')

@section('content')
<div class="border-b border-gray-200 mb-6 sticky top-[64px] bg-white z-40 pt-6">
    <div class="flex items-center space-x-2 pb-4">
        <h1 class="text-3xl font-bold text-gray-900 font-serif">Profile Settings</h1>
    </div>
    <nav class="-mb-px flex space-x-8">
        <a href="#" class="border-gray-900 text-gray-900 whitespace-nowrap pb-4 px-1 border-b-2 font-medium text-sm transition">Account</a>
    </nav>
</div>

<div class="py-4">
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
</div>
@endsection
