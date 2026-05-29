<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Illuminate\View\View;

class ReportController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();
        $requestableUsers = $user->canManageOrganization()
            ? $user->organizationMembers()->orderBy('fullname')->get()
            : collect();

        $reports = Report::query()
            ->with(['user', 'requester', 'requestedFor'])
            ->where(function ($query) use ($user) {
                $query->where('user_id', $user->id)
                    ->orWhere('requested_by', $user->id);
            })
            ->latest('id')
            ->get();

        return view('reports.index', [
            'reports' => $reports,
            'reportCount' => $reports->count(),
            'requestableUsers' => $requestableUsers,
            'canManageOrganization' => $user->canManageOrganization(),
        ]);
    }

    public function download(Request $request, Report $report): StreamedResponse
    {
        abort_unless($report->user_id === $request->user()->id || $request->user()->isAdmin(), 403);
        abort_unless($report->file && Storage::disk('public')->exists($report->file), 404);

        return Storage::disk('public')->download(
            $report->file,
            basename($report->file)
        );
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['nullable', 'string', 'max:160'],
            'content' => ['required', 'string'],
            'file' => ['nullable', 'file', 'max:5120'],
        ]);

        $path = $request->hasFile('file')
            ? $request->file('file')->store('reports', 'public')
            : null;

        Report::create([
            'user_id' => $request->user()->id,
            'title' => $validated['title'] ?? null,
            'content' => $validated['content'],
            'file' => $path,
            'status' => 'submitted',
        ]);

        return back()->with('status', 'Report submitted successfully.');
    }

    public function requestReport(Request $request): RedirectResponse
    {
        $user = $request->user();
        abort_unless($user->canManageOrganization(), 403);

        $memberIds = $user->organizationMembers()->pluck('id')->all();

        $validated = $request->validate([
            'requested_for' => ['required', 'integer'],
            'title' => ['required', 'string', 'max:160'],
        ]);

        abort_unless(in_array((int) $validated['requested_for'], $memberIds, true), 403);

        Report::create([
            'user_id' => (int) $validated['requested_for'],
            'title' => $validated['title'],
            'requested_by' => $user->id,
            'requested_for' => (int) $validated['requested_for'],
            'status' => 'requested',
        ]);

        return back()->with('status', 'Report request sent to employee successfully.');
    }

    public function update(Request $request, Report $report): RedirectResponse
    {
        $user = $request->user();

        if ($report->status === 'requested') {
            abort_unless($report->user_id === $user->id, 403);

            $validated = $request->validate([
                'content' => ['required', 'string'],
                'file' => ['nullable', 'file', 'max:5120'],
            ]);

            $path = $request->hasFile('file')
                ? $request->file('file')->store('reports', 'public')
                : $report->file;

            $report->update([
                'content' => $validated['content'],
                'file' => $path,
                'status' => 'submitted',
            ]);

            return back()->with('status', 'Requested report submitted successfully.');
        }

        abort_unless($report->requested_by === $user->id, 403);

        $validated = $request->validate([
            'status' => ['required', 'in:submitted,reviewed'],
        ]);

        $report->update([
            'status' => $validated['status'],
        ]);

        return back()->with('status', 'Report review status updated successfully.');
    }
}
