@extends('admin.layouts.app')

@section('content')

<div class="container-fluid py-4">

    {{-- Header --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">

        <div>
            <h2 class="fw-bold mb-1">
                <i class="bi bi-pencil-square me-2"></i>
                Edit Topic
            </h2>

            <p class="text-muted mb-0">
                Update this learning topic.
            </p>
        </div>

        <a href="{{ route('admin.topics.index') }}"
           class="btn btn-outline-secondary">

            <i class="bi bi-arrow-left me-1"></i>
            Back to Topics

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

            <form action="{{ route('admin.topics.update', $topic) }}"
                  method="POST">

                @csrf
                @method('PUT')


                {{-- Topic Name --}}
                <div class="mb-4">

                    <label for="name"
                           class="form-label fw-semibold">

                        Topic Name
                        <span class="text-danger">*</span>

                    </label>

                    <input type="text"
                           id="name"
                           name="name"
                           value="{{ old('name', $topic->name) }}"
                           class="form-control"
                           required>

                </div>


                {{-- Description --}}
                <div class="mb-4">

                    <label for="description"
                           class="form-label fw-semibold">

                        Description

                    </label>

                    <textarea id="description"
                              name="description"
                              rows="4"
                              class="form-control"
                              placeholder="Write a short description...">{{ old('description', $topic->description) }}</textarea>

                </div>


                {{-- Status --}}
                <div class="mb-4">

                    <div class="form-check form-switch">

                        <input class="form-check-input"
                               type="checkbox"
                               role="switch"
                               id="is_active"
                               name="is_active"
                               value="1"
                               {{ old('is_active', $topic->is_active) ? 'checked' : '' }}>

                        <label class="form-check-label fw-semibold"
                               for="is_active">

                            Active Topic

                        </label>

                    </div>

                    <div class="form-text">
                        Inactive topics will not be available for new learning materials.
                    </div>

                </div>


                {{-- Buttons --}}
                <div class="d-flex flex-wrap gap-2">

                    <button type="submit"
                            class="btn btn-primary">

                        <i class="bi bi-save me-1"></i>
                        Update Topic

                    </button>

                    <a href="{{ route('admin.topics.index') }}"
                       class="btn btn-light border">

                        Cancel

                    </a>

                </div>

            </form>

        </div>

    </div>

</div>

@endsection