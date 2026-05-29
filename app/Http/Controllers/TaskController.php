<?php

namespace App\Http\Controllers;

use App\Models\Todo;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TaskController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();
        $assignableUsers = $user->canManageOrganization()
            ? $user->organizationMembers()->orderBy('fullname')->get()
            : collect();

        $tasks = Todo::query()
            ->with(['assignee', 'assigner'])
            ->where(function ($query) use ($user) {
                $query->where('user_id', $user->id)
                    ->orWhere('assigned_by', $user->id);
            })
            ->latest('id')
            ->get();

        return view('tasks.index', [
            'tasks' => $tasks,
            'taskCount' => $tasks->count(),
            'assignableUsers' => $assignableUsers,
            'canManageOrganization' => $user->canManageOrganization(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $user = $request->user();
        $memberIds = $user->organizationMembers()->pluck('id')->all();

        $validated = $request->validate([
            'task' => ['required', 'string', 'max:255'],
            'assigned_to' => ['nullable', 'integer'],
        ]);

        $assignedTo = $user->id;
        $assignedBy = null;

        if ($user->canManageOrganization() && !empty($validated['assigned_to'])) {
            abort_unless(in_array((int) $validated['assigned_to'], $memberIds, true), 403);
            $assignedTo = (int) $validated['assigned_to'];
            $assignedBy = $user->id;
        }

        Todo::create([
            'user_id' => $assignedTo,
            'assigned_by' => $assignedBy,
            'task' => $validated['task'],
            'status' => 'pending',
        ]);

        return back()->with('status', $assignedBy ? 'Task assigned successfully.' : 'Task added successfully.');
    }

    public function update(Request $request, Todo $task): RedirectResponse
    {
        $user = $request->user();
        $canManageAssignedTask = $user->canManageOrganization() && $task->assigned_by === $user->id;

        abort_unless($task->user_id === $user->id || $canManageAssignedTask, 403);

        $validated = $request->validate([
            'status' => ['required', 'in:pending,completed'],
        ]);

        $task->update($validated);

        return back()->with('status', 'Task status updated.');
    }
}
