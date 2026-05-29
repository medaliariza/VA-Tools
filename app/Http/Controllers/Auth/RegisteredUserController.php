<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Auth\Concerns\UsesAuthCaptcha;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    use UsesAuthCaptcha;

    public function create(): View
    {
        return view('auth.register', [
            'captcha' => $this->refreshCaptcha(request()),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'fullname' => ['required', 'string', 'max:120'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'captcha_rotation' => ['required', 'integer'],
            'password' => [
                'required',
                'confirmed',
                Password::min(12)->letters()->mixedCase()->numbers()->symbols(),
            ],
        ]);

        $this->validateCaptcha($request);

        $userData = [
            'fullname' => $validated['fullname'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'user',
        ];

        if (Schema::hasColumn('users', 'name')) {
            $userData['name'] = $validated['fullname'];
        }

        return app(AuthOtpController::class)->sendRegistrationOtp($request, $userData);
    }
}
