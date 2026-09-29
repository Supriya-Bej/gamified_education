<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>{{ $task->title }} | LearnQuest</title>

    <link rel="stylesheet"
        href="{{ asset('Asset/Bootstrap-5/css/bootstrap.min.css') }}">

    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        body {
            background: #f7f7fb;
            font-family: Arial, sans-serif;
        }

        .topbar {
            background: white;
            border-bottom: 1px solid #eee;
            padding: 15px 0;
        }

        .brand {
            font-size: 24px;
            font-weight: 800;
            color: #6c5ce7;
            text-decoration: none;
        }

        .task-container {
            max-width: 850px;
            margin: 50px auto;
        }

        .task-card {
            background: white;
            border-radius: 25px;
            padding: 40px;
            border: 1px solid #ecebf5;
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.06);
        }

        .task-icon {
            width: 70px;
            height: 70px;
            border-radius: 20px;
            background: #eeeaff;
            color: #6c5ce7;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 32px;
        }

        .difficulty {
            display: inline-block;
            padding: 6px 13px;
            border-radius: 20px;
            background: #eafaf2;
            color: #159957;
            font-size: 13px;
            font-weight: 700;
        }

        .xp {
            display: inline-block;
            padding: 6px 13px;
            border-radius: 20px;
            background: #fff4d6;
            color: #a86f00;
            font-size: 13px;
            font-weight: 700;
        }

        .question-box {
            background: #f8f7ff;
            border-radius: 18px;
            padding: 25px;
            margin-top: 30px;
        }

        textarea {
            min-height: 150px;
            resize: vertical;
        }
    </style>

</head>

<body>


    <div class="topbar">

        <div class="container">

            <div class="d-flex justify-content-between align-items-center">

                <a href="{{ route('user.dashboard') }}" class="brand">
                    LearnQuest
                </a>

                <a href="{{ route('user.dashboard') }}"
                    class="btn btn-outline-primary btn-sm rounded-pill">

                    <i class="bi bi-arrow-left"></i>

                    Dashboard

                </a>

            </div>

        </div>

    </div>


    <div class="container">

        <div class="task-container">

            <div class="task-card">
                @if(session('success'))

                <div class="alert alert-success rounded-4">

                    <i class="bi bi-check-circle me-2"></i>

                    {{ session('success') }}

                </div>

                @endif


                <!-- Error message -->
                @if(session('error'))

                <div class="alert alert-warning rounded-4">

                    <i class="bi bi-exclamation-circle me-2"></i>

                    {{ session('error') }}

                </div>

                @endif


                <div class="task-icon">

                    <i class="bi bi-journal-code"></i>

                </div>


                <div class="mt-4">

                    <span class="difficulty">

                        {{ ucfirst($task->difficulty) }}

                    </span>

                    <span class="xp ms-2">

                        +{{ $task->xp }} XP

                    </span>

                </div>


                <h1 class="fw-bold mt-3">

                    {{ $task->title }}

                </h1>

                @if($completed)

                <div class="alert alert-success rounded-4 mt-4">

                    <i class="bi bi-check-circle-fill me-2"></i>

                    You have already completed this task.

                </div>

                @else

                <div class="question-box">

                    <h5 class="fw-bold">
                        Your Challenge 🧠
                    </h5>

                    <p class="text-muted">
                        Explain your solution clearly.
                        Think step by step before submitting your answer.
                    </p>

                    <form
                        action="{{ route('student.task.submit', $task->id) }}"
                        method="POST">

                        @csrf

                        <textarea
                            class="form-control"
                            name="answer"
                            placeholder="Write your answer here..."
                            required></textarea>

                        <button
                            type="submit"
                            class="btn btn-primary rounded-pill px-4 mt-3">

                            Submit Answer

                            <i class="bi bi-send ms-2"></i>

                        </button>

                    </form>

                </div>

                @endif


                <p class="text-muted">

                    {{ $task->description }}

                </p>

            </div>
        </div>
    </div>


    <script src="{{ asset('Asset/Bootstrap-5/js/bootstrap.bundle.min.js') }}"></script>

</body>

</html>