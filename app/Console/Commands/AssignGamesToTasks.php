<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\LearningTask;
use App\Models\Game;

class AssignGamesToTasks extends Command
{
    protected $signature = 'ecoquest:assign-games';

    protected $description = 'Assign matching games to learning tasks that do not have a game';

    public function handle()
    {
        $tasks = LearningTask::whereNull('game_id')->get();

        if ($tasks->isEmpty()) {
            $this->info('No tasks found with missing games.');

            return Command::SUCCESS;
        }

        $assigned = 0;

        foreach ($tasks as $task) {

            $category = strtolower(trim($task->category ?? ''));

            if (empty($category)) {
                continue;
            }

            $game = Game::whereRaw(
                'LOWER(category) = ?',
                [$category]
            )
            ->inRandomOrder()
            ->first();

            if ($game) {

                $task->update([
                    'game_id' => $game->id,
                ]);

                $assigned++;

                $this->info(
                    "Task #{$task->id} → Game #{$game->id} ({$game->name})"
                );
            }
        }

        $this->info("Successfully assigned games to {$assigned} task(s).");

        return Command::SUCCESS;
    }
}