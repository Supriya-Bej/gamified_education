@extends('quest.layout')
@section('title', 'Quiz')

@section('content')
    <h2>{{ $task->title }}: Quiz</h2>
    <p class="text-secondary">Pass korte 70% lagbe.</p>

    <form method="POST" action="{{ route('student.quest.submit', $task->id) }}">
        @csrf

        @foreach($questions as $i => $q)
            <div class="q-card">
                <strong>Q{{ $i + 1 }}. {{ $q['question'] }}</strong>

                @foreach($q['options'] as $j => $opt)
                    <div class="form-check mt-2">
                        <input class="form-check-input" type="radio"
                               name="answers[{{ $i }}]" value="{{ $j }}"
                               id="q{{ $i }}o{{ $j }}" required>
                        <label class="form-check-label" for="q{{ $i }}o{{ $j }}">{{ $opt }}</label>
                    </div>
                @endforeach
            </div>
        @endforeach

        <div class="d-flex gap-2">
            <a href="{{ route('student.quest.show', $task->id) }}" class="btn btn-outline-secondary">← Lesson</a>
            <button type="submit" class="btn btn-primary">Submit Quiz</button>
        </div>
    </form>
@endsection