@extends('admin.layouts.app')

@section('title', 'Create Badge')

@section('page-heading')
Create New Badge
@endsection

@section('content')

<div class="container-fluid">

    <div class="row justify-content-center">

        <div class="col-xl-8 col-lg-10">

            <div class="card border-0 shadow-sm">

                <div class="card-header bg-white border-0 p-4">

                    <div class="d-flex align-items-center">

                        <div class="me-3">

                            <div class="badge-create-icon">

                                <i class="bi bi-award-fill"></i>

                            </div>

                        </div>

                        <div>

                            <h4 class="fw-bold mb-1">
                                Create New Badge
                            </h4>

                            <p class="text-muted mb-0">
                                Add a new achievement for EcoQuest students.
                            </p>

                        </div>

                    </div>

                </div>


                <div class="card-body p-4">

                    <form action="{{ route('admin.badges.store') }}"
                        method="POST">

                        @csrf


                        {{-- Badge Name --}}
                        <div class="mb-4">

                            <label class="form-label fw-semibold">
                                Badge Name
                            </label>

                            <input type="text"
                                name="name"
                                value="{{ old('name') }}"
                                class="form-control form-control-lg @error('name') is-invalid @enderror"
                                placeholder="Example: First Quest">

                            @error('name')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror

                        </div>


                        {{-- Description --}}
                        <div class="mb-4">

                            <label class="form-label fw-semibold">
                                Description
                            </label>

                            <textarea name="description"
                                rows="4"
                                class="form-control @error('description') is-invalid @enderror"
                                placeholder="Describe what this badge represents...">{{ old('description') }}</textarea>

                            @error('description')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror

                        </div>


                        <div class="row">

                            {{-- Icon --}}
                            <div class="col-md-6 mb-4">

                                <label class="form-label fw-semibold">
                                    Badge Icon
                                </label>

                                <input type="text"
                                    name="icon"
                                    value="{{ old('icon') }}"
                                    class="form-control @error('icon') is-invalid @enderror"
                                    placeholder="🏆">

                                <small class="text-muted">
                                    You can use an emoji such as 🏆 🌱 ⭐ 🎯
                                </small>

                                @error('icon')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror

                            </div>


                            {{-- Requirement Type --}}
                            <div class="col-md-6 mb-4">

                                <label class="form-label fw-semibold">
                                    Requirement Type
                                </label>

                                <select name="requirement_type"
                                    class="form-select @error('requirement_type') is-invalid @enderror">

                                    <option value="">
                                        Select Requirement
                                    </option>

                                    <option value="total_xp"
                                        {{ old('requirement_type') === 'total_xp' ? 'selected' : '' }}>
                                        Total XP
                                    </option>

                                    <option value="completed_tasks"
                                        {{ old('requirement_type') === 'completed_tasks' ? 'selected' : '' }}>
                                        Completed Tasks
                                    </option>

                                </select>

                                @error('requirement_type')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror

                            </div>

                        </div>


                        {{-- Requirement Value --}}
                        <div class="mb-4">

                            <label class="form-label fw-semibold">
                                Requirement Value
                            </label>

                            <input type="number"
                                name="requirement_value"
                                value="{{ old('requirement_value') }}"
                                min="1"
                                class="form-control form-control-lg @error('requirement_value') is-invalid @enderror"
                                placeholder="Example: 100">

                            <small class="text-muted">
                                Example: 100 XP or 10 completed tasks.
                            </small>

                            @error('requirement_value')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror

                        </div>


                        {{-- Active --}}
                        <div class="form-check form-switch mb-4">

                            <input class="form-check-input"
                                type="checkbox"
                                name="is_active"
                                value="1"
                                id="is_active"
                                checked>

                            <label class="form-check-label fw-semibold"
                                for="is_active">

                                Badge is Active

                            </label>

                        </div>


                        {{-- Buttons --}}
                        <div class="d-flex justify-content-between">

                            <a href="{{ route('admin.badges.index') }}"
                                class="btn btn-light">

                                <i class="bi bi-arrow-left me-1"></i>
                                Cancel

                            </a>


                            <button type="submit"
                                class="btn btn-primary px-4">

                                <i class="bi bi-check-circle me-1"></i>
                                Create Badge

                            </button>

                        </div>

                    </form>

                </div>

            </div>

        </div>

    </div>

</div>


<style>
    .card {
        border-radius: 18px;
    }

    .badge-create-icon {
        width: 55px;
        height: 55px;

        display: flex;
        align-items: center;
        justify-content: center;

        background: #fff3cd;

        color: #ffb300;

        border-radius: 14px;

        font-size: 26px;
    }

    .form-control,
    .form-select {
        border-radius: 10px;
    }
</style>

@endsection