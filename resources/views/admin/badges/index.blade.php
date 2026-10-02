@extends('admin.layouts.app')

@section('title', 'Badge Management')

@section('page-heading')
Badge & Achievement Management
@endsection

@section('content')

<div class="container-fluid">

    {{-- Success Message --}}
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle-fill me-2"></i>
        {{ session('success') }}

        <button type="button"
            class="btn-close"
            data-bs-dismiss="alert"></button>
    </div>
    @endif

    {{-- Error Message --}}
    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="bi bi-exclamation-triangle-fill me-2"></i>
        {{ session('error') }}

        <button type="button"
            class="btn-close"
            data-bs-dismiss="alert"></button>
    </div>
    @endif


    {{-- Header --}}
    <div class="card border-0 shadow-sm mb-4">

        <div class="card-body p-4">

            <div class="d-flex justify-content-between align-items-center">

                <div>
                    <h4 class="fw-bold mb-1">
                        <i class="bi bi-award-fill text-warning me-2"></i>
                        Badges
                    </h4>

                    <p class="text-muted mb-0">
                        Create and manage student achievements and rewards.
                    </p>
                </div>

                <a href="{{ route('admin.badges.create') }}"
                    class="btn btn-primary">

                    <i class="bi bi-plus-circle me-1"></i>
                    Add Badge

                </a>

            </div>

        </div>

    </div>


    {{-- Badge Table --}}
    <div class="card border-0 shadow-sm">

        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-hover align-middle mb-0">

                    <thead class="table-light">

                        <tr>

                            <th class="px-4">#</th>

                            <th>Badge</th>

                            <th>Description</th>

                            <th>Requirement</th>

                            <th>Value</th>

                            <th>Status</th>

                            <th class="text-end px-4">
                                Actions
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        @forelse($badges as $badge)

                        <tr>

                            {{-- ID --}}
                            <td class="px-4">
                                {{ $badge->id }}
                            </td>


                            {{-- Badge --}}
                            <td>

                                <div class="d-flex align-items-center">

                                    <div class="badge-icon me-3">

                                        @if($badge->icon)

                                        <span style="font-size: 28px;">
                                            {{ $badge->icon }}
                                        </span>

                                        @else

                                        <i class="bi bi-award-fill text-warning fs-4"></i>

                                        @endif

                                    </div>

                                    <div>

                                        <div class="fw-bold">
                                            {{ $badge->name }}
                                        </div>

                                    </div>

                                </div>

                            </td>


                            {{-- Description --}}
                            <td>

                                <span class="text-muted">

                                    {{ $badge->description ?: 'No description' }}

                                </span>

                            </td>


                            {{-- Requirement --}}
                            <td>

                                @if($badge->requirement_type === 'total_xp')

                                <span class="badge bg-primary-subtle text-primary">
                                    Total XP
                                </span>

                                @elseif($badge->requirement_type === 'completed_tasks')

                                <span class="badge bg-success-subtle text-success">
                                    Completed Tasks
                                </span>

                                @else

                                <span class="badge bg-secondary">
                                    {{ $badge->requirement_type }}
                                </span>

                                @endif

                            </td>


                            {{-- Requirement Value --}}
                            <td>

                                <strong>
                                    {{ $badge->requirement_value }}
                                </strong>

                            </td>


                            {{-- Status --}}
                            <td>

                                @if($badge->is_active)

                                <span class="badge bg-success">
                                    <i class="bi bi-check-circle me-1"></i>
                                    Active
                                </span>

                                @else

                                <span class="badge bg-secondary">
                                    <i class="bi bi-x-circle me-1"></i>
                                    Inactive
                                </span>

                                @endif

                            </td>


                            {{-- Actions --}}
                            <td class="text-end px-4">

                                <div class="d-flex justify-content-end gap-2">

                                    <a href="{{ route('admin.badges.edit', $badge->id) }}"
                                        class="btn btn-sm btn-outline-primary">

                                        <i class="bi bi-pencil"></i>

                                    </a>


                                    <form action="{{ route('admin.badges.destroy', $badge->id) }}"
                                        method="POST"
                                        onsubmit="return confirm('Are you sure you want to delete this badge?');">

                                        @csrf
                                        @method('DELETE')

                                        <button type="submit"
                                            class="btn btn-sm btn-outline-danger">

                                            <i class="bi bi-trash"></i>

                                        </button>

                                    </form>

                                </div>

                            </td>

                        </tr>

                        @empty

                        <tr>

                            <td colspan="7"
                                class="text-center py-5">

                                <div class="mb-3">

                                    <i class="bi bi-award text-muted"
                                        style="font-size: 50px;"></i>

                                </div>

                                <h5 class="fw-bold">
                                    No badges found
                                </h5>

                                <p class="text-muted">
                                    Create your first badge to reward students.
                                </p>

                                <a href="{{ route('admin.badges.create') }}"
                                    class="btn btn-primary">

                                    <i class="bi bi-plus-circle me-1"></i>
                                    Create First Badge

                                </a>

                            </td>

                        </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>


        {{-- Pagination --}}
        @if($badges->hasPages())

        <div class="card-footer bg-white border-0 py-3">

            {{ $badges->links() }}

        </div>

        @endif

    </div>

</div>


<style>
    .card {
        border-radius: 16px;
    }

    .table> :not(caption)>*>* {
        padding-top: 15px;
        padding-bottom: 15px;
    }

    .badge-icon {
        width: 45px;
        height: 45px;

        display: flex;
        align-items: center;
        justify-content: center;

        background: #fff8e1;

        border-radius: 12px;
    }
</style>

@endsection