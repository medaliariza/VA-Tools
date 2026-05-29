<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\PasswordReset;
use App\Models\PasswordResetCode;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password as PasswordRule;
use Illuminate\View\View;

class NewPasswordController extends Controller
{
    public function create(Request $request): View
    {
        return view('auth.reset-password', [
            'email' => old('email', $request->query('email')),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email', 'exists:users,email'],
            'code' => ['required', 'digits:6'],
            'password' => [
                'required',
                'confirmed',
                PasswordRule::min(12)->letters()->mixedCase()->numbers()->symbols(),
            ],
        ]);

        $email = (string) $request->input('email');
        $code = (string) $request->input('code');

        $resetCode = PasswordResetCode::query()
            ->where('email', $email)
            ->where('code', $code)
            ->first();

        if (!$resetCode || $resetCode->expires_at->isPast()) {
            return back()
                ->withInput($request->except(['password', 'password_confirmation']))
                ->withErrors(['code' => 'The reset code is invalid or has expired.']);
        }

        $user = User::query()->where('email', $email)->firstOrFail();

        $user->forceFill([
            'password' => Hash::make((string) $request->input('password')),
            'remember_token' => Str::random(60),
        ])->save();

        PasswordResetCode::query()->where('email', $user->email)->delete();

        event(new PasswordReset($user));

        return redirect()->route('login')->with('status', 'Your password has been reset successfully.');
    }
}
