@extends('admin.layouts.app')

@section('title', 'Learning Materials')

@section('page-heading')
    Learning Materials
@endsection

@section('content')

<div class="container-fluid px-0">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h4 class="fw-bold mb-1">
                <i class="bi bi-book-half me-2"></i>
                Learning Materials
            </h4>

            <p class="text-muted mb-0">
                Manage educational content available to students.
            </p>
        </div>

        <a href="{{ route('admin.learning-materials.create') }}"
           class="btn btn-primary">

            <i class="bi bi-plus-lg me-1"></i>
            Add Material

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


    {{-- Error Message --}}
    @if(session('error'))

        <div class="alert alert-danger alert-dismissible fade show">

            <i class="bi bi-exclamation-triangle-fill me-2"></i>

            {{ session('error') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
            </button>

        </div>

    @endif


    {{-- Materials Table --}}
    <div class="card border-0 shadow-sm">

        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-hover align-middle mb-0">

                    <thead class="table-light">

                        <tr>

                            <th class="px-4">
                                #
                            </th>

                            <th>
                                Material
                            </th>

                            <th>
                                Topic
                            </th>

                            <th>
                                Difficulty
                            </th>

                            <th>
                                Status
                            </th>

                            <th>
                                Created
                            </th>

                            <th class="text-end px-4">
                                Actions
                            </th>

                        </tr>

                    </thead>

                    <tbody>

                        @forelse($materials as $material)

                            <tr>

                                {{-- ID --}}
                                <td class="px-4">

                                    {{ $material->id }}

                                </td>


                                {{-- Material --}}
                                <td>

                                    <div class="d-flex align-items-center">

                                        @if($material->image)

                                            <img src="{{ asset('storage/' . $material->image) }}"
                                                 alt="{{ $material->title }}"
                                                 width="50"
                                                 height="50"
                                                 class="rounded object-fit-cover me-3">

                                        @else

                                            <div class="rounded bg-primary bg-opacity-10
                                                        text-primary
                                                        d-flex align-items-center
                                                        justify-content-center me-3"
                                                 style="width:50px;height:50px;">

                                                <i class="bi bi-book fs-5"></i>

                                            </div>

                                        @endif


                                        <div>

                                            <div class="fw-semibold">
                                                {{ $material->title }}
                                            </div>

                                            @if($material->description)

                                                <small class="text-muted">

                                                    {{ Str::limit($material->description, 55) }}

                                                </small>

                                            @endif

                                        </div>

                                    </div>

                                </td>


                                {{-- Topic --}}
                                <td>

                                    <span class="badge bg-info-subtle text-info-emphasis">

                                        {{ $material->topic }}

                                    </span>

                                </td>


                                {{-- Difficulty --}}
                                <td>

                                    @if($material->difficulty === 'beginner')

                                        <span class="badge bg-success">
                                            Beginner
                                        </span>

                                    @elseif($material->difficulty === 'intermediate')

                                        <span class="badge bg-warning text-dark">
                                            Intermediate
                                        </span>

                                    @else

                                        <span class="badge bg-danger">
                                            Advanced
                                        </span>

                                    @endif

                                </td>


                                {{-- Status --}}
                                <td>

                                    @if($material->is_published)

                                        <span class="badge bg-success-subtle text-success">

                                            <i class="bi bi-check-circle-fill me-1"></i>

                                            Published

                                        </span>

                                    @else

                                        <span class="badge bg-secondary-subtle text-secondary">

                                            <i class="bi bi-eye-slash-fill me-1"></i>

                                            Draft

                                        </span>

                                    @endif

                                </td>


                                {{-- Created --}}
                                <td>

                                    <small class="text-muted">

                                        {{ $material->created_at?->format('d M Y') }}

                                    </small>

                                </td>


                                {{-- Actions --}}
                                <td class="text-end px-4">

                                    <a href="{{ route('admin.learning-materials.edit', $material) }}"
                                       class="btn btn-sm btn-outline-primary me-1"
                                       title="Edit">

                                        <i class="bi bi-pencil"></i>

                                    </a>


                                    <form action="{{ route('admin.learning-materials.destroy', $material) }}"
                                          method="POST"
                                          class="d-inline"
                                          onsubmit="return confirm('Are you sure you want to delete this learning material?');">

                                        @csrf

                                        @method('DELETE')

                                        <button type="submit"
                                                class="btn btn-sm btn-outline-danger"
                                                title="Delete">

                                            <i class="bi bi-trash"></i>

                                        </button>

                                    </form>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="7"
                                    class="text-center py-5">

                                    <div class="mb-3"
                                         style="font-size:50px;">

                                        📚

                                    </div>

                                    <h5 class="fw-bold">
                                        No Learning Materials
                                    </h5>

                                    <p class="text-muted mb-3">
                                        Start by adding your first educational material.
                                    </p>

                                    <a href="{{ route('admin.learning-materials.create') }}"
                                       class="btn btn-primary">

                                        <i class="bi bi-plus-lg me-1"></i>

                                        Add First Material

                                    </a>

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>


        {{-- Pagination --}}
        @if($materials->hasPages())

            <div class="card-footer bg-white border-0 py-3">

                {{ $materials->links() }}

            </div>

        @endif

    </div>

</div>

@endsection