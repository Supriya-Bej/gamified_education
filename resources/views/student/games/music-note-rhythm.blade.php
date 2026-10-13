@php($questions = data_get($task->game_data, 'questions', []))
@include('student.games.partials.multiple-choice-quiz', [
    'gameLabel' => 'MUSIC · NOTES & RHYTHM QUEST',
    'gameIcon' => '🎵',
    'accentColor' => '#38bdf8',
    'supportingCopy' => 'Train your ear for notes, rhythm, and musical patterns.',
])
