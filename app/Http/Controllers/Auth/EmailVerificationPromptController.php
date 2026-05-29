<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Illuminate\View\View;

class EmailVerificationPromptController extends Controller
{
    public function __invoke(Request $request): View|RedirectResponse
    {
        $verificationUrl = null;

        if (app()->environment('local') || in_array(config('mail.default'), ['log', 'array'], true)) {
            $verificationUrl = URL::temporarySignedRoute(
                'verification.verify',
                now()->addMinutes((int) config('auth.verification.expire', 60)),
                [
                    'id' => $request->user()->getKey(),
                    'hash' => sha1($request->user()->getEmailForVerification()),
                ]
            );
        }

        return $request->user()->hasVerifiedEmail()
            ? redirect()->route('dashboard')
            : view('auth.verify-email', [
                'verificationUrl' => session('verification_url', $verificationUrl),
            ]);
    }
}
