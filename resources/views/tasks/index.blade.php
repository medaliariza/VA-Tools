@extends('layouts.app', ['title' => 'Tasks | VA Tools'])

@section('content')
    <section class="page-hero">
        <div>
            <h1>Tasks</h1>
            <p>Manage personal to-do items and mark progress without leaving the Laravel workspace.</p>
        </div>
        <span class="pill">{{ $taskCount }} Active Records</span>
    </section>

    <section class="split-grid">
        <article class="card">
            <h3>{{ $canManageOrganization ? 'Create Or Assign Task' : 'Add Task' }}</h3>
            <form method="POST" action="{{ route('tasks.store') }}" class="form-grid">
                @csrf
                @if($canManageOrganization)
                    <div class="field-full">
                        <label for="assigned_to">Assign To</label>
                        <select id="assigned_to" name="assigned_to">
                            <option value="">Myself</option>
                            @foreach($assignableUsers as $member)
                                <option value="{{ $member->id }}" @selected(old('assigned_to') == $member->id)>{{ $member->fullname }}</option>
                            @endforeach
                        </select>
                    </div>
                @endif
                <div class="field-full">
                    <label for="task">Task</label>
                    <input id="task" type="text" name="task" value="{{ old('task') }}" required>
                </div>
                <div class="field-full">
                    <button type="submit" class="button-dark">Save Task</button>
                </div>
            </form>
        </article>

        <article class="card">
            <h3>Task Overview</h3>
            <ul class="clean-list">
                <li><span>Total tasks</span><strong>{{ $taskCount }}</strong></li>
                <li><span>Pending default</span><strong>Yes</strong></li>
                <li><span>Status tracking</span><strong>Pending or completed</strong></li>
                @if($canManageOrganization)
                    <li><span>Team assignment</span><strong>Premium enabled</strong></li>
                @endif
            </ul>
        </article>
    </section>

    <section class="table-card">
        <h3>My Tasks</h3>
        @if($tasks->isEmpty())
            <p class="helper-text">No tasks yet. Add one to get started.</p>
        @else
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>Task</th>
                            <th>Assigned To</th>
                            <th>Assigned By</th>
                            <th>Status</th>
                            <th>Update</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($tasks as $task)
                            <tr>
                                <td>{{ $task->task }}</td>
                                <td>{{ $task->assignee?->fullname ?? 'Unknown' }}</td>
                                <td>{{ $task->assigner?->fullname ?? 'Self' }}</td>
                                <td><span class="pill">{{ ucfirst($task->status) }}</span></td>
                                <td>
                                    <form method="POST" action="{{ route('tasks.update', $task) }}" class="inline-actions">
                                        @csrf
                                        @method('PATCH')
                                        <select name="status">
                                            <option value="pending" @selected($task->status === 'pending')>Pending</option>
                                            <option value="completed" @selected($task->status === 'completed')>Completed</option>
                                        </select>
                                        <button type="submit" class="button-dark button-small">Save</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </section>
@endsection
