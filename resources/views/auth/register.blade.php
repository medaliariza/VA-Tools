@extends('layouts.app', ['title' => 'Register | VA Tools'])

@section('content')
    <section class="auth-shell">
        <div class="auth-card">
            <p class="eyebrow">Create Account</p>
            <h1>Set up your workspace</h1>
            <p class="helper-text">Passwords require at least 12 characters with uppercase, lowercase, numbers, and symbols.</p>

            <form method="POST" action="{{ route('register') }}" class="form-grid">
                @csrf
                <div class="field-full">
                    <label for="fullname">Full name</label>
                    <input id="fullname" type="text" name="fullname" value="{{ old('fullname') }}" required>
                </div>

                <div class="field-full">
                    <label for="email">Email address</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required>
                </div>

                <div class="field">
                    <label for="password">Password</label>
                    <input id="password" type="password" name="password" required>
                </div>

                <div class="field">
                    <label for="password_confirmation">Confirm password</label>
                    <input id="password_confirmation" type="password" name="password_confirmation" required>
                </div>

                @include('auth.partials.captcha', ['captcha' => $captcha])

                <div class="field-full">
                    <button type="submit" class="button-dark">Sign Up</button>
                </div>
            </form>

            <p class="auth-links">Already registered? <a href="{{ route('login') }}"><strong>Log in</strong></a></p>
        </div>
    </section>
@endsection
