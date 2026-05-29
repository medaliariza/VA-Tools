@extends('layouts.app', ['title' => 'User Management | VA Tools'])

@section('content')
    <section class="page-hero">
        <div>
            <h1>User Access Control</h1>
            <p>Admins can create accounts, assign departments, and manage the `user` and `admin` access levels from one Laravel-managed screen.</p>
        </div>
        <span class="pill">{{ $users->count() }} Registered Users</span>
    </section>

    <section class="split-grid">
        <article class="card">
            <h3>Create Account</h3>
            <form method="POST" action="{{ route('admin.users.store') }}" class="form-grid">
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
                    <label for="role">Role</label>
                    <select id="role" name="role" required>
                        @foreach($availableRoles as $role)
                            <option value="{{ $role }}" @selected(old('role') === $role)>{{ strtoupper($role) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="field">
                    <label for="department">Department</label>
                    <input id="department" type="text" name="department" value="{{ old('department') }}">
                </div>
                <div class="field-full">
                    <label class="checkbox-row">
                        <input type="checkbox" name="is_premium" value="1" @checked(old('is_premium'))>
                        <span>Premium Organization Access</span>
                    </label>
                </div>
                <div class="field-full">
                    <label for="organization">Organization</label>
                    <input id="organization" type="text" name="organization" value="{{ old('organization') }}">
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
                    <button type="submit" class="button-dark">Create User</button>
                </div>
            </form>
        </article>

        <article class="card">
            <h3>Access Rules</h3>
            <ul class="clean-list">
                <li><span>Email verification</span><strong>Required before full access</strong></li>
                <li><span>Password policy</span><strong>12+ chars with mixed case, number, symbol</strong></li>
                <li><span>Roles available</span><strong>User and Admin</strong></li>
                <li><span>Session handling</span><strong>Managed by Laravel database sessions</strong></li>
            </ul>
        </article>
    </section>

    <section class="table-card">
        <h3>Manage Existing Users</h3>
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Department</th>
                        <th>Organization</th>
                        <th>Verified</th>
                        <th>Save</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                        <tr>
                            <td>{{ $user->fullname }}</td>
                            <td>{{ $user->email }}</td>
                            <td colspan="5">
                                <form method="POST" action="{{ route('admin.users.update', $user) }}" class="inline-actions">
                                    @csrf
                                    @method('PATCH')
                                    <select name="role">
                                        @foreach($availableRoles as $role)
                                            <option value="{{ $role }}" @selected($user->role === $role)>{{ strtoupper($role) }}</option>
                                        @endforeach
                                    </select>
                                    <label class="checkbox-row">
                                        <input type="checkbox" name="is_premium" value="1" @checked($user->is_premium)>
                                        <span>Premium</span>
                                    </label>
                                    <input type="text" name="department" value="{{ $user->department }}" placeholder="Department">
                                    <input type="text" name="organization" value="{{ $user->organization }}" placeholder="Organization">
                                    <span class="pill">{{ $user->email_verified_at ? 'Verified' : 'Pending' }}</span>
                                    <button type="submit" class="button-dark button-small">Save</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </section>
@endsection
