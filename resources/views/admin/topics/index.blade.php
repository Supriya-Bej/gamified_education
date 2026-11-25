@extends('admin.layouts.app')

@section('title', 'Topic Management')

@section('page-heading', 'Topic Management')

@section('content')

<style>
    .topics-page {
        --forest: #123c2d;
        --green: #16845b;
        --teal: #159c91;
        --mint: #e9f6ef;
        --navy: #1d3444;
        --muted: #82938a;
        --border: #e7eee9;
    }

    .topics-page .hero {
        position: relative;
        overflow: hidden;
        padding: 34px;
        border-radius: 23px;
        color: white;
        background: linear-gradient(115deg, #103829, #17684c 60%, #159c91);
        box-shadow: 0 12px 30px rgba(18, 60, 45, .13);
    }

    .topics-page .hero::before {
        content: "";
        position: absolute;
        width: 270px;
        height: 270px;
        right: -65px;
        top: -135px;
        border: 1px solid rgba(255, 255, 255, .13);
        border-radius: 50%;
        box-shadow: 0 0 0 35px rgba(255, 255, 255, .035),
            0 0 0 70px rgba(255, 255, 255, .025);
    }

    .topics-page .hero-content {
        position: relative;
        z-index: 1;
    }

    .topics-page .hero-icon {
        width: 76px;
        height: 76px;
        border-radius: 21px;
        background: rgba(255, 255, 255, .15);
        border: 1px solid rgba(255, 255, 255, .2);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 34px;
        flex-shrink: 0;
    }

    .topics-page .hero-label {
        color: #b7eed5;
        font-size: 12px;
        font-weight: 800;
        letter-spacing: 1.5px;
        margin-bottom: 8px;
    }

    .topics-page .hero-title {
        font-size: 32px;
        font-weight: 850;
        letter-spacing: -.7px;
        margin-bottom: 7px;
    }

    .topics-page .hero-description {
        color: rgba(255, 255, 255, .77);
        font-size: 16px;
        margin: 0;
    }

    .topics-page .hero-count {
        background: rgba(255, 255, 255, .13);
        border: 1px solid rgba(255, 255, 255, .22);
        border-radius: 14px;
        padding: 13px 20px;
        min-width: 120px;
        text-align: center;
    }

    .topics-page .hero-count strong {
        display: block;
        font-size: 27px;
        font-weight: 850;
    }

    .topics-page .hero-count span {
        color: rgba(255, 255, 255, .78);
        font-size: 11px;
        font-weight: 800;
        letter-spacing: .8px;
    }

    .topics-page .add-topic-btn {
        background: white;
        color: #14734f;
        border: 0;
        border-radius: 11px;
        padding: 12px 18px;
        font-size: 14px;
        font-weight: 800;
        box-shadow: 0 5px 15px rgba(0, 0, 0, .08);
        transition: .2s;
        white-space: nowrap;
    }

    .topics-page .add-topic-btn:hover {
        background: #e7f7ed;
        color: #0d6242;
        transform: translateY(-2px);
    }

    .topics-page .stat-card {
        background: #fff;
        border: 1px solid var(--border);
        border-radius: 17px;
        padding: 21px;
        height: 100%;
        box-shadow: 0 5px 20px rgba(18, 60, 45, .04);
        transition: .2s;
    }

    .topics-page .stat-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 24px rgba(18, 60, 45, .08);
    }

    .topics-page .stat-icon {
        width: 46px;
        height: 46px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 21px;
        margin-bottom: 14px;
    }

    .topics-page .stat-number {
        font-size: 29px;
        font-weight: 850;
        color: var(--forest);
        line-height: 1.2;
    }

    .topics-page .stat-label {
        color: var(--muted);
        font-size: 14px;
        font-weight: 650;
        margin-top: 6px;
    }

    .topics-page .content-panel {
        background: white;
        border: 1px solid var(--border);
        border-radius: 19px;
        overflow: hidden;
        box-shadow: 0 5px 22px rgba(18, 60, 45, .045);
    }

    .topics-page .panel-header {
        padding: 24px 27px;
        background: linear-gradient(110deg, #fbfdfb, #f1f8f3);
        border-bottom: 1px solid var(--border);
    }

    .topics-page .panel-title {
        font-size: 21px;
        font-weight: 850;
        color: var(--forest);
        margin-bottom: 5px;
    }

    .topics-page .panel-subtitle {
        font-size: 14px;
        color: var(--muted);
        margin: 0;
    }

    .topics-page .total-pill {
        padding: 9px 15px;
        background: #e6f5ec;
        color: #14734f;
        border-radius: 30px;
        font-size: 13px;
        font-weight: 800;
    }

    .topics-page .table {
        margin-bottom: 0;
    }

    .topics-page .table thead th {
        padding: 17px 18px;
        background: #f1f7f3;
        color: #4a6756;
        border-bottom: 1px solid #e3ede6;
        font-size: 12px;
        text-transform: uppercase;
        letter-spacing: .7px;
        font-weight: 850;
        white-space: nowrap;
    }

    .topics-page .table tbody td {
        padding: 19px 18px;
        vertical-align: middle;
        border-bottom: 1px solid #edf2ee;
        font-size: 14px;
        color: #53665a;
    }

    .topics-page .table tbody tr {
        transition: background .2s;
    }

    .topics-page .table tbody tr:hover {
        background: #f8fcf9;
    }

    .topics-page .table tbody tr:last-child td {
        border-bottom: 0;
    }

    .topics-page .topic-identity {
        display: flex;
        align-items: center;
        gap: 12px;
        min-width: 175px;
    }

    .topics-page .topic-icon {
        width: 43px;
        height: 43px;
        border-radius: 13px;
        background: #e7f5ec;
        color: #16845b;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        flex-shrink: 0;
    }

    .topics-page .topic-name {
        font-size: 15px;
        font-weight: 800;
        color: var(--forest);
        overflow-wrap: anywhere;
    }

    .topics-page .topic-caption {
        font-size: 12px;
        color: #9aa89f;
        margin-top: 3px;
    }

    .topics-page .description-text {
        max-width: 290px;
        line-height: 1.65;
        color: #718177;
        font-size: 13px;
    }

    .topics-page .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        padding: 8px 12px;
        border-radius: 30px;
        font-size: 12px;
        font-weight: 850;
        white-space: nowrap;
    }

    .topics-page .status-active {
        background: #e4f6eb;
        color: #16764b;
    }

    .topics-page .status-inactive {
        background: #eef1f0;
        color: #68766f;
    }

    .topics-page .status-dot {
        width: 7px;
        height: 7px;
        border-radius: 50%;
        background: currentColor;
    }

    .topics-page .date-value {
        font-size: 14px;
        font-weight: 650;
        color: #52675b;
        white-space: nowrap;
    }

    .topics-page .date-icon {
        color: #15966b;
        margin-right: 6px;
    }

    .topics-page .action-btn {
        width: 37px;
        height: 37px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 10px;
        border: 1px solid;
        font-size: 15px;
        transition: .2s;
    }

    .topics-page .edit-btn {
        color: #4872ce;
        border-color: #dce6fa;
        background: #f3f7ff;
    }

    .topics-page .edit-btn:hover {
        color: white;
        background: #4872ce;
        border-color: #4872ce;
    }

    .topics-page .delete-btn {
        color: #d45a5a;
        border-color: #f5dede;
        background: #fff6f5;
    }

    .topics-page .delete-btn:hover {
        color: white;
        background: #d45a5a;
        border-color: #d45a5a;
    }

    .topics-page .empty-state {
        padding: 65px 20px;
        text-align: center;
    }

    .topics-page .empty-icon {
        width: 78px;
        height: 78px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 18px;
        border-radius: 23px;
        background: #e9f6ee;
        color: #16845b;
        font-size: 35px;
    }

    .topics-page .empty-title {
        color: var(--forest);
        font-size: 20px;
        font-weight: 850;
        margin-bottom: 8px;
    }

    .topics-page .empty-description {
        color: var(--muted);
        font-size: 15px;
        margin-bottom: 20px;
    }

    .topics-page .empty-add-btn {
        background: #16845b;
        color: white;
        border-radius: 10px;
        padding: 11px 18px;
        font-size: 14px;
        font-weight: 800;
        border: 0;
    }

    .topics-page .empty-add-btn:hover {
        background: #106b49;
        color: white;
    }

    .topics-page .panel-footer {
        padding: 20px 25px;
        background: white;
        border-top: 1px solid var(--border);
    }

    .topics-page .pagination {
        gap: 5px;
        margin-bottom: 0;
    }

    .topics-page .page-link {
        border: 1px solid #e2ebe4;
        border-radius: 9px !important;
        color: #426b54;
        font-weight: 700;
        padding: 8px 13px;
        box-shadow: none;
    }

    .topics-page .page-item.active .page-link {
        background: #16845b;
        border-color: #16845b;
        color: white;
    }

    .topics-page .page-item.disabled .page-link {
        background: #f8faf9;
        color: #aab5ae;
    }

    .topics-page .success-alert {
        background: #eaf7ef;
        border: 1px solid #ccebd8;
        color: #176b46;
        border-radius: 13px;
        padding: 15px 18px;
        font-size: 15px;
        font-weight: 600;
    }

    @media(max-width: 767px) {
        .topics-page .hero {
            padding: 23px 19px;
        }

        .topics-page .hero-title {
            font-size: 25px;
        }

        .topics-page .hero-top {
            flex-direction: column;
            align-items: flex-start !important;
            gap: 20px;
        }

        .topics-page .hero-icon {
            width: 60px;
            height: 60px;
            font-size: 28px;
        }

        .topics-page .panel-header {
            padding: 21px 17px;
        }

        .topics-page .panel-footer {
            padding: 17px;
        }
    }
</style>


<div class="topics-page">

    {{-- HERO HEADER --}}
    <div class="hero mb-4">

        <div class="hero-content">

            <div class="d-flex justify-content-between align-items-center hero-top">

                <div class="d-flex align-items-center gap-3">

                    <div class="hero-icon">
                        <i class="bi bi-bookmarks-fill"></i>
                    </div>

                    <div>
                        <div class="hero-label">
                            <i class="bi bi-mortarboard-fill me-1"></i>
                            ECOQUEST / LEARNING LIBRARY
                        </div>

                        <h2 class="hero-title">
                            Topic Management
                        </h2>

                        <p class="hero-description">
                            Organize and maintain the learning topics available across EcoQuest.
                        </p>
                    </div>

                </div>

                <div class="d-flex align-items-center gap-3 flex-wrap">

                    <div class="hero-count">
                        <strong>{{ $topics->total() }}</strong>
                        <span>TOTAL TOPICS</span>
                    </div>

                    <a href="{{ route('admin.topics.create') }}"
                        class="btn add-topic-btn">

                        <i class="bi bi-plus-circle-fill me-2"></i>
                        Add New Topic

                    </a>

                </div>

            </div>

        </div>

    </div>


    {{-- SUCCESS MESSAGE --}}
    @if(session('success'))

    <div class="alert success-alert alert-dismissible fade show mb-4">

        <i class="bi bi-check-circle-fill me-2"></i>

        {{ session('success') }}

        <button type="button"
            class="btn-close"
            data-bs-dismiss="alert">
        </button>

    </div>

    @endif


    {{-- SUMMARY CARDS --}}
    <div class="row g-3 mb-4">

        <div class="col-md-4">

            <div class="stat-card">

                <div class="stat-icon"
                    style="background:#e6f5ec;color:#16845b;">
                    <i class="bi bi-bookmarks-fill"></i>
                </div>

                <div class="stat-number">
                    {{ $topics->total() }}
                </div>

                <div class="stat-label">
                    Total Learning Topics
                </div>

            </div>

        </div>


        <div class="col-md-4">

            <div class="stat-card">

                <div class="stat-icon"
                    style="background:#e4f5ee;color:#159c91;">
                    <i class="bi bi-check-circle-fill"></i>
                </div>

                <div class="stat-number">
                    {{ $topics->getCollection()->where('is_active', true)->count() }}
                </div>

                <div class="stat-label">
                    Active Topics on This Page
                </div>

            </div>

        </div>


        <div class="col-md-4">

            <div class="stat-card">

                <div class="stat-icon"
                    style="background:#eef0f3;color:#718096;">
                    <i class="bi bi-pause-circle-fill"></i>
                </div>

                <div class="stat-number">
                    {{ $topics->getCollection()->where('is_active', false)->count() }}
                </div>

                <div class="stat-label">
                    Inactive Topics on This Page
                </div>

            </div>

        </div>

    </div>


    {{-- TOPICS TABLE --}}
    <div class="content-panel">

        <div class="panel-header">

            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">

                <div class="d-flex align-items-center gap-3">

                    <div class="stat-icon mb-0"
                        style="background:#e6f5ec;color:#16845b;width:45px;height:45px;">
                        <i class="bi bi-collection-fill"></i>
                    </div>

                    <div>
                        <h4 class="panel-title">
                            Learning Topics
                        </h4>

                        <p class="panel-subtitle">
                            Manage topic details, availability and creation dates.
                        </p>
                    </div>

                </div>

                <span class="total-pill">
                    <i class="bi bi-layers me-1"></i>
                    {{ $topics->total() }} Topics
                </span>

            </div>

        </div>


        @if($topics->count() > 0)

        <div class="table-responsive">

            <table class="table align-middle">

                <thead>

                    <tr>

                        <th class="ps-4">#</th>

                        <th>Topic Name</th>

                        <th>Description</th>

                        <th>Status</th>

                        <th>Created Date</th>

                        <th class="text-end pe-4">Actions</th>

                    </tr>

                </thead>


                <tbody>

                    @foreach($topics as $topic)

                    <tr>

                        {{-- NUMBER --}}
                        <td class="ps-4">
                            <span class="fw-bold text-secondary">
                                {{ $topics->firstItem() + $loop->index }}
                            </span>
                        </td>


                        {{-- TOPIC NAME --}}
                        <td>

                            <div class="topic-identity">

                                <div class="topic-icon">
                                    <i class="bi bi-book-half"></i>
                                </div>

                                <div>
                                    <div class="topic-name">
                                        {{ $topic->name }}
                                    </div>

                                    <div class="topic-caption">
                                        Learning topic
                                    </div>
                                </div>

                            </div>

                        </td>


                        {{-- DESCRIPTION --}}
                        <td>

                            <div class="description-text">

                                {{ $topic->description
                                            ? \Illuminate\Support\Str::limit($topic->description, 70)
                                            : 'No description available.'
                                        }}

                            </div>

                        </td>


                        {{-- STATUS --}}
                        <td>

                            @if($topic->is_active)

                            <span class="status-badge status-active">
                                <span class="status-dot"></span>
                                Active
                            </span>

                            @else

                            <span class="status-badge status-inactive">
                                <span class="status-dot"></span>
                                Inactive
                            </span>

                            @endif

                        </td>


                        {{-- CREATED DATE --}}
                        <td>

                            <span class="date-value">

                                <i class="bi bi-calendar3 date-icon"></i>

                                {{ $topic->created_at
                                            ? $topic->created_at->format('d M Y')
                                            : '—'
                                        }}

                            </span>

                        </td>


                        {{-- ACTIONS --}}
                        <td class="text-end pe-4">

                            <div class="d-flex justify-content-end gap-2">

                                {{-- EDIT --}}
                                <a href="{{ route('admin.topics.edit', $topic) }}"
                                    class="btn action-btn edit-btn"
                                    title="Edit Topic"
                                    aria-label="Edit Topic">

                                    <i class="bi bi-pencil-square"></i>

                                </a>


                                {{-- DELETE --}}
                                <form action="{{ route('admin.topics.destroy', $topic) }}"
                                    method="POST"
                                    onsubmit="return confirm('Are you sure you want to delete this topic?');">

                                    @csrf
                                    @method('DELETE')

                                    <button type="submit"
                                        class="btn action-btn delete-btn"
                                        title="Delete Topic"
                                        aria-label="Delete Topic">

                                        <i class="bi bi-trash3"></i>

                                    </button>

                                </form>

                            </div>

                        </td>

                    </tr>

                    @endforeach

                </tbody>

            </table>

        </div>


        {{-- PAGINATION --}}
        @if($topics->hasPages())

        <div class="panel-footer">

            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">

                <div class="text-muted" style="font-size:14px;">
                    Showing
                    <strong>{{ $topics->firstItem() }}</strong>
                    to
                    <strong>{{ $topics->lastItem() }}</strong>
                    of
                    <strong>{{ $topics->total() }}</strong>
                    topics
                </div>

                {{ $topics->links('pagination::bootstrap-5') }}

            </div>

        </div>

        @endif

        @else

        {{-- EMPTY STATE --}}
        <div class="empty-state">

            <div class="empty-icon">
                <i class="bi bi-bookmarks-fill"></i>
            </div>

            <h5 class="empty-title">
                No Learning Topics Yet
            </h5>

            <p class="empty-description">
                Your learning library is waiting. Create the first topic to get started.
            </p>

            <a href="{{ route('admin.topics.create') }}"
                class="btn empty-add-btn">

                <i class="bi bi-plus-circle-fill me-2"></i>
                Create First Topic

            </a>

        </div>

        @endif

    </div>

</div>

@endsection