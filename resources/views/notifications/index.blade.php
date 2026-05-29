@extends('layouts.app', ['title' => 'Notifications | VA Tools'])

@section('content')
    <section class="page-hero">
        <div>
            <h1>Notifications</h1>
            <p>Review account alerts, task updates, report requests, and workspace messages in one place.</p>
        </div>
        <div class="inline-actions">
            <span class="pill">{{ $unreadCount }} Unread</span>
            @if($unreadCount > 0)
                <form method="POST" action="{{ route('notifications.read-all') }}">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="button-light button-small">Read All</button>
                </form>
            @endif
        </div>
    </section>

    <section class="table-card">
        <h3>Recent Notifications</h3>
        @if($notifications->isEmpty())
            <p class="helper-text">No notifications yet.</p>
        @else
            <ul class="clean-list">
                @foreach($notifications as $notification)
                    <li>
                        <span>{{ $notification->text }}</span>
                        <div class="inline-actions">
                            <strong>{{ $notification->seen ? 'Seen' : 'New' }}</strong>
                            @unless($notification->seen)
                                <form method="POST" action="{{ route('notifications.read', $notification) }}">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="button-light button-small">Mark Read</button>
                                </form>
                            @endunless
                            <form method="POST" action="{{ route('notifications.destroy', $notification) }}">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="button-light button-small">Delete</button>
                            </form>
                        </div>
                    </li>
                @endforeach
            </ul>
        @endif
    </section>
@endsection
