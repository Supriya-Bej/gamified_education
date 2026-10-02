@extends('quest.layout')
@section('title', 'Result')

@section('content')
    @php
        $quiz = $content->quiz;
        $answers = $attempt->answers ?? [];
    @endphp

    <h2>{{ $attempt->passed ? '🎉 Quest Complete!' : 'Ektu-r jonno hoyni, abar chesta koro' }}</h2>

    <p class="fs-4 mb-1">
        Score: {{ $attempt->score }}% ({{ $attempt->correct_count }}/{{ $attempt->total }})
    </p>

    @if($attempt->passed)
        @if($attempt->xp_awarded > 0)
            <p class="text-success fs-5">+{{ $attempt->xp_awarded }} XP earned</p>
        @endif
    @else
        <p class="text-warning">Pass korte 70% lagbe. Lesson ta abar ekbar poro.</p>
    @endif

    <hr>

    @foreach($quiz as $i => $q)
        @php
            $mine = $answers[$i] ?? null;
            $ok = $mine !== null && (int) $mine === (int) $q['answer'];
        @endphp

        <div class="q-card">
            <strong>Q{{ $i + 1 }}. {{ $q['question'] }}</strong>
            <div class="{{ $ok ? 'text-success' : 'text-danger' }}">
                {{ $ok ? '✅ Sothik' : '❌ Bhul' }}
            </div>

            @if($attempt->passed)
                <div class="small text-secondary mt-1">
                    Sothik uttor: {{ $q['options'][$q['answer']] }}. {{ $q['explanation'] }}
                </div>
            @endif
        </div>
    @endforeach

    <div class="d-flex gap-2 mt-3">
        @if($attempt->passed)
            <a href="{{ route('student.dashboard') }}" class="btn btn-primary">Back to Dashboard</a>
        @else
            <a href="{{ route('student.quest.show', $task->id) }}" class="btn btn-outline-secondary">Lesson abar poro</a>
            <a href="{{ route('student.quest.quiz', $task->id) }}" class="btn btn-primary">Retry Quiz</a>
        @endif
    </div>
@endsection