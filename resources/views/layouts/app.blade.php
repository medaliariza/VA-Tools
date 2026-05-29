<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'VA Tools' }}</title>
    @if (file_exists(public_path('build/manifest.json')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    @endif
</head>
<body class="dashboard-theme app-theme">
    <div class="page-shell dashboard-shell app-shell">
        <header class="topbar dashboard-topbar app-topbar">
            <a class="brand" href="{{ auth()->check() ? route('dashboard') : route('home') }}">
                <img class="brand-mark brand-logo-image" src="{{ asset('images/va-tools-logo.png') }}" alt="VA Tools logo">
                <span>VA Tools</span>
            </a>

            @auth
                @php($unreadNotifications = \App\Models\Notification::query()->where('user_id', auth()->id())->where('seen', false)->count())
                @php($unreadChats = \App\Models\Message::query()->where('receiver_id', auth()->id())->whereNull('read_at')->count())
                <nav class="nav-links" aria-label="Primary navigation">
                    <a href="{{ route('dashboard') }}" @class(['active' => request()->routeIs('dashboard')])>Dashboard</a>
                    <a href="{{ route('tasks.index') }}" @class(['active' => request()->routeIs('tasks.*')])>Tasks</a>
                    <a href="{{ route('notes.index') }}" @class(['active' => request()->routeIs('notes.*')])>Notes</a>
                    <a href="{{ route('inventory.index') }}" @class(['active' => request()->routeIs('inventory.*')])>Inventory</a>
                    <a href="{{ route('reports.index') }}" @class(['active' => request()->routeIs('reports.*')])>Reports</a>
                    <a href="{{ route('profile.index') }}" @class(['nav-icon-link', 'active' => request()->routeIs('profile.*')]) aria-label="Profile" title="Profile">
                        <svg class="nav-icon" aria-hidden="true" viewBox="0 0 24 24" fill="none">
                            <path d="M20 21a8 8 0 0 0-16 0" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                            <circle cx="12" cy="7" r="4" stroke="currentColor" stroke-width="2"/>
                        </svg>
                    </a>
                    <a href="{{ route('chat.index') }}" @class(['nav-icon-link', 'active' => request()->routeIs('chat.*')]) aria-label="Chat" title="Chat">
                        <svg class="nav-icon" aria-hidden="true" viewBox="0 0 24 24" fill="none">
                            <path d="M21 15a4 4 0 0 1-4 4H8l-5 3V7a4 4 0 0 1 4-4h10a4 4 0 0 1 4 4v8Z" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>
                        </svg>
                        @if($unreadChats > 0)
                            <span class="nav-badge">{{ $unreadChats }}</span>
                        @endif
                    </a>
                    <a href="{{ route('notifications.index') }}" @class(['nav-icon-link', 'active' => request()->routeIs('notifications.*')]) aria-label="Notifications" title="Notifications">
                        <svg class="nav-icon" aria-hidden="true" viewBox="0 0 24 24" fill="none">
                            <path d="M18 8a6 6 0 1 0-12 0c0 7-3 7-3 9h18c0-2-3-2-3-9Z" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>
                            <path d="M10 21h4" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                        </svg>
                        @if($unreadNotifications > 0)
                            <span class="nav-badge">{{ $unreadNotifications }}</span>
                        @endif
                    </a>
                    @if (auth()->user()->canManageOrganization())
                        <a href="{{ route('organization.index') }}" @class(['active' => request()->routeIs('organization.*')])>Organization</a>
                    @endif
                    @if (auth()->user()->isAdmin())
                        <a href="{{ route('admin.users.index') }}" @class(['active' => request()->routeIs('admin.*')])>Admin</a>
                    @endif
                </nav>
            @endauth

            <div class="topbar-actions">
                @auth
                    <span class="user-chip">{{ auth()->user()->fullname }} | {{ strtoupper(auth()->user()->role) }}</span>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="button-dark button-small">Logout</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="button-dark button-small">Login</a>
                    <a href="{{ route('register') }}" class="button-light button-small">Sign Up</a>
                @endauth
            </div>
        </header>

        @if (session('status'))
            <div class="flash flash-success">{{ session('status') }}</div>
        @endif

        @if ($errors->any())
            <div class="flash flash-error">
                @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        @yield('content')
    </div>
</body>
</html>
