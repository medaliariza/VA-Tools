<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\AuthOtpMail;
use App\Models\User;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Throwable;

class AuthOtpController extends Controller
{
    public function show(Request $request): View|RedirectResponse
    {
        $pending = $this->pendingOtp($request);

        if (! $pending) {
            return redirect()->route('login');
        }

        return view('auth.otp', [
            'email' => $pending['email'],
            'purpose' => $pending['purpose'],
            'expiresAt' => $pending['expires_at'],
        ]);
    }

    public function verify(Request $request): RedirectResponse
    {
        $pending = $this->pendingOtp($request);

        if (! $pending) {
            return redirect()->route('login');
        }

        $validated = $request->validate([
            'code' => ['required', 'digits:6'],
        ]);

        if (now()->greaterThan($pending['expires_at']) || ! Hash::check($validated['code'], $pending['code_hash'])) {
            throw ValidationException::withMessages([
                'code' => 'The Gmail OTP is invalid or has expired.',
            ]);
        }

        return match ($pending['purpose']) {
            'register' => $this->completeRegistration($request, $pending),
            'verify-email' => $this->completeEmailVerification($request, $pending),
            default => $this->completeLogin($request, $pending),
        };
    }

    public function resend(Request $request): RedirectResponse
    {
        $pending = $this->pendingOtp($request);

        if (! $pending) {
            return redirect()->route('login');
        }

        return $this->sendOtp($request, $pending);
    }

    public function sendLoginOtp(Request $request, User $user, bool $remember): RedirectResponse
    {
        return $this->sendOtp($request, [
            'purpose' => 'login',
            'email' => $user->email,
            'full_name' => $user->fullname,
            'user_id' => $user->getKey(),
            'remember' => $remember,
        ]);
    }

    public function sendRegistrationOtp(Request $request, array $userData): RedirectResponse
    {
        return $this->sendOtp($request, [
            'purpose' => 'register',
            'email' => $userData['email'],
            'full_name' => $userData['fullname'],
            'user_data' => $userData,
        ]);
    }

    public function sendEmailVerificationOtp(Request $request): RedirectResponse
    {
        $user = $request->user();

        return $this->sendOtp($request, [
            'purpose' => 'verify-email',
            'email' => $user->email,
            'full_name' => $user->fullname,
            'user_id' => $user->getKey(),
        ]);
    }

    private function sendOtp(Request $request, array $pending): RedirectResponse
    {
        $code = (string) random_int(100000, 999999);

        $pending = array_merge($pending, [
            'code_hash' => Hash::make($code),
            'expires_at' => now()->addMinutes(10),
        ]);

        try {
            Mail::to($pending['email'])->send(new AuthOtpMail($code, $pending['full_name'], $pending['purpose']));
        } catch (Throwable $exception) {
            Log::warning('Authentication OTP email could not be sent.', [
                'email' => $pending['email'],
                'purpose' => $pending['purpose'],
                'error' => $exception->getMessage(),
            ]);

            return back()->withErrors([
                'email' => 'We could not send the Gmail OTP right now. Check MAIL_USERNAME, MAIL_PASSWORD, MAIL_FROM_ADDRESS, and the Gmail app password.',
            ]);
        }

        $request->session()->put('auth_otp', $pending);

        return redirect()
            ->route('auth.otp')
            ->with('status', 'A 6-digit Gmail OTP has been sent to your email address.');
    }

    private function completeLogin(Request $request, array $pending): RedirectResponse
    {
        $user = User::query()->findOrFail($pending['user_id']);

        Auth::login($user, (bool) ($pending['remember'] ?? false));

        $request->session()->forget('auth_otp');
        $request->session()->regenerate();

        return redirect()->intended(route('dashboard', absolute: false));
    }

    private function completeRegistration(Request $request, array $pending): RedirectResponse
    {
        $userData = $pending['user_data'];

        if (Schema::hasColumn('users', 'email_verified_at')) {
            $userData['email_verified_at'] = now();
        }

        $user = User::create($userData);

        Auth::login($user);

        $request->session()->forget('auth_otp');
        $request->session()->regenerate();

        return redirect()
            ->route('dashboard')
            ->with('status', 'Your account was verified with Gmail OTP and is ready to use.');
    }

    private function completeEmailVerification(Request $request, array $pending): RedirectResponse
    {
        $user = User::query()->findOrFail($pending['user_id']);

        if (! $user->hasVerifiedEmail() && $user->markEmailAsVerified()) {
            event(new Verified($user));
        }

        Auth::login($user);

        $request->session()->forget('auth_otp');
        $request->session()->regenerate();

        return redirect()
            ->route('dashboard')
            ->with('status', 'Your email has been verified with Gmail OTP.');
    }

    private function pendingOtp(Request $request): ?array
    {
        $pending = $request->session()->get('auth_otp');

        return is_array($pending) ? $pending : null;
    }
}
