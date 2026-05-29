@extends('layouts.app', ['title' => 'Notes | VA Tools'])

@section('content')
    <section class="page-hero">
        <div>
            <h1>Notes</h1>
            <p>Store reminders, references, and personal notes directly inside the Laravel workspace.</p>
        </div>
        <span class="pill">{{ $noteCount }} Saved Notes</span>
    </section>

    <section class="split-grid">
        <article class="card">
            <h3>Create Note</h3>
            <form method="POST" action="{{ route('notes.store') }}" class="form-grid">
                @csrf
                <div class="field-full">
                    <label for="title">Title</label>
                    <input id="title" type="text" name="title" value="{{ old('title') }}" required>
                </div>
                <div class="field-full">
                    <label for="content">Content</label>
                    <textarea id="content" name="content" rows="8" required>{{ old('content') }}</textarea>
                </div>
                <div class="field-full">
                    <button type="submit" class="button-dark">Save Note</button>
                </div>
            </form>
        </article>

        <article class="card">
            <h3>Recent Notes</h3>
            @if($notes->isEmpty())
                <p class="helper-text">No notes saved yet.</p>
            @else
                <ul class="item-list">
                    @foreach($notes as $note)
                        <li>
                            <div class="item-main">
                                <strong>{{ $note->title }}</strong>
                                <span class="meta-text">{{ \Illuminate\Support\Str::limit($note->content, 120) }}</span>
                            </div>
                            <span class="pill">Note</span>
                        </li>
                    @endforeach
                </ul>
            @endif
        </article>
    </section>
@endsection
