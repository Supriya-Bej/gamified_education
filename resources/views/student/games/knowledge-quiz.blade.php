@php($questions = data_get($task->game_data, 'questions', []))
@include('student.games.partials.multiple-choice-quiz', [
    'gameLabel' => strtoupper(str_replace('_', ' ', $task->category)).' QUEST',
    'gameIcon' => '🧠',
    'accentColor' => '#38bdf8',
    'supportingCopy' => $game->description ?? 'Answer the questions to complete this learning quest.',
])
