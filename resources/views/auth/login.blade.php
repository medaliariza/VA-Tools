@extends('layouts.app', ['title' => 'Log In | VA Tools'])

@section('content')
    <section class="auth-shell">
        <div class="auth-card">
            <p class="eyebrow">Authentication</p>
            <h1>Log in to your workspace</h1>
            <p class="helper-text">Laravel sessions, email verification, and role-aware routing are now driving access control.</p>

            <form method="POST" action="{{ route('login') }}" class="form-grid">
                @csrf
                <div class="field-full">
                    <label for="email">Email address</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus>
                </div>

                <div class="field-full">
                    <label for="password">Password</label>
                    <input id="password" type="password" name="password" required>
                </div>

                @include('auth.partials.captcha', ['captcha' => $captcha])

                <div class="field-inline">
                    <label class="checkbox-row">
                        <input type="checkbox" name="remember">
                        <span>Remember me</span>
                    </label>
                    <a href="{{ route('password.request') }}" class="inline-link">Forgot password?</a>
                </div>

                <div class="field-full">
                    <button type="submit" class="button-dark">Login</button>
                </div>
            </form>

            <p class="auth-links">Need an account? <a href="{{ route('register') }}"><strong>Sign up</strong></a></p>
        </div>
    </section>
@endsection
