@extends('admin.layouts.app')

@section('title', 'Edit Badge')

@section('page-heading')
Edit Badge
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

                                @if($badge->icon)

                                <span style="font-size: 28px;">
                                    {{ $badge->icon }}
                                </span>

                                @else

                                <i class="bi bi-award-fill"></i>

                                @endif

                            </div>

                        </div>

                        <div>

                            <h4 class="fw-bold mb-1">
                                Edit Badge
                            </h4>

                            <p class="text-muted mb-0">
                                Update badge information and requirements.
                            </p>

                        </div>

                    </div>

                </div>


                <div class="card-body p-4">

                    <form action="{{ route('admin.badges.update', $badge->id) }}"
                        method="POST">

                        @csrf
                        @method('PUT')


                        {{-- Name --}}
                        <div class="mb-4">

                            <label class="form-label fw-semibold">
                                Badge Name
                            </label>

                            <input type="text"
                                name="name"
                                value="{{ old('name', $badge->name) }}"
                                class="form-control form-control-lg @error('name') is-invalid @enderror">

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
                                class="form-control @error('description') is-invalid @enderror">{{ old('description', $badge->description) }}</textarea>

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
                                    value="{{ old('icon', $badge->icon) }}"
                                    class="form-control @error('icon') is-invalid @enderror">

                                <small class="text-muted">
                                    Example: 🏆 🌱 ⭐ 🎯
                                </small>

                                @error('icon')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror

                            </div>


                            {{-- Requirement --}}
                            <div class="col-md-6 mb-4">

                                <label class="form-label fw-semibold">
                                    Requirement Type
                                </label>

                                <select name="requirement_type"
                                    class="form-select @error('requirement_type') is-invalid @enderror">

                                    <option value="total_xp"
                                        {{ old('requirement_type', $badge->requirement_type) === 'total_xp' ? 'selected' : '' }}>
                                        Total XP
                                    </option>

                                    <option value="completed_tasks"
                                        {{ old('requirement_type', $badge->requirement_type) === 'completed_tasks' ? 'selected' : '' }}>
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
                                min="1"
                                value="{{ old('requirement_value', $badge->requirement_value) }}"
                                class="form-control form-control-lg @error('requirement_value') is-invalid @enderror">

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
                                {{ old('is_active', $badge->is_active) ? 'checked' : '' }}>

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
                                Back

                            </a>


                            <button type="submit"
                                class="btn btn-primary px-4">

                                <i class="bi bi-save me-1"></i>
                                Update Badge

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