@extends('layouts.app', ['title' => 'Reset Password | VA Tools'])

@section('content')
    <section class="auth-shell">
        <div class="auth-card">
            <p class="eyebrow">Reset Password</p>
            <h1>Create a secure password</h1>
            <p class="helper-text">Enter the 6-digit code from your email, then choose a new password with at least 12 characters including uppercase, lowercase, a number, and a symbol.</p>

            <form method="POST" action="{{ route('password.store') }}" class="form-grid">
                @csrf

                <div class="field-full">
                    <label for="email">Email address</label>
                    <input id="email" type="email" name="email" value="{{ $email }}" required autofocus>
                </div>

                <div class="field-full">
                    <label for="code">Reset code</label>
                    <input id="code" type="text" name="code" value="{{ old('code') }}" inputmode="numeric" maxlength="6" required>
                </div>

                <div class="field">
                    <label for="password">Password</label>
                    <input id="password" type="password" name="password" required>
                </div>

                <div class="field">
                    <label for="password_confirmation">Confirm password</label>
                    <input id="password_confirmation" type="password" name="password_confirmation" required>
                </div>

                <div class="field-full">
                    <button type="submit" class="button-dark">Reset Password</button>
                </div>
            </form>

            <p class="auth-links"><a href="{{ route('password.request') }}"><strong>Send another code</strong></a></p>
        </div>
    </section>
@endsection
