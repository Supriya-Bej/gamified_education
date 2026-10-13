@php($questions = data_get($task->game_data, 'questions', []))
@include('student.games.partials.multiple-choice-quiz', [
    'gameLabel' => 'ART · COLOR THEORY QUEST',
    'gameIcon' => '🎨',
    'accentColor' => '#e879f9',
    'supportingCopy' => 'Explore color relationships and build your visual eye.',
])
