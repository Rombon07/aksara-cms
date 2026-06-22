<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    /**
     * Handle user request to become an author.
     */
    public function requestAuthor(Request $request): RedirectResponse
    {
        $request->validate([
            'bio' => ['required', 'string', 'max:1000'],
            'portfolio' => ['nullable', 'url', 'max:255'],
        ]);

        $user = $request->user();

        if (in_array($user->role, ['author', 'editor', 'admin'])) {
            return Redirect::route('profile.edit')->with('error', 'Anda sudah memiliki hak akses menulis.');
        }

        if ($user->author_request_status === 'pending') {
            return Redirect::route('profile.edit', ['tab' => 'author-request'])->with('error', 'Permintaan Anda sedang ditinjau.');
        }

        $user->update([
            'author_request_status' => 'pending',
            'author_request_bio' => $request->bio,
            'author_request_portfolio' => $request->portfolio,
            'author_request_at' => now(),
        ]);

        return Redirect::route('profile.edit', ['tab' => 'author-request'])->with('success', 'Permintaan menjadi Author berhasil dikirim!');
    }
}
