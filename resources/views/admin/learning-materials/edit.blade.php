@extends('admin.layouts.app')

@section('content')

<div class="container-fluid py-4">

    {{-- Header --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">

        <div>
            <h2 class="fw-bold mb-1">
                <i class="bi bi-pencil-square me-2"></i>
                Edit Learning Material
            </h2>

            <p class="text-muted mb-0">
                Update learning material information.
            </p>
        </div>

        <a href="{{ route('admin.learning-materials.index') }}"
            class="btn btn-outline-secondary">

            <i class="bi bi-arrow-left me-1"></i>
            Back to Materials

        </a>

    </div>


    {{-- Validation Errors --}}
    @if($errors->any())

    <div class="alert alert-danger">

        <div class="fw-semibold mb-2">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>
            Please fix the following errors:
        </div>

        <ul class="mb-0">

            @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach

        </ul>

    </div>

    @endif


    {{-- Form --}}
    <div class="card border-0 shadow-sm">

        <div class="card-body p-4">

            <form action="{{ route('admin.learning-materials.update', $learningMaterial) }}"
                method="POST"
                enctype="multipart/form-data">

                @csrf
                @method('PUT')


                {{-- Title --}}
                <div class="mb-3">

                    <label for="title"
                        class="form-label fw-semibold">

                        Title
                        <span class="text-danger">*</span>

                    </label>

                    <input type="text"
                        name="title"
                        id="title"
                        class="form-control"
                        value="{{ old('title', $learningMaterial->title) }}"
                        required>

                </div>


                {{-- Topic --}}
                <div class="mb-3">

                    <label for="topic_id"
                        class="form-label fw-semibold">

                        Topic
                        <span class="text-danger">*</span>

                    </label>

                    <select name="topic_id"
                        id="topic_id"
                        class="form-select"
                        required>

                        <option value="">
                            Select Topic
                        </option>

                        @foreach($topics as $topic)

                        <option value="{{ $topic->id }}"
                            {{ old('topic_id', $learningMaterial->topic_id) == $topic->id ? 'selected' : '' }}>

                            {{ $topic->name }}

                        </option>

                        @endforeach

                    </select>

                    @error('topic_id')

                    <div class="text-danger small mt-1">
                        {{ $message }}
                    </div>

                    @enderror

                </div>


                {{-- Difficulty --}}
                <div class="mb-3">

                    <label for="difficulty"
                        class="form-label fw-semibold">

                        Difficulty
                        <span class="text-danger">*</span>

                    </label>

                    <select name="difficulty"
                        id="difficulty"
                        class="form-select"
                        required>

                        <option value="beginner"
                            {{ old('difficulty', $learningMaterial->difficulty) == 'beginner' ? 'selected' : '' }}>
                            Beginner
                        </option>

                        <option value="intermediate"
                            {{ old('difficulty', $learningMaterial->difficulty) == 'intermediate' ? 'selected' : '' }}>
                            Intermediate
                        </option>

                        <option value="advanced"
                            {{ old('difficulty', $learningMaterial->difficulty) == 'advanced' ? 'selected' : '' }}>
                            Advanced
                        </option>

                    </select>

                </div>


                {{-- Description --}}
                <div class="mb-3">

                    <label for="description"
                        class="form-label fw-semibold">

                        Description

                    </label>

                    <textarea name="description"
                        id="description"
                        rows="4"
                        class="form-control"
                        placeholder="Write a short description...">{{ old('description', $learningMaterial->description) }}</textarea>

                </div>


                {{-- Content --}}
                <div class="mb-3">

                    <label for="content"
                        class="form-label fw-semibold">

                        Learning Content
                        <span class="text-danger">*</span>

                    </label>

                    <textarea name="content"
                        id="content"
                        rows="10"
                        class="form-control"
                        placeholder="Write the learning material content..."
                        required>{{ old('content', $learningMaterial->content) }}</textarea>

                </div>


                {{-- Current Image --}}
                @if($learningMaterial->image)

                <div class="mb-3">

                    <label class="form-label fw-semibold">
                        Current Image
                    </label>

                    <div>
                        <img src="{{ asset('storage/' . $learningMaterial->image) }}"
                            alt="{{ $learningMaterial->title }}"
                            class="img-thumbnail"
                            style="max-width: 180px; max-height: 120px; object-fit: cover;">
                    </div>

                </div>

                @endif


                {{-- New Image --}}
                <div class="mb-3">

                    <label for="image"
                        class="form-label fw-semibold">

                        Replace Image

                    </label>

                    <input type="file"
                        name="image"
                        id="image"
                        class="form-control"
                        accept=".jpg,.jpeg,.png,.webp">

                    <div class="form-text">
                        Maximum size: 2MB.
                    </div>

                </div>


                {{-- Published --}}
                <div class="mb-4">

                    <div class="form-check form-switch">

                        <input type="checkbox"
                            name="is_published"
                            value="1"
                            class="form-check-input"
                            role="switch"
                            id="is_published"
                            {{ old('is_published', $learningMaterial->is_published) ? 'checked' : '' }}>

                        <label class="form-check-label fw-semibold"
                            for="is_published">

                            Published

                        </label>

                    </div>

                    <div class="form-text">
                        Published materials can be shown to students.
                    </div>

                </div>


                {{-- Buttons --}}
                <div class="d-flex flex-wrap gap-2">

                    <button type="submit"
                        class="btn btn-primary">

                        <i class="bi bi-save me-1"></i>
                        Update Material

                    </button>

                    <a href="{{ route('admin.learning-materials.index') }}"
                        class="btn btn-light border">

                        Cancel

                    </a>

                </div>

            </form>

        </div>

    </div>

</div>

@endsection