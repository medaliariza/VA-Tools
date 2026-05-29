@extends('layouts.app', ['title' => 'Organization | VA Tools'])

@section('content')
    <section class="page-hero">
        <div>
            <h1>Organization</h1>
            <p>Premium workspace owners can manage their organization, add employees, and keep team access organized from one place.</p>
        </div>
        <span class="pill">{{ $members->count() }} Members</span>
    </section>

    <section class="split-grid">
        <article class="card">
            <h3>Organization Settings</h3>
            <form method="POST" action="{{ route('organization.update') }}" class="form-grid">
                @csrf
                <div class="field-full">
                    <label for="organization">Organization Name</label>
                    <input id="organization" type="text" name="organization" value="{{ old('organization', $owner->organization ?: $owner->fullname.\"'s Organization\") }}" required>
                </div>
                <div class="field-full">
                    <button type="submit" class="button-dark">Save Organization</button>
                </div>
            </form>
        </article>

        <article class="card">
            <h3>Add Organization Member</h3>
            <form method="POST" action="{{ route('organization.members.store') }}" class="form-grid">
                @csrf
                <div class="field-full">
                    <label for="fullname">Full name</label>
                    <input id="fullname" type="text" name="fullname" value="{{ old('fullname') }}" required>
                </div>
                <div class="field-full">
                    <label for="email">Email address</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required>
                </div>
                <div class="field-full">
                    <label for="department">Department</label>
                    <input id="department" type="text" name="department" value="{{ old('department') }}">
                </div>
                <div class="field">
                    <label for="password">Temporary password</label>
                    <input id="password" type="password" name="password" required>
                </div>
                <div class="field">
                    <label for="password_confirmation">Confirm password</label>
                    <input id="password_confirmation" type="password" name="password_confirmation" required>
                </div>
                <div class="field-full">
                    <button type="submit" class="button-dark">Add Member</button>
                </div>
            </form>
        </article>
    </section>

    <section class="table-card">
        <h3>Organization Members</h3>
        @if($members->isEmpty())
            <p class="helper-text">No members added yet. Create your first employee account above.</p>
        @else
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Department</th>
                            <th>Organization</th>
                            <th>Verified</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($members as $member)
                            <tr>
                                <td>{{ $member->fullname }}</td>
                                <td>{{ $member->email }}</td>
                                <td>{{ $member->department ?: 'Not assigned' }}</td>
                                <td>{{ $member->organization ?: 'Not assigned' }}</td>
                                <td><span class="pill">{{ $member->email_verified_at ? 'Verified' : 'Pending' }}</span></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </section>
@endsection
