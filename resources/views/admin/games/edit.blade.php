@extends('admin.layouts.app')

@section('title', 'Edit Game')

@section('page-heading', 'Edit Game')

@section('content')

<style>
    .edit-game-page {
        --navy: #17233e;
        --navy-light: #26385a;
        --emerald: #14966c;
        --emerald-dark: #087650;
        --violet: #7664d8;
        --text: #26364d;
        --muted: #8491a5;
        --border: #e8edf3;
        color: var(--text);
    }

    .edit-game-page .edit-hero {
        position: relative;
        overflow: hidden;
        padding: 34px;
        border-radius: 23px;
        color: white;
        background: linear-gradient(115deg, #17233e 0%, #253e60 55%, #147e65 100%);
        box-shadow: 0 12px 30px rgba(23, 35, 62, .14);
    }

    .edit-game-page .edit-hero::before {
        content: "";
        position: absolute;
        width: 270px;
        height: 270px;
        border: 1px solid rgba(255, 255, 255, .12);
        border-radius: 50%;
        right: -70px;
        top: -125px;
        box-shadow: 0 0 0 35px rgba(255, 255, 255, .035),
            0 0 0 70px rgba(255, 255, 255, .025);
    }

    .edit-game-page .hero-content {
        position: relative;
        z-index: 1;
    }

    .edit-game-page .hero-icon {
        width: 75px;
        height: 75px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 21px;
        background: rgba(255, 255, 255, .14);
        border: 1px solid rgba(255, 255, 255, .2);
        font-size: 35px;
        flex-shrink: 0;
    }

    .edit-game-page .hero-title {
        font-size: 31px;
        font-weight: 800;
        letter-spacing: -.7px;
        margin-bottom: 7px;
    }

    .edit-game-page .hero-description {
        color: rgba(255, 255, 255, .75);
        font-size: 16px;
        margin: 0;
    }

    .edit-game-page .hero-label {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        font-size: 12px;
        letter-spacing: 1.4px;
        font-weight: 800;
        color: #a7e9d1;
        margin-bottom: 9px;
    }

    .edit-game-page .back-btn {
        color: white;
        border: 1px solid rgba(255, 255, 255, .4);
        border-radius: 11px;
        padding: 11px 17px;
        font-size: 14px;
        font-weight: 700;
        transition: .2s;
    }

    .edit-game-page .back-btn:hover {
        background: white;
        color: var(--navy);
    }

    .edit-game-page .form-panel {
        background: #fff;
        border: 1px solid var(--border);
        border-radius: 20px;
        box-shadow: 0 6px 25px rgba(31, 49, 75, .045);
        overflow: hidden;
    }

    .edit-game-page .panel-header {
        padding: 24px 28px;
        border-bottom: 1px solid var(--border);
        background: linear-gradient(110deg, #fbfcfe, #f5f9f7);
    }

    .edit-game-page .panel-heading {
        font-size: 20px;
        font-weight: 800;
        color: var(--navy);
        margin-bottom: 5px;
    }

    .edit-game-page .panel-description {
        color: var(--muted);
        font-size: 14px;
        margin: 0;
    }

    .edit-game-page .panel-body {
        padding: 30px;
    }

    .edit-game-page .section-heading {
        display: flex;
        align-items: center;
        gap: 13px;
        margin-bottom: 22px;
    }

    .edit-game-page .section-icon {
        width: 43px;
        height: 43px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 13px;
        font-size: 20px;
        flex-shrink: 0;
    }

    .edit-game-page .section-title {
        font-size: 17px;
        font-weight: 800;
        color: var(--navy);
        margin-bottom: 3px;
    }

    .edit-game-page .section-subtitle {
        color: var(--muted);
        font-size: 13px;
        margin: 0;
    }

    .edit-game-page .form-label {
        color: #35465e;
        font-size: 14px;
        font-weight: 750;
        margin-bottom: 9px;
    }

    .edit-game-page .required-mark {
        color: #e05b5b;
        margin-left: 3px;
    }

    .edit-game-page .form-control,
    .edit-game-page .form-select {
        min-height: 49px;
        border: 1px solid #dfe6ed;
        border-radius: 11px;
        padding: 11px 14px;
        color: #273951;
        font-size: 15px;
        font-weight: 500;
        background-color: #fbfcfe;
        box-shadow: none;
        transition: .2s;
    }

    .edit-game-page textarea.form-control {
        min-height: 125px;
        line-height: 1.7;
        resize: vertical;
    }

    .edit-game-page .form-control:focus,
    .edit-game-page .form-select:focus {
        border-color: #36a983;
        background: #fff;
        box-shadow: 0 0 0 4px rgba(20, 150, 108, .10);
    }

    .edit-game-page .field-hint {
        color: #8a97a8;
        font-size: 12px;
        margin-top: 7px;
    }

    .edit-game-page .field-icon {
        color: var(--emerald);
        margin-right: 5px;
    }

    .edit-game-page .config-box {
        padding: 21px;
        height: 100%;
        background: #f8fafc;
        border: 1px solid #e9eef4;
        border-radius: 15px;
        transition: .2s;
    }

    .edit-game-page .config-box:hover {
        border-color: #b9dfcf;
        background: #fbfefc;
    }

    .edit-game-page .config-top {
        display: flex;
        align-items: center;
        gap: 11px;
        margin-bottom: 17px;
    }

    .edit-game-page .config-icon {
        width: 38px;
        height: 38px;
        border-radius: 11px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
    }

    .edit-game-page .config-title {
        font-size: 15px;
        font-weight: 800;
        color: var(--navy);
        margin: 0;
    }

    .edit-game-page .alert-danger {
        background: #fff4f3;
        border: 1px solid #f6d3d0;
        color: #a53c35;
        border-radius: 14px;
        padding: 20px;
        font-size: 14px;
    }

    .edit-game-page .alert-danger ul {
        padding-left: 20px;
    }

    .edit-game-page .action-footer {
        background: #f8fafc;
        border-top: 1px solid var(--border);
        padding: 21px 30px;
    }

    .edit-game-page .cancel-btn {
        border: 1px solid #dce4ec;
        background: #fff;
        color: #526176;
        border-radius: 10px;
        padding: 12px 21px;
        font-size: 14px;
        font-weight: 750;
    }

    .edit-game-page .cancel-btn:hover {
        background: #eef2f6;
    }

    .edit-game-page .save-btn {
        background: linear-gradient(110deg, #11845f, #19a578);
        color: white;
        border: 0;
        border-radius: 10px;
        padding: 12px 24px;
        font-size: 14px;
        font-weight: 800;
        box-shadow: 0 5px 13px rgba(20, 150, 108, .2);
        transition: .2s;
    }

    .edit-game-page .save-btn:hover {
        color: white;
        background: linear-gradient(110deg, #08734f, #118e64);
        transform: translateY(-1px);
        box-shadow: 0 7px 17px rgba(20, 150, 108, .27);
    }

    .edit-game-page .divider {
        border-color: #e9eef3;
        opacity: 1;
        margin: 29px 0;
    }

    .edit-game-page .game-preview {
        padding: 17px;
        border-radius: 13px;
        background: #eff8f3;
        border: 1px solid #d9eee2;
    }

    .edit-game-page .game-preview-title {
        color: #166647;
        font-size: 13px;
        font-weight: 800;
        margin-bottom: 5px;
    }

    .edit-game-page .game-preview-text {
        color: #648473;
        font-size: 13px;
        margin: 0;
        line-height: 1.6;
    }

    @media(max-width: 767px) {
        .edit-game-page .edit-hero {
            padding: 23px 19px;
        }

        .edit-game-page .hero-title {
            font-size: 25px;
        }

        .edit-game-page .hero-icon {
            width: 59px;
            height: 59px;
            font-size: 28px;
        }

        .edit-game-page .hero-top {
            flex-direction: column;
            align-items: flex-start !important;
            gap: 20px;
        }

        .edit-game-page .panel-body {
            padding: 21px 17px;
        }

        .edit-game-page .panel-header {
            padding: 21px 18px;
        }

        .edit-game-page .action-footer {
            padding: 18px;
        }
    }
</style>


<div class="edit-game-page">

    {{-- HERO --}}
    <div class="edit-hero mb-4">

        <div class="hero-content">

            <div class="d-flex justify-content-between align-items-center hero-top">

                <div class="d-flex align-items-center gap-3">

                    <div class="hero-icon">
                        <i class="bi bi-controller"></i>
                    </div>

                    <div>
                        <div class="hero-label">
                            <i class="bi bi-sliders"></i>
                            GAME CONFIGURATION STUDIO
                        </div>

                        <h2 class="hero-title">
                            Edit Game
                        </h2>

                        <p class="hero-description">
                            Refine your game settings and keep the learning experience engaging.
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


    {{-- VALIDATION ERRORS --}}
    @if($errors->any())

    <div class="alert alert-danger mb-4">

        <div class="d-flex gap-2 align-items-center mb-2">
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
                <i class="bi bi-pencil-square fs-4"
                    style="color:#15966c;"></i>

                <div>
                    <h4 class="panel-heading">
                        Update Game Details
                    </h4>

                    <p class="panel-description">
                        Modify the information and configuration of your existing game.
                    </p>
                </div>
            </div>

        </div>


        <form action="{{ route('admin.games.update', $game->id) }}"
            method="POST">

            @csrf
            @method('PUT')


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
                            Set the identity and description of your game.
                        </p>
                    </div>

                </div>


                {{-- GAME NAME --}}
                <div class="mb-4">

                    <label class="form-label" for="game-name">
                        <i class="bi bi-controller field-icon"></i>
                        Game Name
                        <span class="required-mark">*</span>
                    </label>

                    <input type="text"
                        id="game-name"
                        name="name"
                        class="form-control @error('name') is-invalid @enderror"
                        value="{{ old('name', $game->name) }}"
                        placeholder="Enter an engaging game name"
                        required>

                    @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror

                    <div class="field-hint">
                        Choose a clear and memorable name for this learning game.
                    </div>

                </div>


                {{-- DESCRIPTION --}}
                <div class="mb-4">

                    <label class="form-label" for="game-description">
                        <i class="bi bi-card-heading field-icon"></i>
                        Game Description
                    </label>

                    <textarea id="game-description"
                        name="description"
                        class="form-control @error('description') is-invalid @enderror"
                        rows="4"
                        placeholder="Describe the objective and learning experience of this game...">{{ old('description', $game->description) }}</textarea>

                    @error('description')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror

                    <div class="field-hint">
                        Explain what students will learn or achieve through this game.
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
                            Configure the interest, challenge level and game engine.
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
                                    Category
                                </h6>

                            </div>

                            <label class="form-label" for="game-category">
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
                                    @selected(old('category', $game->category) === $category)>

                                    {{ ucwords(str_replace('_', ' ', $category)) }}

                                </option>

                                @endforeach

                            </select>

                            @error('category')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror

                            <div class="field-hint">
                                Match the game with a suitable student interest.
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
                                    Difficulty
                                </h6>

                            </div>

                            <label class="form-label" for="game-difficulty">
                                Challenge Level
                                <span class="required-mark">*</span>
                            </label>

                            <select name="difficulty"
                                id="game-difficulty"
                                class="form-select @error('difficulty') is-invalid @enderror"
                                required>

                                <option value="beginner"
                                    @selected(old('difficulty', $game->difficulty) === 'beginner')>
                                    Beginner
                                </option>

                                <option value="intermediate"
                                    @selected(old('difficulty', $game->difficulty) === 'intermediate')>
                                    Intermediate
                                </option>

                                <option value="advanced"
                                    @selected(old('difficulty', $game->difficulty) === 'advanced')>
                                    Advanced
                                </option>

                            </select>

                            @error('difficulty')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror

                            <div class="field-hint">
                                Define how challenging the game will be.
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

                            <label class="form-label" for="game-engine">
                                Select Engine
                                <span class="required-mark">*</span>
                            </label>

                            <select name="game_type"
                                id="game-engine"
                                class="form-select @error('game_type') is-invalid @enderror"
                                required>

                                @foreach($engines as $engine)

                                <option
                                    value="{{ $engine->engine_key }}"
                                    data-supported-interests="{{ json_encode($engine->supported_interests ?? []) }}"
                                    @selected(old('game_type', $game->game_type) === $engine->engine_key)
                                    >
                                    {{ $engine->name }}
                                </option>

                                @endforeach

                            </select>

                            @error('game_type')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror

                            <div class="field-hint">
                                Choose the engine that powers this game.
                            </div>

                        </div>

                    </div>

                </div>


                {{-- CATEGORY FILTER INFO --}}
                <div class="game-preview mt-4">

                    <div class="d-flex align-items-start gap-2">

                        <i class="bi bi-lightbulb-fill fs-5"
                            style="color:#16835a;"></i>

                        <div>
                            <div class="game-preview-title">
                                Smart Category Matching
                            </div>

                            <p class="game-preview-text">
                                Available categories automatically adjust according to the supported interests of the selected game engine.
                                This helps maintain compatibility between games and student interests.
                            </p>
                        </div>

                    </div>

                </div>

            </div>


            {{-- FORM FOOTER --}}
            <div class="action-footer">

                <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">

                    <div class="text-muted" style="font-size:13px;">
                        <i class="bi bi-shield-check me-1"
                            style="color:#15966c;"></i>
                        Review your changes before saving.
                    </div>

                    <div class="d-flex gap-2">

                        <a href="{{ route('admin.games.index') }}"
                            class="btn cancel-btn">
                            Cancel
                        </a>

                        <button type="submit"
                            class="btn save-btn">

                            <i class="bi bi-check2-circle me-2"></i>
                            Save Changes

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
                supportedInterests = selectedEngine?.dataset.supportedInterests ?
                    JSON.parse(selectedEngine.dataset.supportedInterests) :
                    [];
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