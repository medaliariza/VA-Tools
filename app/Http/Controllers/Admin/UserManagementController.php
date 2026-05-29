<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class UserManagementController extends Controller
{
    private const AVAILABLE_ROLES = ['user', 'admin'];

    public function index(): View
    {
        return view('admin.users', [
            'users' => User::query()->orderBy('fullname')->get(),
            'availableRoles' => self::AVAILABLE_ROLES,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'fullname' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'role' => ['required', Rule::in(self::AVAILABLE_ROLES)],
            'is_premium' => ['nullable', 'boolean'],
            'department' => ['nullable', 'string', 'max:120'],
            'organization' => ['nullable', 'string', 'max:120'],
            'password' => ['required', 'confirmed', Password::min(12)->letters()->mixedCase()->numbers()->symbols()],
        ]);

        $user = User::create([
            'fullname' => $validated['fullname'],
            'email' => $validated['email'],
            'role' => $validated['role'],
            'is_premium' => $request->boolean('is_premium'),
            'department' => $validated['department'] ?? null,
            'organization' => $validated['organization'] ?? null,
            'password' => $validated['password'],
            'created_by' => $request->user()->id,
        ]);

        event(new Registered($user));

        return back()->with('status', 'Account created successfully. Verification email has been queued.');
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'role' => ['required', Rule::in(self::AVAILABLE_ROLES)],
            'is_premium' => ['nullable', 'boolean'],
            'department' => ['nullable', 'string', 'max:120'],
            'organization' => ['nullable', 'string', 'max:120'],
        ]);

        $user->update([
            ...$validated,
            'is_premium' => $request->boolean('is_premium'),
        ]);

        return back()->with('status', 'User access updated successfully.');
    }
}
