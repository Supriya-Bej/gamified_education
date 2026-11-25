@extends('admin.layouts.app')

@section('title', 'Edit Learning Material')

@section('page-heading', 'Edit Learning Material')

@section('content')

<style>
    .material-editor {
        --forest: #123c2d;
        --emerald: #15966b;
        --emerald-dark: #087650;
        --navy: #1c2d45;
        --muted: #7d8c83;
        --border: #e5ede8;
        --soft: #f5f9f6;
        color: var(--navy);
    }

    .material-editor .hero {
        position: relative;
        overflow: hidden;
        padding: 32px;
        border-radius: 23px;
        background: linear-gradient(115deg, #103c2b, #176b4d 60%, #15966b);
        color: #fff;
        box-shadow: 0 12px 28px rgba(15, 65, 45, .14);
    }

    .material-editor .hero::before {
        content: "";
        position: absolute;
        width: 260px;
        height: 260px;
        right: -65px;
        top: -125px;
        border: 1px solid rgba(255, 255, 255, .15);
        border-radius: 50%;
        box-shadow: 0 0 0 35px rgba(255, 255, 255, .035),
            0 0 0 70px rgba(255, 255, 255, .025);
    }

    .material-editor .hero-content {
        position: relative;
        z-index: 1;
    }

    .material-editor .hero-icon {
        width: 72px;
        height: 72px;
        border-radius: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 33px;
        background: rgba(255, 255, 255, .15);
        border: 1px solid rgba(255, 255, 255, .22);
        flex-shrink: 0;
    }

    .material-editor .hero-label {
        font-size: 12px;
        font-weight: 800;
        letter-spacing: 1.5px;
        color: #b2ead1;
        margin-bottom: 7px;
    }

    .material-editor .hero-title {
        font-size: 31px;
        font-weight: 850;
        letter-spacing: -.7px;
        margin-bottom: 7px;
    }

    .material-editor .hero-description {
        font-size: 15px;
        color: rgba(255, 255, 255, .78);
        margin: 0;
    }

    .material-editor .back-btn {
        color: #fff;
        border: 1px solid rgba(255, 255, 255, .45);
        border-radius: 11px;
        padding: 11px 17px;
        font-size: 14px;
        font-weight: 700;
        transition: .2s;
    }

    .material-editor .back-btn:hover {
        background: #fff;
        color: var(--forest);
    }

    .material-editor .editor-card {
        background: #fff;
        border: 1px solid var(--border);
        border-radius: 19px;
        box-shadow: 0 5px 22px rgba(25, 65, 45, .045);
        overflow: hidden;
    }

    .material-editor .card-heading {
        padding: 23px 25px;
        border-bottom: 1px solid var(--border);
        background: linear-gradient(110deg, #fbfdfb, #f3f8f4);
    }

    .material-editor .heading-icon {
        width: 43px;
        height: 43px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #e4f4ea;
        color: var(--emerald-dark);
        border-radius: 13px;
        font-size: 21px;
        flex-shrink: 0;
    }

    .material-editor .heading-title {
        font-size: 19px;
        font-weight: 850;
        color: var(--forest);
        margin-bottom: 4px;
    }

    .material-editor .heading-subtitle {
        color: var(--muted);
        font-size: 13px;
        margin: 0;
    }

    .material-editor .card-body-custom {
        padding: 27px;
    }

    .material-editor .section-title {
        font-size: 17px;
        font-weight: 850;
        color: var(--forest);
        margin-bottom: 5px;
    }

    .material-editor .section-description {
        font-size: 13px;
        color: var(--muted);
        margin-bottom: 20px;
    }

    .material-editor .form-label {
        font-size: 14px;
        font-weight: 750;
        color: #344b3e;
        margin-bottom: 9px;
    }

    .material-editor .required {
        color: #e15c55;
    }

    .material-editor .form-control,
    .material-editor .form-select {
        min-height: 49px;
        border: 1px solid #dce7df;
        border-radius: 11px;
        background-color: #fbfdfb;
        padding: 11px 14px;
        color: #293d32;
        font-size: 15px;
        font-weight: 500;
        box-shadow: none;
        transition: .2s;
    }

    .material-editor textarea.form-control {
        resize: vertical;
        line-height: 1.8;
    }

    .material-editor textarea.content-area {
        min-height: 320px;
    }

    .material-editor textarea.description-area {
        min-height: 115px;
    }

    .material-editor .form-control:focus,
    .material-editor .form-select:focus {
        border-color: #37a77e;
        background: #fff;
        box-shadow: 0 0 0 4px rgba(21, 150, 107, .10);
    }

    .material-editor .form-text {
        color: #89988e;
        font-size: 12px;
        margin-top: 7px;
    }

    .material-editor .field-divider {
        border-color: #e9f0eb;
        opacity: 1;
        margin: 26px 0;
    }

    .material-editor .side-card {
        padding: 21px;
        border: 1px solid var(--border);
        border-radius: 16px;
        background: #fff;
        margin-bottom: 17px;
    }

    .material-editor .side-title {
        font-size: 16px;
        font-weight: 850;
        color: var(--forest);
        margin-bottom: 5px;
    }

    .material-editor .side-description {
        color: var(--muted);
        font-size: 12px;
        margin-bottom: 18px;
    }

    .material-editor .image-preview {
        width: 100%;
        height: 180px;
        border-radius: 13px;
        overflow: hidden;
        background: #edf5ef;
        border: 1px dashed #cbded0;
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
    }

    .material-editor .image-preview img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .material-editor .image-placeholder {
        text-align: center;
        color: #8ca497;
        padding: 15px;
    }

    .material-editor .image-placeholder i {
        font-size: 34px;
        display: block;
        margin-bottom: 8px;
        color: #69a88a;
    }

    .material-editor .upload-label {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 11px;
        margin-top: 12px;
        border: 1px solid #b9deca;
        border-radius: 10px;
        background: #f0f9f3;
        color: #16734f;
        font-size: 13px;
        font-weight: 800;
        cursor: pointer;
        transition: .2s;
    }

    .material-editor .upload-label:hover {
        background: #e1f3e8;
    }

    .material-editor .status-box {
        padding: 16px;
        border: 1px solid #dceee2;
        background: #f1f9f3;
        border-radius: 13px;
        transition: .2s;
    }

    .material-editor .status-box.is-draft {
        background: #fff8eb;
        border-color: #f1dfb9;
    }

    .material-editor .status-icon {
        width: 39px;
        height: 39px;
        border-radius: 11px;
        background: #dff2e5;
        color: #168052;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 19px;
    }

    .material-editor .status-box.is-draft .status-icon {
        background: #fff0d2;
        color: #a16b0a;
    }

    .material-editor .status-name {
        font-size: 14px;
        font-weight: 850;
        color: #176c4a;
    }

    .material-editor .status-box.is-draft .status-name {
        color: #946300;
    }

    .material-editor .status-description {
        font-size: 12px;
        color: #7c9284;
        margin-top: 3px;
    }

    .material-editor .form-check-input {
        width: 43px;
        height: 23px;
        cursor: pointer;
        box-shadow: none;
        border-color: #b9c9bd;
    }

    .material-editor .form-check-input:checked {
        background-color: #15966b;
        border-color: #15966b;
    }

    .material-editor .form-check-input:focus {
        box-shadow: 0 0 0 4px rgba(21, 150, 107, .12);
    }

    .material-editor .tips-box {
        padding: 17px;
        background: #f0f5ff;
        border: 1px solid #dce6fa;
        border-radius: 13px;
    }

    .material-editor .tips-title {
        color: #3e5fa2;
        font-size: 13px;
        font-weight: 850;
        margin-bottom: 7px;
    }

    .material-editor .tips-text {
        color: #7182a4;
        font-size: 12px;
        line-height: 1.7;
        margin: 0;
    }

    .material-editor .validation-box {
        background: #fff3f2;
        border: 1px solid #f5d2cf;
        border-radius: 14px;
        color: #a53c35;
        padding: 20px;
        font-size: 14px;
    }

    .material-editor .validation-box ul {
        padding-left: 22px;
    }

    .material-editor .form-footer {
        padding: 21px 27px;
        background: #f8faf8;
        border-top: 1px solid var(--border);
    }

    .material-editor .cancel-btn {
        background: #fff;
        border: 1px solid #dce5df;
        color: #596b60;
        border-radius: 10px;
        padding: 12px 20px;
        font-size: 14px;
        font-weight: 750;
    }

    .material-editor .cancel-btn:hover {
        background: #eef3ef;
    }

    .material-editor .update-btn {
        background: linear-gradient(110deg, #117e59, #19a477);
        border: 0;
        color: #fff;
        border-radius: 10px;
        padding: 12px 22px;
        font-size: 14px;
        font-weight: 850;
        box-shadow: 0 5px 13px rgba(21, 150, 107, .2);
        transition: .2s;
    }

    .material-editor .update-btn:hover {
        background: linear-gradient(110deg, #0c704d, #128b61);
        color: #fff;
        transform: translateY(-1px);
    }

    @media(max-width: 767px) {
        .material-editor .hero {
            padding: 23px 19px;
        }

        .material-editor .hero-title {
            font-size: 25px;
        }

        .material-editor .hero-top {
            flex-direction: column;
            align-items: flex-start !important;
            gap: 18px;
        }

        .material-editor .hero-icon {
            width: 59px;
            height: 59px;
            font-size: 27px;
        }

        .material-editor .card-body-custom {
            padding: 20px 16px;
        }

        .material-editor .card-heading,
        .material-editor .form-footer {
            padding: 19px 17px;
        }
    }
</style>


<div class="material-editor">

    {{-- HERO HEADER --}}
    <div class="hero mb-4">

        <div class="hero-content">

            <div class="d-flex justify-content-between align-items-center hero-top">

                <div class="d-flex align-items-center gap-3">

                    <div class="hero-icon">
                        <i class="bi bi-journal-richtext"></i>
                    </div>

                    <div>
                        <div class="hero-label">
                            <i class="bi bi-book-half me-1"></i>
                            ECOQUEST / LEARNING LIBRARY
                        </div>

                        <h2 class="hero-title">
                            Edit Learning Material
                        </h2>

                        <p class="hero-description">
                            Refine your educational content and keep learning resources up to date.
                        </p>
                    </div>

                </div>

                <a href="{{ route('admin.learning-materials.index') }}"
                    class="btn back-btn">

                    <i class="bi bi-arrow-left me-2"></i>
                    Back to Materials

                </a>

            </div>

        </div>

    </div>


    {{-- VALIDATION ERRORS --}}
    @if($errors->any())

    <div class="validation-box mb-4">

        <div class="d-flex align-items-center gap-2 mb-2">
            <i class="bi bi-exclamation-triangle-fill fs-5"></i>

            <strong>
                Please fix the following errors:
            </strong>
        </div>

        <ul class="mb-0">
            @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>

    </div>

    @endif


    {{-- MAIN FORM --}}
    <div class="editor-card">

        <div class="card-heading">

            <div class="d-flex align-items-center gap-3">

                <div class="heading-icon">
                    <i class="bi bi-pencil-square"></i>
                </div>

                <div>
                    <h4 class="heading-title">
                        Material Editor
                    </h4>

                    <p class="heading-subtitle">
                        Update the content, learning level and publishing settings.
                    </p>
                </div>

            </div>

        </div>


        <form action="{{ route('admin.learning-materials.update', $learningMaterial) }}"
            method="POST"
            enctype="multipart/form-data">

            @csrf
            @method('PUT')


            <div class="card-body-custom">

                <div class="row g-4">

                    {{-- LEFT: MAIN CONTENT --}}
                    <div class="col-lg-8">

                        <div class="section-title">
                            <i class="bi bi-file-earmark-text me-2"
                                style="color:#15966b;"></i>
                            Content Information
                        </div>

                        <p class="section-description">
                            Add and organize the educational material students will read.
                        </p>


                        {{-- TITLE --}}
                        <div class="mb-4">

                            <label for="title" class="form-label">
                                Material Title
                                <span class="required">*</span>
                            </label>

                            <input type="text"
                                name="title"
                                id="title"
                                class="form-control @error('title') is-invalid @enderror"
                                value="{{ old('title', $learningMaterial->title) }}"
                                placeholder="e.g. Understanding Environmental Sustainability"
                                required>

                            @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror

                            <div class="form-text">
                                Use a clear title that describes the learning topic.
                            </div>

                        </div>


                        {{-- DESCRIPTION --}}
                        <div class="mb-4">

                            <label for="description" class="form-label">
                                Short Description
                            </label>

                            <textarea name="description"
                                id="description"
                                rows="4"
                                class="form-control description-area @error('description') is-invalid @enderror"
                                placeholder="Write a short introduction to this material...">{{ old('description', $learningMaterial->description) }}</textarea>

                            @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror

                            <div class="form-text">
                                Give students a quick idea of what they will learn.
                            </div>

                        </div>


                        <hr class="field-divider">


                        {{-- LEARNING CONTENT --}}
                        <div class="mb-3">

                            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-2">

                                <label for="content" class="form-label mb-0">
                                    <i class="bi bi-journal-text me-1"
                                        style="color:#15966b;"></i>
                                    Learning Content
                                    <span class="required">*</span>
                                </label>

                                <span class="badge rounded-pill"
                                    style="background:#e8f5ed;color:#14734f;padding:8px 11px;">
                                    Main Material
                                </span>

                            </div>

                            <textarea name="content"
                                id="content"
                                rows="12"
                                class="form-control content-area @error('content') is-invalid @enderror"
                                placeholder="Write the complete learning material here..."
                                required>{{ old('content', $learningMaterial->content) }}</textarea>

                            @error('content')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror

                            <div class="form-text">
                                Write detailed learning content with clear explanations and examples.
                            </div>

                        </div>

                    </div>


                    {{-- RIGHT: SETTINGS --}}
                    <div class="col-lg-4">

                        {{-- CLASSIFICATION --}}
                        <div class="side-card">

                            <h5 class="side-title">
                                <i class="bi bi-sliders me-2"
                                    style="color:#15966b;"></i>
                                Material Settings
                            </h5>

                            <p class="side-description">
                                Organize the material for the right learners.
                            </p>


                            {{-- TOPIC --}}
                            <div class="mb-4">

                                <label for="topic_id" class="form-label">
                                    Topic
                                    <span class="required">*</span>
                                </label>

                                <select name="topic_id"
                                    id="topic_id"
                                    class="form-select @error('topic_id') is-invalid @enderror"
                                    required>

                                    <option value="">
                                        Select Topic
                                    </option>

                                    @foreach($topics as $topic)

                                    <option value="{{ $topic->id }}"
                                        @selected(old('topic_id', $learningMaterial->topic_id) == $topic->id)>

                                        {{ $topic->name }}

                                    </option>

                                    @endforeach

                                </select>

                                @error('topic_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror

                            </div>


                            {{-- DIFFICULTY --}}
                            <div>

                                <label for="difficulty" class="form-label">
                                    Difficulty Level
                                    <span class="required">*</span>
                                </label>

                                <select name="difficulty"
                                    id="difficulty"
                                    class="form-select @error('difficulty') is-invalid @enderror"
                                    required>

                                    <option value="beginner"
                                        @selected(old('difficulty', $learningMaterial->difficulty) == 'beginner')>
                                        Beginner
                                    </option>

                                    <option value="intermediate"
                                        @selected(old('difficulty', $learningMaterial->difficulty) == 'intermediate')>
                                        Intermediate
                                    </option>

                                    <option value="advanced"
                                        @selected(old('difficulty', $learningMaterial->difficulty) == 'advanced')>
                                        Advanced
                                    </option>

                                </select>

                                @error('difficulty')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror

                            </div>

                        </div>


                        {{-- IMAGE --}}
                        <div class="side-card">

                            <h5 class="side-title">
                                <i class="bi bi-image me-2"
                                    style="color:#15966b;"></i>
                                Cover Image
                            </h5>

                            <p class="side-description">
                                Manage the visual for this learning material.
                            </p>


                            <div class="image-preview" id="image-preview">

                                @if($learningMaterial->image)

                                <img
                                    src="{{ asset('storage/' . $learningMaterial->image) }}"
                                    alt="{{ $learningMaterial->title }}"
                                    id="preview-image">

                                @else

                                <div class="image-placeholder" id="image-placeholder">
                                    <i class="bi bi-image"></i>
                                    <span>No cover image available</span>
                                </div>

                                <img src=""
                                    alt="New image preview"
                                    id="preview-image"
                                    class="d-none">

                                @endif

                            </div>


                            <label for="image" class="upload-label">
                                <i class="bi bi-cloud-arrow-up"></i>
                                Choose New Image
                            </label>

                            <input type="file"
                                name="image"
                                id="image"
                                class="d-none @error('image') is-invalid @enderror"
                                accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp">

                            @error('image')
                            <div class="text-danger small mt-2">
                                {{ $message }}
                            </div>
                            @enderror

                            <div class="form-text">
                                JPG, JPEG, PNG or WEBP. Maximum size: 2MB.
                            </div>

                        </div>


                        {{-- PUBLISHING --}}
                        <div class="side-card">

                            <h5 class="side-title">
                                <i class="bi bi-globe2 me-2"
                                    style="color:#15966b;"></i>
                                Publishing
                            </h5>

                            <p class="side-description">
                                Control student access to this material.
                            </p>


                            <div class="status-box" id="publish-status-box">

                                <div class="d-flex align-items-center gap-3">

                                    <div class="status-icon"
                                        id="publish-status-icon">
                                        <i class="bi bi-check-circle-fill"></i>
                                    </div>

                                    <div class="flex-grow-1">
                                        <div class="status-name"
                                            id="publish-status-name">
                                            Published
                                        </div>

                                        <div class="status-description"
                                            id="publish-status-description">
                                            Students can access this material.
                                        </div>
                                    </div>

                                </div>

                            </div>


                            <div class="d-flex align-items-center justify-content-between gap-2 mt-3">

                                <label for="is_published"
                                    class="form-label mb-0">
                                    Publish Material
                                </label>

                                <div class="form-check form-switch mb-0">
                                    <input type="checkbox"
                                        name="is_published"
                                        value="1"
                                        class="form-check-input"
                                        role="switch"
                                        id="is_published"
                                        @checked(old('is_published', $learningMaterial->is_published))>
                                </div>

                            </div>

                            <div class="form-text">
                                Published materials are available to students.
                            </div>

                        </div>


                        {{-- EDITING TIP --}}
                        <div class="tips-box">

                            <div class="tips-title">
                                <i class="bi bi-lightbulb-fill me-1"></i>
                                Content Tip
                            </div>

                            <p class="tips-text">
                                Keep learning materials structured and easy to understand.
                                Use clear explanations and relevant examples to make learning more engaging.
                            </p>

                        </div>

                    </div>

                </div>

            </div>


            {{-- FOOTER --}}
            <div class="form-footer">

                <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">

                    <div class="text-muted" style="font-size:13px;">
                        <i class="bi bi-shield-check me-1"
                            style="color:#15966b;"></i>
                        Review your updates before saving.
                    </div>

                    <div class="d-flex gap-2">

                        <a href="{{ route('admin.learning-materials.index') }}"
                            class="btn cancel-btn">
                            Cancel
                        </a>

                        <button type="submit" class="btn update-btn">
                            <i class="bi bi-check2-circle me-2"></i>
                            Update Material
                        </button>

                    </div>

                </div>

            </div>

        </form>

    </div>

</div>

@endsection


@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {

        // IMAGE PREVIEW
        const imageInput = document.getElementById('image');
        const previewImage = document.getElementById('preview-image');
        const placeholder = document.getElementById('image-placeholder');

        if (imageInput && previewImage) {

            imageInput.addEventListener('change', function() {

                const file = this.files[0];

                if (!file) {
                    return;
                }

                const allowedTypes = [
                    'image/jpeg',
                    'image/png',
                    'image/webp'
                ];

                if (!allowedTypes.includes(file.type)) {
                    alert('Please select a JPG, PNG or WEBP image.');
                    this.value = '';
                    return;
                }

                if (file.size > 2 * 1024 * 1024) {
                    alert('Image size must not exceed 2MB.');
                    this.value = '';
                    return;
                }

                const reader = new FileReader();

                reader.onload = function(event) {

                    previewImage.src = event.target.result;
                    previewImage.classList.remove('d-none');

                    if (placeholder) {
                        placeholder.classList.add('d-none');
                    }

                };

                reader.readAsDataURL(file);

            });

        }


        // PUBLISH / DRAFT STATUS PREVIEW
        const publishCheckbox = document.getElementById('is_published');
        const statusBox = document.getElementById('publish-status-box');
        const statusIcon = document.getElementById('publish-status-icon');
        const statusName = document.getElementById('publish-status-name');
        const statusDescription = document.getElementById('publish-status-description');

        function updatePublishStatus() {

            if (!publishCheckbox || !statusBox) {
                return;
            }

            if (publishCheckbox.checked) {

                statusBox.classList.remove('is-draft');

                statusIcon.innerHTML = '<i class="bi bi-check-circle-fill"></i>';

                statusName.textContent = 'Published';

                statusDescription.textContent =
                    'Students can access this material.';

            } else {

                statusBox.classList.add('is-draft');

                statusIcon.innerHTML = '<i class="bi bi-pencil-square"></i>';

                statusName.textContent = 'Draft';

                statusDescription.textContent =
                    'This material will not be shown to students.';

            }

        }

        if (publishCheckbox) {
            publishCheckbox.addEventListener('change', updatePublishStatus);
            updatePublishStatus();
        }

    });
</script>
@endpush