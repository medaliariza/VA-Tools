<?php

namespace App\Http\Controllers\Auth;

use App\Mail\PasswordResetCodeMail;
use App\Http\Controllers\Controller;
use App\Models\PasswordResetCode;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Throwable;

class PasswordResetLinkController extends Controller
{
    public function create(): View
    {
        return view('auth.forgot-password');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email', 'exists:users,email'],
        ]);

        $email = (string) $request->input('email');
        $user = User::query()->where('email', $email)->firstOrFail();
        $code = (string) random_int(100000, 999999);

        PasswordResetCode::query()->updateOrCreate(
            ['email' => $user->email],
            [
                'code' => $code,
                'expires_at' => now()->addMinutes(15),
            ]
        );

        try {
            Mail::to($user->email)->send(new PasswordResetCodeMail($code, $user->fullname));
        } catch (Throwable $exception) {
            Log::warning('Password reset code email could not be sent.', [
                'user_id' => $user->getKey(),
                'email' => $user->email,
                'error' => $exception->getMessage(),
            ]);

            return back()->withErrors([
                'email' => 'We could not send the Gmail reset email right now. Check MAIL_USERNAME, MAIL_PASSWORD, MAIL_FROM_ADDRESS, and the Gmail app password in Railway variables.',
            ]);
        }

        return redirect()
            ->route('password.reset', ['email' => $user->email])
            ->with('status', 'A 6-digit password reset code has been sent to your Gmail account.');
    }
}
