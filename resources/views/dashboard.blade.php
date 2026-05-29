@extends('layouts.app', ['title' => 'Dashboard | VA Tools'])

@section('content')     
    <section class="dashboard-hero">
        <div class="dashboard-hero-inner">
            <p class="dashboard-badge">The Ultimate VA Platform</p>
            <h1 class="dashboard-title">
                Manage Your <span>Workspace</span><br>
                With Total Precision.
            </h1>
            <p class="dashboard-lead">
                VA Tools is the centralized hub for Virtual Assistants and organizations. One
                workspace to manage tasks, notes, reports, messages, inventory, and team access.
            </p>

            <div class="dashboard-actions">
                <a href="{{ route('tasks.index') }}" class="dashboard-primary-action">Open Tasks</a>
                <a href="{{ route('profile.index') }}" class="dashboard-secondary-action">Open Profile</a>
                @if(auth()->user()->canManageOrganization())
                    <a href="{{ route('organization.index') }}" class="dashboard-tertiary-link">Manage Organization</a>
                @endif
                @if(auth()->user()->isAdmin())
                    <a href="{{ route('admin.users.index') }}" class="dashboard-tertiary-link">Manage Access</a>
                @endif
            </div>
        </div>
    </section>

    <section class="dashboard-stats-grid">
        <article class="dashboard-stat-card">
            <span class="dashboard-stat-label">Tasks</span>
            <strong>{{ $todoCount }}</strong>
            <p>Personal task items tracked in your workspace.</p>
        </article>
        <article class="dashboard-stat-card">
            <span class="dashboard-stat-label">Notes</span>
            <strong>{{ $noteCount }}</strong>
            <p>Saved references and written updates for your account.</p>
        </article>
        <article class="dashboard-stat-card">
            <span class="dashboard-stat-label">Inventory</span>
            <strong>{{ $inventoryCount }}</strong>
            <p>Items and equipment currently listed in the system.</p>
        </article>
        <article class="dashboard-stat-card">
            <span class="dashboard-stat-label">Messages</span>
            <strong>{{ $messageCount }}</strong>
            <p>Conversation records available inside the chat module.</p>
        </article>
    </section>

    <section class="dashboard-card-grid">
        <article class="dashboard-panel">
            <div class="dashboard-panel-heading">
                <p class="dashboard-section-label">Recent Activity</p>
                <h3>Recent To-Do Items</h3>
            </div>
            @if($recentTodos->isEmpty())
                <p class="dashboard-helper-text">No tasks yet for this account.</p>
            @else
                <ul class="dashboard-item-list">
                    @foreach($recentTodos as $todo)
                        <li>
                            <div class="dashboard-item-main">
                                <strong>{{ $todo->task }}</strong>
                                <span>Personal task item</span>
                            </div>
                            <span class="dashboard-item-pill">{{ ucfirst($todo->status) }}</span>
                        </li>
                    @endforeach
                </ul>
            @endif
        </article>

        <article class="dashboard-panel">
            <div class="dashboard-panel-heading">
                <p class="dashboard-section-label">Saved Content</p>
                <h3>Saved Notes</h3>
            </div>
            @if($recentNotes->isEmpty())
                <p class="dashboard-helper-text">No notes yet for this account.</p>
            @else
                <ul class="dashboard-item-list">
                    @foreach($recentNotes as $note)
                        <li>
                            <div class="dashboard-item-main">
                                <strong>{{ $note->title }}</strong>
                                <span>{{ \Illuminate\Support\Str::limit($note->content, 90) }}</span>
                            </div>
                            <span class="dashboard-item-pill">Note</span>
                        </li>
                    @endforeach
                </ul>
            @endif
        </article>

        <article class="dashboard-panel">
            <div class="dashboard-panel-heading">
                <p class="dashboard-section-label">System Access</p>
                <h3>Authentication Summary</h3>
            </div>
            <ul class="dashboard-clean-list">
                <li><span>Email verification</span><strong>{{ auth()->user()->hasVerifiedEmail() ? 'Verified' : 'Pending' }}</strong></li>
                <li><span>Session driver</span><strong>Database</strong></li>
                <li><span>Role</span><strong>{{ strtoupper(auth()->user()->role) }}</strong></li>
                <li><span>Premium</span><strong>{{ auth()->user()->isPremium() ? 'Enabled' : 'Standard' }}</strong></li>
            </ul>
        </article>

        <article class="dashboard-panel">
            <div class="dashboard-panel-heading">
                <p class="dashboard-section-label">Organization</p>
                <h3>Account Access Snapshot</h3>
            </div>
            <ul class="dashboard-clean-list">
                <li><span>Admin accounts</span><strong>{{ $adminUserCount }}</strong></li>
                <li><span>User accounts</span><strong>{{ $userAccountCount }}</strong></li>
                <li><span>My members</span><strong>{{ $organizationMemberCount }}</strong></li>
                <li><span>Department</span><strong>{{ auth()->user()->department ?: 'Not assigned' }}</strong></li>
                <li><span>Organization</span><strong>{{ auth()->user()->organization ?: 'Not assigned' }}</strong></li>
            </ul>
        </article>
    </section>
@endsection
