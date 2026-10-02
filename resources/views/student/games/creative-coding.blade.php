<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>{{ $game->name }} | EcoQuest</title>

    <link rel="stylesheet"
        href="{{ asset('Asset/Bootstrap-5/css/bootstrap.min.css') }}">

    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            min-height: 100vh;
            font-family: Arial, sans-serif;
            background:
                radial-gradient(circle at top left, #1e293b, transparent 35%),
                radial-gradient(circle at bottom right, #312e81, transparent 35%),
                #070b14;
            color: white;
        }

        .game-wrapper {
            min-height: 100vh;
            padding: 35px 20px;
        }

        .game-container {
            max-width: 1100px;
            margin: auto;
        }

        .top-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
        }

        .brand {
            font-size: 22px;
            font-weight: 800;
        }

        .back-btn {
            color: white;
            text-decoration: none;
            border: 1px solid rgba(255, 255, 255, .15);
            padding: 10px 16px;
            border-radius: 12px;
            background: rgba(255, 255, 255, .06);
        }

        .back-btn:hover {
            color: white;
            background: rgba(255, 255, 255, .12);
        }

        .hero-card {
            border-radius: 30px;
            padding: 40px;
            background: rgba(15, 23, 42, .88);
            border: 1px solid rgba(255, 255, 255, .1);
            box-shadow: 0 25px 80px rgba(0, 0, 0, .35);
            backdrop-filter: blur(20px);
        }

        .game-icon {
            width: 75px;
            height: 75px;
            border-radius: 22px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 38px;
            background: rgba(255, 255, 255, .1);
            margin-bottom: 20px;
        }

        .badge-custom {
            display: inline-block;
            padding: 8px 15px;
            border-radius: 50px;
            background: rgba(56, 189, 248, .15);
            color: #7dd3fc;
            font-weight: 700;
            margin-bottom: 15px;
        }

        h1 {
            font-size: clamp(40px, 7vw, 76px);
            font-weight: 900;
            line-height: .95;
            margin-bottom: 18px;
        }

        .description {
            color: #cbd5e1;
            font-size: 18px;
            max-width: 750px;
        }

        .section {
            margin-top: 35px;
            padding: 28px;
            border-radius: 22px;
            background: rgba(255, 255, 255, .05);
            border: 1px solid rgba(255, 255, 255, .08);
        }

        .section h2 {
            font-size: 25px;
            font-weight: 800;
            margin-bottom: 15px;
        }

        .mission-box {
            border-left: 4px solid #38bdf8;
            padding: 18px 20px;
            background: rgba(56, 189, 248, .08);
            border-radius: 0 15px 15px 0;
            color: #dbeafe;
        }

        .concept-card {
            padding: 20px;
            border-radius: 18px;
            background: rgba(255, 255, 255, .05);
            height: 100%;
        }

        .concept-number {
            font-size: 30px;
            font-weight: 900;
            color: #38bdf8;
        }

        .code-area {
            width: 100%;
            min-height: 180px;
            resize: vertical;
            border: 1px solid rgba(255, 255, 255, .12);
            border-radius: 18px;
            background: #020617;
            color: #e2e8f0;
            padding: 20px;
            font-family: Consolas, monospace;
            font-size: 16px;
            outline: none;
        }

        .code-area:focus {
            border-color: #38bdf8;
            box-shadow: 0 0 0 3px rgba(56, 189, 248, .1);
        }

        .preview-box {
            min-height: 220px;
            border-radius: 20px;
            background: #020617;
            border: 1px solid rgba(255, 255, 255, .1);
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }

        #previewCanvas {
            max-width: 100%;
        }

        .challenge-btn {
            border: none;
            border-radius: 14px;
            padding: 13px 22px;
            font-weight: 800;
            font-size: 16px;
            transition: .2s;
        }

        .run-btn {
            background: #38bdf8;
            color: #020617;
        }

        .run-btn:hover {
            transform: translateY(-2px);
            background: #7dd3fc;
        }

        .reset-btn {
            background: rgba(255, 255, 255, .1);
            color: white;
        }

        .result-box {
            display: none;
            margin-top: 20px;
            padding: 20px;
            border-radius: 18px;
            background: rgba(34, 197, 94, .1);
            border: 1px solid rgba(34, 197, 94, .25);
        }

        .xp-box {
            font-size: 25px;
            font-weight: 900;
        }

        .progress-track {
            height: 10px;
            background: rgba(255, 255, 255, .1);
            border-radius: 20px;
            overflow: hidden;
        }

        .progress-fill {
            height: 100%;
            width: 0%;
            background: #38bdf8;
            transition: width .5s ease;
        }

        @media(max-width:768px) {

            .hero-card {
                padding: 25px;
            }

            .top-bar {
                gap: 15px;
                align-items: flex-start;
            }

            h1 {
                font-size: 48px;
            }

        }
    </style>
</head>

<body>

    <div class="game-wrapper">

        <div class="game-container">

            <!-- TOP BAR -->
            <div class="top-bar">

                <div class="brand">
                    <i class="bi bi-controller"></i>
                    EcoQuest
                </div>

                <a href="{{ route('student.dashboard') }}"
                    class="back-btn">
                    <i class="bi bi-arrow-left"></i>
                    Dashboard
                </a>

            </div>


            <!-- HERO -->
            <div class="hero-card">

                <div class="game-icon">
                    💻
                </div>

                <span class="badge-custom">
                    Creative Coding
                </span>

                <h1>
                    {{ $game->name }}
                </h1>

                <p class="description">
                    {{ $game->description }}
                </p>


                <!-- MISSION -->
                <div class="section">

                    <h2>
                        <i class="bi bi-flag"></i>
                        Mission Brief
                    </h2>

                    <div class="mission-box">

                        <strong>Your Quest:</strong>

                        <div class="mt-2">
                            {{ $task->title }}
                        </div>

                        <div class="mt-2 text-secondary">
                            {{ $task->description }}
                        </div>

                    </div>

                </div>


                <!-- LEARN -->
                <div class="section">

                    <h2>
                        <i class="bi bi-lightbulb"></i>
                        Learn Before You Play
                    </h2>

                    <p class="text-secondary">
                        Creative coding means using programming logic to
                        create visual or interactive results. Instead of
                        writing code only to calculate numbers, you can
                        use code to create patterns, shapes, animations,
                        and digital artwork.
                    </p>


                    <div class="row g-3 mt-2">

                        <div class="col-md-4">

                            <div class="concept-card">

                                <div class="concept-number">
                                    01
                                </div>

                                <h5>
                                    Sequence
                                </h5>

                                <p class="text-secondary mb-0">
                                    Programs execute instructions in a
                                    particular order.
                                </p>

                            </div>

                        </div>


                        <div class="col-md-4">

                            <div class="concept-card">

                                <div class="concept-number">
                                    02
                                </div>

                                <h5>
                                    Repetition
                                </h5>

                                <p class="text-secondary mb-0">
                                    Loops allow you to repeat patterns
                                    without writing the same code again.
                                </p>

                            </div>

                        </div>


                        <div class="col-md-4">

                            <div class="concept-card">

                                <div class="concept-number">
                                    03
                                </div>

                                <h5>
                                    Creativity
                                </h5>

                                <p class="text-secondary mb-0">
                                    Programming logic can become a tool
                                    for creating visual ideas.
                                </p>

                            </div>

                        </div>

                    </div>

                </div>


                <!-- PRACTICE -->
                <div class="section">

                    <h2>
                        <i class="bi bi-code-slash"></i>
                        Practice
                    </h2>

                    <p class="text-secondary">
                        Your goal is to create a simple repeating visual
                        pattern. Write JavaScript below and click
                        <strong>Run Code</strong>.
                    </p>

                    <textarea id="codeInput"
                        class="code-area">const canvas = document.getElementById("previewCanvas");
const ctx = canvas.getContext("2d");

ctx.clearRect(0, 0, canvas.width, canvas.height);

for (let i = 0; i < 8; i++) {
    ctx.fillRect(30 + i * 35, 80, 25, 25);
}</textarea>


                    <div class="d-flex gap-2 mt-3 flex-wrap">

                        <button class="challenge-btn run-btn"
                            onclick="runCode()">

                            <i class="bi bi-play-fill"></i>
                            Run Code

                        </button>

                        <button class="challenge-btn reset-btn"
                            onclick="resetGame()">

                            <i class="bi bi-arrow-counterclockwise"></i>
                            Reset

                        </button>

                    </div>


                    <!-- PREVIEW -->

                    <div class="mt-4">

                        <div class="small text-secondary mb-2">
                            Live Preview
                        </div>

                        <div class="preview-box">

                            <canvas id="previewCanvas"
                                width="600"
                                height="220">
                            </canvas>

                        </div>

                    </div>


                    <!-- RESULT -->

                    <div id="resultBox"
                        class="result-box">

                        <div class="xp-box">
                            +{{ $task->xp }} XP
                        </div>

                        <div class="mt-2">
                            🎉 Pattern generated successfully!
                        </div>

                        <div class="progress-track mt-3">
                            <div id="progressFill"
                                class="progress-fill">
                            </div>
                        </div>

                        <div class="mt-3 text-secondary">
                            You completed the creative coding challenge.
                        </div>

                    </div>

                </div>


                <!-- REAL WORLD -->
                <div class="section">

                    <h2>
                        <i class="bi bi-globe2"></i>
                        Real-World Connection
                    </h2>

                    <p class="text-secondary mb-0">

                        Creative coding is used in websites, games,
                        interactive art, data visualization, simulations,
                        animations, and digital design. The same programming
                        concepts you practiced here can be used to create
                        real interactive experiences.

                    </p>

                </div>

            </div>

        </div>

    </div>


    <script>
        const originalCode =
            `const canvas = document.getElementById("previewCanvas");
const ctx = canvas.getContext("2d");

ctx.clearRect(0, 0, canvas.width, canvas.height);

for (let i = 0; i < 8; i++) {
    ctx.fillRect(30 + i * 35, 80, 25, 25);
}`;

        function runCode() {

            const code =
                document.getElementById('codeInput').value;

            const canvas =
                document.getElementById('previewCanvas');

            const ctx =
                canvas.getContext('2d');

            ctx.clearRect(
                0,
                0,
                canvas.width,
                canvas.height
            );

            try {

                /*
                 * Run student's code.
                 */

                const execute =
                    new Function(
                        'canvas',
                        'ctx',
                        code
                    );

                execute(canvas, ctx);

                /*
                 * Successful result.
                 */

                document.getElementById('resultBox')
                    .style.display = 'block';

                document.getElementById('progressFill')
                    .style.width = '100%';

            } catch (error) {

                alert(
                    'There is an error in your code:\\n\\n' +
                    error.message
                );

            }

        }


        function resetGame() {

            document.getElementById('codeInput').value =
                originalCode;

            const canvas =
                document.getElementById('previewCanvas');

            const ctx =
                canvas.getContext('2d');

            ctx.clearRect(
                0,
                0,
                canvas.width,
                canvas.height
            );

            document.getElementById('resultBox')
                .style.display = 'none';

            document.getElementById('progressFill')
                .style.width = '0%';

        }
    </script>

</body>

</html>