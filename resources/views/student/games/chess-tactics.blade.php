<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $game->name }} | EcoQuest</title>
    <style>
        :root {
            color-scheme: dark;
            font-family: Arial, sans-serif;
            background: #0a0a0c;
            color: #f8fafc;
        }

        body {
            margin: 0;
            min-height: 100vh;
            background: radial-gradient(circle at top, #263244, #0a0a0c 55%);
        }

        main {
            width: min(900px, calc(100% - 32px));
            margin: 0 auto;
            padding: 28px 0 56px;
        }

        .topbar, .stats, .options {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
        }

        .topbar {
            margin-bottom: 28px;
        }

        .topbar a, button {
            color: inherit;
            font: inherit;
        }

        .topbar a {
            text-decoration: none;
            border: 1px solid #475569;
            border-radius: 10px;
            padding: 10px 14px;
        }

        .card {
            border: 1px solid #334155;
            border-radius: 22px;
            background: rgba(15, 23, 42, .94);
            padding: clamp(20px, 5vw, 38px);
            box-shadow: 0 24px 70px rgba(0, 0, 0, .35);
        }

        .eyebrow, .muted {
            color: #cbd5e1;
        }

        h1 {
            margin: 8px 0;
            font-size: clamp(2rem, 6vw, 3.4rem);
        }

        h2 {
            margin-top: 0;
        }

        .stats {
            justify-content: flex-start;
            margin: 24px 0;
        }

        .stat {
            background: #1e293b;
            border-radius: 12px;
            padding: 10px 14px;
        }

        .question {
            margin: 22px 0;
            font-size: 1.2rem;
            line-height: 1.6;
        }

        .options {
            flex-direction: column;
            align-items: stretch;
        }

        .option, .next {
            border: 1px solid #64748b;
            border-radius: 12px;
            background: #1e293b;
            padding: 14px 16px;
            text-align: left;
            cursor: pointer;
        }

        .option:hover:not(:disabled), .next:hover {
            background: #334155;
        }

        .option:disabled {
            cursor: default;
            opacity: .8;
        }

        .feedback {
            min-height: 28px;
            margin: 18px 0;
            font-weight: 700;
        }

        .next {
            display: none;
            margin-left: auto;
            text-align: center;
        }

        .error {
            color: #fecaca;
        }

        [hidden] {
            display: none !important;
        }

        @media (max-width: 560px) {
            .topbar, .stats {
                align-items: stretch;
                flex-direction: column;
            }
        }
    </style>
</head>

<body>
    <main>
        <nav class="topbar" aria-label="Game navigation">
            <strong>EcoQuest · Chess</strong>
            <a href="{{ route('student.dashboard') }}">Back to dashboard</a>
        </nav>

        <section class="card">
            <p class="eyebrow">CHESS TACTICS QUEST</p>
            <h1>{{ $game->name }}</h1>
            <h2>{{ $task->title }}</h2>
            <p class="muted">{{ $task->description }}</p>
            <p>{{ data_get($task->game_data, 'instructions') }}</p>

            @php($puzzles = data_get($task->game_data, 'puzzles', []))
            @if (is_array($puzzles) && count($puzzles) > 0)
                <div class="stats">
                    <div class="stat">Puzzle <span id="puzzleNumber">1</span>/{{ count($puzzles) }}</div>
                    <div class="stat">Correct <span id="score">0</span></div>
                    <div class="stat">Quest reward {{ $task->xp }} XP</div>
                </div>

                <div id="puzzlePanel">
                    <h2 id="question" class="question"></h2>
                    <div id="options" class="options"></div>
                    <p id="feedback" class="feedback" aria-live="polite"></p>
                    <button id="nextButton" class="next" type="button">Next puzzle</button>
                </div>

                <div id="resultPanel" hidden>
                    <h2>Quest complete</h2>
                    <p id="resultMessage" class="question"></p>
                    <a href="{{ route('student.dashboard') }}">Return to dashboard</a>
                </div>
            @else
                <p class="error" role="alert">This chess quest has no valid puzzles and cannot be played.</p>
            @endif
        </section>
    </main>

    @if (is_array($puzzles) && count($puzzles) > 0)
        <script>
            const puzzles = @json($puzzles);
            let currentPuzzle = 0;
            let score = 0;
            let answered = false;
            let completionSent = false;
            const submittedAnswers = Array(puzzles.length).fill('');

            const questionElement = document.getElementById('question');
            const optionsElement = document.getElementById('options');
            const feedbackElement = document.getElementById('feedback');
            const nextButton = document.getElementById('nextButton');

            function showPuzzle() {
                const puzzle = puzzles[currentPuzzle];
                answered = false;
                document.getElementById('puzzleNumber').textContent = currentPuzzle + 1;
                questionElement.textContent = puzzle.question;
                feedbackElement.textContent = '';
                nextButton.style.display = 'none';
                optionsElement.replaceChildren();

                puzzle.options.forEach((option) => {
                    const button = document.createElement('button');
                    button.type = 'button';
                    button.className = 'option';
                    button.textContent = option;
                    button.addEventListener('click', () => chooseAnswer(button, option, puzzle.answer));
                    optionsElement.appendChild(button);
                });
            }

            function chooseAnswer(button, selected, answer) {
                if (answered) return;
                answered = true;
                optionsElement.querySelectorAll('button').forEach((option) => {
                    option.disabled = true;
                });

                if (selected === answer) {
                    score += 1;
                    document.getElementById('score').textContent = score;
                    feedbackElement.textContent = 'Correct. You found the best move.';
                } else {
                    feedbackElement.textContent = `Not quite. The best move was: ${answer}`;
                }
                submittedAnswers[currentPuzzle] = selected;

                nextButton.textContent = currentPuzzle === puzzles.length - 1 ? 'Finish quest' : 'Next puzzle';
                nextButton.style.display = 'block';
            }

            async function finishQuest() {
                if (completionSent) return;
                completionSent = true;

                document.getElementById('puzzlePanel').hidden = true;
                document.getElementById('resultPanel').hidden = false;
                const resultMessage = document.getElementById('resultMessage');
                resultMessage.textContent = 'Saving your progress...';

                try {
                    const response = await fetch(@json(route('student.game.complete', $task->id)), {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({ answers: submittedAnswers })
                    });
                    const data = await response.json();

                    if (!response.ok || !data.success) {
                        throw new Error(data.message || 'Quest progress could not be saved.');
                    }

                    resultMessage.textContent = data.passed
                        ? `All ${data.correct_answers} puzzles are correct. You earned ${data.xp_earned} XP.`
                        : `You solved ${data.correct_answers} of ${data.total_questions} puzzles correctly. No XP was awarded; retry the quest.`;
                    if (data.passed && Array.isArray(data.badges) && data.badges.length > 0) {
                        const badgeNames = data.badges
                            .map((badge) => `${badge.icon ? `${badge.icon} ` : ''}${badge.name}`)
                            .join(', ');
                        resultMessage.textContent +=
                            ` New badge${data.badges.length === 1 ? '' : 's'} earned: ${badgeNames}.`;
                    }
                } catch (error) {
                    resultMessage.textContent = error.message;
                    resultMessage.classList.add('error');
                }
            }

            nextButton.addEventListener('click', () => {
                currentPuzzle += 1;
                if (currentPuzzle >= puzzles.length) {
                    finishQuest();
                    return;
                }
                showPuzzle();
            });

            showPuzzle();
        </script>
    @endif
</body>

</html>
