<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ChatController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();
        $search = trim((string) $request->query('search', ''));

        $contacts = User::query()
            ->whereKeyNot($user->id)
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query->where('fullname', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('role', 'like', "%{$search}%");
                });
            })
            ->orderBy('fullname')
            ->get();

        $selectedContactId = $request->integer('contact');

        if (!$selectedContactId || !$contacts->contains('id', $selectedContactId)) {
            $selectedContactId = $user->isAdmin()
                ? $contacts->first()?->id
                : $contacts->firstWhere('role', 'admin')?->id ?? $contacts->first()?->id;
        }

        $messages = collect();
        $selectedContact = null;

        if ($selectedContactId) {
            $selectedContact = $contacts->firstWhere('id', $selectedContactId);

            $messages = Message::query()
                ->with(['sender', 'receiver'])
                ->where(function ($query) use ($user, $selectedContactId) {
                    $query->where('sender_id', $user->id)
                        ->where('receiver_id', $selectedContactId);
                })
                ->orWhere(function ($query) use ($user, $selectedContactId) {
                    $query->where('sender_id', $selectedContactId)
                        ->where('receiver_id', $user->id);
                })
                ->oldest('id')
                ->get();
        }

        $conversationSummaries = $contacts->map(function (User $contact) use ($user) {
            $latestMessage = Message::query()
                ->where(function ($query) use ($user, $contact) {
                    $query->where('sender_id', $user->id)
                        ->where('receiver_id', $contact->id);
                })
                ->orWhere(function ($query) use ($user, $contact) {
                    $query->where('sender_id', $contact->id)
                        ->where('receiver_id', $user->id);
                })
                ->latest('id')
                ->first();
            $unreadCount = Message::query()
                ->where('sender_id', $contact->id)
                ->where('receiver_id', $user->id)
                ->whereNull('read_at')
                ->count();

            return [
                'contact' => $contact,
                'latest_message' => $latestMessage,
                'unread_count' => $unreadCount,
            ];
        })->sortByDesc(fn (array $summary) => $summary['latest_message']?->id ?? 0)->values();

        $unreadCount = Message::query()
            ->where('receiver_id', $user->id)
            ->whereNull('read_at')
            ->count();

        return view('chat.index', [
            'messages' => $messages,
            'messageCount' => Message::query()
                ->where('sender_id', $user->id)
                ->orWhere('receiver_id', $user->id)
                ->count(),
            'userId' => $user->id,
            'contacts' => $contacts,
            'selectedContact' => $selectedContact,
            'conversationSummaries' => $conversationSummaries,
            'search' => $search,
            'unreadCount' => $unreadCount,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'receiver_id' => [
                'required',
                'integer',
                Rule::exists('users', 'id'),
                Rule::notIn([$request->user()->id]),
            ],
            'message' => ['required', 'string'],
        ]);

        Message::create([
            'sender_id' => $request->user()->id,
            'receiver_id' => $validated['receiver_id'],
            'message' => $validated['message'],
        ]);

        return redirect()
            ->route('chat.index', ['contact' => $validated['receiver_id']])
            ->with('status', 'Message sent successfully.');
    }

    public function markRead(Request $request): RedirectResponse
    {
        $contactId = $request->validate([
            'contact_id' => ['required', 'integer', Rule::exists('users', 'id')],
        ])['contact_id'];

        Message::query()
            ->where('sender_id', $contactId)
            ->where('receiver_id', $request->user()->id)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        return redirect()
            ->route('chat.index', ['contact' => $contactId])
            ->with('status', 'Conversation marked as read.');
    }

    public function markAllRead(Request $request): RedirectResponse
    {
        Message::query()
            ->where('receiver_id', $request->user()->id)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        return back()->with('status', 'All chats marked as read.');
    }

    public function destroy(Request $request, Message $message): RedirectResponse
    {
        abort_unless(
            $message->sender_id === $request->user()->id || $message->receiver_id === $request->user()->id,
            403
        );

        $contactId = $message->sender_id === $request->user()->id
            ? $message->receiver_id
            : $message->sender_id;

        $message->delete();

        return redirect()
            ->route('chat.index', ['contact' => $contactId])
            ->with('status', 'Message deleted.');
    }
}
