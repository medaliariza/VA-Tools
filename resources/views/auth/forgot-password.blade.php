@extends('layouts.app', ['title' => 'Forgot Password | VA Tools'])

@section('content')
    <section class="auth-shell">
        <div class="auth-card">
            <p class="eyebrow">Password Recovery</p>
            <h1>Request a reset code</h1>
            <p class="helper-text">A 6-digit password reset code will be sent automatically to your registered Gmail address.</p>

            <form method="POST" action="{{ route('password.email') }}" class="form-grid">
                @csrf
                <div class="field-full">
                    <label for="email">Email address</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus>
                </div>

                <div class="field-full">
                    <button type="submit" class="button-dark">Send Reset Code</button>
                </div>
            </form>

            <p class="auth-links"><a href="{{ route('login') }}"><strong>Back to login</strong></a></p>
        </div>
    </section>
@endsection
