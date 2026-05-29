@extends('layouts.app', ['title' => 'Chat | VA Tools'])

@section('content')
    <section class="page-hero">
        <div>
            <h1>Messages</h1>
            <p>Chat in direct conversations, keep replies organized by contact, and manage your workspace messages in one place.</p>
        </div>
        <div class="inline-actions">
            <span class="pill">{{ $messageCount }} Messages</span>
            <span class="pill">{{ $unreadCount }} Unread</span>
            @if($unreadCount > 0)
                <form method="POST" action="{{ route('chat.read-all') }}">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="button-light button-small">Read All</button>
                </form>
            @endif
        </div>
    </section>

    <section class="chat-layout">
        <article class="card">
            <h3>Contacts</h3>
            <form method="GET" action="{{ route('chat.index') }}" class="form-grid">
                <div class="field-full">
                    <label for="search">Search users</label>
                    <input id="search" type="search" name="search" value="{{ $search }}" placeholder="Name, email, or role">
                </div>
                <div class="field-full">
                    <button type="submit" class="button-dark">Search</button>
                </div>
            </form><br>

            @if($conversationSummaries->isEmpty())
                <p class="helper-text">{{ $search ? 'No users matched your search.' : 'No other users are available for messaging yet.' }}</p>
            @else
                <div class="conversation-list">
                    @foreach($conversationSummaries as $summary)
                        @php($contact = $summary['contact'])
                        @php($latestMessage = $summary['latest_message'])
                        @php($contactUnreadCount = $summary['unread_count'])
                        <a
                            href="{{ route('chat.index', ['contact' => $contact->id]) }}"
                            class="conversation-item {{ $selectedContact?->id === $contact->id ? 'active' : '' }}"
                        >
                            <div class="conversation-item-main">
                                <strong>{{ $contact->fullname }}</strong>
                                <span>{{ strtoupper($contact->role) }}</span>
                            </div>
                            @if($contactUnreadCount > 0)
                                <span class="message-badge">{{ $contactUnreadCount }}</span>
                            @endif
                            <small>{{ $contact->email }}</small>
                            <small>
                                {{ $latestMessage ? \Illuminate\Support\Str::limit($latestMessage->message, 56) : 'Start a new conversation' }}
                            </small>
                        </a>
                    @endforeach
                </div>
            @endif
        </article>

        <article class="card">
            <div class="chat-panel-header">
                <div>
                    <h3>{{ $selectedContact?->fullname ?? 'Choose a contact' }}</h3>
                    <p class="helper-text">
                        {{ $selectedContact ? 'Direct conversation with '.strtoupper($selectedContact->role) : 'Select a contact from the left to start messaging.' }}
                    </p>
                </div>
                @if($selectedContact)
                    <div class="inline-actions">
                        <span class="pill">{{ strtoupper($selectedContact->role) }}</span>
                        <form method="POST" action="{{ route('chat.read') }}">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="contact_id" value="{{ $selectedContact->id }}">
                            <button type="submit" class="button-light button-small">Mark Read</button>
                        </form>
                    </div>
                @endif
            </div>

            @if($selectedContact)
                <div class="chat-thread">
                    @forelse($messages as $message)
                        <div class="chat-bubble {{ $message->sender_id === $userId ? 'chat-bubble-own' : 'chat-bubble-other' }}">
                            <strong>{{ $message->sender_id === $userId ? 'You' : $message->sender?->fullname }}</strong>
                            <div>{{ $message->message }}</div>
                            <small>{{ $message->created_at?->format('M d, Y h:i A') }}</small>
                            <form method="POST" action="{{ route('chat.destroy', $message) }}" class="inline-form">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="button-light button-small">Delete</button>
                            </form>
                        </div>
                    @empty
                        <p class="helper-text">No messages in this conversation yet. Send the first one below.</p>
                    @endforelse
                </div>

                <form method="POST" action="{{ route('chat.store') }}" class="form-grid">
                    @csrf
                    <input type="hidden" name="receiver_id" value="{{ $selectedContact->id }}">
                    <div class="field-full">
                        <label for="message">Message</label>
                        <textarea id="message" name="message" rows="6" required>{{ old('message') }}</textarea>
                    </div>
                    <div class="field-full">
                        <button type="submit" class="button-dark">Send Message</button>
                    </div>
                </form>
            @else
                <p class="helper-text">There are no available contacts yet.</p>
            @endif
        </article>
    </section>
@endsection
