
@extends('admin.layouts.app')

@section('title', 'Add Game')

@section('page-heading', 'Add Game')

@section('content')

<style>
    .add-game-page {
        --navy: #17233e;
        --emerald: #14966c;
        --emerald-dark: #087650;
        --violet: #7664d8;
        --text: #26364d;
        --muted: #8491a5;
        --border: #e8edf3;
        color: var(--text);
    }

    /* Hero */
    .add-game-page .hero-panel {
        position: relative;
        overflow: hidden;
        padding: 35px;
        border-radius: 23px;
        color: #fff;
        background: linear-gradient(115deg, #17233e 0%, #253e60 55%, #147e65 100%);
        box-shadow: 0 12px 30px rgba(23, 35, 62, .14);
    }

    .add-game-page .hero-panel::before {
        content: "";
        position: absolute;
        width: 280px;
        height: 280px;
        border: 1px solid rgba(255,255,255,.13);
        border-radius: 50%;
        right: -75px;
        top: -135px;
        box-shadow: 0 0 0 35px rgba(255,255,255,.035),
                    0 0 0 70px rgba(255,255,255,.025);
    }

    .add-game-page .hero-content {
        position: relative;
        z-index: 1;
    }

    .add-game-page .hero-icon {
        width: 76px;
        height: 76px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 21px;
        background: rgba(255,255,255,.14);
        border: 1px solid rgba(255,255,255,.2);
        font-size: 36px;
        flex-shrink: 0;
    }

    .add-game-page .hero-label {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        color: #a7e9d1;
        font-size: 12px;
        font-weight: 800;
        letter-spacing: 1.5px;
        margin-bottom: 8px;
    }

    .add-game-page .hero-title {
        font-size: 32px;
        font-weight: 850;
        letter-spacing: -.7px;
        margin-bottom: 8px;
    }

    .add-game-page .hero-description {
        font-size: 16px;
        color: rgba(255,255,255,.76);
        margin: 0;
    }

    .add-game-page .back-btn {
        color: #fff;
        border: 1px solid rgba(255,255,255,.4);
        border-radius: 11px;
        padding: 11px 18px;
        font-size: 14px;
        font-weight: 700;
        transition: .2s;
    }

    .add-game-page .back-btn:hover {
        background: #fff;
        color: var(--navy);
    }

    /* Info strip */
    .add-game-page .info-strip {
        display: flex;
        align-items: flex-start;
        gap: 14px;
        padding: 19px 22px;
        border-radius: 15px;
        background: #edf7f2;
        border: 1px solid #d8eee2;
    }

    .add-game-page .info-strip-icon {
        width: 42px;
        height: 42px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        background: #d8f0e3;
        color: #13845c;
        font-size: 20px;
    }

    .add-game-page .info-strip-title {
        color: #176647;
        font-size: 15px;
        font-weight: 800;
        margin-bottom: 4px;
    }

    .add-game-page .info-strip-text {
        color: #638372;
        font-size: 14px;
        line-height: 1.6;
        margin: 0;
    }

    /* Form panel */
    .add-game-page .form-panel {
        background: #fff;
        border: 1px solid var(--border);
        border-radius: 20px;
        box-shadow: 0 6px 25px rgba(31,49,75,.045);
        overflow: hidden;
    }

    .add-game-page .panel-header {
        padding: 25px 29px;
        border-bottom: 1px solid var(--border);
        background: linear-gradient(110deg, #fbfcfe, #f5f9f7);
    }

    .add-game-page .panel-heading {
        font-size: 21px;
        font-weight: 850;
        color: var(--navy);
        margin-bottom: 5px;
    }

    .add-game-page .panel-description {
        color: var(--muted);
        font-size: 14px;
        margin: 0;
    }

    .add-game-page .panel-body {
        padding: 30px;
    }

    .add-game-page .section-heading {
        display: flex;
        align-items: center;
        gap: 13px;
        margin-bottom: 23px;
    }

    .add-game-page .section-icon {
        width: 44px;
        height: 44px;
        border-radius: 13px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        flex-shrink: 0;
    }

    .add-game-page .section-title {
        font-size: 18px;
        font-weight: 850;
        color: var(--navy);
        margin-bottom: 3px;
    }

    .add-game-page .section-subtitle {
        font-size: 13px;
        color: var(--muted);
        margin: 0;
    }

    /* Inputs */
    .add-game-page .form-label {
        color: #35465e;
        font-size: 14px;
        font-weight: 750;
        margin-bottom: 9px;
    }

    .add-game-page .required-mark {
        color: #e05b5b;
        margin-left: 3px;
    }

    .add-game-page .form-control,
    .add-game-page .form-select {
        min-height: 50px;
        border: 1px solid #dfe6ed;
        border-radius: 11px;
        padding: 12px 15px;
        color: #273951;
        font-size: 15px;
        font-weight: 500;
        background-color: #fbfcfe;
        box-shadow: none;
        transition: .2s;
    }

    .add-game-page textarea.form-control {
        min-height: 135px;
        resize: vertical;
        line-height: 1.7;
    }

    .add-game-page .form-control:focus,
    .add-game-page .form-select:focus {
        border-color: #36a983;
        background: #fff;
        box-shadow: 0 0 0 4px rgba(20,150,108,.10);
    }

    .add-game-page .field-hint {
        color: #8a97a8;
        font-size: 12px;
        margin-top: 7px;
    }

    .add-game-page .field-icon {
        color: var(--emerald);
        margin-right: 5px;
    }

    /* Configuration cards */
    .add-game-page .config-box {
        padding: 21px;
        height: 100%;
        background: #f8fafc;
        border: 1px solid #e9eef4;
        border-radius: 15px;
        transition: .2s;
    }

    .add-game-page .config-box:hover {
        border-color: #b9dfcf;
        background: #fbfefc;
        transform: translateY(-2px);
    }

    .add-game-page .config-top {
        display: flex;
        align-items: center;
        gap: 11px;
        margin-bottom: 17px;
    }

    .add-game-page .config-icon {
        width: 39px;
        height: 39px;
        border-radius: 11px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
    }

    .add-game-page .config-title {
        color: var(--navy);
        font-size: 15px;
        font-weight: 850;
        margin: 0;
    }

    /* Engine helper */
    .add-game-page .engine-note {
        display: flex;
        align-items: flex-start;
        gap: 10px;
        padding: 13px;
        border-radius: 11px;
        background: #f0edff;
        border: 1px solid #e2dcff;
        margin-top: 14px;
    }

    .add-game-page .engine-note i {
        color: #7664d8;
        font-size: 17px;
        margin-top: 1px;
    }

    .add-game-page .engine-note p {
        color: #6e64a5;
        font-size: 12px;
        line-height: 1.6;
        margin: 0;
    }

    /* Validation */
    .add-game-page .alert-danger {
        background: #fff4f3;
        border: 1px solid #f6d3d0;
        color: #a53c35;
        border-radius: 14px;
        padding: 20px;
        font-size: 14px;
    }

    .add-game-page .alert-danger ul {
        padding-left: 20px;
    }

    /* Footer */
    .add-game-page .action-footer {
        background: #f8fafc;
        border-top: 1px solid var(--border);
        padding: 22px 30px;
    }

    .add-game-page .cancel-btn {
        border: 1px solid #dce4ec;
        background: #fff;
        color: #526176;
        border-radius: 10px;
        padding: 12px 22px;
        font-size: 14px;
        font-weight: 750;
    }

    .add-game-page .cancel-btn:hover {
        background: #eef2f6;
    }

    .add-game-page .submit-btn {
        background: linear-gradient(110deg, #11845f, #19a578);
        color: #fff;
        border: 0;
        border-radius: 10px;
        padding: 12px 25px;
        font-size: 14px;
        font-weight: 800;
        box-shadow: 0 5px 13px rgba(20,150,108,.2);
        transition: .2s;
    }

    .add-game-page .submit-btn:hover {
        color: #fff;
        background: linear-gradient(110deg, #08734f, #118e64);
        transform: translateY(-1px);
        box-shadow: 0 7px 17px rgba(20,150,108,.27);
    }

    .add-game-page .divider {
        border-color: #e9eef3;
        opacity: 1;
        margin: 30px 0;
    }

    @media(max-width:767px) {
        .add-game-page .hero-panel {
            padding: 24px 19px;
        }

        .add-game-page .hero-title {
            font-size: 25px;
        }

        .add-game-page .hero-icon {
            width: 60px;
            height: 60px;
            font-size: 29px;
        }

        .add-game-page .hero-top {
            flex-direction: column;
            align-items: flex-start !important;
            gap: 20px;
        }

        .add-game-page .panel-body {
            padding: 21px 17px;
        }

        .add-game-page .panel-header {
            padding: 21px 18px;
        }

        .add-game-page .action-footer {
            padding: 18px;
        }
    }
</style>


<div class="add-game-page">

    {{-- HERO SECTION --}}
    <div class="hero-panel mb-4">

        <div class="hero-content">

            <div class="d-flex justify-content-between align-items-center hero-top">

                <div class="d-flex align-items-center gap-3">

                    <div class="hero-icon">
                        <i class="bi bi-controller"></i>
                    </div>

                    <div>

                        <div class="hero-label">
                            <i class="bi bi-stars"></i>
                            ECOQUEST GAME CREATION STUDIO
                        </div>

                        <h2 class="hero-title">
                            Create a New Game
                        </h2>

                        <p class="hero-description">
                            Expand your game library and create engaging learning experiences.
                        </p>

                    </div>

                </div>

                <a href="{{ route('admin.games.index') }}"
                   class="btn back-btn">

                    <i class="bi bi-arrow-left me-2"></i>
                    Back to Games

                </a>

            </div>

        </div>

    </div>


    {{-- INTRODUCTION --}}
    <div class="info-strip mb-4">

        <div class="info-strip-icon">
            <i class="bi bi-lightbulb-fill"></i>
        </div>

        <div>
            <div class="info-strip-title">
                Before you create a game
            </div>

            <p class="info-strip-text">
                Add a library entry using an existing playable game engine.
                The selected engine provides the playable game screen and answer-checking logic.
                New engine code must be added by a developer.
            </p>
        </div>

    </div>


    {{-- VALIDATION ERRORS --}}
    @if($errors->any())

        <div class="alert alert-danger mb-4">

            <div class="d-flex align-items-center gap-2 mb-2">

                <i class="bi bi-exclamation-triangle-fill fs-5"></i>

                <strong class="fs-6">
                    Please review the following errors
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
    <div class="form-panel">

        <div class="panel-header">

            <div class="d-flex align-items-center gap-2">

                <i class="bi bi-plus-square fs-4"
                   style="color:#15966c;"></i>

                <div>

                    <h4 class="panel-heading">
                        Game Information
                    </h4>

                    <p class="panel-description">
                        Fill in the details below to add a new game to your library.
                    </p>

                </div>

            </div>

        </div>


        <form action="{{ route('admin.games.store') }}"
              method="POST">

            @csrf

            <div class="panel-body">


                {{-- BASIC INFORMATION --}}
                <div class="section-heading">

                    <div class="section-icon"
                         style="background:#e5f5ed;color:#13845c;">

                        <i class="bi bi-card-text"></i>

                    </div>

                    <div>

                        <h5 class="section-title">
                            Basic Information
                        </h5>

                        <p class="section-subtitle">
                            Give your game a name and explain its purpose.
                        </p>

                    </div>

                </div>


                {{-- GAME NAME --}}
                <div class="mb-4">

                    <label for="game-name" class="form-label">

                        <i class="bi bi-controller field-icon"></i>
                        Game Name

                        <span class="required-mark">*</span>

                    </label>

                    <input type="text"
                           id="game-name"
                           name="name"
                           class="form-control @error('name') is-invalid @enderror"
                           value="{{ old('name') }}"
                           placeholder="Example: Plastic Waste Sorting"
                           required>

                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror

                    <div class="field-hint">
                        Choose a clear and memorable name for your learning game.
                    </div>

                </div>


                {{-- DESCRIPTION --}}
                <div class="mb-4">

                    <label for="game-description" class="form-label">

                        <i class="bi bi-card-heading field-icon"></i>
                        Game Description

                    </label>

                    <textarea id="game-description"
                              name="description"
                              class="form-control @error('description') is-invalid @enderror"
                              rows="4"
                              placeholder="Describe what students will learn, explore or achieve in this game...">{{ old('description') }}</textarea>

                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror

                    <div class="field-hint">
                        Describe the learning objective and what students will do while playing.
                    </div>

                </div>


                <hr class="divider">


                {{-- GAME CONFIGURATION --}}
                <div class="section-heading">

                    <div class="section-icon"
                         style="background:#eeebff;color:#7664d8;">

                        <i class="bi bi-gear-wide-connected"></i>

                    </div>

                    <div>

                        <h5 class="section-title">
                            Game Configuration
                        </h5>

                        <p class="section-subtitle">
                            Choose the interest, difficulty level and engine for this game.
                        </p>

                    </div>

                </div>


                <div class="row g-3">


                    {{-- CATEGORY --}}
                    <div class="col-lg-4 col-md-6">

                        <div class="config-box">

                            <div class="config-top">

                                <div class="config-icon"
                                     style="background:#e6f5ed;color:#16835a;">

                                    <i class="bi bi-grid"></i>

                                </div>

                                <h6 class="config-title">
                                    Game Category
                                </h6>

                            </div>

                            <label for="game-category" class="form-label">

                                Select Interest
                                <span class="required-mark">*</span>

                            </label>

                            <select name="category"
                                    id="game-category"
                                    class="form-select @error('category') is-invalid @enderror"
                                    required>

                                <option value="">
                                    Select an interest
                                </option>

                                @foreach($categories as $category)

                                    <option value="{{ $category }}"
                                        @selected(old('category') === $category)>

                                        {{ ucwords(str_replace('_', ' ', $category)) }}

                                    </option>

                                @endforeach

                            </select>

                            @error('category')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror

                            <div class="field-hint">
                                Connect the game with a suitable student interest.
                            </div>

                        </div>

                    </div>


                    {{-- DIFFICULTY --}}
                    <div class="col-lg-4 col-md-6">

                        <div class="config-box">

                            <div class="config-top">

                                <div class="config-icon"
                                     style="background:#fff2df;color:#c2872b;">

                                    <i class="bi bi-speedometer2"></i>

                                </div>

                                <h6 class="config-title">
                                    Difficulty Level
                                </h6>

                            </div>

                            <label for="game-difficulty" class="form-label">

                                Select Challenge
                                <span class="required-mark">*</span>

                            </label>

                            <select name="difficulty"
                                    id="game-difficulty"
                                    class="form-select @error('difficulty') is-invalid @enderror"
                                    required>

                                <option value="">
                                    Select difficulty
                                </option>

                                <option value="beginner"
                                    @selected(old('difficulty') === 'beginner')>
                                    Beginner
                                </option>

                                <option value="intermediate"
                                    @selected(old('difficulty') === 'intermediate')>
                                    Intermediate
                                </option>

                                <option value="advanced"
                                    @selected(old('difficulty') === 'advanced')>
                                    Advanced
                                </option>

                            </select>

                            @error('difficulty')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror

                            <div class="field-hint">
                                Set the challenge level for students.
                            </div>

                        </div>

                    </div>


                    {{-- GAME ENGINE --}}
                    <div class="col-lg-4 col-md-12">

                        <div class="config-box">

                            <div class="config-top">

                                <div class="config-icon"
                                     style="background:#eeeaff;color:#7664d8;">

                                    <i class="bi bi-cpu"></i>

                                </div>

                                <h6 class="config-title">
                                    Game Engine
                                </h6>

                            </div>

                            <label for="game-engine" class="form-label">

                                Select Engine
                                <span class="required-mark">*</span>

                            </label>

                            <select name="game_type"
                                    id="game-engine"
                                    class="form-select @error('game_type') is-invalid @enderror"
                                    required>

                                <option value="">
                                    Select game engine
                                </option>

                                @foreach($engines as $engine)

                                    <option
                                        value="{{ $engine->engine_key }}"
                                        data-supported-interests="{{ json_encode($engine->supported_interests ?? []) }}"
                                        @selected(old('game_type') === $engine->engine_key)
                                    >
                                        {{ $engine->name }}
                                    </option>

                                @endforeach

                            </select>

                            @error('game_type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror

                            <div class="engine-note">

                                <i class="bi bi-code-square"></i>

                                <p>
                                    The selected engine supplies the playable game screen and answer-checking logic.
                                    New engine code must be added by a developer.
                                </p>

                            </div>

                        </div>

                    </div>

                </div>


                {{-- SMART MATCHING NOTE --}}
                <div class="info-strip mt-4">

                    <div class="info-strip-icon">
                        <i class="bi bi-stars"></i>
                    </div>

                    <div>

                        <div class="info-strip-title">
                            Smart Category Matching
                        </div>

                        <p class="info-strip-text">
                            Available categories automatically adjust according to the supported interests
                            of the selected game engine. This keeps game and interest combinations compatible.
                        </p>

                    </div>

                </div>

            </div>


            {{-- FOOTER ACTIONS --}}
            <div class="action-footer">

                <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">

                    <div class="text-muted" style="font-size:13px;">

                        <i class="bi bi-shield-check me-1"
                           style="color:#15966c;"></i>

                        All required fields must be completed.

                    </div>

                    <div class="d-flex gap-2">

                        <a href="{{ route('admin.games.index') }}"
                           class="btn cancel-btn">

                            Cancel

                        </a>

                        <button type="submit"
                                class="btn submit-btn">

                            <i class="bi bi-plus-circle me-2"></i>
                            Create Game

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
    document.addEventListener('DOMContentLoaded', function () {

        const gameEngineSelect = document.querySelector('[name="game_type"]');
        const gameCategorySelect = document.getElementById('game-category');

        if (!gameEngineSelect || !gameCategorySelect) {
            return;
        }

        const allCategories = Array.from(gameCategorySelect.options).slice(1);

        function filterGameCategories() {

            const selectedEngine = gameEngineSelect.selectedOptions[0];

            let supportedInterests = [];

            try {
                supportedInterests = selectedEngine?.dataset.supportedInterests
                    ? JSON.parse(selectedEngine.dataset.supportedInterests)
                    : [];
            } catch (error) {
                console.error('Invalid supported interests data:', error);
            }

            const currentCategory = gameCategorySelect.value;

            gameCategorySelect.replaceChildren(
                new Option('Select an interest', '')
            );

            allCategories
                .filter(option => supportedInterests.includes(option.value))
                .forEach(option => {
                    gameCategorySelect.add(option.cloneNode(true));
                });

            if (supportedInterests.includes(currentCategory)) {
                gameCategorySelect.value = currentCategory;
            } else {
                gameCategorySelect.value = '';
            }
        }

        gameEngineSelect.addEventListener('change', filterGameCategories);

        filterGameCategories();

    });
</script>
@endpush
