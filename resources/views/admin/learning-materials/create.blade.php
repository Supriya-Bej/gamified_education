@extends('admin.layouts.app')

@section('title', 'Add Learning Material')

@section('page-heading')
Add Learning Material
@endsection

@section('content')

<div class="container-fluid px-0">

    {{-- Header --}}
    <div class="mb-4">

        <a href="{{ route('admin.learning-materials.index') }}"
            class="text-decoration-none text-muted">

            <i class="bi bi-arrow-left me-1"></i>
            Back to Learning Materials

        </a>

        <h4 class="fw-bold mt-3 mb-1">
            <i class="bi bi-plus-circle me-2"></i>
            Add Learning Material
        </h4>

        <p class="text-muted mb-0">
            Create educational content for EcoQuest students.
        </p>

    </div>


    {{-- Validation Errors --}}
    @if($errors->any())

    <div class="alert alert-danger">

        <strong>
            Please fix the following errors:
        </strong>

        <ul class="mb-0 mt-2">

            @foreach($errors->all() as $error)

            <li>{{ $error }}</li>

            @endforeach

        </ul>

    </div>

    @endif


    <div class="card border-0 shadow-sm">

        <div class="card-body p-4">

            <form action="{{ route('admin.learning-materials.store') }}"
                method="POST"
                enctype="multipart/form-data">

                @csrf


                <div class="row g-4">


                    {{-- Title --}}
                    <div class="col-12">

                        <label class="form-label fw-semibold">

                            Material Title
                            <span class="text-danger">*</span>

                        </label>

                        <input type="text"
                            name="title"
                            value="{{ old('title') }}"
                            class="form-control"
                            placeholder="Example: Introduction to Climate Change"
                            required>

                    </div>


                    {{-- Topic --}}
                    <div class="col-md-6">

                        <label for="topic_id" class="form-label fw-semibold">
                            Topic <span class="text-danger">*</span>
                        </label>

                        <select name="topic_id"
                            id="topic_id"
                            class="form-select"
                            required>

                            <option value="">Select Topic</option>

                            @foreach($topics as $topic)

                            <option value="{{ $topic->id }}"
                                {{ old('topic_id') == $topic->id ? 'selected' : '' }}>

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
                    <div class="col-md-6">

                        <label class="form-label fw-semibold">

                            Difficulty
                            <span class="text-danger">*</span>

                        </label>

                        <select name="difficulty"
                            class="form-select"
                            required>

                            <option value="beginner"
                                {{ old('difficulty', 'beginner') === 'beginner' ? 'selected' : '' }}>

                                Beginner

                            </option>

                            <option value="intermediate"
                                {{ old('difficulty') === 'intermediate' ? 'selected' : '' }}>

                                Intermediate

                            </option>

                            <option value="advanced"
                                {{ old('difficulty') === 'advanced' ? 'selected' : '' }}>

                                Advanced

                            </option>

                        </select>

                    </div>


                    {{-- Description --}}
                    <div class="col-12">

                        <label class="form-label fw-semibold">

                            Short Description

                        </label>

                        <textarea name="description"
                            rows="3"
                            class="form-control"
                            placeholder="Write a short introduction about this material...">{{ old('description') }}</textarea>

                    </div>


                    {{-- Content --}}
                    <div class="col-12">

                        <label class="form-label fw-semibold">

                            Learning Content
                            <span class="text-danger">*</span>

                        </label>

                        <textarea name="content"
                            rows="10"
                            class="form-control"
                            placeholder="Write the complete educational content here..."
                            required>{{ old('content') }}</textarea>

                        <div class="form-text">

                            This is the main content students will read.

                        </div>

                    </div>


                    {{-- Image --}}
                    <div class="col-md-8">

                        <label class="form-label fw-semibold">

                            Cover Image

                        </label>

                        <input type="file"
                            name="image"
                            class="form-control"
                            accept=".jpg,.jpeg,.png,.webp">

                        <div class="form-text">

                            JPG, JPEG, PNG or WEBP. Maximum 2MB.

                        </div>

                    </div>


                    {{-- Published --}}
                    <div class="col-md-4">

                        <label class="form-label fw-semibold d-block">

                            Publication Status

                        </label>

                        <div class="form-check form-switch mt-2">

                            <input class="form-check-input"
                                type="checkbox"
                                name="is_published"
                                value="1"
                                id="isPublished"
                                {{ old('is_published', true) ? 'checked' : '' }}>

                            <label class="form-check-label"
                                for="isPublished">

                                Publish immediately

                            </label>

                        </div>

                    </div>


                </div>


                {{-- Buttons --}}
                <div class="d-flex justify-content-end gap-2 mt-4 pt-3 border-top">

                    <a href="{{ route('admin.learning-materials.index') }}"
                        class="btn btn-light">

                        Cancel

                    </a>

                    <button type="submit"
                        class="btn btn-primary">

                        <i class="bi bi-check-lg me-1"></i>

                        Create Material

                    </button>

                </div>


            </form>

        </div>

    </div>

</div>

@endsection