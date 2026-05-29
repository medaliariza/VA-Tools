@extends('layouts.app', ['title' => 'Verify Email | VA Tools'])

@section('content')
    <section class="auth-shell">
        <div class="auth-card auth-card-wide">
            <div class="verify-grid">
                <div>
                    <p class="eyebrow">Email Verification</p>
                    <h1>Verify your email</h1>
                    <p class="helper-text">
                        Your account is almost ready. Verify your email address to unlock the dashboard,
                        tasks, notes, reports, inventory, and role-based access.
                    </p>

                    <div class="verify-steps">
                        <div class="verify-step">
                            <strong>1</strong>
                            <span>Send a Gmail OTP to your account email address.</span>
                        </div>
                        <div class="verify-step">
                            <strong>2</strong>
                            <span>Enter the 6-digit code on the OTP screen.</span>
                        </div>
                        <div class="verify-step">
                            <strong>3</strong>
                            <span>Once verified, the app will allow full access to your workspace.</span>
                        </div>
                    </div>
                </div>

                <div class="verify-panel">
                    @if (session('mail_issue'))
                        <div class="dev-link-box">
                            <p class="eyebrow">Mail Issue</p>
                            <p class="helper-text">{{ session('mail_issue') }}</p>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('verification.send') }}" class="form-grid">
                        @csrf
                        <div class="field-full">
                            <button type="submit" class="button-dark">Send Gmail OTP</button>
                        </div>
                    </form>

                    <form method="POST" action="{{ route('logout') }}" class="inline-form">
                        @csrf
                        <button type="submit" class="button-light">Logout</button>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection
