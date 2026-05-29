<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ProfileController extends Controller
{
    public function index(Request $request): View
    {
        return view('profile.index', [
            'user' => $request->user(),
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'bio' => ['nullable', 'string'],
            'avatar' => ['nullable', 'image', 'max:2048'],
        ]);

        $user = $request->user();
        $user->bio = $validated['bio'] ?? null;

        if ($request->hasFile('avatar')) {
            if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
            }

            $user->avatar = $request->file('avatar')->store('avatars', 'public');
        }

        $user->save();

        return back()->with('status', 'Profile updated successfully.');
    }

    public function avatar(Request $request): StreamedResponse
    {
        $user = $request->user();

        abort_unless($user->avatar && Storage::disk('public')->exists($user->avatar), 404);

        return Storage::disk('public')->response($user->avatar);
    }
}
