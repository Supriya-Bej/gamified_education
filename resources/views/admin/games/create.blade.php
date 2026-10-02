@extends('admin.layouts.app')

@section('title', 'Add Game')

@section('page-heading', 'Add Game')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">

    <div>
        <h3 class="fw-bold mb-1">
            Add New Game
        </h3>

        <p class="text-muted mb-0">
            Add a reusable game to the EcoQuest game library.
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

        <form action="{{ route('admin.games.store') }}"
              method="POST">

            @csrf


            <!-- GAME NAME -->

            <div class="mb-4">

                <label class="form-label fw-semibold">
                    Game Name
                </label>

                <input type="text"
                       name="name"
                       class="form-control"
                       value="{{ old('name') }}"
                       placeholder="Example: Plastic Waste Sorting"
                       required>

            </div>


            <!-- DESCRIPTION -->

            <div class="mb-4">

                <label class="form-label fw-semibold">
                    Description
                </label>

                <textarea name="description"
                          class="form-control"
                          rows="4"
                          placeholder="Describe what students will learn or do in this game...">{{ old('description') }}</textarea>

            </div>


            <div class="row g-4">


                <!-- CATEGORY -->

                <div class="col-md-4">

                    <label class="form-label fw-semibold">
                        Category
                    </label>

                    <input type="text"
                           name="category"
                           class="form-control"
                           value="{{ old('category') }}"
                           placeholder="Example: environment"
                           required>

                </div>


                <!-- DIFFICULTY -->

                <div class="col-md-4">

                    <label class="form-label fw-semibold">
                        Difficulty
                    </label>

                    <select name="difficulty"
                            class="form-select"
                            required>

                        <option value="">
                            Select difficulty
                        </option>

                        <option value="easy"
                            {{ old('difficulty') === 'easy' ? 'selected' : '' }}>
                            Easy
                        </option>

                        <option value="medium"
                            {{ old('difficulty') === 'medium' ? 'selected' : '' }}>
                            Medium
                        </option>

                        <option value="hard"
                            {{ old('difficulty') === 'hard' ? 'selected' : '' }}>
                            Hard
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

                        <option value="">
                            Select game engine
                        </option>

                        <option value="arithmetic_speed"
                            {{ old('game_type') === 'arithmetic_speed' ? 'selected' : '' }}>
                            Arithmetic Speed
                        </option>

                        <option value="algorithm_race"
                            {{ old('game_type') === 'algorithm_race' ? 'selected' : '' }}>
                            Algorithm Race
                        </option>

                        <option value="sorting_challenge"
                            {{ old('game_type') === 'sorting_challenge' ? 'selected' : '' }}>
                            Sorting Challenge
                        </option>

                        <option value="creative_coding"
                            {{ old('game_type') === 'creative_coding' ? 'selected' : '' }}>
                            Creative Coding
                        </option>

                        <option value="physics_quiz"
                            {{ old('game_type') === 'physics_quiz' ? 'selected' : '' }}>
                            Physics Quiz
                        </option>

                    </select>

                    <small class="text-muted">
                        This determines which game engine the student will use.
                    </small>

                </div>

            </div>


            <hr class="my-4">


            <div class="d-flex justify-content-end gap-2">

                <a href="{{ route('admin.games.index') }}"
                   class="btn btn-light">

                    Cancel

                </a>

                <button type="submit"
                        class="btn btn-success">

                    <i class="bi bi-plus-circle me-1"></i>

                    Add Game

                </button>

            </div>

        </form>

    </div>

</div>

@endsection