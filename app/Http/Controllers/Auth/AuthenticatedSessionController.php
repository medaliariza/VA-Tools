<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Auth\Concerns\UsesAuthCaptcha;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    use UsesAuthCaptcha;

    public function create(): View
    {
        return view('auth.login', [
            'captcha' => $this->refreshCaptcha(request()),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
            'captcha_rotation' => ['required', 'integer'],
        ]);

        $this->validateCaptcha($request);

        $authCredentials = [
            'email' => $credentials['email'],
            'password' => $credentials['password'],
        ];

        if (! Auth::validate($authCredentials)) {
            throw ValidationException::withMessages([
                'email' => 'The provided credentials do not match our records.',
            ]);
        }

        $user = User::query()
            ->where('email', $credentials['email'])
            ->firstOrFail();

        return app(AuthOtpController::class)->sendLoginOtp(
            $request,
            $user,
            $request->boolean('remember')
        );
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
