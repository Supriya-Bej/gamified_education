<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">

    <meta name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>{{ $game->name }} - EcoQuest</title>

    <link rel="stylesheet"
        href="{{ asset('Asset/Bootstrap-5/css/bootstrap.min.css') }}">

    <style>
        body {
            min-height: 100vh;
            background:
                radial-gradient(circle at top left, #203a43, transparent 40%),
                radial-gradient(circle at bottom right, #2c5364, transparent 40%),
                #07111f;
            color: white;
        }

        .game-wrapper {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 30px;
        }

        .game-card {
            width: 100%;
            max-width: 850px;
            padding: 45px;
            border-radius: 30px;
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(15px);
            box-shadow: 0 25px 70px rgba(0, 0, 0, 0.35);
        }

        .game-icon {
            width: 90px;
            height: 90px;
            border-radius: 25px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 25px;
            font-size: 42px;
            background: rgba(255, 255, 255, 0.12);
        }

        .game-title {
            font-size: clamp(2rem, 5vw, 4rem);
            font-weight: 800;
        }

        .game-description {
            color: rgba(255, 255, 255, 0.75);
            font-size: 1.1rem;
            line-height: 1.8;
        }

        .difficulty {
            display: inline-block;
            padding: 8px 18px;
            border-radius: 50px;
            background: rgba(255, 255, 255, 0.12);
            margin-bottom: 25px;
        }

        .start-game-btn {
            border: none;
            padding: 14px 30px;
            border-radius: 14px;
            font-weight: 700;
            background: white;
            color: #07111f;
        }
    </style>
</head>

<body>

    <div class="game-wrapper">

        <div class="game-card">

            <div class="game-icon">
                🎮
            </div>

            <div class="difficulty">
                {{ ucfirst($game->difficulty) }}
            </div>

            <h1 class="game-title">
                {{ $game->name }}
            </h1>

            <p class="game-description">
                {{ $game->description }}
            </p>

            <hr class="border-secondary my-4">

            <p>
                <strong>Your Quest:</strong>
                {{ $task->title }}
            </p>

            <button class="start-game-btn mt-3">
                🚀 Start Game
            </button>

        </div>

    </div>

</body>

</html>