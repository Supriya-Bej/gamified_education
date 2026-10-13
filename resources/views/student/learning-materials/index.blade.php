@extends('student.layouts.app')

@section('title', 'Learning Materials | EcoQuest')

@section('head')
<style>
    .materials-pagination {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        justify-content: space-between;
        gap: 18px;
        margin-top: 30px;
        padding: 18px 20px;
        border: 1px solid var(--border);
        border-radius: 18px;
        background: linear-gradient(145deg, var(--surface), var(--surface-alt));
    }

    .materials-pagination-summary {
        color: var(--text-muted);
        font-size: 0.86rem;
        line-height: 1.5;
    }

    .materials-pagination-summary strong {
        color: var(--text-main);
        font-weight: 800;
    }

    .materials-pagination .pagination {
        flex-wrap: wrap;
        gap: 6px;
        margin-bottom: 0;
    }

    .materials-pagination .page-link {
        min-width: 40px;
        min-height: 40px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-color: var(--border);
        border-radius: 12px !important;
        background: rgba(255, 255, 255, 0.035);
        color: var(--text-muted);
        font-size: 0.84rem;
        font-weight: 800;
        transition: border-color 0.18s ease, background-color 0.18s ease, color 0.18s ease, transform 0.18s ease;
    }

    .materials-pagination .page-link:hover {
        transform: translateY(-1px);
        border-color: var(--secondary);
        background: rgba(255, 255, 255, 0.08);
        color: var(--text-main);
    }

    .materials-pagination .page-link:focus-visible {
        outline: 3px solid var(--secondary);
        outline-offset: 3px;
        box-shadow: none;
    }

    .materials-pagination .page-item.active .page-link {
        border-color: transparent;
        background: linear-gradient(135deg, var(--primary), var(--secondary));
        color: #fff;
        box-shadow: 0 5px 16px var(--glow);
    }

    .materials-pagination .page-item.disabled .page-link {
        background: rgba(255, 255, 255, 0.02);
        color: var(--text-muted);
        opacity: 0.55;
    }

    @media (max-width: 767px) {
        .materials-pagination {
            align-items: stretch;
            padding: 16px;
        }

        .materials-pagination > nav {
            width: 100%;
        }

        .materials-pagination .pagination {
            justify-content: center;
        }

        .materials-pagination .page-link {
            min-width: 38px;
            min-height: 38px;
        }
    }

    @media (prefers-reduced-motion: reduce) {
        .materials-pagination .page-link {
            transition: none;
        }

        .materials-pagination .page-link:hover {
            transform: none;
        }
    }
</style>
@endsection

@section('content')

<div class="container-fluid py-4">

    {{-- Header --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">

        <div>
            <div class="d-flex align-items-center gap-2 mb-2">
                <span class="badge bg-primary-subtle text-primary px-3 py-2">
                    <i class="bi bi-book-half me-1"></i>
                    LEARN
                </span>
            </div>

            <h2 class="fw-bold mb-1">
                Learning Materials
            </h2>

            <p class="text-muted mb-0">
                Explore topics, learn something new, and grow your knowledge.
            </p>
        </div>

        <div class="text-end">
            <div class="small text-muted">
                Welcome back
            </div>

            <div class="fw-semibold">
                {{ $student->name }}
            </div>
        </div>

    </div>

    

    {{-- Topic Section --}}
    <div class="mb-4">

        <h5 class="fw-bold mb-3">
            <i class="bi bi-grid-fill me-2 text-primary"></i>
            Explore Topics
        </h5>

        <div class="d-flex flex-wrap gap-2">

            {{-- All Topics --}}
            <a href="{{ route('student.learning-materials.index') }}"
                class="btn {{ !request('topic') ? 'btn-primary' : 'btn-outline-secondary' }} rounded-pill px-4">

                <i class="bi bi-stars me-1"></i>
                All Topics

            </a>


            {{-- Database Topics --}}
            @foreach($topics as $topic)

            <a href="{{ route(
                    'student.learning-materials.index',
                    ['topic' => $topic->id]
                ) }}"
                class="btn {{ request('topic') == $topic->id
                    ? 'btn-primary'
                    : 'btn-outline-secondary' }}
                    rounded-pill px-4">

                {{ $topic->name }}

            </a>

            @endforeach

        </div>

    </div>

    {{-- Materials --}}
    @if($materials->count() > 0)

    <div class="row g-4">

        @foreach($materials as $material)
        @php
            $isUnlocked = in_array((int) $material->id, $unlockedMaterialIds, true);
            $progress = $materialProgress->get($material->id);
        @endphp

        <div class="col-md-6 col-xl-4 material-card"
            data-topic="{{ strtolower($material->topic) }}">

            <div class="card border-0 shadow-sm h-100 overflow-hidden">

                {{-- Image --}}
                @if($material->image)

                <img src="{{ asset('storage/' . $material->image) }}"
                    alt="{{ $material->title }}"
                    class="w-100"
                    style="height:190px; object-fit:cover;">

                @else

                <div class="d-flex align-items-center justify-content-center bg-primary-subtle"
                    style="height:190px;">

                    <i class="bi bi-book-half text-primary"
                        style="font-size:55px;"></i>

                </div>

                @endif


                <div class="card-body p-4">

                    {{-- Topic + Difficulty --}}
                    <div class="d-flex flex-wrap gap-2 mb-3">

                        <span class="badge bg-primary-subtle text-primary">
                            {{ $material->topic }}
                        </span>

                        <span class="badge
                                    @if($material->difficulty === 'beginner')
                                        bg-success-subtle text-success
                                    @elseif($material->difficulty === 'intermediate')
                                        bg-warning-subtle text-warning
                                    @else
                                        bg-danger-subtle text-danger
                                    @endif">

                            {{ ucfirst($material->difficulty) }}

                        </span>

                    </div>


                    {{-- Title --}}
                    <h5 class="fw-bold mb-2">
                        {{ $material->title }}
                    </h5>


                    {{-- Description --}}
                    <p class="text-muted small mb-4">

                        {{ \Illuminate\Support\Str::limit(
                                    $material->description,
                                    110
                                ) }}

                    </p>


                    {{-- Read Button --}}
                    @if($isUnlocked)
                        <a href="{{ route('student.learning-materials.show', $material) }}"
                            class="btn btn-outline-primary w-100">
                            <i class="bi {{ $progress?->completed_at ? 'bi-patch-question' : 'bi-book-open' }} me-1"></i>
                            {{ $progress?->completed_at ? 'Continue to quiz' : 'Start Learning' }}
                        </a>
                        @if($progress?->quiz_completed_at)
                            <div class="small text-success text-center mt-2">
                                <i class="bi bi-check-circle-fill me-1" aria-hidden="true"></i>
                                Quiz passed — level complete
                            </div>
                        @endif
                    @else
                        <button type="button" class="btn btn-outline-secondary w-100" disabled>
                            <i class="bi bi-lock-fill me-1" aria-hidden="true"></i>
                            Locked — pass the previous lesson quiz
                        </button>
                    @endif

                </div>

            </div>

        </div>

        @endforeach

    </div>


    {{-- Pagination --}}
    <div class="materials-pagination">
        <p class="materials-pagination-summary mb-0" role="status" aria-live="polite">
            Showing
            <strong>{{ number_format($materials->firstItem()) }}–{{ number_format($materials->lastItem()) }}</strong>
            of
            <strong>{{ number_format($materials->total()) }}</strong>
            {{ \Illuminate\Support\Str::plural('learning material', $materials->total()) }}
        </p>

        @if($materials->hasPages())
            {{ $materials->links() }}
        @endif
    </div>

    @else

    {{-- Empty State --}}
    <div class="card border-0 shadow-sm">

        <div class="card-body text-center py-5">

            <div class="mb-3">
                <i class="bi bi-journal-x text-muted"
                    style="font-size:65px;"></i>
            </div>

            <h4 class="fw-bold">
                No Learning Materials Yet
            </h4>

            <p class="text-muted mb-0">
                New learning materials will appear here soon.
            </p>

        </div>

    </div>

    @endif

</div>


@endsection