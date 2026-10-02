<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Sorting Challenge | EcoQuest</title>

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
            background:
                radial-gradient(circle at top left, #203b2b, transparent 35%),
                radial-gradient(circle at bottom right, #10251b, transparent 35%),
                #07110c;
            color: #fff;
            font-family: Arial, sans-serif;
        }

        .game-wrapper {
            max-width: 1150px;
            margin: auto;
            padding: 30px 18px 60px;
        }

        .top-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 15px;
            margin-bottom: 25px;
            flex-wrap: wrap;
        }

        .brand {
            font-size: 1.4rem;
            font-weight: 800;
        }

        .brand span {
            color: #66e39b;
        }

        .stats {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        .stat {
            background: rgba(255, 255, 255, .08);
            border: 1px solid rgba(255, 255, 255, .12);
            border-radius: 14px;
            padding: 9px 15px;
            min-width: 95px;
            text-align: center;
        }

        .stat small {
            display: block;
            color: #9db3a5;
            font-size: .72rem;
        }

        .stat strong {
            font-size: 1.15rem;
        }

        .hero {
            background: linear-gradient(135deg,
                    rgba(70, 180, 112, .20),
                    rgba(255, 255, 255, .04));
            border: 1px solid rgba(110, 230, 155, .18);
            border-radius: 25px;
            padding: 30px;
            margin-bottom: 25px;
        }

        .hero-icon {
            width: 60px;
            height: 60px;
            border-radius: 18px;
            background: rgba(102, 227, 155, .14);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 28px;
            color: #66e39b;
            margin-bottom: 15px;
        }

        .hero h1 {
            font-size: clamp(1.8rem, 4vw, 3rem);
            font-weight: 800;
            margin-bottom: 10px;
        }

        .hero p {
            color: #b7c9be;
            max-width: 750px;
            margin-bottom: 0;
        }

        .instruction {
            margin-top: 18px;
            padding: 14px 17px;
            border-radius: 14px;
            background: rgba(255, 255, 255, .06);
            color: #d9e7de;
        }

        .instruction i {
            color: #66e39b;
        }

        .game-board {
            background: rgba(255, 255, 255, .045);
            border: 1px solid rgba(255, 255, 255, .10);
            border-radius: 25px;
            padding: 25px;
        }

        .items-area {
            margin-bottom: 28px;
        }

        .section-title {
            font-weight: 700;
            margin-bottom: 15px;
        }

        .items-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 14px;
        }

        .item {
            border: 1px solid rgba(255, 255, 255, .12);
            background: #122019;
            border-radius: 18px;
            padding: 20px 12px;
            text-align: center;
            cursor: grab;
            transition: .25s;
            user-select: none;
        }

        .item:hover {
            transform: translateY(-4px);
            border-color: #66e39b;
            background: #17291f;
        }

        .item:active {
            cursor: grabbing;
        }

        .item-icon {
            font-size: 2.5rem;
            margin-bottom: 8px;
        }

        .item-name {
            font-weight: 700;
        }

        .bins {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 18px;
        }

        .bin {
            min-height: 220px;
            border: 2px dashed rgba(255, 255, 255, .18);
            border-radius: 20px;
            padding: 20px;
            text-align: center;
            background: rgba(255, 255, 255, .025);
            transition: .25s;
        }

        .bin.drag-over {
            border-color: #66e39b;
            background: rgba(102, 227, 155, .09);
            transform: scale(1.02);
        }

        .bin-icon {
            font-size: 2.7rem;
            margin-bottom: 7px;
        }

        .bin h4 {
            font-weight: 800;
            margin-bottom: 5px;
        }

        .bin p {
            color: #8fa99a;
            font-size: .85rem;
            margin-bottom: 15px;
        }

        .bin-items {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 8px;
        }

        .placed-item {
            background: rgba(102, 227, 155, .12);
            border: 1px solid rgba(102, 227, 155, .3);
            border-radius: 10px;
            padding: 7px 10px;
            font-size: .82rem;
        }

        .wrong {
            animation: shake .35s;
        }

        @keyframes shake {

            0%,
            100% {
                transform: translateX(0);
            }

            25% {
                transform: translateX(-7px);
            }

            75% {
                transform: translateX(7px);
            }
        }

        .feedback {
            margin-top: 22px;
            min-height: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 14px;
            padding: 12px;
            font-weight: 700;
        }

        .feedback.success {
            background: rgba(102, 227, 155, .12);
            color: #7df0aa;
        }

        .feedback.error {
            background: rgba(255, 90, 90, .12);
            color: #ff9999;
        }

        .controls {
            margin-top: 22px;
            display: flex;
            justify-content: space-between;
            gap: 12px;
            flex-wrap: wrap;
        }

        .btn-game {
            border: 0;
            border-radius: 13px;
            padding: 12px 20px;
            font-weight: 700;
            transition: .2s;
        }

        .btn-primary-game {
            background: #66e39b;
            color: #07110c;
        }

        .btn-primary-game:hover {
            transform: translateY(-2px);
            background: #7bf0ab;
        }

        .btn-secondary-game {
            background: rgba(255, 255, 255, .08);
            color: #fff;
            border: 1px solid rgba(255, 255, 255, .12);
        }

        .result-screen {
            display: none;
            text-align: center;
            padding: 35px 20px;
        }

        .result-icon {
            font-size: 4rem;
            color: #66e39b;
            margin-bottom: 15px;
        }

        .result-screen h2 {
            font-weight: 800;
        }

        .result-score {
            font-size: 3rem;
            font-weight: 900;
            color: #66e39b;
        }

        .xp-box {
            margin: 20px auto;
            max-width: 400px;
            padding: 18px;
            border-radius: 18px;
            background: rgba(102, 227, 155, .09);
            border: 1px solid rgba(102, 227, 155, .18);
        }

        .real-world {
            margin-top: 25px;
            padding: 20px;
            border-radius: 18px;
            background: rgba(255, 255, 255, .05);
            text-align: left;
        }

        .real-world h5 {
            color: #66e39b;
            font-weight: 800;
        }

        @media (max-width: 768px) {
            .bins {
                grid-template-columns: 1fr;
            }

            .hero,
            .game-board {
                padding: 20px;
            }
        }
    </style>
</head>

<body>

    <div class="game-wrapper">

        <!-- TOP BAR -->
        <div class="top-bar">

            <div class="brand">
                Eco<span>Quest</span>
            </div>

            <div class="stats">

                <div class="stat">
                    <small>SCORE</small>
                    <strong id="score">0</strong>
                </div>

                <div class="stat">
                    <small>STREAK</small>
                    <strong id="streak">0</strong>
                </div>

                <div class="stat">
                    <small>ITEMS</small>
                    <strong>
                        <span id="completed">0</span>/9
                    </strong>
                </div>

            </div>

        </div>


        <!-- HERO -->
        <div class="hero">

            <div class="hero-icon">
                <i class="bi bi-recycle"></i>
            </div>

            <h1>Sorting Challenge</h1>

            <p>
                Learn how everyday waste should be separated.
                Sort every item into the correct waste category.
            </p>

            <div class="instruction">
                <i class="bi bi-lightbulb-fill me-2"></i>

                <strong>Mission:</strong>
                Drag each item into the correct bin.
                Correct answers increase your score and streak.
            </div>

        </div>


        <!-- GAME BOARD -->
        <div class="game-board">

            <div id="gameArea">

                <!-- ITEMS -->
                <div class="items-area">

                    <div class="section-title">
                        <i class="bi bi-box-seam me-2"></i>
                        Items to Sort
                    </div>

                    <div class="items-container" id="itemsContainer">

                        <div class="item"
                            draggable="true"
                            data-type="recyclable"
                            data-name="Plastic Bottle">

                            <div class="item-icon">🧴</div>
                            <div class="item-name">Plastic Bottle</div>

                        </div>


                        <div class="item"
                            draggable="true"
                            data-type="organic"
                            data-name="Banana Peel">

                            <div class="item-icon">🍌</div>
                            <div class="item-name">Banana Peel</div>

                        </div>


                        <div class="item"
                            draggable="true"
                            data-type="general"
                            data-name="Chip Packet">

                            <div class="item-icon">🥔</div>
                            <div class="item-name">Chip Packet</div>

                        </div>


                        <div class="item"
                            draggable="true"
                            data-type="recyclable"
                            data-name="Newspaper">

                            <div class="item-icon">📰</div>
                            <div class="item-name">Newspaper</div>

                        </div>


                        <div class="item"
                            draggable="true"
                            data-type="organic"
                            data-name="Apple Core">

                            <div class="item-icon">🍎</div>
                            <div class="item-name">Apple Core</div>

                        </div>


                        <div class="item"
                            draggable="true"
                            data-type="general"
                            data-name="Used Tissue">

                            <div class="item-icon">🧻</div>
                            <div class="item-name">Used Tissue</div>

                        </div>


                        <div class="item"
                            draggable="true"
                            data-type="recyclable"
                            data-name="Glass Jar">

                            <div class="item-icon">🫙</div>
                            <div class="item-name">Glass Jar</div>

                        </div>


                        <div class="item"
                            draggable="true"
                            data-type="organic"
                            data-name="Vegetable Waste">

                            <div class="item-icon">🥬</div>
                            <div class="item-name">Vegetable Waste</div>

                        </div>


                        <div class="item"
                            draggable="true"
                            data-type="general"
                            data-name="Broken Sponge">

                            <div class="item-icon">🧽</div>
                            <div class="item-name">Broken Sponge</div>

                        </div>

                    </div>

                </div>


                <!-- BINS -->
                <div class="section-title">
                    <i class="bi bi-trash3 me-2"></i>
                    Waste Categories
                </div>

                <div class="bins">

                    <!-- ORGANIC -->
                    <div class="bin"
                        data-bin="organic">

                        <div class="bin-icon">🌱</div>

                        <h4>Organic</h4>

                        <p>
                            Food & biodegradable waste
                        </p>

                        <div class="bin-items"></div>

                    </div>


                    <!-- RECYCLABLE -->
                    <div class="bin"
                        data-bin="recyclable">

                        <div class="bin-icon">♻️</div>

                        <h4>Recyclable</h4>

                        <p>
                            Materials that can be recycled
                        </p>

                        <div class="bin-items"></div>

                    </div>


                    <!-- GENERAL -->
                    <div class="bin"
                        data-bin="general">

                        <div class="bin-icon">🗑️</div>

                        <h4>General Waste</h4>

                        <p>
                            Waste that cannot be recycled
                        </p>

                        <div class="bin-items"></div>

                    </div>

                </div>


                <div id="feedback" class="feedback"></div>


                <div class="controls">

                    <button
                        class="btn-game btn-secondary-game"
                        onclick="resetGame()">

                        <i class="bi bi-arrow-clockwise me-2"></i>
                        Restart

                    </button>

                </div>

            </div>


            <!-- RESULT SCREEN -->
            <div class="result-screen" id="resultScreen">

                <div class="result-icon">
                    <i class="bi bi-trophy-fill"></i>
                </div>

                <h2>Mission Complete!</h2>

                <p class="text-secondary">
                    You successfully completed the Sorting Challenge.
                </p>

                <div class="result-score">
                    <span id="finalScore">0</span>
                </div>

                <div>
                    Points Earned
                </div>

                <div class="xp-box">

                    <i class="bi bi-stars me-2"></i>

                    Quest Reward:
                    <strong>
                        +{{ $task->xp ?? 20 }} XP
                    </strong>

                </div>


                <div class="real-world">

                    <h5>
                        <i class="bi bi-globe2 me-2"></i>
                        Real-World Connection
                    </h5>

                    <p class="mb-0">
                        Proper waste sorting helps reduce landfill waste,
                        improves recycling efficiency and supports a cleaner
                        environment. The same decisions you made in this game
                        can be applied when separating waste at home, school
                        or college.
                    </p>

                </div>


                <div class="mt-4">

                    <a href="{{ route('student.dashboard') }}"
                        class="btn-game btn-primary-game text-decoration-none">

                        <i class="bi bi-house-door me-2"></i>
                        Back to Dashboard

                    </a>

                </div>

            </div>

        </div>

    </div>


    <script>
        let score = 0;
        let streak = 0;
        let completed = 0;

        const totalItems = 9;

        let draggedItem = null;


        /*
        |--------------------------------------------------------------------------
        | DRAG START
        |--------------------------------------------------------------------------
        */

        document.querySelectorAll('.item').forEach(item => {

            item.addEventListener('dragstart', function() {

                draggedItem = this;

                this.style.opacity = '0.5';

            });


            item.addEventListener('dragend', function() {

                this.style.opacity = '1';

            });

        });


        /*
        |--------------------------------------------------------------------------
        | DROP ZONES
        |--------------------------------------------------------------------------
        */

        document.querySelectorAll('.bin').forEach(bin => {

            bin.addEventListener('dragover', function(event) {

                event.preventDefault();

                this.classList.add('drag-over');

            });


            bin.addEventListener('dragleave', function() {

                this.classList.remove('drag-over');

            });


            bin.addEventListener('drop', function(event) {

                event.preventDefault();

                this.classList.remove('drag-over');

                if (!draggedItem) {
                    return;
                }

                checkAnswer(this, draggedItem);

            });

        });


        /*
        |--------------------------------------------------------------------------
        | CHECK ANSWER
        |--------------------------------------------------------------------------
        */

        function checkAnswer(bin, item) {

            const correctType = item.dataset.type;

            const selectedBin = bin.dataset.bin;

            const feedback = document.getElementById('feedback');


            if (correctType === selectedBin) {

                // CORRECT

                score += 10;

                streak += 1;

                completed += 1;


                document.getElementById('score').innerText = score;

                document.getElementById('streak').innerText = streak;

                document.getElementById('completed').innerText = completed;


                const placedItem = document.createElement('div');

                placedItem.className = 'placed-item';

                placedItem.innerHTML =
                    item.querySelector('.item-icon').innerText +
                    ' ' +
                    item.dataset.name;


                bin.querySelector('.bin-items')
                    .appendChild(placedItem);


                item.remove();


                feedback.className = 'feedback success';

                feedback.innerHTML =
                    '<i class="bi bi-check-circle-fill me-2"></i>' +
                    'Correct! Great job. +10 points';


                if (completed === totalItems) {

                    setTimeout(showResult, 700);

                }

            } else {

                // WRONG

                streak = 0;

                document.getElementById('streak').innerText = streak;


                bin.classList.add('wrong');


                setTimeout(() => {

                    bin.classList.remove('wrong');

                }, 400);


                feedback.className = 'feedback error';

                feedback.innerHTML =
                    '<i class="bi bi-x-circle-fill me-2"></i>' +
                    'Not quite! Try another category.';

            }

        }


        /*
        |--------------------------------------------------------------------------
        | RESULT
        |--------------------------------------------------------------------------
        */

        async function showResult() {

            document.getElementById('gameArea').style.display = 'none';

            document.getElementById('resultScreen').style.display = 'block';

            document.getElementById('finalScore').innerText = score;


            /*
            |--------------------------------------------------------------------------
            | SAVE QUEST COMPLETION
            |--------------------------------------------------------------------------
            */

            try {

                const response = await fetch(
                    "{{ route('student.game.complete', $task->id) }}", {
                        method: "POST",

                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": "{{ csrf_token() }}",
                            "Accept": "application/json"
                        },

                        body: JSON.stringify({
                            score: score
                        })
                    }
                );


                const data = await response.json();


                if (data.success) {

                    console.log("Quest completed successfully.");

                } else {

                    console.log(data.message);

                }

            } catch (error) {

                console.error(
                    "Could not save quest completion:",
                    error
                );

            }

        }


        /*
        |--------------------------------------------------------------------------
        | RESET
        |--------------------------------------------------------------------------
        */

        function resetGame() {

            window.location.reload();

        }
    </script>

</body>

</html>