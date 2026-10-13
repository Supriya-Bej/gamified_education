@extends('student.layouts.app')

@section('title', $learningMaterial->title . ' | Learning Materials')

@section('head')
<style>
    .material-page {
        min-height: 100vh;
        padding: 28px 30px 60px;
        color: var(--text-main);
    }

    .material-hero,
    .material-panel {
        background: linear-gradient(145deg, var(--surface), var(--surface-alt));
        border: 1px solid var(--border);
        border-radius: 22px;
        box-shadow: 0 14px 34px rgba(0, 0, 0, 0.14);
    }

    .material-hero {
        position: relative;
        overflow: hidden;
        margin-bottom: 24px;
    }

    .material-hero::after {
        content: '';
        position: absolute;
        width: 260px;
        height: 260px;
        right: -90px;
        top: -130px;
        border-radius: 50%;
        background: var(--glow);
        opacity: 0.18;
        pointer-events: none;
    }

    .material-hero-image {
        display: block;
        width: 100%;
        max-height: 360px;
        object-fit: cover;
    }

    .material-hero-copy {
        position: relative;
        z-index: 1;
        padding: 30px clamp(22px, 4vw, 44px) 34px;
    }

    .material-back-link {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 22px;
        color: var(--text-muted);
        font-size: 0.88rem;
        font-weight: 700;
        text-decoration: none;
        transition: color 0.2s ease;
    }

    .material-back-link:hover,
    .material-back-link:focus-visible {
        color: var(--secondary);
    }

    .material-eyebrow {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 14px;
        color: var(--secondary);
        font-size: 0.76rem;
        font-weight: 800;
        letter-spacing: 0.12em;
        text-transform: uppercase;
    }

    .material-meta {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        margin-bottom: 16px;
    }

    .material-pill {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        max-width: 100%;
        padding: 8px 13px;
        border: 1px solid var(--border);
        border-radius: 999px;
        background: rgba(255, 255, 255, 0.04);
        color: var(--text-main);
        font-size: 0.8rem;
        font-weight: 700;
        overflow-wrap: anywhere;
    }

    .material-pill-topic {
        border-color: var(--glow);
        color: var(--secondary);
    }

    .material-title {
        max-width: 900px;
        margin: 0 0 12px;
        color: var(--text-main);
        font-family: var(--font-display);
        font-size: clamp(1.8rem, 4vw, 2.7rem);
        font-weight: 800;
        line-height: 1.15;
        overflow-wrap: anywhere;
    }

    .material-description {
        max-width: 760px;
        margin: 0;
        color: var(--text-muted);
        font-size: 1rem;
        line-height: 1.75;
        overflow-wrap: anywhere;
    }

    .material-layout {
        display: grid;
        grid-template-columns: minmax(0, 1fr) 310px;
        align-items: start;
        gap: 22px;
    }

    .material-panel {
        min-width: 0;
        padding: clamp(22px, 4vw, 36px);
    }

    .material-feedback {
        margin: 0 0 18px;
        padding: 14px 17px;
        border: 1px solid rgba(34, 197, 94, 0.35);
        border-radius: 14px;
        background: rgba(34, 197, 94, 0.1);
        color: var(--text-main);
        font-weight: 700;
    }

    .material-feedback-error {
        border-color: rgba(248, 113, 113, 0.4);
        background: rgba(248, 113, 113, 0.1);
    }

    .material-quiz {
        margin-top: 22px;
    }

    .material-quiz-question {
        margin: 0 0 20px;
        padding: 18px;
        border: 1px solid var(--border);
        border-radius: 16px;
        background: rgba(255, 255, 255, 0.025);
    }

    .material-quiz-question legend {
        float: none;
        width: auto;
        margin-bottom: 12px;
        color: var(--text-main);
        font-size: 1rem;
        font-weight: 800;
    }

    .material-quiz-option {
        display: flex;
        align-items: flex-start;
        gap: 10px;
        padding: 10px 12px;
        border-radius: 10px;
        color: var(--text-muted);
        cursor: pointer;
    }

    .material-quiz-option:hover {
        background: rgba(255, 255, 255, 0.045);
        color: var(--text-main);
    }

    .material-quiz-option input {
        margin-top: 5px;
        accent-color: var(--secondary);
    }

    .material-quiz-result {
        margin-bottom: 18px;
        padding: 14px 17px;
        border: 1px solid var(--border);
        border-radius: 14px;
        background: rgba(255, 255, 255, 0.04);
        color: var(--text-main);
        font-weight: 700;
    }

    .material-quiz-result-passed {
        border-color: rgba(34, 197, 94, 0.4);
        background: rgba(34, 197, 94, 0.1);
    }

    .material-section-heading {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 22px;
    }

    .material-section-icon {
        display: inline-flex;
        width: 44px;
        height: 44px;
        flex: 0 0 44px;
        align-items: center;
        justify-content: center;
        border-radius: 14px;
        background: linear-gradient(135deg, var(--primary), var(--secondary));
        color: #fff;
        font-size: 1.15rem;
        box-shadow: 0 6px 18px var(--glow);
    }

    .material-section-heading h2 {
        margin: 0;
        color: var(--text-main);
        font-family: var(--font-display);
        font-size: 1.2rem;
        font-weight: 800;
    }

    .material-section-heading p {
        margin: 3px 0 0;
        color: var(--text-muted);
        font-size: 0.82rem;
    }

    .material-reading {
        color: var(--text-main);
        font-size: 1.04rem;
        line-height: 1.95;
        overflow-wrap: anywhere;
        white-space: pre-line;
    }

    .material-reading::selection {
        background: var(--primary);
        color: #fff;
    }

    .material-sidebar {
        display: grid;
        gap: 18px;
    }

    .material-sidebar h2 {
        margin: 0 0 16px;
        color: var(--text-main);
        font-family: var(--font-display);
        font-size: 1.04rem;
        font-weight: 800;
    }

    .material-topic-name {
        margin-bottom: 8px;
        color: var(--secondary);
        font-size: 1rem;
        font-weight: 800;
        overflow-wrap: anywhere;
    }

    .material-topic-description,
    .material-helper-text {
        margin: 0;
        color: var(--text-muted);
        font-size: 0.85rem;
        line-height: 1.65;
        overflow-wrap: anywhere;
    }

    .material-difficulty {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .material-difficulty-icon {
        display: inline-flex;
        width: 42px;
        height: 42px;
        flex: 0 0 42px;
        align-items: center;
        justify-content: center;
        border-radius: 13px;
        background: rgba(255, 255, 255, 0.06);
        color: var(--secondary);
        font-size: 1.1rem;
    }

    .material-difficulty-label {
        margin-bottom: 3px;
        color: var(--text-muted);
        font-size: 0.75rem;
        font-weight: 700;
        letter-spacing: 0.06em;
        text-transform: uppercase;
    }

    .material-difficulty-value {
        color: var(--text-main);
        font-weight: 800;
    }

    .material-completion-icon {
        display: inline-flex;
        width: 48px;
        height: 48px;
        align-items: center;
        justify-content: center;
        margin-bottom: 14px;
        border-radius: 16px;
        background: rgba(34, 197, 94, 0.14);
        color: #4ade80;
        font-size: 1.35rem;
    }

    .material-completion-title {
        margin: 0 0 8px;
        color: var(--text-main);
        font-family: var(--font-display);
        font-size: 1.05rem;
        font-weight: 800;
    }

    .material-complete-button {
        width: 100%;
        margin-top: 18px;
        padding: 12px 16px;
        border: 0;
        border-radius: 12px;
        background: linear-gradient(135deg, var(--primary), var(--secondary));
        color: #fff;
        font-weight: 800;
        box-shadow: 0 6px 18px var(--glow);
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .material-complete-button:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 24px var(--glow);
    }

    .material-complete-button:focus-visible {
        outline: 3px solid var(--text-main);
        outline-offset: 3px;
    }

    @media (max-width: 991px) {
        .material-layout {
            grid-template-columns: minmax(0, 1fr) 270px;
        }
    }

    @media (max-width: 767px) {
        .material-page {
            padding: 18px 14px 40px;
        }

        .material-layout {
            grid-template-columns: 1fr;
        }

        .material-sidebar {
            grid-row: auto;
        }

        .material-hero-image {
            max-height: 240px;
        }
    }

    @media (prefers-reduced-motion: reduce) {
        .material-back-link,
        .material-complete-button {
            transition: none;
        }

        .material-complete-button:hover {
            transform: none;
        }
    }
</style>
@endsection

@section('content')
<div class="material-page">
    <a href="{{ route('student.learning-materials.index') }}" class="material-back-link">
        <i class="bi bi-arrow-left" aria-hidden="true"></i>
        <span>All learning materials</span>
    </a>

    @if(session('success'))
        <div class="material-feedback" role="status" aria-live="polite">
            <i class="bi bi-check-circle-fill me-2" aria-hidden="true"></i>
            {{ session('success') }}
        </div>
    @endif
    @if(session('info'))
        <div class="material-feedback" role="status" aria-live="polite">
            <i class="bi bi-info-circle-fill me-2" aria-hidden="true"></i>
            {{ session('info') }}
        </div>
    @endif
    @if(session('error'))
        <div class="material-feedback material-feedback-error" role="alert">
            <i class="bi bi-exclamation-circle-fill me-2" aria-hidden="true"></i>
            {{ session('error') }}
        </div>
    @endif
    @if($errors->any())
        <div class="material-feedback material-feedback-error" role="alert">
            Please answer every quiz question before submitting.
        </div>
    @endif

    <header class="material-hero">
        @if($learningMaterial->image)
            <img
                src="{{ asset('storage/' . $learningMaterial->image) }}"
                alt="{{ $learningMaterial->title }}"
                class="material-hero-image"
            >
        @endif

        <div class="material-hero-copy">
            <div class="material-eyebrow">
                <i class="bi bi-book-half" aria-hidden="true"></i>
                <span>Learning material</span>
            </div>

            <div class="material-meta" aria-label="Material details">
                <span class="material-pill material-pill-topic">
                    <i class="bi bi-bookmark-fill" aria-hidden="true"></i>
                    {{ $learningMaterial->topic }}
                </span>
                <span class="material-pill">
                    <i class="bi bi-layers-fill" aria-hidden="true"></i>
                    Level {{ $levelNumber }}
                </span>
                <span class="material-pill">
                    <i class="bi bi-bar-chart-fill" aria-hidden="true"></i>
                    {{ ucfirst($learningMaterial->difficulty) }} level
                </span>
            </div>

            <h1 class="material-title">{{ $learningMaterial->title }}</h1>

            @if($learningMaterial->description)
                <p class="material-description">{{ $learningMaterial->description }}</p>
            @else
                <p class="material-description">
                    Read through this lesson at your own pace, then mark it complete when you’re ready.
                </p>
            @endif
        </div>
    </header>

    <div class="material-layout">
        <article class="material-panel" aria-labelledby="material-content-heading">
            <div class="material-section-heading">
                <span class="material-section-icon" aria-hidden="true">
                    <i class="bi bi-journal-text"></i>
                </span>
                <div>
                    <h2 id="material-content-heading">Lesson</h2>
                    <p>Take your time and work through the ideas below.</p>
                </div>
            </div>

            <div class="material-reading">{{ $learningMaterial->content }}</div>
        </article>

        <aside class="material-sidebar" aria-label="Lesson information and progress">
            <section class="material-panel">
                <h2>About this topic</h2>
                <div class="material-topic-name">{{ $learningMaterial->topic }}</div>

                @if($topic && $topic->description)
                    <p class="material-topic-description">{{ $topic->description }}</p>
                @else
                    <p class="material-topic-description">
                        Explore this lesson to build your understanding of {{ $learningMaterial->topic }}.
                    </p>
                @endif
            </section>

            <section class="material-panel">
                <h2>Your progress</h2>

                @if($progress->completed_at)
                    <div class="material-completion-icon" aria-hidden="true">
                        <i class="bi bi-check-lg"></i>
                    </div>
                    <p class="material-completion-title">Lesson completed</p>
                    <p class="material-helper-text">
                        Completed {{ $progress->completed_at->format('M j, Y \a\t g:i A') }}.
                        You can revisit the lesson whenever you need a refresher.
                    </p>
                @else
                    <p class="material-helper-text">
                        Read the lesson, then complete it to reveal the quiz. Pass with at least 70% to unlock the next level.
                    </p>

                    <form method="POST" action="{{ route('student.learning-materials.complete', $learningMaterial) }}">
                        @csrf
                        <button type="submit" class="material-complete-button">
                            <i class="bi bi-check2-circle me-2" aria-hidden="true"></i>
                            Mark as completed
                        </button>
                    </form>
                @endif
            </section>

            <div class="material-panel">
                <div class="material-difficulty">
                    <span class="material-difficulty-icon" aria-hidden="true">
                        <i class="bi bi-speedometer2"></i>
                    </span>
                    <div>
                        <div class="material-difficulty-label">Difficulty</div>
                        <div class="material-difficulty-value">{{ ucfirst($learningMaterial->difficulty) }}</div>
                    </div>
                </div>
            </div>
        </aside>
    </div>

    @if($progress->completed_at)
        <section class="material-panel material-quiz" aria-labelledby="lesson-quiz-heading">
            <div class="material-section-heading">
                <span class="material-section-icon" aria-hidden="true">
                    <i class="bi bi-patch-question-fill"></i>
                </span>
                <div>
                    <h2 id="lesson-quiz-heading">Lesson Quiz</h2>
                    <p>Answer at least 70% correctly to unlock the next level. You can retry if needed.</p>
                </div>
            </div>

            @if(session('quiz_result'))
                <div class="material-quiz-result {{ session('quiz_result.passed') ? 'material-quiz-result-passed' : '' }}"
                    role="status" aria-live="polite">
                    @if(session('quiz_result.passed'))
                        Quiz passed! You scored {{ session('quiz_result.score') }}% ({{ session('quiz_result.correct') }}/{{ session('quiz_result.total') }}). The next level is unlocked.
                    @else
                        You scored {{ session('quiz_result.score') }}% ({{ session('quiz_result.correct') }}/{{ session('quiz_result.total') }}). Review the lesson and try again; 70% is required.
                    @endif
                </div>
            @endif

            @if($progress->quiz_data && is_array($progress->quiz_data['questions'] ?? null))
                @if($progress->quiz_completed_at)
                    <p class="material-helper-text mb-3">
                        Quiz passed with {{ $progress->quiz_score }}%. You may revisit this lesson; your next level remains unlocked.
                    </p>
                    @if($nextLearningMaterial)
                        <a href="{{ route('student.learning-materials.show', $nextLearningMaterial) }}"
                            class="material-complete-button d-inline-flex align-items-center justify-content-center text-decoration-none">
                            Continue to Level {{ $levelNumber + 1 }}
                            <i class="bi bi-arrow-right ms-2" aria-hidden="true"></i>
                        </a>
                    @else
                        <p class="material-helper-text mb-0">You completed the final published lesson in this topic.</p>
                    @endif
                @else
                    <form method="POST" action="{{ route('student.learning-materials.quiz.submit', $learningMaterial) }}">
                        @csrf
                        @foreach($progress->quiz_data['questions'] as $questionIndex => $question)
                            <fieldset class="material-quiz-question">
                                <legend>{{ $questionIndex + 1 }}. {{ $question['question'] }}</legend>
                                @foreach($question['options'] as $option)
                                    <label class="material-quiz-option">
                                        <input
                                            type="radio"
                                            name="answers[{{ $questionIndex }}]"
                                            value="{{ $option }}"
                                            required
                                            {{ old("answers.{$questionIndex}") === $option ? 'checked' : '' }}
                                        >
                                        <span>{{ $option }}</span>
                                    </label>
                                @endforeach
                            </fieldset>
                        @endforeach
                        <button type="submit" class="material-complete-button">
                            <i class="bi bi-check2-circle me-2" aria-hidden="true"></i>
                            Submit quiz
                        </button>
                    </form>
                @endif
            @else
                <p class="material-helper-text">
                    Your lesson is complete, but its quiz is not ready yet. Generate it from this lesson content to continue.
                </p>
                <form method="POST" action="{{ route('student.learning-materials.quiz.generate', $learningMaterial) }}">
                    @csrf
                    <button type="submit" class="material-complete-button">
                        <i class="bi bi-arrow-repeat me-2" aria-hidden="true"></i>
                        Generate quiz
                    </button>
                </form>
            @endif
        </section>
    @endif
</div>
@endsection
