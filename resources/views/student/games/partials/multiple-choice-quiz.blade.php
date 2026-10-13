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
            font-family: Inter, ui-sans-serif, system-ui, sans-serif;
            background: #09111f;
            color: #f8fafc;
            --quest-accent: {{ $accentColor }};
        }
        * { box-sizing: border-box; }
        body {
            margin: 0;
            min-height: 100vh;
            background:
                radial-gradient(circle at 15% 15%, color-mix(in srgb, var(--quest-accent) 24%, transparent), transparent 34%),
                radial-gradient(circle at 90% 90%, rgba(56, 189, 248, .12), transparent 35%),
                #09111f;
        }
        main { width: min(880px, calc(100% - 32px)); margin: auto; padding: 28px 0 56px; }
        .topbar, .stats { display: flex; align-items: center; gap: 12px; }
        .topbar { justify-content: space-between; margin-bottom: 28px; }
        .topbar a, button { color: inherit; font: inherit; }
        .topbar a, .action {
            text-decoration: none;
            border: 1px solid rgba(255,255,255,.2);
            border-radius: 12px;
            padding: 11px 15px;
            background: rgba(255,255,255,.06);
        }
        .card {
            border: 1px solid rgba(255,255,255,.12);
            border-radius: 24px;
            background: rgba(15,23,42,.9);
            padding: clamp(22px, 5vw, 42px);
            box-shadow: 0 28px 80px rgba(0,0,0,.32);
        }
        .eyebrow { color: var(--quest-accent); font-size: .78rem; font-weight: 800; letter-spacing: .12em; }
        h1 { margin: 8px 0; font-size: clamp(2rem, 6vw, 3.4rem); }
        h2 { margin: 0 0 14px; }
        .muted { color: #cbd5e1; line-height: 1.65; }
        .stats { flex-wrap: wrap; margin: 24px 0; }
        .stat { background: #1e293b; border-radius: 12px; padding: 10px 14px; }
        .progress-track { height: 7px; overflow: hidden; border-radius: 8px; background: #263449; margin: 20px 0 28px; }
        .progress-fill { height: 100%; width: 0; background: var(--quest-accent); transition: width .2s ease; }
        .question { margin: 22px 0; font-size: clamp(1.25rem, 4vw, 1.8rem); line-height: 1.5; }
        .options { display: grid; gap: 12px; }
        .option, .next, .retry {
            border: 1px solid #475569;
            border-radius: 13px;
            background: #1e293b;
            padding: 14px 16px;
            text-align: left;
            cursor: pointer;
        }
        .option:hover:not(:disabled), .option[aria-pressed="true"] { border-color: var(--quest-accent); background: #27364a; }
        .option:disabled { cursor: default; opacity: .75; }
        .next { display: none; margin: 22px 0 0 auto; text-align: center; border-color: var(--quest-accent); }
        .feedback { min-height: 25px; margin: 16px 0 0; color: #cbd5e1; }
        .result { margin: 20px 0; font-size: 1.15rem; line-height: 1.6; }
        .error { color: #fecaca; }
        .result-actions { display: flex; flex-wrap: wrap; gap: 12px; margin-top: 20px; }
        .retry { display: none; cursor: pointer; border-color: var(--quest-accent); }
        [hidden] { display: none !important; }
        @media (max-width: 560px) {
            .topbar { align-items: flex-start; flex-direction: column; }
        }
    </style>
</head>
<body>
<main>
    <nav class="topbar" aria-label="Game navigation">
        <strong>EcoQuest · {{ $gameIcon }} {{ ucfirst($game->category) }}</strong>
        <a href="{{ route('student.dashboard') }}">Back to dashboard</a>
    </nav>
    <section class="card">
        <p class="eyebrow">{{ $gameLabel }}</p>
        <h1>{{ $game->name }}</h1>
        <h2>{{ $task->title }}</h2>
        <p class="muted">{{ $task->description }}</p>
        <p class="muted">{{ data_get($task->game_data, 'instructions') }}</p>
        @if (is_array($questions) && count($questions) > 0)
            <div class="stats">
                <div class="stat">Question <span id="question-number">1</span>/{{ count($questions) }}</div>
                <div class="stat">Reward {{ $task->xp }} XP</div>
            </div>
            <div class="progress-track" aria-hidden="true"><div id="progress-fill" class="progress-fill"></div></div>
            <div id="quiz-panel">
                <p id="question" class="question"></p>
                <div id="options" class="options"></div>
                <p id="feedback" class="feedback" aria-live="polite"></p>
                <button id="next-button" class="next" type="button">Next question</button>
            </div>
            <div id="result-panel" hidden>
                <h2 id="result-heading">Quest result</h2>
                <p id="result-message" class="result" aria-live="polite"></p>
                <div class="result-actions">
                    <button id="retry-button" class="retry" type="button">Try again</button>
                    <a class="action" href="{{ route('student.dashboard') }}">Return to dashboard</a>
                </div>
            </div>
        @else
            <p class="error" role="alert">This quest has no valid questions and cannot be played.</p>
        @endif
    </section>
</main>
@if (is_array($questions) && count($questions) > 0)
<script>
    const questions = @json($questions);
    const completionUrl = @json(route('student.game.complete', $task->id));
    let currentQuestion = 0;
    let selectedAnswers = Array(questions.length).fill('');
    let sending = false;

    const questionElement = document.getElementById('question');
    const optionsElement = document.getElementById('options');
    const feedbackElement = document.getElementById('feedback');
    const nextButton = document.getElementById('next-button');

    function renderQuestion() {
        const question = questions[currentQuestion];
        document.getElementById('question-number').textContent = currentQuestion + 1;
        document.getElementById('progress-fill').style.width = `${(currentQuestion / questions.length) * 100}%`;
        questionElement.textContent = question.question;
        feedbackElement.textContent = '';
        nextButton.style.display = 'none';
        optionsElement.replaceChildren();

        question.options.forEach((option) => {
            const button = document.createElement('button');
            button.type = 'button';
            button.className = 'option';
            button.textContent = option;
            button.setAttribute('aria-pressed', String(selectedAnswers[currentQuestion] === option));
            button.addEventListener('click', () => {
                selectedAnswers[currentQuestion] = option;
                optionsElement.querySelectorAll('button').forEach((choice) => {
                    choice.setAttribute('aria-pressed', String(choice.textContent === option));
                });
                feedbackElement.textContent = 'Answer selected.';
                nextButton.textContent = currentQuestion === questions.length - 1 ? 'Submit answers' : 'Next question';
                nextButton.style.display = 'block';
            });
            optionsElement.appendChild(button);
        });
    }

    async function submitAnswers() {
        if (sending || selectedAnswers.some((answer) => answer === '')) {
            feedbackElement.textContent = 'Please answer every question before submitting.';
            return;
        }

        sending = true;
        nextButton.disabled = true;
        nextButton.textContent = 'Checking answers…';
        try {
            const response = await fetch(completionUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                },
                body: JSON.stringify({ answers: selectedAnswers }),
            });
            const result = await response.json();
            if (!response.ok || !result.success) {
                throw new Error(result.message || 'Could not save your result. Please try again.');
            }

            document.getElementById('quiz-panel').hidden = true;
            document.getElementById('result-panel').hidden = false;
            document.getElementById('progress-fill').style.width = '100%';
            const resultMessage = document.getElementById('result-message');

            if (result.passed) {
                document.getElementById('result-heading').textContent = 'Quest complete!';
                resultMessage.textContent = `${result.correct_answers}/${result.total_questions} correct. You earned ${result.xp_earned} XP.`;
                if (result.badges.length > 0) {
                    resultMessage.textContent += ` Badge unlocked: ${result.badges.map((badge) => badge.name).join(', ')}.`;
                }
            } else {
                document.getElementById('result-heading').textContent = 'Keep practicing';
                resultMessage.textContent = `${result.correct_answers}/${result.total_questions} correct. You need every answer correct to complete this quest; no XP was awarded.`;
                document.getElementById('retry-button').style.display = 'inline-block';
            }
        } catch (error) {
            sending = false;
            nextButton.disabled = false;
            nextButton.textContent = 'Try submitting again';
            feedbackElement.textContent = error.message || 'Could not save your result.';
            feedbackElement.classList.add('error');
        }
    }

    nextButton.addEventListener('click', () => {
        if (selectedAnswers[currentQuestion] === '') {
            feedbackElement.textContent = 'Choose an answer before continuing.';
            return;
        }
        if (currentQuestion === questions.length - 1) {
            submitAnswers();
            return;
        }
        currentQuestion += 1;
        renderQuestion();
    });

    document.getElementById('retry-button').addEventListener('click', () => {
        currentQuestion = 0;
        selectedAnswers = Array(questions.length).fill('');
        sending = false;
        document.getElementById('result-panel').hidden = true;
        document.getElementById('quiz-panel').hidden = false;
        renderQuestion();
    });

    renderQuestion();
</script>
@endif
</body>
</html>
