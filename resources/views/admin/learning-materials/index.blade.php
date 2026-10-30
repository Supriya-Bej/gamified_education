
@extends('admin.layouts.app')

@section('title', 'Learning Materials')
@section('page-heading', 'Learning Materials')

@section('content')

<div class="container-fluid py-3">

    {{-- Flash Messages --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm rounded-3">
            <i class="bi bi-check-circle-fill me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm rounded-3">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Hero Header --}}
    <div class="materials-hero rounded-4 p-4 p-lg-5 mb-4 text-white">
        <div class="row align-items-center g-4">
            <div class="col-lg-8">
                <div class="d-flex align-items-center gap-3">
                    <div class="materials-hero-icon">
                        <i class="bi bi-book-half"></i>
                    </div>

                    <div>
                        <span class="text-white-50 text-uppercase small fw-semibold">
                            EcoQuest Administration
                        </span>
                        <h2 class="fw-bold mb-2 mt-1">Learning Materials</h2>
                        <p class="text-white-50 mb-0">
                            Create, organize and publish educational content for students.
                        </p>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 text-lg-end">
                <a href="{{ route('admin.learning-materials.create') }}"
                   class="btn btn-light btn-lg rounded-3 fw-semibold px-4 materials-add-btn">
                    <i class="bi bi-plus-lg me-2"></i>
                    Add Material
                </a>
            </div>
        </div>
    </div>

    {{-- Summary Cards --}}
    <div class="row g-3 mb-4">

        <div class="col-12 col-sm-6 col-xl-4">
            <div class="card material-stat-card h-100 border-0 shadow-sm rounded-4">
                <div class="card-body p-4 d-flex align-items-center gap-3">
                    <div class="stat-icon stat-blue">
                        <i class="bi bi-collection"></i>
                    </div>
                    <div>
                        <p class="text-muted small mb-1">Total Materials</p>
                        <h3 class="fw-bold mb-0">{{ $materials->total() }}</h3>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-4">
            <div class="card material-stat-card h-100 border-0 shadow-sm rounded-4">
                <div class="card-body p-4 d-flex align-items-center gap-3">
                    <div class="stat-icon stat-green">
                        <i class="bi bi-cloud-check"></i>
                    </div>
                    <div>
                        <p class="text-muted small mb-1">Published on This Page</p>
                        <h3 class="fw-bold mb-0">
                            {{ $materials->getCollection()->where('is_published', true)->count() }}
                        </h3>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-4">
            <div class="card material-stat-card h-100 border-0 shadow-sm rounded-4">
                <div class="card-body p-4 d-flex align-items-center gap-3">
                    <div class="stat-icon stat-orange">
                        <i class="bi bi-file-earmark-text"></i>
                    </div>
                    <div>
                        <p class="text-muted small mb-1">Drafts on This Page</p>
                        <h3 class="fw-bold mb-0">
                            {{ $materials->getCollection()->where('is_published', false)->count() }}
                        </h3>
                    </div>
                </div>
            </div>
        </div>

    </div>

    {{-- Materials Table --}}
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">

        <div class="card-header bg-white border-0 p-4">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                <div>
                    <h5 class="fw-bold mb-1">
                        <i class="bi bi-grid-1x2-fill text-primary me-2"></i>
                        All Learning Materials
                    </h5>
                    <p class="text-muted small mb-0">
                        Manage your educational resources and publication status.
                    </p>
                </div>

                <span class="badge rounded-pill bg-light text-dark border px-3 py-2">
                    Page {{ $materials->currentPage() }} of {{ max(1, $materials->lastPage()) }}
                </span>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table material-table align-middle mb-0">

                <thead>
                    <tr>
                        <th class="ps-4">#</th>
                        <th>Learning Material</th>
                        <th>Topic</th>
                        <th>Difficulty</th>
                        <th>Publication</th>
                        <th>Created</th>
                        <th class="text-end pe-4">Actions</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($materials as $material)

                        <tr>
                            {{-- ID --}}
                            <td class="ps-4">
                                <span class="material-id">#{{ $material->id }}</span>
                            </td>

                            {{-- Material --}}
                            <td class="material-main-cell">
                                <div class="d-flex align-items-center gap-3">

                                    @if($material->image)
                                        <img
                                            src="{{ asset('storage/' . $material->image) }}"
                                            alt="{{ $material->title }}"
                                            width="58"
                                            height="58"
                                            class="material-thumbnail"
                                            loading="lazy"
                                        >
                                    @else
                                        <div class="material-thumbnail-placeholder">
                                            <i class="bi bi-book-half"></i>
                                        </div>
                                    @endif

                                    <div class="min-w-0">
                                        <div class="fw-semibold text-dark mb-1">
                                            {{ $material->title }}
                                        </div>

                                        @if($material->description)
                                            <small class="text-muted material-description">
                                                {{ Str::limit($material->description, 65) }}
                                            </small>
                                        @else
                                            <small class="text-muted fst-italic">
                                                No description available
                                            </small>
                                        @endif
                                    </div>
                                </div>
                            </td>

                            {{-- Topic --}}
                            <td>
                                <span class="topic-pill">
                                    <i class="bi bi-bookmark-fill me-1"></i>
                                    {{ $material->topic ?: 'Uncategorized' }}
                                </span>
                            </td>

                            {{-- Difficulty --}}
                            <td>
                                @php
                                    $difficulty = strtolower($material->difficulty ?? '');

                                    $difficultyClass = match($difficulty) {
                                        'beginner' => 'difficulty-beginner',
                                        'intermediate' => 'difficulty-intermediate',
                                        'advanced' => 'difficulty-advanced',
                                        default => 'difficulty-default'
                                    };
                                @endphp

                                <span class="difficulty-pill {{ $difficultyClass }}">
                                    @if($difficulty === 'beginner')
                                        <i class="bi bi-emoji-smile me-1"></i>
                                    @elseif($difficulty === 'intermediate')
                                        <i class="bi bi-lightning me-1"></i>
                                    @elseif($difficulty === 'advanced')
                                        <i class="bi bi-fire me-1"></i>
                                    @else
                                        <i class="bi bi-dash-circle me-1"></i>
                                    @endif

                                    {{ ucfirst($material->difficulty ?? 'N/A') }}
                                </span>
                            </td>

                            {{-- Publication Status --}}
                            <td>
                                @if($material->is_published)
                                    <span class="publication-pill publication-live">
                                        <span class="status-dot"></span>
                                        Published
                                    </span>
                                @else
                                    <span class="publication-pill publication-draft">
                                        <i class="bi bi-eye-slash me-1"></i>
                                        Draft
                                    </span>
                                @endif
                            </td>

                            {{-- Created --}}
                            <td>
                                <div class="created-date">
                                    <i class="bi bi-calendar3 me-1"></i>
                                    {{ $material->created_at?->format('d M Y') ?? 'N/A' }}
                                </div>
                            </td>

                            {{-- Actions --}}
                            <td class="text-end pe-4">
                                <div class="d-inline-flex align-items-center gap-2">

                                    <a
                                        href="{{ route('admin.learning-materials.edit', $material) }}"
                                        class="btn btn-sm action-btn action-edit"
                                        title="Edit material"
                                        aria-label="Edit {{ $material->title }}"
                                    >
                                        <i class="bi bi-pencil-square"></i>
                                    </a>

                                    <form
                                        action="{{ route('admin.learning-materials.destroy', $material) }}"
                                        method="POST"
                                        class="d-inline"
                                        onsubmit="return confirm('Are you sure you want to delete this learning material?');"
                                    >
                                        @csrf
                                        @method('DELETE')

                                        <button
                                            type="submit"
                                            class="btn btn-sm action-btn action-delete"
                                            title="Delete material"
                                            aria-label="Delete {{ $material->title }}"
                                        >
                                            <i class="bi bi-trash3"></i>
                                        </button>
                                    </form>

                                </div>
                            </td>
                        </tr>

                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-5">
                                <div class="empty-material-icon mx-auto mb-3">
                                    <i class="bi bi-journal-x"></i>
                                </div>

                                <h5 class="fw-bold mb-2">No Learning Materials Yet</h5>

                                <p class="text-muted mb-3">
                                    Add your first educational resource to get started.
                                </p>

                                <a
                                    href="{{ route('admin.learning-materials.create') }}"
                                    class="btn btn-primary rounded-3 px-4"
                                >
                                    <i class="bi bi-plus-lg me-2"></i>
                                    Add First Material
                                </a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>

            </table>
        </div>

        {{-- Pagination --}}
        @if($materials->hasPages())
            <div class="card-footer bg-white border-0 px-4 py-4">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">

                    <small class="text-muted">
                        Showing
                        <strong>{{ $materials->firstItem() ?? 0 }}</strong>
                        to
                        <strong>{{ $materials->lastItem() ?? 0 }}</strong>
                        of
                        <strong>{{ $materials->total() }}</strong>
                        materials
                    </small>

                    <div class="material-pagination">
                        {{ $materials->onEachSide(1)->links() }}
                    </div>

                </div>
            </div>
        @endif

    </div>

</div>

<style>
    .materials-hero {
        background: linear-gradient(120deg, #173b8f 0%, #315bd6 55%, #667eea 100%);
        box-shadow: 0 12px 30px rgba(49, 91, 214, .16);
    }

    .materials-hero-icon {
        width: 60px;
        height: 60px;
        display: grid;
        place-items: center;
        flex-shrink: 0;
        border-radius: 18px;
        background: rgba(255, 255, 255, .16);
        font-size: 1.8rem;
    }

    .materials-add-btn {
        transition: transform .2s ease, box-shadow .2s ease;
    }

    .materials-add-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, .12);
    }

    .material-stat-card {
        transition: transform .2s ease, box-shadow .2s ease;
    }

    .material-stat-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 24px rgba(0, 0, 0, .07) !important;
    }

    .stat-icon {
        width: 52px;
        height: 52px;
        display: grid;
        place-items: center;
        flex-shrink: 0;
        border-radius: 15px;
        font-size: 1.4rem;
    }

    .stat-blue {
        color: #315bd6;
        background: #edf2ff;
    }

    .stat-green {
        color: #15803d;
        background: #dcfce7;
    }

    .stat-orange {
        color: #c2410c;
        background: #ffedd5;
    }

    .material-table thead th {
        padding: 16px 12px;
        background: #f7f9fc;
        color: #667085;
        font-size: .75rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .045em;
        white-space: nowrap;
        border-bottom: 1px solid #edf0f5;
    }

    .material-table tbody td {
        padding-top: 17px;
        padding-bottom: 17px;
        border-color: #f0f2f6;
    }

    .material-table tbody tr {
        transition: background-color .2s ease;
    }

    .material-table tbody tr:hover {
        background: #f8faff;
    }

    .material-id {
        color: #667085;
        font-size: .8rem;
        font-weight: 600;
    }

    .material-main-cell {
        min-width: 280px;
        max-width: 380px;
    }

    .material-thumbnail,
    .material-thumbnail-placeholder {
        width: 58px;
        height: 58px;
        flex-shrink: 0;
        border-radius: 13px;
    }

    .material-thumbnail {
        object-fit: cover;
        border: 1px solid #edf0f5;
        background: #f8fafc;
    }

    .material-thumbnail-placeholder {
        display: grid;
        place-items: center;
        color: #315bd6;
        background: #edf2ff;
        font-size: 1.5rem;
    }

    .material-description {
        display: block;
        line-height: 1.5;
    }

    .topic-pill,
    .difficulty-pill,
    .publication-pill {
        display: inline-flex;
        align-items: center;
        padding: 7px 10px;
        border-radius: 50px;
        font-size: .75rem;
        font-weight: 600;
        white-space: nowrap;
    }

    .topic-pill {
        color: #1d4ed8;
        background: #eff6ff;
        border: 1px solid #dbeafe;
    }

    .difficulty-beginner {
        color: #15803d;
        background: #dcfce7;
    }

    .difficulty-intermediate {
        color: #a16207;
        background: #fef3c7;
    }

    .difficulty-advanced {
        color: #b91c1c;
        background: #fee2e2;
    }

    .difficulty-default {
        color: #475467;
        background: #f2f4f7;
    }

    .publication-live {
        color: #15803d;
        background: #dcfce7;
    }

    .publication-draft {
        color: #667085;
        background: #f2f4f7;
    }

    .status-dot {
        width: 7px;
        height: 7px;
        display: inline-block;
        margin-right: 5px;
        border-radius: 50%;
        background: #16a34a;
    }

    .created-date {
        color: #667085;
        font-size: .8rem;
        white-space: nowrap;
    }

    .action-btn {
        width: 35px;
        height: 35px;
        display: inline-grid;
        place-items: center;
        border-radius: 10px;
        transition: transform .2s ease, background-color .2s ease;
    }

    .action-btn:hover {
        transform: translateY(-2px);
    }

    .action-edit {
        color: #315bd6;
        border: 1px solid #dbeafe;
        background: #eff6ff;
    }

    .action-edit:hover {
        color: #fff;
        border-color: #315bd6;
        background: #315bd6;
    }

    .action-delete {
        color: #dc2626;
        border: 1px solid #fee2e2;
        background: #fff1f2;
    }

    .action-delete:hover {
        color: #fff;
        border-color: #dc2626;
        background: #dc2626;
    }

    .empty-material-icon {
        width: 72px;
        height: 72px;
        display: grid;
        place-items: center;
        border-radius: 22px;
        color: #315bd6;
        background: #edf2ff;
        font-size: 2rem;
    }

    .material-pagination nav > div:first-child {
        display: none;
    }

    .material-pagination nav > div:last-child {
        display: flex;
        flex-wrap: wrap;
        justify-content: flex-end;
        gap: 5px;
    }

    @media (max-width: 767.98px) {
        .materials-hero {
            padding: 1.5rem !important;
        }

        .materials-hero-icon {
            width: 48px;
            height: 48px;
            font-size: 1.4rem;
        }

        .material-pagination nav > div:last-child {
            justify-content: center;
        }
    }
</style>

@endsection
