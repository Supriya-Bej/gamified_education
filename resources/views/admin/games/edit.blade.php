@extends('admin.layouts.app')

@section('title', 'Edit Game')

@section('page-heading', 'Edit Game')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">

    <div>

        <h3 class="fw-bold mb-1">
            Edit Game
        </h3>

        <p class="text-muted mb-0">
            Update the configuration of this game.
        </p>

    </div>

    <a href="{{ route('admin.games.index') }}"
        class="btn btn-outline-secondary">

        <i class="bi bi-arrow-left me-1"></i>

        Back to Games

    </a>

</div>


@if($errors->any())

<div class="alert alert-danger">

    <strong>Please fix the following:</strong>

    <ul class="mb-0 mt-2">

        @foreach($errors->all() as $error)

        <li>{{ $error }}</li>

        @endforeach

    </ul>

</div>

@endif


<div class="card border-0 shadow-sm">

    <div class="card-body p-4">

        <form action="{{ route('admin.games.update', $game->id) }}"
            method="POST">

            @csrf

            @method('PUT')


            <!-- GAME NAME -->

            <div class="mb-4">

                <label class="form-label fw-semibold">
                    Game Name
                </label>

                <input type="text"
                    name="name"
                    class="form-control"
                    value="{{ old('name', $game->name) }}"
                    required>

            </div>


            <!-- DESCRIPTION -->

            <div class="mb-4">

                <label class="form-label fw-semibold">
                    Description
                </label>

                <textarea name="description"
                    class="form-control"
                    rows="4">{{ old('description', $game->description) }}</textarea>

            </div>


            <div class="row g-4">


                <!-- CATEGORY -->

                <div class="col-md-4">

                    <label class="form-label fw-semibold">
                        Category
                    </label>

                    <select name="category" id="game-category" class="form-select" required>
                        <option value="">Select an interest</option>
                        @foreach($categories as $category)
                            <option value="{{ $category }}" @selected(old('category', $game->category) === $category)>
                                {{ ucwords(str_replace('_', ' ', $category)) }}
                            </option>
                        @endforeach
                    </select>

                </div>


                <!-- DIFFICULTY -->

                <div class="col-md-4">

                    <label class="form-label fw-semibold">
                        Difficulty
                    </label>

                    <select name="difficulty"
                        class="form-select"
                        required>

                        <option value="beginner" @selected(old('difficulty', $game->difficulty) === 'beginner')>
                            Beginner
                        </option>

                        <option value="intermediate" @selected(old('difficulty', $game->difficulty) === 'intermediate')>
                            Intermediate
                        </option>

                        <option value="advanced" @selected(old('difficulty', $game->difficulty) === 'advanced')>
                            Advanced
                        </option>

                    </select>

                </div>


                <!-- GAME TYPE -->

                <div class="col-md-4">

                    <label class="form-label fw-semibold">
                        Game Engine
                    </label>

                    <select name="game_type"
                        class="form-select"
                        required>

                        @foreach($engines as $engine)
                            <option
                                value="{{ $engine->engine_key }}"
                                data-supported-interests="{{ json_encode($engine->supported_interests ?? []) }}"
                                @selected(old('game_type', $game->game_type) === $engine->engine_key)
                            >{{ $engine->name }}</option>
                        @endforeach

                    </select>

                </div>

            </div>


            <hr class="my-4">


            <div class="d-flex justify-content-end gap-2">

                <a href="{{ route('admin.games.index') }}"
                    class="btn btn-light">

                    Cancel

                </a>

                <button type="submit"
                    class="btn btn-primary">

                    <i class="bi bi-save me-1"></i>

                    Save Changes

                </button>

            </div>

        </form>

    </div>

</div>

@endsection

@push('scripts')
<script>
    const gameEngineSelect = document.querySelector('[name="game_type"]');
    const gameCategorySelect = document.getElementById('game-category');
    const allCategories = Array.from(gameCategorySelect.options).slice(1);

    function filterGameCategories() {
        const selectedEngine = gameEngineSelect.selectedOptions[0];
        const supportedInterests = selectedEngine?.dataset.supportedInterests
            ? JSON.parse(selectedEngine.dataset.supportedInterests)
            : [];
        const currentCategory = gameCategorySelect.value;

        gameCategorySelect.replaceChildren(new Option('Select an interest', ''));
        allCategories
            .filter((option) => supportedInterests.includes(option.value))
            .forEach((option) => gameCategorySelect.add(option.cloneNode(true)));

        if (supportedInterests.includes(currentCategory)) {
            gameCategorySelect.value = currentCategory;
        }
    }

    gameEngineSelect.addEventListener('change', filterGameCategories);
    filterGameCategories();
</script>
@endpush