@extends('quest.layout')
@section('title', $task->title)

@section('content')
    <span class="badge bg-secondary text-capitalize">{{ $task->category }} · {{ $task->difficulty }}</span>
    <h2 class="mt-2">{{ $task->title }}</h2>
    <p class="text-secondary">{{ $task->description }}</p>
    <hr>

    <div style="line-height:1.8;">
        {!! nl2br(e($content->lesson)) !!}
    </div>

    <div class="d-flex gap-2 mt-4">
        <a href="{{ route('student.dashboard') }}" class="btn btn-outline-secondary">Back</a>
        <a href="{{ route('student.quest.quiz', $task->id) }}" class="btn btn-primary">
            Ami ready, Quiz dei →
        </a>
    </div>
@endsection