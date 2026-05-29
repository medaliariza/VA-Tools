<?php

namespace App\Http\Controllers;

use App\Models\Note;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class NoteController extends Controller
{
    public function index(Request $request): View
    {
        $notes = Note::query()
            ->where('user_id', $request->user()->id)
            ->latest('id')
            ->get();

        return view('notes.index', [
            'notes' => $notes,
            'noteCount' => $notes->count(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string'],
        ]);

        Note::create([
            'user_id' => $request->user()->id,
            'title' => $validated['title'],
            'content' => $validated['content'],
        ]);

        return back()->with('status', 'Note saved successfully.');
    }
}
