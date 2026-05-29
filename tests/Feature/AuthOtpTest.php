<?php

namespace Tests\Feature;

use App\Mail\AuthOtpMail;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class AuthOtpTest extends TestCase
{
    use RefreshDatabase;

    private function captchaChallenge(): array
    {
        return [
            'prompt' => 'Rotate the puzzle piece until it is upright.',
            'start_rotation' => 90,
            'answer' => 0,
            'piece' => ['label' => 'VA', 'shape' => 'triangle'],
        ];
    }

    public function test_login_requires_gmail_otp_after_valid_credentials_and_captcha(): void
    {
        Mail::fake();

        $user = User::factory()->create([
            'email' => 'login@example.test',
            'password' => 'Password123!@#',
            'email_verified_at' => now(),
        ]);

        $response = $this
            ->withSession(['auth_captcha' => $this->captchaChallenge()])
            ->post(route('login'), [
                'email' => $user->email,
                'password' => 'Password123!@#',
                'captcha_rotation' => 0,
            ]);

        $response->assertRedirect(route('auth.otp'));
        $this->assertGuest();

        $code = null;
        Mail::assertSent(AuthOtpMail::class, function (AuthOtpMail $mail) use (&$code, $user) {
            $code = $mail->code;

            return $mail->hasTo($user->email) && $mail->purpose === 'login';
        });

        $this->post(route('auth.otp.verify'), [
            'code' => $code,
        ])->assertRedirect(route('dashboard', absolute: false));

        $this->assertAuthenticatedAs($user);
    }

    public function test_registration_creates_verified_user_after_gmail_otp(): void
    {
        Mail::fake();

        $response = $this
            ->withSession(['auth_captcha' => $this->captchaChallenge()])
            ->post(route('register'), [
                'fullname' => 'OTP User',
                'email' => 'register@example.test',
                'password' => 'Password123!@#',
                'password_confirmation' => 'Password123!@#',
                'captcha_rotation' => 0,
            ]);

        $response->assertRedirect(route('auth.otp'));
        $this->assertDatabaseMissing('users', ['email' => 'register@example.test']);

        $code = null;
        Mail::assertSent(AuthOtpMail::class, function (AuthOtpMail $mail) use (&$code) {
            $code = $mail->code;

            return $mail->hasTo('register@example.test') && $mail->purpose === 'register';
        });

        $this->post(route('auth.otp.verify'), [
            'code' => $code,
        ])->assertRedirect(route('dashboard'));

        $user = User::query()->where('email', 'register@example.test')->firstOrFail();

        $this->assertAuthenticatedAs($user);
        $this->assertTrue($user->hasVerifiedEmail());
    }

    public function test_email_verification_page_sends_and_accepts_gmail_otp(): void
    {
        Mail::fake();

        $user = User::factory()->unverified()->create([
            'email' => 'verify@example.test',
            'password' => 'Password123!@#',
        ]);

        $response = $this
            ->actingAs($user)
            ->post(route('verification.send'));

        $response->assertRedirect(route('auth.otp'));

        $code = null;
        Mail::assertSent(AuthOtpMail::class, function (AuthOtpMail $mail) use (&$code, $user) {
            $code = $mail->code;

            return $mail->hasTo($user->email) && $mail->purpose === 'verify-email';
        });

        $this
            ->actingAs($user)
            ->post(route('auth.otp.verify'), [
                'code' => $code,
            ])
            ->assertRedirect(route('dashboard'));

        $this->assertTrue($user->fresh()->hasVerifiedEmail());
    }
}
