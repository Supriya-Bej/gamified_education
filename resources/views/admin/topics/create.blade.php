@extends('admin.layouts.app')

@section('content')

<div class="container-fluid py-4">

    {{-- Header --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">

        <div>
            <h2 class="fw-bold mb-1">
                <i class="bi bi-plus-circle-fill me-2"></i>
                Add New Topic
            </h2>

            <p class="text-muted mb-0">
                Create a new learning topic for EcoQuest.
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

            <form action="{{ route('admin.topics.store') }}"
                  method="POST">

                @csrf


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
                           value="{{ old('name') }}"
                           class="form-control"
                           placeholder="Example: Climate Change"
                           required>

                    <div class="form-text">
                        Enter a unique name for the learning topic.
                    </div>

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
                              placeholder="Write a short description about this topic...">{{ old('description') }}</textarea>

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
                               {{ old('is_active', true) ? 'checked' : '' }}>

                        <label class="form-check-label fw-semibold"
                               for="is_active">

                            Active Topic

                        </label>

                    </div>

                    <div class="form-text">
                        Only active topics will be available for learning materials.
                    </div>

                </div>


                {{-- Buttons --}}
                <div class="d-flex flex-wrap gap-2">

                    <button type="submit"
                            class="btn btn-primary">

                        <i class="bi bi-check-circle me-1"></i>
                        Create Topic

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