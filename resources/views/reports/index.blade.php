@extends('layouts.app', ['title' => 'Reports | VA Tools'])

@section('content')
    <section class="page-hero">
        <div>
            <h1>Reports</h1>
            <p>Submit updates, attach supporting files, and keep a record of work that needs review.</p>
        </div>
        <span class="pill">{{ $reportCount }} Reports Submitted</span>
    </section>

    <section class="split-grid">
        <article class="card">
            <h3>Create a Report</h3>
            <form method="POST" action="{{ route('reports.store') }}" enctype="multipart/form-data" class="form-grid">
                @csrf
                <div class="field-full">
                    <label for="title">Title</label>
                    <input id="title" type="text" name="title" value="{{ old('title') }}" placeholder="Weekly update, project summary, support request">
                </div>
                <div class="field-full">
                    <label for="content">Report Content</label>
                    <textarea id="content" name="content" rows="8" required>{{ old('content') }}</textarea>
                </div>
                <div class="field-full">
                    <label for="file">Attachment</label>
                    <input id="file" type="file" name="file">
                </div>
                <div class="field-full">
                    <button type="submit" class="button-dark">Send Report</button>
                </div>
            </form>
        </article>

        <article class="card">
            @if($canManageOrganization)
                <h3>Request Report From Employee</h3>
                <form method="POST" action="{{ route('reports.request') }}" class="form-grid">
                    @csrf
                    <div class="field-full">
                        <label for="requested_for">Employee</label>
                        <select id="requested_for" name="requested_for" required>
                            <option value="">Select employee</option>
                            @foreach($requestableUsers as $member)
                                <option value="{{ $member->id }}" @selected(old('requested_for') == $member->id)>{{ $member->fullname }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="field-full">
                        <label for="request_title">Request Title</label>
                        <input id="request_title" type="text" name="title" value="{{ old('title') }}" placeholder="Request a daily productivity report" required>
                    </div>
                    <div class="field-full">
                        <button type="submit" class="button-dark">Request Report</button>
                    </div>
                </form>
            @else
                <h3>Submission Guide</h3>
                <ul class="clean-list">
                    <li><span>Content required</span><strong>Yes</strong></li>
                    <li><span>Attachment optional</span><strong>Yes</strong></li>
                    <li><span>Status tracking</span><strong>Requested, submitted, reviewed</strong></li>
                </ul>
            @endif
        </article>
    </section>

    <section class="table-card">
        <h3>My Reports</h3>
        @if($reports->isEmpty())
            <p class="helper-text">No reports submitted yet.</p>
        @else
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Content</th>
                            <th>Requested By</th>
                            <th>Requested For</th>
                            <th>Attachment</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($reports as $report)
                            <tr>
                                <td>{{ $report->title ?: 'General Report' }}</td>
                                <td>{{ \Illuminate\Support\Str::limit($report->content, 100) }}</td>
                                <td>{{ $report->requester?->fullname ?: 'Self' }}</td>
                                <td>{{ $report->requestedFor?->fullname ?: ($report->user?->fullname ?? 'Self') }}</td>
                                <td>
                                    @if($report->file)
                                        <a href="{{ route('reports.download', $report) }}">Open attachment</a>
                                    @else
                                        <span class="meta-text">No attachment</span>
                                    @endif
                                </td>
                                <td><span class="pill">{{ ucfirst($report->status) }}</span></td>
                                <td>
                                    @if($report->status === 'requested' && $report->user_id === auth()->id())
                                        <form method="POST" action="{{ route('reports.update', $report) }}" enctype="multipart/form-data" class="form-grid">
                                            @csrf
                                            @method('PATCH')
                                            <div class="field-full">
                                                <textarea name="content" rows="4" placeholder="Submit the requested report here" required>{{ old('content') }}</textarea>
                                            </div>
                                            <div class="field-full">
                                                <input type="file" name="file">
                                            </div>
                                            <div class="field-full">
                                                <button type="submit" class="button-dark button-small">Submit</button>
                                            </div>
                                        </form>
                                    @elseif($report->requested_by === auth()->id() && in_array($report->status, ['submitted', 'reviewed'], true))
                                        <form method="POST" action="{{ route('reports.update', $report) }}" class="inline-actions">
                                            @csrf
                                            @method('PATCH')
                                            <select name="status">
                                                <option value="submitted" @selected($report->status === 'submitted')>Submitted</option>
                                                <option value="reviewed" @selected($report->status === 'reviewed')>Reviewed</option>
                                            </select>
                                            <button type="submit" class="button-dark button-small">Save</button>
                                        </form>
                                    @else
                                        <span class="meta-text">No action</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </section>
@endsection
