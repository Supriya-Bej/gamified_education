<?php

namespace App\Services;

use App\Models\LearningMaterial;
use App\Models\LearningMaterialProgress;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Str;

class LearningMaterialProgressionService
{
    public function isUnlocked(LearningMaterial $material, int $userId): bool
    {
        $previousIds = $this->previousLessons($material)
            ->pluck('id')
            ->all();

        if ($previousIds === []) {
            return true;
        }

        $passedCount = LearningMaterialProgress::query()
            ->where('user_id', $userId)
            ->whereIn('learning_material_id', $previousIds)
            ->whereNotNull('quiz_completed_at')
            ->count();

        return $passedCount === count($previousIds);
    }

    public function blockedBy(LearningMaterial $material, int $userId): ?LearningMaterial
    {
        $previousLessons = $this->previousLessons($material);
        if ($previousLessons->isEmpty()) {
            return null;
        }

        $passedIds = LearningMaterialProgress::query()
            ->where('user_id', $userId)
            ->whereIn('learning_material_id', $previousLessons->modelKeys())
            ->whereNotNull('quiz_completed_at')
            ->pluck('learning_material_id')
            ->map(fn ($id) => (int) $id)
            ->all();

        return $previousLessons->first(
            fn (LearningMaterial $previous) => ! in_array((int) $previous->id, $passedIds, true)
        );
    }

    /**
     * @param  Collection<int, LearningMaterial>  $materials
     * @return array<int, int>
     */
    public function unlockedIds(Collection $materials, int $userId): array
    {
        return $materials
            ->filter(fn (LearningMaterial $material) => $this->isUnlocked($material, $userId))
            ->modelKeys();
    }

    /**
     * Find the first actionable lesson from the student's registered interests.
     *
     * @param  array<int, mixed>  $interests
     */
    public function nextForInterests(int $userId, array $interests): ?LearningMaterial
    {
        $materials = LearningMaterial::query()
            ->where('is_published', true)
            ->orderBy('id')
            ->get();
        $candidateMaterials = $materials->filter(
            fn (LearningMaterial $material) => $this->matchesInterests($material, $interests)
        );
        $completedQuizIds = LearningMaterialProgress::query()
            ->where('user_id', $userId)
            ->whereNotNull('quiz_completed_at')
            ->pluck('learning_material_id')
            ->map(fn ($id) => (int) $id)
            ->all();

        foreach ($candidateMaterials as $material) {
            if (
                ! in_array((int) $material->id, $completedQuizIds, true)
                && $this->isUnlocked($material, $userId)
            ) {
                return $material;
            }
        }

        return null;
    }

    public function levelNumber(LearningMaterial $material): int
    {
        return $this->previousLessons($material)->count() + 1;
    }

    public function nextLesson(LearningMaterial $material): ?LearningMaterial
    {
        return LearningMaterial::query()
            ->where('is_published', true)
            ->where('id', '>', $material->id)
            ->where(function ($query) use ($material) {
                $query->where('topic_id', $material->topic_id)
                    ->orWhere(function ($legacyQuery) use ($material) {
                        $legacyQuery->whereNull('topic_id')
                            ->where('topic', $material->topic);
                    });
            })
            ->orderBy('id')
            ->first();
    }

    private function previousLessons(LearningMaterial $material): Collection
    {
        return LearningMaterial::query()
            ->where('is_published', true)
            ->where('id', '<', $material->id)
            ->where(function ($query) use ($material) {
                if ($material->topic_id) {
                    $query->where('topic_id', $material->topic_id)
                        ->orWhere(function ($legacyQuery) use ($material) {
                            $legacyQuery->whereNull('topic_id')
                                ->where('topic', $material->topic);
                        });
                } else {
                    $query->whereNull('topic_id')->where('topic', $material->topic);
                }
            })
            ->orderBy('id')
            ->get();
    }

    /**
     * @param  array<int, mixed>  $interests
     */
    private function matchesInterests(LearningMaterial $material, array $interests): bool
    {
        $topic = $this->canonicalTopic((string) $material->topic);

        foreach ($interests as $interest) {
            if (! is_string($interest) || trim($interest) === '') {
                continue;
            }

            $canonicalInterest = $this->canonicalTopic($interest);
            if (
                $canonicalInterest === $topic
                || str_starts_with($topic, $canonicalInterest.'-')
            ) {
                return true;
            }
        }

        return false;
    }

    private function canonicalTopic(string $value): string
    {
        $slug = Str::slug($value);

        return match ($slug) {
            'programming', 'computer-science' => 'coding',
            'art-design', 'drawing', 'painting' => 'art',
            'math' => 'mathematics',
            default => $slug,
        };
    }
}
