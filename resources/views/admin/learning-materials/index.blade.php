
@extends('admin.layouts.app')

@section('title', 'Learning Materials')
@section('page-heading', 'Learning Materials')

@section('content')

<style>
    .materials-page {
        --forest: #153d2e;
        --forest-light: #205b42;
        --emerald: #15966b;
        --mint: #e8f5ed;
        --text: #243c30;
        --muted: #819087;
        --border: #e8eee9;
        color: var(--text);
    }

    /* HERO */
    .materials-page .materials-hero {
        position: relative;
        overflow: hidden;
        padding: 36px;
        border-radius: 23px;
        background: linear-gradient(115deg, #103b2b 0%, #1d6547 60%, #15966b 100%);
        color: white;
        box-shadow: 0 12px 30px rgba(18, 65, 43, .15);
    }

    .materials-page .materials-hero::before {
        content: "";
        position: absolute;
        width: 280px;
        height: 280px;
        right: -65px;
        top: -130px;
        border: 1px solid rgba(255,255,255,.13);
        border-radius: 50%;
        box-shadow: 0 0 0 35px rgba(255,255,255,.04),
                    0 0 0 70px rgba(255,255,255,.025);
    }

    .materials-page .hero-content {
        position: relative;
        z-index: 1;
    }

    .materials-page .hero-icon {
        width: 76px;
        height: 76px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        border-radius: 22px;
        background: rgba(255,255,255,.15);
        border: 1px solid rgba(255,255,255,.2);
        font-size: 36px;
    }

    .materials-page .hero-label {
        color: #a9e8cc;
        font-size: 12px;
        font-weight: 800;
        letter-spacing: 1.5px;
        margin-bottom: 9px;
    }

    .materials-page .hero-title {
        font-size: 32px;
        font-weight: 850;
        letter-spacing: -.7px;
        margin-bottom: 8px;
    }

    .materials-page .hero-description {
        color: rgba(255,255,255,.77);
        font-size: 16px;
        margin: 0;
    }

    .materials-page .add-material-btn {
        background: white;
        color: #176344;
        border: 0;
        padding: 13px 21px;
        border-radius: 11px;
        font-size: 14px;
        font-weight: 800;
        transition: .2s;
        white-space: nowrap;
    }

    .materials-page .add-material-btn:hover {
        background: #e6f5eb;
        color: #103b2b;
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(0,0,0,.13);
    }

    /* ALERTS */
    .materials-page .custom-alert {
        border: 0;
        border-radius: 13px;
        padding: 17px 20px;
        font-size: 15px;
    }

    /* STAT CARDS */
    .materials-page .stat-card {
        height: 100%;
        padding: 23px;
        background: #fff;
        border: 1px solid var(--border);
        border-radius: 17px;
        box-shadow: 0 5px 20px rgba(20,60,40,.045);
        transition: .2s;
    }

    .materials-page .stat-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 24px rgba(20,60,40,.08);
    }

    .materials-page .stat-icon {
        width: 50px;
        height: 50px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 15px;
        font-size: 22px;
        margin-bottom: 17px;
    }

    .materials-page .stat-number {
        font-size: 31px;
        line-height: 1.2;
        font-weight: 850;
        color: var(--forest);
    }

    .materials-page .stat-label {
        color: var(--muted);
        font-size: 14px;
        font-weight: 650;
        margin-top: 6px;
    }

    .materials-page .stat-note {
        font-size: 12px;
        color: #9aa69f;
        margin-top: 10px;
    }

    /* MAIN PANEL */
    .materials-page .content-panel {
        background: white;
        border: 1px solid var(--border);
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 5px 20px rgba(20,60,40,.045);
    }

    .materials-page .panel-header {
        padding: 25px 27px;
        background: linear-gradient(110deg,#fbfdfb,#f3f8f4);
        border-bottom: 1px solid var(--border);
    }

    .materials-page .panel-icon {
        width: 46px;
        height: 46px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 14px;
        background: #e5f4e9;
        color: #168052;
        font-size: 21px;
        flex-shrink: 0;
    }

    .materials-page .panel-title {
        font-size: 21px;
        font-weight: 850;
        color: var(--forest);
        margin-bottom: 5px;
    }

    .materials-page .panel-description {
        color: var(--muted);
        font-size: 14px;
        margin: 0;
    }

    .materials-page .page-pill {
        padding: 10px 15px;
        border-radius: 30px;
        color: #176344;
        background: #e5f4e9;
        font-size: 13px;
        font-weight: 800;
        white-space: nowrap;
    }

    /* TABLE */
    .materials-page .table {
        margin: 0;
    }

    .materials-page .table thead th {
        padding: 17px 16px;
        background: #f1f7f2;
        color: #526d5c;
        border-bottom: 1px solid #e5eee7;
        font-size: 12px;
        font-weight: 850;
        text-transform: uppercase;
        letter-spacing: .65px;
        white-space: nowrap;
    }

    .materials-page .table tbody td {
        padding: 19px 16px;
        border-bottom: 1px solid #edf2ee;
        vertical-align: middle;
        color: #52665a;
        font-size: 14px;
    }

    .materials-page .table tbody tr {
        transition: background .2s;
    }

    .materials-page .table tbody tr:hover {
        background: #f8fcf9;
    }

    .materials-page .table tbody tr:last-child td {
        border-bottom: 0;
    }

    .materials-page .material-id {
        color: #84958a;
        font-size: 13px;
        font-weight: 750;
    }

    .materials-page .material-main-cell {
        min-width: 300px;
        max-width: 400px;
    }

    .materials-page .material-thumbnail {
        width: 61px;
        height: 61px;
        flex-shrink: 0;
        border-radius: 14px;
        object-fit: cover;
        background: #f0f6f2;
        border: 1px solid #e7eee9;
    }

    .materials-page .material-thumbnail-placeholder {
        width: 61px;
        height: 61px;
        flex-shrink: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 14px;
        background: #e8f5ed;
        color: #168052;
        font-size: 25px;
    }

    .materials-page .material-name {
        color: var(--forest);
        font-size: 15px;
        font-weight: 800;
        margin-bottom: 5px;
    }

    .materials-page .material-description {
        display: block;
        color: #8b9a90;
        font-size: 12px;
        line-height: 1.5;
    }

    /* BADGES */
    .materials-page .topic-pill,
    .materials-page .difficulty-pill,
    .materials-page .publication-pill {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 8px 11px;
        border-radius: 30px;
        font-size: 12px;
        font-weight: 800;
        white-space: nowrap;
    }

    .materials-page .topic-pill {
        background: #eaf1ff;
        color: #4668bc;
    }

    .materials-page .difficulty-beginner {
        background: #e4f6e9;
        color: #167347;
    }

    .materials-page .difficulty-intermediate {
        background: #fff3d9;
        color: #9a6b12;
    }

    .materials-page .difficulty-advanced {
        background: #fde8e6;
        color: #b53d37;
    }

    .materials-page .difficulty-default {
        background: #eef1ef;
        color: #66756c;
    }

    .materials-page .publication-live {
        background: #e3f6e9;
        color: #147448;
    }

    .materials-page .publication-draft {
        background: #f0f2f1;
        color: #69776f;
    }

    .materials-page .status-dot {
        width: 7px;
        height: 7px;
        display: inline-block;
        border-radius: 50%;
        background: #1ba66b;
        box-shadow: 0 0 0 3px rgba(27,166,107,.12);
    }

    .materials-page .created-date {
        color: #718279;
        font-size: 13px;
        white-space: nowrap;
    }

    /* ACTIONS */
    .materials-page .action-btn {
        width: 37px;
        height: 37px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 10px;
        font-size: 15px;
        transition: .2s;
    }

    .materials-page .action-edit {
        background: #eaf1ff;
        border: 1px solid #dce6ff;
        color: #476bc4;
    }

    .materials-page .action-edit:hover {
        background: #476bc4;
        color: white;
        transform: translateY(-2px);
    }

    .materials-page .action-delete {
        background: #fff0ef;
        border: 1px solid #f7dedd;
        color: #c24b43;
    }

    .materials-page .action-delete:hover {
        background: #c24b43;
        color: white;
        transform: translateY(-2px);
    }

    /* EMPTY STATE */
    .materials-page .empty-state {
        padding: 65px 20px;
        text-align: center;
    }

    .materials-page .empty-icon {
        width: 77px;
        height: 77px;
        margin: 0 auto 18px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 23px;
        background: #e8f5ed;
        color: #168052;
        font-size: 33px;
    }

    .materials-page .empty-title {
        color: var(--forest);
        font-size: 20px;
        font-weight: 850;
        margin-bottom: 8px;
    }

    .materials-page .empty-description {
        color: var(--muted);
        font-size: 15px;
        margin-bottom: 20px;
    }

    .materials-page .empty-add-btn {
        padding: 11px 19px;
        border: 0;
        border-radius: 10px;
        background: #168052;
        color: white;
        font-size: 14px;
        font-weight: 800;
    }

    .materials-page .empty-add-btn:hover {
        background: #105f3d;
        color: white;
    }

    /* PAGINATION */
    .materials-page .panel-footer {
        padding: 22px 25px;
        border-top: 1px solid var(--border);
        background: white;
    }

    .materials-page .pagination {
        gap: 5px;
        margin: 0;
    }

    .materials-page .page-link {
        border: 1px solid #e2eae4;
        border-radius: 9px !important;
        color: #326b4d;
        padding: 8px 13px;
        font-weight: 700;
        box-shadow: none;
    }

    .materials-page .page-item.active .page-link {
        background: #16784f;
        border-color: #16784f;
        color: white;
    }

    .materials-page .page-item.disabled .page-link {
        color: #aab6ae;
        background: #f7faf8;
    }

    @media(max-width: 767px) {
        .materials-page .materials-hero {
            padding: 24px 19px;
        }

        .materials-page .hero-title {
            font-size: 25px;
        }

        .materials-page .hero-icon {
            width: 59px;
            height: 59px;
            font-size: 29px;
        }

        .materials-page .hero-top {
            flex-direction: column;
            align-items: flex-start !important;
            gap: 22px;
        }

        .materials-page .panel-header {
            padding: 21px 17px;
        }

        .materials-page .panel-footer {
            padding: 18px;
        }
    }
</style>


<div class="materials-page">

    {{-- FLASH MESSAGES --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show custom-alert shadow-sm mb-4">
            <i class="bi bi-check-circle-fill me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show custom-alert shadow-sm mb-4">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif


    {{-- HERO HEADER --}}
    <div class="materials-hero mb-4">

        <div class="hero-content">

            <div class="d-flex justify-content-between align-items-center hero-top">

                <div class="d-flex align-items-center gap-3">

                    <div class="hero-icon">
                        <i class="bi bi-book-half"></i>
                    </div>

                    <div>
                        <div class="hero-label">
                            <i class="bi bi-mortarboard-fill me-1"></i>
                            ECOQUEST / CONTENT MANAGEMENT
                        </div>

                        <h2 class="hero-title">
                            Learning Materials
                        </h2>

                        <p class="hero-description">
                            Create, organize and publish meaningful learning resources for students.
                        </p>
                    </div>

                </div>

                <a href="{{ route('admin.learning-materials.create') }}"
                   class="btn add-material-btn">

                    <i class="bi bi-plus-circle-fill me-2"></i>
                    Add New Material

                </a>

            </div>

        </div>

    </div>


    {{-- SUMMARY CARDS --}}
    <div class="row g-3 mb-4">

        <div class="col-md-4">

            <div class="stat-card">

                <div class="stat-icon"
                     style="background:#eaf0ff;color:#5274cb;">
                    <i class="bi bi-collection"></i>
                </div>

                <div class="stat-number">
                    {{ $materials->total() }}
                </div>

                <div class="stat-label">
                    Total Learning Materials
                </div>

                <div class="stat-note">
                    <i class="bi bi-database me-1"></i>
                    Across all pages
                </div>

            </div>

        </div>


        <div class="col-md-4">

            <div class="stat-card">

                <div class="stat-icon"
                     style="background:#e5f5eb;color:#168052;">
                    <i class="bi bi-cloud-check-fill"></i>
                </div>

                <div class="stat-number">
                    {{ $materials->getCollection()->where('is_published', true)->count() }}
                </div>

                <div class="stat-label">
                    Published on This Page
                </div>

                <div class="stat-note">
                    <i class="bi bi-globe me-1"></i>
                    Currently available resources
                </div>

            </div>

        </div>


        <div class="col-md-4">

            <div class="stat-card">

                <div class="stat-icon"
                     style="background:#fff2df;color:#bd8229;">
                    <i class="bi bi-file-earmark-text"></i>
                </div>

                <div class="stat-number">
                    {{ $materials->getCollection()->where('is_published', false)->count() }}
                </div>

                <div class="stat-label">
                    Drafts on This Page
                </div>

                <div class="stat-note">
                    <i class="bi bi-pencil-square me-1"></i>
                    Not yet published
                </div>

            </div>

        </div>

    </div>


    {{-- MAIN TABLE --}}
    <div class="content-panel">

        <div class="panel-header">

            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">

                <div class="d-flex align-items-center gap-3">

                    <div class="panel-icon">
                        <i class="bi bi-journals"></i>
                    </div>

                    <div>
                        <h4 class="panel-title">
                            All Learning Materials
                        </h4>

                        <p class="panel-description">
                            Manage educational resources, topics and publication status.
                        </p>
                    </div>

                </div>

                <span class="page-pill">
                    <i class="bi bi-layers me-1"></i>
                    Page {{ $materials->currentPage() }} of {{ max(1, $materials->lastPage()) }}
                </span>

            </div>

        </div>


        <div class="table-responsive">

            <table class="table align-middle">

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

                        @php
                            $difficulty = strtolower($material->difficulty ?? '');

                            $difficultyClass = match($difficulty) {
                                'beginner' => 'difficulty-beginner',
                                'intermediate' => 'difficulty-intermediate',
                                'advanced' => 'difficulty-advanced',
                                default => 'difficulty-default'
                            };

                            $difficultyIcon = match($difficulty) {
                                'beginner' => 'bi-emoji-smile',
                                'intermediate' => 'bi-lightning',
                                'advanced' => 'bi-fire',
                                default => 'bi-dash-circle'
                            };
                        @endphp

                        <tr>

                            {{-- ID --}}
                            <td class="ps-4">
                                <span class="material-id">
                                    #{{ $material->id }}
                                </span>
                            </td>


                            {{-- MATERIAL --}}
                            <td class="material-main-cell">

                                <div class="d-flex align-items-center gap-3">

                                    @if($material->image)

                                        <img
                                            src="{{ asset('storage/' . $material->image) }}"
                                            alt="{{ $material->title }}"
                                            class="material-thumbnail"
                                            loading="lazy"
                                        >

                                    @else

                                        <div class="material-thumbnail-placeholder">
                                            <i class="bi bi-book-half"></i>
                                        </div>

                                    @endif

                                    <div class="min-w-0">

                                        <div class="material-name">
                                            {{ $material->title }}
                                        </div>

                                        @if($material->description)

                                            <small class="material-description">
                                                {{ Str::limit($material->description, 65) }}
                                            </small>

                                        @else

                                            <small class="material-description fst-italic">
                                                No description available
                                            </small>

                                        @endif

                                    </div>

                                </div>

                            </td>


                            {{-- TOPIC --}}
                            <td>

                                <span class="topic-pill">
                                    <i class="bi bi-bookmark-fill"></i>
                                    {{ $material->topic ?: 'Uncategorized' }}
                                </span>

                            </td>


                            {{-- DIFFICULTY --}}
                            <td>

                                <span class="difficulty-pill {{ $difficultyClass }}">

                                    <i class="bi {{ $difficultyIcon }}"></i>

                                    {{ ucfirst($material->difficulty ?? 'N/A') }}

                                </span>

                            </td>


                            {{-- PUBLICATION --}}
                            <td>

                                @if($material->is_published)

                                    <span class="publication-pill publication-live">
                                        <span class="status-dot"></span>
                                        Published
                                    </span>

                                @else

                                    <span class="publication-pill publication-draft">
                                        <i class="bi bi-eye-slash"></i>
                                        Draft
                                    </span>

                                @endif

                            </td>


                            {{-- CREATED --}}
                            <td>

                                <div class="created-date">
                                    <i class="bi bi-calendar3 me-1"></i>
                                    {{ $material->created_at?->format('d M Y') ?? 'N/A' }}
                                </div>

                            </td>


                            {{-- ACTIONS --}}
                            <td class="text-end pe-4">

                                <div class="d-inline-flex align-items-center gap-2">

                                    <a href="{{ route('admin.learning-materials.edit', $material) }}"
                                       class="btn action-btn action-edit"
                                       title="Edit material"
                                       aria-label="Edit {{ $material->title }}">

                                        <i class="bi bi-pencil-square"></i>

                                    </a>


                                    <form action="{{ route('admin.learning-materials.destroy', $material) }}"
                                          method="POST"
                                          class="d-inline"
                                          onsubmit="return confirm('Are you sure you want to delete this learning material?');">

                                        @csrf
                                        @method('DELETE')

                                        <button type="submit"
                                                class="btn action-btn action-delete"
                                                title="Delete material"
                                                aria-label="Delete {{ $material->title }}">

                                            <i class="bi bi-trash3"></i>

                                        </button>

                                    </form>

                                </div>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="7">

                                <div class="empty-state">

                                    <div class="empty-icon">
                                        <i class="bi bi-journal-x"></i>
                                    </div>

                                    <h5 class="empty-title">
                                        No Learning Materials Yet
                                    </h5>

                                    <p class="empty-description">
                                        Your learning library is waiting for its first educational resource.
                                    </p>

                                    <a href="{{ route('admin.learning-materials.create') }}"
                                       class="btn empty-add-btn">

                                        <i class="bi bi-plus-lg me-2"></i>
                                        Add First Material

                                    </a>

                                </div>

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>


        {{-- PAGINATION --}}
        @if($materials->hasPages())

            <div class="panel-footer">

                <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">

                    <div class="text-muted" style="font-size:14px;">

                        Showing
                        <strong>{{ $materials->firstItem() ?? 0 }}</strong>
                        to
                        <strong>{{ $materials->lastItem() ?? 0 }}</strong>
                        of
                        <strong>{{ $materials->total() }}</strong>
                        materials

                    </div>

                    {{ $materials->onEachSide(1)->links('pagination::bootstrap-5') }}

                </div>

            </div>

        @endif

    </div>

</div>

@endsection
