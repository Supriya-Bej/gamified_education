<?php

namespace App\Services;

use App\Models\Certificate;
use App\Models\LearningTask;
use App\Models\UserRegister;
use Illuminate\Support\Str;

class InterestGameProgressionService
{
    public const TASKS_PER_LEVEL = 25;

    public const LEVELS = ['beginner', 'intermediate', 'advanced'];

    /**
     * @return array{
     *     interest: string,
     *     levels: array<string, array{completed: int, total: int}>,
     *     active_difficulty: ?string,
     *     active_completed: int,
     *     active_total: int,
     *     course_complete: bool
     * }
     */
    public function stateFor(int $userId, string $interest): array
    {
        $interest = strtolower(trim($interest));
        $tasks = LearningTask::query()
            ->where('user_id', $userId)
            ->whereRaw('LOWER(category) = ?', [$interest])
            ->whereIn('status', ['pending', 'completed'])
            ->get(['difficulty', 'status']);

        $levels = [];
        foreach (self::LEVELS as $difficulty) {
            $levelTasks = $tasks->where('difficulty', $difficulty);
            $levels[$difficulty] = [
                'completed' => $levelTasks->where('status', 'completed')->count(),
                'total' => $levelTasks->count(),
            ];
        }

        $certificate = Certificate::query()
            ->where('user_id', $userId)
            ->where('interest', $interest)
            ->first(['status']);

        $activeDifficulty = null;
        foreach (self::LEVELS as $difficulty) {
            if ($levels[$difficulty]['completed'] < self::TASKS_PER_LEVEL) {
                $activeDifficulty = $difficulty;
                break;
            }
        }

        return [
            'interest' => $interest,
            'levels' => $levels,
            'active_difficulty' => $activeDifficulty,
            'active_completed' => $activeDifficulty
                ? $levels[$activeDifficulty]['completed']
                : self::TASKS_PER_LEVEL,
            'active_total' => $activeDifficulty
                ? $levels[$activeDifficulty]['total']
                : self::TASKS_PER_LEVEL,
            'course_complete' => $activeDifficulty === null,
            'certificate_status' => $certificate?->status,
        ];
    }

    public function issueCertificateIfCourseComplete(
        UserRegister $student,
        LearningTask $finalTask
    ): ?Certificate {
        $interest = strtolower(trim((string) $finalTask->category));
        if ($interest === '') {
            return null;
        }

        $state = $this->stateFor((int) $student->id, $interest);
        if (! $state['course_complete']) {
            return null;
        }

        $certificate = Certificate::firstOrCreate(
            [
                'user_id' => $student->id,
                'interest' => $interest,
            ],
            [
                'task_id' => $finalTask->id,
                'issued_by' => null,
                'quest_attempt_id' => null,
                'certificate_number' => 'EQ-'.Str::upper(Str::random(12)),
                'title' => Str::title(str_replace('_', ' ', $interest)).' Mastery Certificate',
                'status' => 'pending_review',
                'issued_at' => null,
            ]
        );

        return $certificate->wasRecentlyCreated ? $certificate : null;
    }
}
