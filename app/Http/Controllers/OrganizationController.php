<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class OrganizationController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();
        abort_unless($user->canManageOrganization(), 403);

        return view('organization.index', [
            'owner' => $user,
            'members' => $user->organizationMembers()->orderBy('fullname')->get(),
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $user = $request->user();
        abort_unless($user->canManageOrganization(), 403);

        $validated = $request->validate([
            'organization' => ['required', 'string', 'max:120'],
        ]);

        $user->update([
            'organization' => $validated['organization'],
        ]);

        $user->organizationMembers()->update([
            'organization' => $validated['organization'],
        ]);

        return back()->with('status', 'Organization details updated successfully.');
    }

    public function storeMember(Request $request): RedirectResponse
    {
        $user = $request->user();
        abort_unless($user->canManageOrganization(), 403);

        $validated = $request->validate([
            'fullname' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'department' => ['nullable', 'string', 'max:120'],
            'password' => ['required', 'confirmed', Password::min(12)->letters()->mixedCase()->numbers()->symbols()],
        ]);

        $member = User::create([
            'fullname' => $validated['fullname'],
            'email' => $validated['email'],
            'password' => $validated['password'],
            'role' => 'user',
            'is_premium' => false,
            'department' => $validated['department'] ?? null,
            'organization' => $user->organization ?: $user->fullname."'s Organization",
            'created_by' => $user->id,
        ]);

        event(new Registered($member));

        return back()->with('status', 'Organization member added successfully.');
    }
}
