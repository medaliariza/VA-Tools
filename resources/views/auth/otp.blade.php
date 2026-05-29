@extends('layouts.app', ['title' => 'Gmail OTP | VA Tools'])

@section('content')
    <section class="auth-shell">
        <div class="auth-card">
            <p class="eyebrow">Gmail OTP</p>
            <h1>Enter your 6-digit code</h1>
            <p class="helper-text">We sent a one-time code to {{ $email }}. It expires in 10 minutes.</p>

            <form method="POST" action="{{ route('auth.otp.verify') }}" class="form-grid">
                @csrf
                <div class="field-full">
                    <label for="code">Verification code</label>
                    <input id="code" type="text" name="code" inputmode="numeric" autocomplete="one-time-code" pattern="[0-9]{6}" maxlength="6" required autofocus>
                </div>

                <div class="field-full">
                    <button type="submit" class="button-dark">Verify Code</button>
                </div>
            </form><br>
            <form method="POST" action="{{ route('auth.otp.resend') }}" class="form-grid">
                @csrf
                <div class="field-full">
                    <button type="submit" class="button-light">Resend Gmail OTP</button>
                </div>
            </form>
        </div>
    </section>
@endsection
