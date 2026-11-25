@extends('admin.layouts.app')

@section('title', 'Homepage Content')

@section('content')

{{-- =====================================================
     STYLES
     Everything is grouped by part of the page, so it is
     easy to find what you want to change.
     ===================================================== --}}
<style>
    /* 1. COLORS - change them here and the whole page updates */
    :root {
        --green: #198754;
        --green-dark: #123c2b;
        --green-soft: #eaf7ef;
        --border: #e3ece6;
        --text: #263d30;
        --muted: #78877e;
        --radius: 18px;
    }

    .homepage-editor { padding: 25px 0 100px; }

    /* 2. TOP BANNER */
    .banner {
        position: relative;
        overflow: hidden;
        padding: 36px;
        margin-bottom: 24px;
        color: #fff;
        border-radius: 22px;
        background: linear-gradient(120deg, var(--green-dark), var(--green));
        box-shadow: 0 12px 30px rgba(18, 60, 43, .25);
    }

    /* big faded leaf in the corner */
    .banner::after {
        content: "🌿";
        position: absolute;
        right: 40px;
        top: -10px;
        font-size: 140px;
        opacity: .14;
        transform: rotate(-20deg);
    }

    .banner-tag {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        padding: 7px 14px;
        margin-bottom: 14px;
        font-size: 12px;
        font-weight: 600;
        letter-spacing: .5px;
        border-radius: 30px;
        background: rgba(255, 255, 255, .15);
        border: 1px solid rgba(255, 255, 255, .25);
    }

    .banner h2 { font-size: 28px; font-weight: 750; margin-bottom: 8px; }

    .banner p {
        max-width: 580px;
        margin: 0;
        font-size: 14px;
        line-height: 1.7;
        color: rgba(255, 255, 255, .82);
    }

    /* 3. QUICK JUMP MENU (small pills under the banner) */
    .jump-menu {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        margin-bottom: 24px;
    }

    .jump-menu a {
        padding: 7px 14px;
        font-size: 12px;
        font-weight: 600;
        text-decoration: none;
        color: var(--green);
        background: #fff;
        border: 1px solid var(--border);
        border-radius: 30px;
        transition: .2s;
    }

    .jump-menu a:hover {
        color: #fff;
        background: var(--green);
        border-color: var(--green);
    }

    /* 4. MESSAGES (success / error) */
    .eco-alert { border: none; border-radius: 12px; padding: 14px 18px; }

    /* 5. SECTION CARDS */
    .content-section {
        margin-bottom: 24px;
        overflow: hidden;
        background: #fff;
        border: 1px solid var(--border);
        border-radius: var(--radius);
        box-shadow: 0 3px 15px rgba(20, 70, 40, .04);
        scroll-margin-top: 20px;          /* keeps the card visible after jump */
        transition: box-shadow .2s;
    }

    .content-section:hover { box-shadow: 0 10px 28px rgba(20, 70, 40, .09); }

    .section-heading {
        display: flex;
        align-items: center;
        gap: 14px;
        padding: 20px 24px;
        background: linear-gradient(90deg, #f1faf4, #fff);
        border-bottom: 1px solid var(--border);
    }

    .section-icon {
        flex-shrink: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        width: 46px;
        height: 46px;
        font-size: 21px;
        color: var(--green);
        background: #dcf3e4;
        border-radius: 13px;
    }

    .section-heading h3 {
        margin: 0 0 3px;
        font-size: 16px;
        font-weight: 750;
        color: var(--green-dark);
    }

    .section-heading p { margin: 0; font-size: 12px; color: var(--muted); }

    .section-body { padding: 24px; }

    /* 6. FORM FIELDS */
    .field-label {
        margin-bottom: 8px;
        font-size: 13px;
        font-weight: 650;
        color: #30463a;
    }

    .field-label .required { color: #dc3545; }

    .eco-input {
        padding: 11px 14px;
        font-size: 13px;
        color: var(--text);
        background: #fbfdfc;
        border: 1px solid #dbe7de;
        border-radius: 10px;
        transition: .2s;
    }

    .eco-input:focus {
        background: #fff;
        border-color: var(--green);
        box-shadow: 0 0 0 4px rgba(25, 135, 84, .12);
    }

    textarea.eco-input { min-height: 110px; line-height: 1.6; resize: vertical; }

    .field-footer {
        display: flex;
        justify-content: space-between;
        margin-top: 7px;
        font-size: 11px;
        color: var(--muted);
    }

    /* turns red when the text is almost at the limit */
    .char-count.warning { color: #dc3545; font-weight: 700; }

    /* 7. STICKY SAVE BAR (always visible at the bottom) */
    .save-bar {
        position: sticky;
        bottom: 15px;
        z-index: 10;
        display: flex;
        flex-wrap: wrap;
        gap: 15px;
        align-items: center;
        justify-content: space-between;
        padding: 16px 22px;
        background: rgba(255, 255, 255, .92);
        backdrop-filter: blur(8px);
        border: 1px solid var(--border);
        border-radius: 16px;
        box-shadow: 0 8px 28px rgba(20, 70, 40, .13);
    }

    .save-info { display: flex; align-items: center; gap: 12px; }
    .save-info i { font-size: 22px; color: var(--green); }
    .save-info strong { display: block; font-size: 13px; color: var(--text); }
    .save-info span { font-size: 11px; color: var(--muted); }

    .btn-eco {
        padding: 11px 22px;
        font-size: 13px;
        font-weight: 650;
        color: #fff;
        background: var(--green);
        border: none;
        border-radius: 10px;
        transition: .2s;
    }

    .btn-eco:hover {
        color: #fff;
        background: #146c43;
        transform: translateY(-1px);
        box-shadow: 0 6px 14px rgba(25, 135, 84, .3);
    }

    .btn-preview {
        padding: 11px 18px;
        font-size: 13px;
        font-weight: 600;
        color: #315c43;
        background: #fff;
        border: 1px solid #d6e4da;
        border-radius: 10px;
    }

    .btn-preview:hover {
        color: var(--green);
        background: var(--green-soft);
        border-color: var(--green);
    }

    /* 8. MOBILE */
    @media (max-width: 576px) {
        .banner { padding: 24px 20px; }
        .banner h2 { font-size: 22px; }
        .banner::after { right: -15px; font-size: 90px; }
        .section-heading { padding: 17px; }
        .section-body { padding: 18px; }
        .save-bar { align-items: stretch; }
        .save-actions { width: 100%; flex-direction: column; }
        .save-actions a,
        .save-actions button { width: 100%; }
    }
</style>


{{-- =====================================================
     SMALL SETTINGS FOR THE VIEW
     Icon and description for each section name.
     If a section is not listed here, a default is used.
     ===================================================== --}}
@php
    $sectionIcons = [
        'Hero Section'     => 'bi-stars',
        'About Section'    => 'bi-globe-americas',
        'Features Section' => 'bi-controller',
        'Learning Section' => 'bi-book-half',
        'Call To Action'   => 'bi-rocket-takeoff',
        'Footer Section'   => 'bi-tree',
    ];

    $sectionDescriptions = [
        'Hero Section'     => 'The first thing students see when visiting EcoQuest.',
        'About Section'    => 'Introduce your environmental learning platform.',
        'Features Section' => 'Showcase the exciting features of EcoQuest.',
        'Learning Section' => 'Highlight your learning and gaming experience.',
        'Call To Action'   => 'Encourage students to join and start their journey.',
        'Footer Section'   => 'Manage the closing message of your homepage.',
    ];

    $defaultIcon        = 'bi-leaf';
    $defaultDescription = 'Manage the content displayed in this homepage section.';
@endphp


<div class="container-fluid homepage-editor">

    {{-- 1. BANNER --}}
    <div class="banner">
        <div class="banner-tag">
            <i class="bi bi-stars"></i> ECOQUEST ADMIN STUDIO
        </div>

        <h2>Homepage Content Manager</h2>

        <p>
            Shape the first impression of EcoQuest. Edit the homepage text,
            learning messages and button labels to inspire students to
            learn, play and protect the planet.
        </p>
    </div>


    {{-- 2. QUICK JUMP MENU: one pill for every section --}}
    <nav class="jump-menu">
        @foreach ($sections as $section => $fields)
            <a href="#section-{{ $loop->index }}">
                <i class="bi {{ $sectionIcons[$section] ?? $defaultIcon }} me-1"></i>
                {{ $section }}
            </a>
        @endforeach
    </nav>


    {{-- 3. SUCCESS MESSAGE (shown after saving) --}}
    @if (session('success'))
        <div class="alert alert-success eco-alert d-flex align-items-center gap-2">
            <i class="bi bi-check-circle-fill"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif


    {{-- 4. ERROR MESSAGE (shown when validation fails) --}}
    @if ($errors->any())
        <div class="alert alert-danger eco-alert">
            <strong><i class="bi bi-exclamation-circle-fill me-1"></i> Please check your content.</strong>
            <br>
            <small>
                Correct the highlighted fields. All fields are required
                and must contain no more than 1,000 characters.
            </small>
        </div>
    @endif


    {{-- 5. THE FORM --}}
    <form method="POST" action="{{ route('admin.homepage.update') }}">
        @csrf
        @method('PUT')

        {{-- Loop 1: one card for every section --}}
        @foreach ($sections as $section => $fields)

            <section class="content-section" id="section-{{ $loop->index }}">

                {{-- Card title --}}
                <div class="section-heading">
                    <div class="section-icon">
                        <i class="bi {{ $sectionIcons[$section] ?? $defaultIcon }}"></i>
                    </div>

                    <div>
                        <h3>{{ $section }}</h3>
                        <p>{{ $sectionDescriptions[$section] ?? $defaultDescription }}</p>
                    </div>
                </div>

                {{-- Card fields --}}
                <div class="section-body">
                    <div class="row g-4">

                        {{-- Loop 2: one input for every field in the section --}}
                        @foreach ($fields as $key => $field)

                            <div class="col-12 col-lg-6">

                                <label for="{{ $key }}" class="field-label">
                                    {{ $field['label'] }}
                                    <span class="required">*</span>
                                </label>

                                {{-- Big text box or small text box --}}
                                @if (($field['type'] ?? 'text') === 'textarea')

                                    <textarea
                                        id="{{ $key }}"
                                        name="{{ $key }}"
                                        class="form-control eco-input @error($key) is-invalid @enderror"
                                        rows="4"
                                        maxlength="1000"
                                        data-count-target="count-{{ $key }}"
                                        required>{{ old($key, $content[$key] ?? '') }}</textarea>

                                @else

                                    <input
                                        type="text"
                                        id="{{ $key }}"
                                        name="{{ $key }}"
                                        class="form-control eco-input @error($key) is-invalid @enderror"
                                        maxlength="1000"
                                        data-count-target="count-{{ $key }}"
                                        value="{{ old($key, $content[$key] ?? '') }}"
                                        required>

                                @endif

                                {{-- Validation error for this field --}}
                                @error($key)
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror

                                {{-- Live character counter --}}
                                <div class="field-footer">
                                    <span><i class="bi bi-pencil-square me-1"></i> Edit homepage content</span>
                                    <span class="char-count" id="count-{{ $key }}">0 / 1000</span>
                                </div>

                            </div>

                        @endforeach

                    </div>
                </div>

            </section>

        @endforeach


        {{-- 6. SAVE BAR (sticks to the bottom of the screen) --}}
        <div class="save-bar">

            <div class="save-info">
                <i class="bi bi-shield-check"></i>
                <div>
                    <strong>Ready to update your homepage?</strong>
                    <span>Your changes will appear on the public EcoQuest website.</span>
                </div>
            </div>

            <div class="d-flex gap-2 flex-wrap save-actions">
                <a href="{{ url('/') }}" target="_blank" rel="noopener" class="btn btn-preview">
                    <i class="bi bi-eye me-1"></i> Preview Homepage
                </a>

                <button type="submit" class="btn btn-eco">
                    <i class="bi bi-check2-circle me-1"></i> Save Changes
                </button>
            </div>

        </div>

    </form>

</div>


{{-- =====================================================
     JAVASCRIPT - live character counter
     Every input that has "data-count-target" shows how many
     characters are typed, e.g. "25 / 1000".
     ===================================================== --}}
<script>
    document.querySelectorAll('[data-count-target]').forEach(function (input) {

        // the <span> where the number is shown
        var counter = document.getElementById(input.dataset.countTarget);

        function updateCounter() {
            var length = input.value.length;
            counter.textContent = length + ' / 1000';

            // turn red when more than 900 characters are used
            counter.classList.toggle('warning', length > 900);
        }

        updateCounter();                          // run once when the page loads
        input.addEventListener('input', updateCounter);  // run on every key press
    });
</script>

@endsection