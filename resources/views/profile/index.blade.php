@extends('layouts.app', ['title' => 'Profile | VA Tools'])

@section('content')
    <section class="page-hero">
        <div>
            <h1>My Profile</h1>
            <p>Manage your personal details, workspace identity, and profile information in one place.</p>
        </div>
        <span class="pill">{{ strtoupper($user->role) }} Account</span>
    </section>

    <section class="split-grid">
        <article class="card">
            <div class="profile-summary">
                @if($user->avatar)
                    <img class="avatar" src="{{ route('profile.avatar') }}" alt="Profile avatar">
                @else
                    <div class="avatar">{{ strtoupper(substr($user->fullname, 0, 2)) }}</div>
                @endif
                <div>
                    <h3>{{ $user->fullname }}</h3>
                    <p class="meta-text">{{ $user->email }}</p>
                </div>
            </div>

            <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="form-grid">
                @csrf
                <div class="field-full">
                    <label for="bio">Bio</label>
                    <textarea id="bio" name="bio" rows="8">{{ old('bio', $user->bio) }}</textarea>
                </div>
                <div class="field-full">
                    <label for="avatar">Profile Image</label>
                    <input id="avatar" type="file" name="avatar" accept="image/*">
                </div>
                <div class="field-full">
                    <button type="submit" class="button-dark">Save Profile</button>
                </div>
            </form>
        </article>

        <article class="card">
            <h3>Account Summary</h3>
            <ul class="clean-list">
                <li><span>Full name</span><strong>{{ $user->fullname }}</strong></li>
                <li><span>Email</span><strong>{{ $user->email }}</strong></li>
                <li><span>Role</span><strong>{{ $user->role }}</strong></li>
                <li><span>Department</span><strong>{{ $user->department ?: 'Not assigned' }}</strong></li>
                <li><span>Organization</span><strong>{{ $user->organization ?: 'Not assigned' }}</strong></li>
            </ul>
        </article>
    </section>
@endsection
