@extends('student.layouts.app')

@section('title', 'Feedback | EcoQuest')

@section('head')
<style>
    .feedback-page { padding: 28px 30px 60px; min-height: 100vh; background: var(--page-bg); color: var(--text-main); }
    .fb-card { background: var(--surface); border: 1px solid var(--border); border-radius: 18px; padding: 24px; margin-bottom: 24px; }
    .fb-card .form-control, .fb-card .form-select { background: rgba(0,0,0,.2); color: var(--text-main); border-color: var(--border); }
    .star-rating { display: flex; flex-direction: row-reverse; justify-content: flex-end; gap: 4px; }
    .star-rating input { position: absolute; opacity: 0; }
    .star-rating label { font-size: 30px; color: #64748b; cursor: pointer; line-height: 1; }
    .star-rating input:checked ~ label,
    .star-rating label:hover,
    .star-rating label:hover ~ label { color: #fbbf24; }
    .star-rating input:focus-visible + label { outline: 2px solid var(--primary); border-radius: 4px; }
    .fb-item { border-top: 1px solid var(--border); padding: 14px 0; }
    .fb-item:first-child { border-top: 0; }
</style>
@endsection

@section('content')
<div class="feedback-page">

    <h2 class="fw-bold mb-1">Send Feedback</h2>
    <p style="color:var(--text-muted)">Tell us what you like, what is broken, or what we should add.</p>

    @if(session('success'))
        <div class="alert alert-success" role="status">{{ session('success') }}</div>
    @endif

    <div class="fb-card">
        <form method="POST" action="{{ route('student.feedback.store') }}">
            @csrf

            <div class="mb-3">
                <label for="category" class="form-label fw-semibold">Type</label>
                <select id="category" name="category" class="form-select" required>
                    <option value="general"    @selected(old('category') === 'general')>General feedback</option>
                    <option value="suggestion" @selected(old('category') === 'suggestion')>Suggestion / new idea</option>
                    <option value="bug"        @selected(old('category') === 'bug')>Problem / bug</option>
                    <option value="content"    @selected(old('category') === 'content')>Learning content</option>
                </select>
                @error('category') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
                <span class="form-label fw-semibold d-block">How would you rate EcoQuest? (optional)</span>
                <div class="star-rating" role="radiogroup" aria-label="Rating">
                    @for($i = 5; $i >= 1; $i--)
                        <input type="radio" id="star{{ $i }}" name="rating" value="{{ $i }}" @checked((int) old('rating') === $i)>
                        <label for="star{{ $i }}" title="{{ $i }} star{{ $i > 1 ? 's' : '' }}">★<span class="visually-hidden">{{ $i }} stars</span></label>
                    @endfor
                </div>
                @error('rating') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
                <label for="message" class="form-label fw-semibold">Your message</label>
                <textarea id="message" name="message" rows="5" maxlength="2000" class="form-control"
                          placeholder="Write your feedback here..." required>{{ old('message') }}</textarea>
                @error('message') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
            </div>

            <button type="submit" class="btn btn-primary px-4">
                <i class="bi bi-send me-1"></i> Submit Feedback
            </button>
        </form>
    </div>

    @if($myFeedback->isNotEmpty())
        <div class="fb-card">
            <h5 class="fw-bold mb-3">Your recent feedback</h5>
            @foreach($myFeedback as $item)
                <div class="fb-item">
                    <div class="small" style="color:var(--text-muted)">
                        {{ ucfirst($item->category) }}
                        @if($item->rating) · {{ str_repeat('★', $item->rating) }} @endif
                        · {{ $item->created_at->diffForHumans() }}
                        · {{ $item->status === 'read' ? 'Seen by admin' : 'Sent' }}
                    </div>
                    <div>{{ $item->message }}</div>
                </div>
            @endforeach
        </div>
    @endif

</div>
@endsection