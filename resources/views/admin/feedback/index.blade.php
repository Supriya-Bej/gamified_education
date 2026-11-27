@extends('admin.layouts.app')

@section('title', 'Student Feedback')
@section('page-heading', 'Student Feedback')

@section('content')

@if(session('success'))
<div class="alert alert-success" role="status">
    <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
</div>
@endif

{{-- Stats --}}
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card p-3">
            <div class="text-muted small">Total feedback</div>
            <div class="fs-3 fw-bold">{{ $stats['total'] }}</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card p-3">
            <div class="text-muted small">Unread</div>
            <div class="fs-3 fw-bold">{{ $stats['new'] }}</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card p-3">
            <div class="text-muted small">Average rating</div>
            <div class="fs-3 fw-bold">{{ $stats['average'] ?: '—' }} <span class="text-warning">★</span></div>
        </div>
    </div>
</div>

{{-- Filters --}}
<form method="GET" class="row g-2 mb-3">
    <div class="col-auto">
        <select name="status" class="form-select" aria-label="Filter by status" onchange="this.form.submit()">
            <option value="">All status</option>
            <option value="new" @selected(request('status')==='new' )>Unread</option>
            <option value="read" @selected(request('status')==='read' )>Read</option>
        </select>
    </div>
    <div class="col-auto">
        <select name="category" class="form-select" aria-label="Filter by type" onchange="this.form.submit()">
            <option value="">All types</option>
            @foreach(['general' => 'General', 'suggestion' => 'Suggestion', 'bug' => 'Bug', 'content' => 'Content'] as $value => $label)
            <option value="{{ $value }}" @selected(request('category')===$value)>{{ $label }}</option>
            @endforeach
        </select>
    </div>
</form>

{{-- List --}}
<div class="card">
    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead>
                <tr>
                    <th>Student</th>
                    <th>Type</th>
                    <th>Rating</th>
                    <th>Message</th>
                    <th>Date</th>
                    <th>Status</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($feedbacks as $feedback)
                <tr>
                    <td>
                        {{ $feedback->student->name ?? 'Deleted student' }}
                        <div class="small text-muted">{{ $feedback->student->email ?? '' }}</div>
                    </td>
                    <td><span class="badge text-bg-secondary">{{ ucfirst($feedback->category) }}</span></td>
                    <td class="text-warning">{{ $feedback->rating ? str_repeat('★', $feedback->rating) : '—' }}</td>
                    <td style="max-width:360px; white-space:pre-line">{{ $feedback->message }}</td>
                    <td class="small text-muted">{{ $feedback->created_at->format('d M Y, h:i A') }}</td>
                    <td>
                        <span class="badge {{ $feedback->status === 'new' ? 'text-bg-danger' : 'text-bg-success' }}">
                            {{ $feedback->status === 'new' ? 'Unread' : 'Read' }}
                        </span>
                    </td>
                    <td class="text-end text-nowrap">
                        @if($feedback->status === 'new')
                        <form method="POST" action="{{ route('admin.feedback.read', $feedback) }}" class="d-inline">
                            @csrf @method('PUT')
                            <button class="btn btn-sm btn-outline-success">Mark read</button>
                        </form>
                        @endif
                        <form method="POST" action="{{ route('admin.feedback.destroy', $feedback) }}" class="d-inline"
                            onsubmit="return confirm('Delete this feedback?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger">Delete</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center text-muted py-4">No feedback yet.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-3">{{ $feedbacks->links() }}</div>

@endsection