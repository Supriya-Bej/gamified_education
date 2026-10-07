@extends('admin.layouts.app')

@section('content')

<div class="container-fluid py-4">

    {{-- Header --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">

        <div>
            <h2 class="fw-bold mb-1">
                <i class="bi bi-bookmarks-fill me-2"></i>
                Topics
            </h2>

            <p class="text-muted mb-0">
                Manage learning topics for EcoQuest.
            </p>
        </div>

        <a href="{{ route('admin.topics.create') }}"
            class="btn btn-primary">

            <i class="bi bi-plus-circle me-1"></i>
            Add Topic

        </a>

    </div>


    {{-- Success Message --}}
    @if(session('success'))

    <div class="alert alert-success alert-dismissible fade show">
        <i class="bi bi-check-circle-fill me-2"></i>

        {{ session('success') }}

        <button type="button"
            class="btn-close"
            data-bs-dismiss="alert">
        </button>
    </div>

    @endif


    {{-- Topics Table --}}
    <div class="card border-0 shadow-sm">

        <div class="card-body p-0">

            @if($topics->count() > 0)

            <div class="table-responsive">

                <table class="table table-hover align-middle mb-0">

                    <thead class="table-light">

                        <tr>

                            <th class="px-4">#</th>

                            <th>Topic Name</th>

                            <th>Description</th>

                            <th>Status</th>

                            <th>Created</th>

                            <th class="text-end px-4">
                                Actions
                            </th>

                        </tr>

                    </thead>

                    <tbody>

                        @foreach($topics as $topic)

                        <tr>

                            <td class="px-4">
                                {{ $topics->firstItem() + $loop->index }}
                            </td>


                            {{-- Topic Name --}}
                            <td>
                                <div class="fw-semibold">
                                    {{ $topic->name }}
                                </div>
                            </td>


                            {{-- Description --}}
                            <td>

                                <span class="text-muted">

                                    {{ $topic->description
                                                ? \Illuminate\Support\Str::limit(
                                                    $topic->description,
                                                    70
                                                )
                                                : 'No description'
                                            }}

                                </span>

                            </td>


                            {{-- Status --}}
                            <td>

                                @if($topic->is_active)

                                <span class="badge bg-success">
                                    <i class="bi bi-check-circle me-1"></i>
                                    Active
                                </span>

                                @else

                                <span class="badge bg-secondary">
                                    <i class="bi bi-pause-circle me-1"></i>
                                    Inactive
                                </span>

                                @endif

                            </td>


                            {{-- Created --}}
                            <td>

                                <span class="text-muted">
                                    {{ $topic->created_at->format('d M Y') }}
                                </span>

                            </td>


                            {{-- Actions --}}
                            <td class="text-end px-4">

                                <div class="d-flex justify-content-end gap-2">

                                    {{-- Edit --}}
                                    <a href="{{ route('admin.topics.edit', $topic) }}"
                                        class="btn btn-sm btn-outline-primary"
                                        title="Edit">

                                        <i class="bi bi-pencil"></i>

                                    </a>


                                    {{-- Delete --}}
                                    <form action="{{ route('admin.topics.destroy', $topic) }}"
                                        method="POST"
                                        onsubmit="return confirm('Are you sure you want to delete this topic?');">

                                        @csrf
                                        @method('DELETE')

                                        <button type="submit"
                                            class="btn btn-sm btn-outline-danger"
                                            title="Delete">

                                            <i class="bi bi-trash"></i>

                                        </button>

                                    </form>

                                </div>

                            </td>

                        </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>


            {{-- Pagination --}}
            <div class="p-3">

                {{ $topics->links() }}

            </div>

            @else

            {{-- Empty State --}}
            <div class="text-center py-5">

                <div class="mb-3">

                    <i class="bi bi-bookmarks"
                        style="font-size: 55px; color: #adb5bd;">
                    </i>

                </div>

                <h5 class="fw-bold">
                    No Topics Found
                </h5>

                <p class="text-muted">
                    Create your first learning topic.
                </p>

                <a href="{{ route('admin.topics.create') }}"
                    class="btn btn-primary">

                    <i class="bi bi-plus-circle me-1"></i>
                    Add First Topic

                </a>

            </div>

            @endif

        </div>

    </div>

</div>

@endsection