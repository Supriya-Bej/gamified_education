@php
    $interestKey = strtolower(trim($game->category ?: $task->category));
    $interestKey = str_replace([' ', '-'], '_', $interestKey);
    $interestAliases = [
        'mathematics' => 'math',
        'programming' => 'coding',
        'strategy' => 'chess',
        'general_knowledge' => 'gk',
        'environmental_education' => 'environment',
        'environmental_science' => 'environment',
        'color_theory' => 'art',
    ];
    $interestKey = $interestAliases[$interestKey] ?? $interestKey;
    $interestThemes = [
        'math' => ['icon' => '🔢', 'accent' => '#34d399', 'message' => 'Warm up your number sense with a challenge made for curious minds.'],
        'science' => ['icon' => '🔬', 'accent' => '#38bdf8', 'message' => 'Explore the clues, test your ideas, and uncover how the world works.'],
        'coding' => ['icon' => '💻', 'accent' => '#818cf8', 'message' => 'Think like a creator and turn each question into your next big idea.'],
        'chess' => ['icon' => '♟️', 'accent' => '#fbbf24', 'message' => 'Plan your next move and sharpen your strategic thinking.'],
        'sports' => ['icon' => '🏅', 'accent' => '#fb7185', 'message' => 'Get in the game and test your knowledge of sports and play.'],
        'art' => ['icon' => '🎨', 'accent' => '#e879f9', 'message' => 'Bring your creative eye and explore a world of color and ideas.'],
        'music' => ['icon' => '🎵', 'accent' => '#38bdf8', 'message' => 'Find the rhythm and explore the patterns behind the music.'],
        'reading' => ['icon' => '📚', 'accent' => '#f59e0b', 'message' => 'Turn the page on a new challenge and follow each clue.'],
        'puzzles' => ['icon' => '🧩', 'accent' => '#a78bfa', 'message' => 'Look for patterns, connect the clues, and solve the challenge.'],
        'technology' => ['icon' => '⚙️', 'accent' => '#22d3ee', 'message' => 'Explore the ideas and inventions shaping our digital world.'],
        'gk' => ['icon' => '🌍', 'accent' => '#60a5fa', 'message' => 'Take a curious tour through fascinating facts about the world.'],
        'environment' => ['icon' => '🌿', 'accent' => '#4ade80', 'message' => 'Discover how small choices can make a big difference for our planet.'],
    ];
    $interestTheme = $interestThemes[$interestKey] ?? [
        'icon' => $gameIcon,
        'accent' => $accentColor,
        'message' => $supportingCopy ?? 'Bring your curiosity and take on this learning challenge.',
    ];
    $interestLabel = $interestKey === 'gk' ? 'GK' : ucfirst($interestKey);
    $isArtQuest = strtolower($game->game_type) === 'art_color_theory';
@endphp
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
            --quest-accent: {{ $interestTheme['accent'] }};
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
        .music-player {
            margin: 22px 0;
            padding: 18px;
            border: 1px solid rgba(255,255,255,.12);
            border-radius: 16px;
            background: rgba(255,255,255,.04);
        }
        .music-player label { display: block; margin-bottom: 10px; font-weight: 700; }
        .music-player audio { display: block; width: 100%; margin-top: 14px; }
        .music-status { min-height: 1.5em; margin: 10px 0 0; color: #cbd5e1; }
        .music-track { margin: 0; font-weight: 700; }
        .music-actions { display: flex; flex-wrap: wrap; gap: 10px; margin-top: 12px; }
        .music-actions button { color: inherit; font: inherit; cursor: pointer; }
        .interest-studio {
            display: flex;
            align-items: center;
            gap: 16px;
            margin: 0 0 30px;
            padding: 18px 22px;
            border: 1px solid color-mix(in srgb, var(--quest-accent) 30%, transparent);
            border-radius: 18px;
            background: linear-gradient(115deg, color-mix(in srgb, var(--quest-accent) 17%, #0f172a), rgba(15, 23, 42, .88));
        }
        .interest-icon {
            display: grid;
            flex: 0 0 58px;
            width: 58px;
            height: 58px;
            place-items: center;
            border: 1px solid color-mix(in srgb, var(--quest-accent) 42%, transparent);
            border-radius: 17px;
            background: color-mix(in srgb, var(--quest-accent) 18%, transparent);
            font-size: 1.8rem;
        }
        .interest-kicker {
            margin: 0 0 5px;
            color: var(--quest-accent);
            font-size: .73rem;
            font-weight: 900;
            letter-spacing: .13em;
            text-transform: uppercase;
        }
        .interest-message { margin: 0; color: #cbd5e1; line-height: 1.5; }
        [hidden] { display: none !important; }
        @media (max-width: 560px) {
            .topbar { align-items: flex-start; flex-direction: column; }
            .interest-studio { padding: 16px; }
        }
        .art-quest {
            color-scheme: light;
            color: #302b3a;
            background:
                radial-gradient(circle at 12% 18%, rgba(251, 191, 36, .2), transparent 25%),
                radial-gradient(circle at 90% 12%, rgba(232, 121, 249, .18), transparent 28%),
                radial-gradient(circle at 85% 90%, rgba(56, 189, 248, .16), transparent 30%),
                #fff8ed;
        }
        .art-quest .topbar a {
            color: #453b50;
            border-color: rgba(91, 67, 102, .16);
            background: rgba(255, 255, 255, .75);
        }
        .art-quest .card {
            position: relative;
            overflow: hidden;
            border: 1px solid rgba(115, 82, 91, .16);
            background: rgba(255, 255, 255, .92);
            box-shadow: 0 28px 80px rgba(111, 73, 82, .14);
        }
        .art-quest .card::before {
            position: absolute;
            top: 0;
            right: 0;
            left: 0;
            height: 7px;
            background: linear-gradient(90deg, #f97316, #facc15, #22c55e, #38bdf8, #a78bfa, #ec4899);
            content: "";
        }
        .art-quest .eyebrow { color: #b445a8; }
        .art-quest .muted, .art-quest .feedback { color: #6b6473; }
        .art-quest .stat {
            border: 1px solid rgba(115, 82, 91, .1);
            background: #fff7eb;
            color: #55485a;
        }
        .art-quest .progress-track { background: #f0e5e8; }
        .art-quest .progress-fill { background: linear-gradient(90deg, #f97316, #e879f9); }
        .art-quest .option, .art-quest .next, .art-quest .retry {
            border-color: #e8dce2;
            background: #fffdf9;
            color: #332d3a;
            transition: border-color .18s ease, background .18s ease, transform .18s ease, box-shadow .18s ease;
        }
        .art-quest .option:hover:not(:disabled), .art-quest .option[aria-pressed="true"] {
            border-color: #d66acb;
            background: #fff3fc;
            box-shadow: 0 5px 16px rgba(186, 91, 172, .12);
            transform: translateY(-2px);
        }
        .art-quest .next, .art-quest .retry {
            border-color: #d66acb;
            background: #fae8f7;
            color: #71396d;
        }
        .art-quest .error { color: #b4233b; }
        .art-studio {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
            margin: 0 0 30px;
            padding: 18px 22px;
            overflow: hidden;
            border: 1px solid rgba(221, 175, 95, .3);
            border-radius: 18px;
            background: linear-gradient(115deg, #fff4d9, #fff0f8 58%, #edf8ff);
        }
        .studio-copy { position: relative; z-index: 1; }
        .studio-kicker {
            margin: 0 0 5px;
            color: #a44c87;
            font-size: .73rem;
            font-weight: 900;
            letter-spacing: .13em;
        }
        .studio-message { margin: 0; color: #65566a; line-height: 1.5; }
        .pencil-cup {
            display: flex;
            align-items: flex-end;
            justify-content: center;
            flex: 0 0 140px;
            height: 94px;
            padding: 0 12px 10px;
            border-bottom: 8px solid #d5a176;
            border-radius: 8px 8px 20px 20px;
            background: linear-gradient(90deg, #f3d0a6, #ffe2bd 50%, #edc195);
            transform: rotate(-3deg);
        }
        .pencil {
            position: relative;
            width: 14px;
            height: 72px;
            margin: 0 3px;
            border: 1px solid rgba(65, 48, 62, .14);
            border-radius: 5px 5px 2px 2px;
            background: linear-gradient(90deg, rgba(255,255,255,.55), transparent 35%, rgba(0,0,0,.12)), var(--pencil-color);
            box-shadow: 1px 2px 3px rgba(70, 49, 64, .12);
            transform: rotate(var(--pencil-tilt));
            transform-origin: bottom center;
        }
        .pencil::before {
            position: absolute;
            top: -11px;
            left: -1px;
            width: 14px;
            height: 12px;
            background: #f3d5a8;
            clip-path: polygon(50% 0, 100% 100%, 0 100%);
            content: "";
        }
        .pencil::after {
            position: absolute;
            top: -3px;
            left: 6px;
            width: 3px;
            height: 4px;
            background: #50404d;
            clip-path: polygon(50% 0, 100% 100%, 0 100%);
            content: "";
        }
        @media (max-width: 560px) {
            .art-studio { padding: 16px; }
            .pencil-cup { flex-basis: 110px; transform: scale(.85) rotate(-3deg); transform-origin: right center; }
        }
    </style>
</head>
<body class="{{ $isArtQuest ? 'art-quest' : '' }}">
<main>
    <nav class="topbar" aria-label="Game navigation">
        <strong>EcoQuest · {{ $gameIcon }} {{ ucfirst($game->category) }}</strong>
        <a href="{{ route('student.dashboard') }}">Back to dashboard</a>
    </nav>
    <section class="card">
        @unless ($isArtQuest)
            <div class="interest-studio">
                <span class="interest-icon" aria-hidden="true">{{ $interestTheme['icon'] }}</span>
                <div>
                    <p class="interest-kicker">{{ $interestLabel }} quest</p>
                    <p class="interest-message">{{ $interestTheme['message'] }}</p>
                </div>
            </div>
        @endunless
        @if ($isArtQuest)
            <div class="art-studio">
                <div class="studio-copy">
                    <p class="studio-kicker">WELCOME TO YOUR ART STUDIO</p>
                    <p class="studio-message">Pick up your colours and let your creativity lead the way.</p>
                </div>
                <div class="pencil-cup" aria-hidden="true">
                    <span class="pencil" style="--pencil-color: #ef4444; --pencil-tilt: -10deg"></span>
                    <span class="pencil" style="--pencil-color: #f59e0b; --pencil-tilt: -4deg"></span>
                    <span class="pencil" style="--pencil-color: #22c55e; --pencil-tilt: 4deg"></span>
                    <span class="pencil" style="--pencil-color: #3b82f6; --pencil-tilt: 9deg"></span>
                    <span class="pencil" style="--pencil-color: #a855f7; --pencil-tilt: 14deg"></span>
                </div>
            </div>
        @endif
        <p class="eyebrow">{{ $gameLabel }}</p>
        <h1>{{ $game->name }}</h1>
        <h2>{{ $task->title }}</h2>
        <p class="muted">{{ $task->description }}</p>
        <p class="muted">{{ data_get($task->game_data, 'instructions') }}</p>
        @if (strtolower($game->game_type) === 'music_note_rhythm')
            <section class="music-player" aria-label="Music player">
                <label for="music-audio">Quest background music</label>
                <p id="music-track" class="music-track" aria-live="polite"></p>
                <audio id="music-audio" controls autoplay preload="auto" aria-describedby="music-status">
                    Your browser does not support audio playback.
                </audio>
                <div class="music-actions">
                    <button id="music-previous" class="action" type="button" aria-label="Play previous song">Previous song</button>
                    <button id="music-next" class="action" type="button" aria-label="Play next song">Next song</button>
                </div>
                <p id="music-status" class="music-status" role="status" aria-live="polite">Starting quest music…</p>
            </section>
        @endif
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
    const musicAudio = document.getElementById('music-audio');
    const musicStatus = document.getElementById('music-status');
    const musicTrackLabel = document.getElementById('music-track');
    const musicTracks = [
        { title: 'Bright Beginnings', url: @json(asset('audio/ecoquest-music.wav')) },
        { title: 'Forest Adventure', url: @json(asset('audio/ecoquest-forest.wav')) },
        { title: 'Starlight Journey', url: @json(asset('audio/ecoquest-starlight.wav')) },
    ];

    if (musicAudio && musicStatus && musicTrackLabel) {
        let currentMusicTrack = 0;

        function playMusicTrack(index) {
            currentMusicTrack = (index + musicTracks.length) % musicTracks.length;
            const track = musicTracks[currentMusicTrack];
            musicTrackLabel.textContent = `Song ${currentMusicTrack + 1} of ${musicTracks.length}: ${track.title}`;
            musicAudio.src = track.url;
            musicAudio.load();
            musicAudio.play().catch(() => {
                musicStatus.textContent = 'Press play on the music controls to start the quest music.';
            });
        }

        musicAudio.addEventListener('play', () => {
            musicStatus.textContent = 'Music is playing. Use the player or Previous/Next buttons to control it.';
        });

        musicAudio.addEventListener('pause', () => {
            musicStatus.textContent = 'Music is paused.';
        });

        musicAudio.addEventListener('ended', () => {
            playMusicTrack(currentMusicTrack + 1);
        });

        musicAudio.addEventListener('error', () => {
            musicStatus.textContent = 'This song could not be loaded. Use Next song to continue.';
        });

        document.getElementById('music-previous').addEventListener('click', () => {
            playMusicTrack(currentMusicTrack - 1);
        });

        document.getElementById('music-next').addEventListener('click', () => {
            playMusicTrack(currentMusicTrack + 1);
        });

        playMusicTrack(0);
    }

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
                if (result.certificate?.url) {
                    const certificateLink = document.createElement('a');
                    certificateLink.href = result.certificate.url;
                    certificateLink.textContent = ' View your interest certificate.';
                    resultMessage.appendChild(certificateLink);
                } else if (result.certificate?.status === 'pending_review') {
                    resultMessage.textContent += ' Your course certificate is waiting for admin approval.';
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
