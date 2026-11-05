<?php

namespace App\Http\Controllers;

use App\Models\LearningMaterialProgress;
use App\Models\LearningTask;
use App\Models\StudentProgress;
use App\Services\BadgeService;
use App\Services\GeminiService;
use App\Services\InterestGameProgressionService;
use Illuminate\Support\Facades\Auth;

class StudentProgressController extends Controller
{
    public function index(
        BadgeService $badgeService,
        GeminiService $gemini,
        InterestGameProgressionService $interestProgression
    ) {
        $student = Auth::guard('student')->user();

        /* Student Progress */
        $progress = StudentProgress::firstOrCreate(
            [
                'user_id' => $student->id,
            ],
            [
                'total_xp' => 0,
                'level' => 1,
                'completed_tasks' => 0,
                'subject_xp' => [],
            ]
        );

        // Complete task count
        $completedQuestCount = LearningTask::where(
            'user_id',
            $student->id
        )
            ->where('status', 'completed')
            ->count();

        /* Completed Quests */
        $completedTasks = LearningTask::query()
            ->where('user_id', $student->id)
            ->where('status', 'completed')
            ->orderByDesc('updated_at')
            ->orderByDesc('id')
            ->paginate(8, ['*'], 'completed_page')
            ->withQueryString();

        $completedLearningMaterials = LearningMaterialProgress::with(
            'learningMaterial'
        )
            ->where('user_id', $student->id)
            ->whereHas('learningMaterial', fn ($query) => $query->where('is_published', true))
            ->orderByDesc('updated_at')
            ->orderByDesc('id')
            ->paginate(8, ['*'], 'materials_page')
            ->withQueryString();

        $badgeService->checkAndAward($student);

        $interests = $student->preferences?->interests ?? [];
        if (is_string($interests) && trim($interests) !== '') {
            $interests = [trim($interests)];
        }
        $courseProgress = $gemini->getPlayableGamesForInterests(
            is_array($interests) ? $interests : []
        )
            ->pluck('category')
            ->filter(fn ($category) => is_string($category) && trim($category) !== '')
            ->map(fn ($category) => strtolower(trim($category)))
            ->unique()
            ->mapWithKeys(fn (string $category) => [
                $category => $interestProgression->stateFor((int) $student->id, $category),
            ])
            ->all();

        /* XP Progress */
        $currentLevel = max(1, (int) $progress->level);
        $currentLevelXp = ($currentLevel - 1) * 100;
        $nextLevelXp = $currentLevel * 100;
        $xpIntoLevel = max(
            0,
            $progress->total_xp - $currentLevelXp
        );

        $xpNeededForLevel = 100;

        $xpPercentage = min(
            100,
            ($xpIntoLevel / $xpNeededForLevel) * 100
        );

        return view(
            'student.progress.index',
            compact(
                'progress',
                'completedTasks',
                'courseProgress',
                'completedLearningMaterials',
                'currentLevel',
                'currentLevelXp',
                'nextLevelXp',
                'xpIntoLevel',
                'xpNeededForLevel',
                'xpPercentage',
                'completedQuestCount',
            )
        );
    }
}
