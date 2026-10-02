<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Math Speed Challenge | EcoQuest</title>

    <link rel="stylesheet"
          href="{{ asset('Asset/Bootstrap-5/css/bootstrap.min.css') }}">

    <link rel="stylesheet"
          href="{{ asset('Asset/Bootstrap-5/icons/bootstrap-icons.css') }}">

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            min-height: 100vh;
            font-family: Arial, sans-serif;
            color: #ffffff;
            overflow-x: hidden;

            background:
                radial-gradient(circle at 15% 15%, rgba(0, 255, 170, .18), transparent 30%),
                radial-gradient(circle at 85% 85%, rgba(0, 150, 255, .18), transparent 30%),
                linear-gradient(135deg, #06131a, #09242b, #06131a);
        }

        .game-shell {
            min-height: 100vh;
            padding: 25px;
        }

        /* TOP BAR */

        .game-topbar {
            max-width: 1100px;
            margin: auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 15px;
            margin-bottom: 30px;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 12px;
            font-weight: 800;
            font-size: 1.2rem;
        }

        .brand-icon {
            width: 45px;
            height: 45px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(255, 255, 255, .1);
            border: 1px solid rgba(255, 255, 255, .15);
            font-size: 22px;
        }

        .quit-btn {
            border: 1px solid rgba(255, 255, 255, .15);
            background: rgba(255, 255, 255, .08);
            color: white;
            border-radius: 12px;
            padding: 10px 16px;
            text-decoration: none;
            transition: .25s;
        }

        .quit-btn:hover {
            background: rgba(255, 255, 255, .15);
            color: white;
        }

        /* GAME CARD */

        .game-container {
            max-width: 900px;
            margin: auto;
        }

        .game-card {
            position: relative;
            overflow: hidden;
            border-radius: 30px;
            padding: 35px;
            background: rgba(255, 255, 255, .07);
            border: 1px solid rgba(255, 255, 255, .13);
            backdrop-filter: blur(18px);
            box-shadow: 0 30px 80px rgba(0, 0, 0, .35);
        }

        .game-card::before {
            content: "";
            position: absolute;
            width: 300px;
            height: 300px;
            border-radius: 50%;
            background: rgba(0, 255, 170, .08);
            top: -160px;
            right: -100px;
        }

        /* HEADER */

        .game-heading {
            position: relative;
            z-index: 1;
        }

        .game-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 15px;
            border-radius: 50px;
            background: rgba(0, 255, 170, .1);
            border: 1px solid rgba(0, 255, 170, .2);
            color: #7dffd8;
            font-size: .85rem;
            font-weight: 700;
            margin-bottom: 15px;
        }

        .game-title {
            font-size: clamp(2rem, 5vw, 3.5rem);
            font-weight: 900;
            margin-bottom: 10px;
        }

        .game-subtitle {
            color: rgba(255, 255, 255, .65);
            margin-bottom: 30px;
        }

        /* STATS */

        .stats-row {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 15px;
            margin-bottom: 35px;
        }

        .stat-box {
            padding: 18px;
            border-radius: 18px;
            background: rgba(255, 255, 255, .06);
            border: 1px solid rgba(255, 255, 255, .08);
        }

        .stat-label {
            display: block;
            font-size: .75rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: rgba(255, 255, 255, .5);
            margin-bottom: 5px;
        }

        .stat-value {
            font-size: 1.5rem;
            font-weight: 800;
        }

        /* TIMER */

        .timer-wrapper {
            margin-bottom: 30px;
        }

        .timer-label {
            display: flex;
            justify-content: space-between;
            margin-bottom: 8px;
            font-size: .85rem;
            color: rgba(255, 255, 255, .65);
        }

        .timer-track {
            height: 8px;
            border-radius: 20px;
            background: rgba(255, 255, 255, .1);
            overflow: hidden;
        }

        .timer-bar {
            width: 100%;
            height: 100%;
            border-radius: 20px;
            background: #61f7bd;
            transition: width 1s linear;
        }

        /* QUESTION */

        .question-box {
            text-align: center;
            padding: 40px 25px;
            border-radius: 25px;
            background: rgba(0, 0, 0, .18);
            border: 1px solid rgba(255, 255, 255, .08);
        }

        .question-number {
            color: #7dffd8;
            font-size: .85rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 2px;
            margin-bottom: 20px;
        }

        .question {
            font-size: clamp(2rem, 7vw, 4rem);
            font-weight: 900;
            margin-bottom: 30px;
        }

        /* ANSWER */

        .answer-input {
            width: 100%;
            max-width: 400px;
            margin: auto;
            display: block;

            padding: 18px 20px;
            border-radius: 16px;

            border: 2px solid rgba(255, 255, 255, .1);
            background: rgba(255, 255, 255, .07);

            color: white;
            text-align: center;

            font-size: 1.5rem;
            font-weight: 700;

            outline: none;
        }

        .answer-input:focus {
            border-color: #61f7bd;
            box-shadow: 0 0 0 4px rgba(97, 247, 189, .08);
        }

        .answer-input::placeholder {
            color: rgba(255, 255, 255, .3);
        }

        .submit-btn {
            margin-top: 18px;
            border: none;
            border-radius: 15px;
            padding: 14px 35px;

            background: #61f7bd;
            color: #052018;

            font-weight: 800;
            font-size: 1rem;

            transition: .25s;
        }

        .submit-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(97, 247, 189, .2);
        }

        /* FEEDBACK */

        .feedback {
            min-height: 30px;
            margin-top: 20px;
            font-weight: 700;
        }

        .feedback.correct {
            color: #61f7bd;
        }

        .feedback.wrong {
            color: #ff8c8c;
        }

        /* RESULT */

        .result-screen {
            display: none;
            text-align: center;
            padding: 35px 20px;
        }

        .result-icon {
            font-size: 70px;
            margin-bottom: 15px;
        }

        .result-title {
            font-size: 2.5rem;
            font-weight: 900;
        }

        .final-score {
            font-size: 4rem;
            font-weight: 900;
            color: #61f7bd;
            margin: 15px 0;
        }

        .play-again-btn {
            border: none;
            border-radius: 14px;
            padding: 13px 25px;
            background: #61f7bd;
            color: #052018;
            font-weight: 800;
            margin-top: 15px;
        }

        @media (max-width: 700px) {

            .game-shell {
                padding: 15px;
            }

            .game-card {
                padding: 22px;
                border-radius: 22px;
            }

            .stats-row {
                grid-template-columns: 1fr;
            }

            .question-box {
                padding: 30px 15px;
            }

            .game-topbar {
                align-items: flex-start;
            }

            .brand span {
                display: none;
            }
        }
    </style>
</head>

<body>

<div class="game-shell">

    <!-- TOP BAR -->
    <div class="game-topbar">

        <div class="brand">
            <div class="brand-icon">
                🧮
            </div>

            <span>EcoQuest</span>
        </div>

        <a href="{{ route('student.dashboard') }}"
           class="quit-btn">
            <i class="bi bi-arrow-left"></i>
            Quit Game
        </a>

    </div>


    <!-- GAME -->
    <div class="game-container">

        <div class="game-card">

            <!-- GAME HEADER -->

            <div class="game-heading">

                <div class="game-badge">
                    <i class="bi bi-lightning-charge-fill"></i>
                    SPEED CHALLENGE
                </div>

                <h1 class="game-title">
                    Math Speed Challenge
                </h1>

                <p class="game-subtitle">
                    Solve as many problems as you can before the timer runs out.
                </p>

            </div>


            <!-- STATS -->

            <div class="stats-row">

                <div class="stat-box">

                    <span class="stat-label">
                        Question
                    </span>

                    <span class="stat-value">
                        <span id="currentQuestion">1</span>/10
                    </span>

                </div>


                <div class="stat-box">

                    <span class="stat-label">
                        Score
                    </span>

                    <span class="stat-value">
                        <span id="score">0</span>
                    </span>

                </div>


                <div class="stat-box">

                    <span class="stat-label">
                        Streak
                    </span>

                    <span class="stat-value">
                        🔥 <span id="streak">0</span>
                    </span>

                </div>

            </div>


            <!-- TIMER -->

            <div class="timer-wrapper">

                <div class="timer-label">

                    <span>
                        Time Remaining
                    </span>

                    <strong>
                        <span id="timer">60</span>s
                    </strong>

                </div>

                <div class="timer-track">

                    <div
                        class="timer-bar"
                        id="timerBar">
                    </div>

                </div>

            </div>


            <!-- QUESTION AREA -->

            <div
                class="question-box"
                id="gameArea">

                <div class="question-number">
                    Question <span id="questionNumber">1</span>
                </div>

                <div
                    class="question"
                    id="question">
                    5 + 7 = ?
                </div>

                <input
                    type="number"
                    id="answer"
                    class="answer-input"
                    placeholder="Enter your answer"
                    autocomplete="off">

                <button
                    type="button"
                    class="submit-btn"
                    id="submitBtn">

                    Submit Answer
                    <i class="bi bi-arrow-right"></i>

                </button>

                <div
                    id="feedback"
                    class="feedback">
                </div>

            </div>


            <!-- RESULT -->

            <div
                class="result-screen"
                id="resultScreen">

                <div class="result-icon">
                    🏆
                </div>

                <h2 class="result-title">
                    Challenge Complete!
                </h2>

                <p class="text-white-50">
                    Your final score
                </p>

                <div
                    class="final-score"
                    id="finalScore">
                    0
                </div>

                <p id="resultMessage">
                    Great work!
                </p>

                <button
                    type="button"
                    class="play-again-btn"
                    onclick="location.reload()">

                    <i class="bi bi-arrow-repeat"></i>
                    Play Again

                </button>

            </div>

        </div>

    </div>

</div>


<script>

    /*
    |--------------------------------------------------------------------------
    | GAME SETTINGS
    |--------------------------------------------------------------------------
    */

    const totalQuestions = 10;

    const gameTime = 60;


    /*
    |--------------------------------------------------------------------------
    | GAME STATE
    |--------------------------------------------------------------------------
    */

    let currentQuestionIndex = 0;

    let score = 0;

    let streak = 0;

    let timeLeft = gameTime;

    let timerInterval;

    let currentAnswer;


    /*
    |--------------------------------------------------------------------------
    | ELEMENTS
    |--------------------------------------------------------------------------
    */

    const questionElement =
        document.getElementById('question');

    const questionNumberElement =
        document.getElementById('questionNumber');

    const currentQuestionElement =
        document.getElementById('currentQuestion');

    const scoreElement =
        document.getElementById('score');

    const streakElement =
        document.getElementById('streak');

    const answerElement =
        document.getElementById('answer');

    const feedbackElement =
        document.getElementById('feedback');

    const timerElement =
        document.getElementById('timer');

    const timerBar =
        document.getElementById('timerBar');

    const submitButton =
        document.getElementById('submitBtn');

    const gameArea =
        document.getElementById('gameArea');

    const resultScreen =
        document.getElementById('resultScreen');

    const finalScoreElement =
        document.getElementById('finalScore');

    const resultMessage =
        document.getElementById('resultMessage');


    /*
    |--------------------------------------------------------------------------
    | GENERATE QUESTION
    |--------------------------------------------------------------------------
    */

    function generateQuestion() {

        const operations = [
            'addition',
            'subtraction',
            'multiplication'
        ];

        const operation =
            operations[
                Math.floor(
                    Math.random() * operations.length
                )
            ];


        let firstNumber;

        let secondNumber;


        if (operation === 'addition') {

            firstNumber =
                Math.floor(Math.random() * 50) + 1;

            secondNumber =
                Math.floor(Math.random() * 50) + 1;

            currentAnswer =
                firstNumber + secondNumber;

            questionElement.textContent =
                `${firstNumber} + ${secondNumber} = ?`;

        }


        else if (operation === 'subtraction') {

            firstNumber =
                Math.floor(Math.random() * 50) + 20;

            secondNumber =
                Math.floor(Math.random() * 20) + 1;

            currentAnswer =
                firstNumber - secondNumber;

            questionElement.textContent =
                `${firstNumber} - ${secondNumber} = ?`;

        }


        else {

            firstNumber =
                Math.floor(Math.random() * 10) + 1;

            secondNumber =
                Math.floor(Math.random() * 10) + 1;

            currentAnswer =
                firstNumber * secondNumber;

            questionElement.textContent =
                `${firstNumber} × ${secondNumber} = ?`;

        }


        questionNumberElement.textContent =
            currentQuestionIndex + 1;

        currentQuestionElement.textContent =
            currentQuestionIndex + 1;

        answerElement.value = '';

        answerElement.focus();

        feedbackElement.textContent = '';

        feedbackElement.className = 'feedback';
    }


    /*
    |--------------------------------------------------------------------------
    | SUBMIT ANSWER
    |--------------------------------------------------------------------------
    */

    function submitAnswer() {

        const userAnswer =
            Number(answerElement.value);


        if (
            answerElement.value.trim() === ''
        ) {
            feedbackElement.textContent =
                'Please enter an answer.';

            feedbackElement.className =
                'feedback wrong';

            return;
        }


        if (userAnswer === currentAnswer) {

            score += 10;

            streak++;

            feedbackElement.textContent =
                '✓ Correct! +10 points';

            feedbackElement.className =
                'feedback correct';

        }

        else {

            streak = 0;

            feedbackElement.textContent =
                `✗ Wrong! Correct answer was ${currentAnswer}`;

            feedbackElement.className =
                'feedback wrong';

        }


        scoreElement.textContent =
            score;

        streakElement.textContent =
            streak;


        currentQuestionIndex++;


        if (
            currentQuestionIndex >= totalQuestions
        ) {

            setTimeout(
                finishGame,
                700
            );

            return;
        }


        setTimeout(
            generateQuestion,
            700
        );
    }


    /*
    |--------------------------------------------------------------------------
    | TIMER
    |--------------------------------------------------------------------------
    */

    function startTimer() {

        timerInterval =
            setInterval(function () {

                timeLeft--;

                timerElement.textContent =
                    timeLeft;


                const percentage =
                    (timeLeft / gameTime) * 100;

                timerBar.style.width =
                    percentage + '%';


                if (timeLeft <= 0) {

                    clearInterval(timerInterval);

                    finishGame();
                }

            }, 1000);
    }


    /*
    |--------------------------------------------------------------------------
    | FINISH GAME
    |--------------------------------------------------------------------------
    */

    function finishGame() {

        clearInterval(timerInterval);

        gameArea.style.display =
            'none';

        resultScreen.style.display =
            'block';

        finalScoreElement.textContent =
            score;


        if (score >= 80) {

            resultMessage.textContent =
                '🔥 Amazing! You are a Math Speed Master!';

        }

        else if (score >= 50) {

            resultMessage.textContent =
                '👏 Great job! Keep practicing!';

        }

        else {

            resultMessage.textContent =
                '💪 Good attempt! Try again and improve your score!';

        }
    }


    /*
    |--------------------------------------------------------------------------
    | BUTTON
    |--------------------------------------------------------------------------
    */

    submitButton.addEventListener(
        'click',
        submitAnswer
    );


    /*
    |--------------------------------------------------------------------------
    | ENTER KEY
    |--------------------------------------------------------------------------
    */

    answerElement.addEventListener(
        'keydown',
        function(event) {

            if (event.key === 'Enter') {

                submitAnswer();

            }

        }
    );


    /*
    |--------------------------------------------------------------------------
    | START GAME
    |--------------------------------------------------------------------------
    */

    generateQuestion();

    startTimer();

</script>

</body>
</html>