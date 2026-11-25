@extends('admin.layouts.app')

@section('title', 'Add Learning Material')

@section('page-heading', 'Add Learning Material')

@section('content')

<style>
    .material-page {
        --forest: #173d2e;
        --green: #16865d;
        --emerald: #20a477;
        --mint: #e8f5ed;
        --cream: #f7faf6;
        --text: #263c32;
        --muted: #819087;
        --border: #e5ece6;
    }

    .material-page .hero {
        position: relative;
        overflow: hidden;
        background: linear-gradient(115deg, #123c2c, #17684a 65%, #21a078);
        border-radius: 23px;
        padding: 32px;
        color: white;
        box-shadow: 0 12px 28px rgba(20, 72, 48, .14);
    }

    .material-page .hero::after {
        content: "";
        position: absolute;
        width: 260px;
        height: 260px;
        border: 1px solid rgba(255, 255, 255, .13);
        border-radius: 50%;
        right: -65px;
        top: -120px;
        box-shadow: 0 0 0 35px rgba(255, 255, 255, .035),
            0 0 0 70px rgba(255, 255, 255, .025);
    }

    .material-page .hero-content {
        position: relative;
        z-index: 1;
    }

    .material-page .hero-icon {
        width: 74px;
        height: 74px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 21px;
        background: rgba(255, 255, 255, .15);
        border: 1px solid rgba(255, 255, 255, .2);
        font-size: 34px;
        flex-shrink: 0;
    }

    .material-page .hero-title {
        font-size: 31px;
        font-weight: 850;
        letter-spacing: -.6px;
        margin-bottom: 7px;
    }

    .material-page .hero-description {
        font-size: 16px;
        color: rgba(255, 255, 255, .77);
        margin: 0;
    }

    .material-page .hero-label {
        color: #a9ebd0;
        font-size: 12px;
        font-weight: 800;
        letter-spacing: 1.4px;
        margin-bottom: 8px;
    }

    .material-page .back-link {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        color: white;
        border: 1px solid rgba(255, 255, 255, .4);
        border-radius: 10px;
        padding: 10px 15px;
        font-size: 14px;
        font-weight: 700;
        text-decoration: none;
        transition: .2s;
    }

    .material-page .back-link:hover {
        background: white;
        color: var(--forest);
    }

    .material-page .main-card {
        background: white;
        border: 1px solid var(--border);
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 6px 25px rgba(31, 65, 45, .045);
    }

    .material-page .card-heading {
        background: linear-gradient(110deg, #fbfdfb, #f1f8f3);
        padding: 25px 29px;
        border-bottom: 1px solid var(--border);
    }

    .material-page .heading-title {
        font-size: 21px;
        font-weight: 850;
        color: var(--forest);
        margin-bottom: 5px;
    }

    .material-page .heading-description {
        font-size: 14px;
        color: var(--muted);
        margin: 0;
    }

    .material-page .form-body {
        padding: 30px;
    }

    .material-page .section-heading {
        display: flex;
        align-items: center;
        gap: 13px;
        margin-bottom: 23px;
    }

    .material-page .section-icon {
        width: 45px;
        height: 45px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 13px;
        background: var(--mint);
        color: var(--green);
        font-size: 21px;
        flex-shrink: 0;
    }

    .material-page .section-title {
        font-size: 18px;
        font-weight: 850;
        color: var(--forest);
        margin-bottom: 3px;
    }

    .material-page .section-description {
        color: var(--muted);
        font-size: 13px;
        margin: 0;
    }

    .material-page .form-label {
        font-size: 15px;
        font-weight: 750;
        color: #344c3e;
        margin-bottom: 9px;
    }

    .material-page .required {
        color: #e05252;
    }

    .material-page .form-control,
    .material-page .form-select {
        min-height: 49px;
        border: 1px solid #dfe8e1;
        border-radius: 11px;
        background-color: #fbfdfb;
        color: #294236;
        font-size: 15px;
        padding: 11px 14px;
        box-shadow: none;
        transition: .2s;
    }

    .material-page textarea.form-control {
        resize: vertical;
        line-height: 1.8;
    }

    .material-page .form-control:focus,
    .material-page .form-select:focus {
        background: white;
        border-color: #2aa77b;
        box-shadow: 0 0 0 4px rgba(32, 164, 119, .10);
    }

    .material-page .title-input {
        font-size: 17px;
        font-weight: 650;
        min-height: 55px;
    }

    .material-page .content-area {
        min-height: 320px;
        background: #fdfefd;
        font-size: 16px;
        padding: 20px;
    }

    .material-page .description-area {
        min-height: 105px;
    }

    .material-page .field-help {
        font-size: 13px;
        color: #89968d;
        margin-top: 8px;
    }

    .material-page .input-card {
        background: #f8fbf8;
        border: 1px solid #e8efe9;
        border-radius: 15px;
        padding: 20px;
        height: 100%;
    }

    .material-page .input-card-title {
        font-size: 15px;
        font-weight: 800;
        color: var(--forest);
        margin-bottom: 15px;
    }

    .material-page .difficulty-icon {
        width: 37px;
        height: 37px;
        border-radius: 10px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        margin-right: 8px;
        background: #e8f4ed;
        color: #16865d;
    }

    .material-page .upload-area {
        position: relative;
        border: 2px dashed #cbded0;
        background: #f8fcf9;
        border-radius: 14px;
        padding: 23px;
        text-align: center;
        transition: .2s;
    }

    .material-page .upload-area:hover {
        border-color: #20a477;
        background: #f1faf4;
    }

    .material-page .upload-icon {
        width: 49px;
        height: 49px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #e5f4e9;
        color: #16865d;
        border-radius: 14px;
        font-size: 23px;
        margin: 0 auto 11px;
    }

    .material-page .upload-title {
        font-size: 15px;
        font-weight: 800;
        color: var(--forest);
        margin-bottom: 5px;
    }

    .material-page .upload-description {
        font-size: 13px;
        color: var(--muted);
        margin: 0;
    }

    .material-page .preview-image {
        display: none;
        max-width: 100%;
        max-height: 190px;
        object-fit: cover;
        border-radius: 11px;
        margin: 15px auto 0;
    }

    .material-page .publish-card {
        background: linear-gradient(135deg, #eff9f1, #f8fcf7);
        border: 1px solid #d9ecdd;
        border-radius: 15px;
        padding: 21px;
        height: 100%;
    }

    .material-page .publish-title {
        font-size: 15px;
        font-weight: 850;
        color: var(--forest);
        margin-bottom: 5px;
    }

    .material-page .publish-description {
        font-size: 13px;
        color: #73877a;
        margin-bottom: 16px;
        line-height: 1.6;
    }

    .material-page .form-switch .form-check-input {
        width: 3em;
        height: 1.5em;
        cursor: pointer;
        box-shadow: none;
        margin-top: 0;
    }

    .material-page .form-switch .form-check-input:checked {
        background-color: #16865d;
        border-color: #16865d;
    }

    .material-page .form-check-label {
        font-size: 14px;
        font-weight: 750;
        color: #246044;
        cursor: pointer;
    }

    .material-page .error-panel {
        border: 1px solid #f2d0ce;
        background: #fff4f3;
        color: #a23d36;
        border-radius: 14px;
        padding: 19px;
    }

    .material-page .error-panel li {
        margin-top: 5px;
        font-size: 14px;
    }

    .material-page .form-divider {
        border-color: #e8eee9;
        opacity: 1;
        margin: 31px 0;
    }

    .material-page .form-footer {
        padding: 22px 30px;
        background: #f8faf8;
        border-top: 1px solid var(--border);
    }

    .material-page .cancel-btn {
        border: 1px solid #dce6dd;
        background: white;
        color: #52685a;
        padding: 12px 21px;
        border-radius: 10px;
        font-weight: 750;
        font-size: 14px;
    }

    .material-page .cancel-btn:hover {
        background: #eef3ef;
    }

    .material-page .submit-btn {
        background: linear-gradient(110deg, #117b54, #20a477);
        color: white;
        border: 0;
        border-radius: 10px;
        padding: 12px 23px;
        font-size: 14px;
        font-weight: 850;
        box-shadow: 0 5px 14px rgba(22, 134, 93, .19);
        transition: .2s;
    }

    .material-page .submit-btn:hover {
        color: white;
        background: linear-gradient(110deg, #0d6846, #168c63);
        transform: translateY(-1px);
    }

    @media(max-width: 767px) {
        .material-page .hero {
            padding: 23px 19px;
        }

        .material-page .hero-title {
            font-size: 25px;
        }

        .material-page .hero-top {
            flex-direction: column;
            align-items: flex-start !important;
            gap: 20px;
        }

        .material-page .hero-icon {
            width: 60px;
            height: 60px;
            font-size: 28px;
        }

        .material-page .form-body {
            padding: 21px 17px;
        }

        .material-page .card-heading {
            padding: 21px 18px;
        }

        .material-page .form-footer {
            padding: 18px;
        }

        .material-page .content-area {
            min-height: 240px;
        }
    }
</style>


<div class="material-page">

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
                            <i class="bi bi-stars me-1"></i>
                            ECOQUEST / CONTENT STUDIO
                        </div>

                        <h2 class="hero-title">
                            Create Learning Material
                        </h2>

                        <p class="hero-description">
                            Build meaningful educational resources for the next generation of eco-learners.
                        </p>
                    </div>

                </div>

                <a href="{{ route('admin.learning-materials.index') }}"
                    class="back-link">

                    <i class="bi bi-arrow-left"></i>
                    Back to Materials

                </a>

            </div>

        </div>

    </div>


    {{-- VALIDATION ERRORS --}}
    @if($errors->any())

    <div class="error-panel mb-4">

        <div class="d-flex align-items-center gap-2 mb-2">
            <i class="bi bi-exclamation-triangle-fill fs-5"></i>
            <strong>Please fix the following errors:</strong>
        </div>

        <ul class="mb-0 ps-4">
            @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>

    </div>

    @endif


    {{-- FORM CARD --}}
    <div class="main-card">

        <div class="card-heading">

            <div class="d-flex align-items-center gap-3">

                <div class="section-icon">
                    <i class="bi bi-pencil-square"></i>
                </div>

                <div>
                    <h4 class="heading-title">
                        Learning Material Details
                    </h4>

                    <p class="heading-description">
                        Complete the information below to create a new learning resource.
                    </p>
                </div>

            </div>

        </div>


        <form action="{{ route('admin.learning-materials.store') }}"
            method="POST"
            enctype="multipart/form-data">

            @csrf

            <div class="form-body">


                {{-- BASIC INFORMATION --}}
                <div class="section-heading">

                    <div class="section-icon">
                        <i class="bi bi-card-text"></i>
                    </div>

                    <div>
                        <h5 class="section-title">
                            Basic Information
                        </h5>

                        <p class="section-description">
                            Give your learning resource a clear title and introduction.
                        </p>
                    </div>

                </div>


                {{-- TITLE --}}
                <div class="mb-4">

                    <label class="form-label" for="material-title">
                        Material Title
                        <span class="required">*</span>
                    </label>

                    <input type="text"
                        id="material-title"
                        name="title"
                        value="{{ old('title') }}"
                        class="form-control title-input @error('title') is-invalid @enderror"
                        placeholder="e.g. Introduction to Climate Change"
                        required>

                    @error('title')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror

                    <div class="field-help">
                        Choose a title that clearly communicates the topic to students.
                    </div>

                </div>


                {{-- TOPIC & DIFFICULTY --}}
                <div class="row g-3">

                    <div class="col-md-6">

                        <div class="input-card">

                            <div class="input-card-title">
                                <span class="difficulty-icon">
                                    <i class="bi bi-bookmark-star"></i>
                                </span>
                                Learning Topic
                            </div>

                            <label for="topic_id" class="form-label">
                                Select Topic
                                <span class="required">*</span>
                            </label>

                            <select name="topic_id"
                                id="topic_id"
                                class="form-select @error('topic_id') is-invalid @enderror"
                                required>

                                <option value="">Choose a topic</option>

                                @foreach($topics as $topic)

                                <option value="{{ $topic->id }}"
                                    @selected(old('topic_id')==$topic->id)>

                                    {{ $topic->name }}

                                </option>

                                @endforeach

                            </select>

                            @error('topic_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror

                            <div class="field-help">
                                Organize this material under the relevant learning topic.
                            </div>

                        </div>

                    </div>


                    <div class="col-md-6">

                        <div class="input-card">

                            <div class="input-card-title">
                                <span class="difficulty-icon"
                                    style="background:#fff2df;color:#c2872b;">
                                    <i class="bi bi-speedometer2"></i>
                                </span>
                                Learning Level
                            </div>

                            <label for="difficulty" class="form-label">
                                Difficulty
                                <span class="required">*</span>
                            </label>

                            <select name="difficulty"
                                id="difficulty"
                                class="form-select @error('difficulty') is-invalid @enderror"
                                required>

                                <option value="beginner"
                                    @selected(old('difficulty', 'beginner' )==='beginner' )>
                                    Beginner
                                </option>

                                <option value="intermediate"
                                    @selected(old('difficulty')==='intermediate' )>
                                    Intermediate
                                </option>

                                <option value="advanced"
                                    @selected(old('difficulty')==='advanced' )>
                                    Advanced
                                </option>

                            </select>

                            @error('difficulty')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror

                            <div class="field-help">
                                Select the level that best matches the content.
                            </div>

                        </div>

                    </div>

                </div>


                {{-- DESCRIPTION --}}
                <div class="mt-4">

                    <label class="form-label" for="material-description">
                        <i class="bi bi-chat-left-text me-1"
                            style="color:#16865d;"></i>
                        Short Description
                    </label>

                    <textarea name="description"
                        id="material-description"
                        rows="3"
                        class="form-control description-area @error('description') is-invalid @enderror"
                        placeholder="Write a short introduction about this material...">{{ old('description') }}</textarea>

                    @error('description')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror

                    <div class="field-help">
                        A brief overview helps students understand what they are going to learn.
                    </div>

                </div>


                <hr class="form-divider">


                {{-- LEARNING CONTENT --}}
                <div class="section-heading">

                    <div class="section-icon"
                        style="background:#eeeaff;color:#7965d7;">
                        <i class="bi bi-file-earmark-richtext"></i>
                    </div>

                    <div>
                        <h5 class="section-title">
                            Learning Content
                        </h5>

                        <p class="section-description">
                            Write the main educational content students will read.
                        </p>
                    </div>

                </div>


                <div class="mb-3">

                    <label class="form-label" for="material-content">
                        Main Content
                        <span class="required">*</span>
                    </label>

                    <textarea name="content"
                        id="material-content"
                        rows="12"
                        class="form-control content-area @error('content') is-invalid @enderror"
                        placeholder="Start writing your educational content here...

You can explain the topic, add examples, discuss environmental problems, and provide useful learning information."
                        required>{{ old('content') }}</textarea>

                    @error('content')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror

                    <div class="field-help">
                        <i class="bi bi-info-circle me-1"></i>
                        Write clear, informative and student-friendly educational content.
                    </div>

                </div>


                <hr class="form-divider">


                {{-- IMAGE AND PUBLISHING --}}
                <div class="section-heading">

                    <div class="section-icon"
                        style="background:#fff1e5;color:#d4873c;">
                        <i class="bi bi-image"></i>
                    </div>

                    <div>
                        <h5 class="section-title">
                            Cover Image & Publishing
                        </h5>

                        <p class="section-description">
                            Add a visual identity and choose when this material becomes available.
                        </p>
                    </div>

                </div>


                <div class="row g-3">

                    {{-- IMAGE UPLOAD --}}
                    <div class="col-lg-7">

                        <div class="input-card">

                            <div class="input-card-title">
                                <i class="bi bi-cloud-arrow-up me-2"
                                    style="color:#16865d;"></i>
                                Material Cover Image
                            </div>

                            <label for="material-image" class="form-label">
                                Upload Image
                            </label>

                            <div class="upload-area">

                                <div class="upload-icon">
                                    <i class="bi bi-image"></i>
                                </div>

                                <div class="upload-title">
                                    Choose a cover image
                                </div>

                                <p class="upload-description">
                                    JPG, JPEG, PNG or WEBP · Maximum 2MB
                                </p>

                                <input type="file"
                                    name="image"
                                    id="material-image"
                                    class="form-control mt-3 @error('image') is-invalid @enderror"
                                    accept=".jpg,.jpeg,.png,.webp">

                                <img id="image-preview"
                                    class="preview-image"
                                    alt="Selected cover image preview">

                            </div>

                            @error('image')
                            <div class="text-danger small mt-2">
                                {{ $message }}
                            </div>
                            @enderror

                        </div>

                    </div>


                    {{-- PUBLICATION --}}
                    <div class="col-lg-5">

                        <div class="publish-card">

                            <div class="d-flex align-items-center gap-2 mb-3">

                                <div class="section-icon"
                                    style="background:#dff2e4;color:#16865d;width:40px;height:40px;">
                                    <i class="bi bi-megaphone"></i>
                                </div>

                                <div>
                                    <div class="publish-title">
                                        Publication Status
                                    </div>
                                    <div class="field-help mt-0">
                                        Manage student visibility
                                    </div>
                                </div>

                            </div>


                            <p class="publish-description">
                                Publish immediately to make this learning material available to students.
                                Turn it off to save it as a draft.
                            </p>


                            <div class="form-check form-switch d-flex align-items-center gap-3 ps-0">

                                <input class="form-check-input ms-0"
                                    type="checkbox"
                                    name="is_published"
                                    value="1"
                                    id="isPublished"
                                    @checked(old('is_published', true))>

                                <label class="form-check-label"
                                    for="isPublished"
                                    id="publish-label">
                                    Publish immediately
                                </label>

                            </div>


                            <div class="mt-4 pt-3 border-top"
                                style="border-color:#dcebe0 !important;">

                                <div class="d-flex align-items-center gap-2"
                                    style="font-size:13px;color:#63816d;">

                                    <i class="bi bi-shield-check fs-5"
                                        style="color:#16865d;"></i>

                                    <span>
                                        You can manage publication later.
                                    </span>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            </div>


            {{-- FOOTER ACTIONS --}}
            <div class="form-footer">

                <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">

                    <div class="text-muted" style="font-size:13px;">
                        <i class="bi bi-check-circle me-1"
                            style="color:#16865d;"></i>
                        Review your content before creating.
                    </div>

                    <div class="d-flex gap-2">

                        <a href="{{ route('admin.learning-materials.index') }}"
                            class="btn cancel-btn">
                            Cancel
                        </a>

                        <button type="submit"
                            class="btn submit-btn">

                            <i class="bi bi-plus-circle me-2"></i>
                            Create Material

                        </button>

                    </div>

                </div>

            </div>

        </form>

    </div>

</div>


@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {

        // Image preview
        const imageInput = document.getElementById('material-image');
        const imagePreview = document.getElementById('image-preview');

        imageInput.addEventListener('change', function() {

            const file = this.files[0];

            if (!file) {
                imagePreview.removeAttribute('src');
                imagePreview.style.display = 'none';
                return;
            }

            const allowedTypes = [
                'image/jpeg',
                'image/png',
                'image/webp'
            ];

            if (!allowedTypes.includes(file.type)) {
                alert('Please select a JPG, JPEG, PNG or WEBP image.');
                this.value = '';
                imagePreview.style.display = 'none';
                return;
            }

            if (file.size > 2 * 1024 * 1024) {
                alert('Image size must not exceed 2MB.');
                this.value = '';
                imagePreview.style.display = 'none';
                return;
            }

            const reader = new FileReader();

            reader.onload = function(event) {
                imagePreview.src = event.target.result;
                imagePreview.style.display = 'block';
            };

            reader.readAsDataURL(file);

        });


        // Publication status text
        const publishCheckbox = document.getElementById('isPublished');
        const publishLabel = document.getElementById('publish-label');

        function updatePublishLabel() {
            publishLabel.textContent = publishCheckbox.checked ?
                'Publish immediately' :
                'Save as draft';
        }

        publishCheckbox.addEventListener('change', updatePublishLabel);

        updatePublishLabel();

    });
</script>
@endpush

@endsection