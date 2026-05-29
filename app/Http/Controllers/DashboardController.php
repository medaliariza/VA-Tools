<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use App\Models\Message;
use App\Models\Note;
use App\Models\Todo;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(Request $request): View
    {
        $user = $request->user();

        $todoQuery = Todo::query()
            ->where(function ($query) use ($user) {
                $query->where('user_id', $user->id)
                    ->orWhere('assigned_by', $user->id);
            })
            ->latest('id');
        $noteQuery = Note::query()->where('user_id', $user->id)->latest('id');

        return view('dashboard', [
            'todoCount' => (clone $todoQuery)->count(),
            'noteCount' => (clone $noteQuery)->count(),
            'inventoryCount' => Inventory::query()->count(),
            'messageCount' => Message::query()->where('sender_id', $user->id)->count(),
            'recentTodos' => $todoQuery->limit(4)->get(),
            'recentNotes' => $noteQuery->limit(4)->get(),
            'adminUserCount' => User::query()->where('role', 'admin')->count(),
            'userAccountCount' => User::query()->where('role', 'user')->count(),
            'organizationMemberCount' => $user->organizationMembers()->count(),
        ]);
    }
}
