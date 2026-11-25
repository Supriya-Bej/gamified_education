<?php

namespace App\Services;

use App\Models\SiteSetting;

class AIVerificationRules
{
    public function current(): array
    {
        $savedRules = SiteSetting::query()
            ->where('key', 'ai_verification')
            ->first()
            ?->value ?? [];

        return array_replace(config('ai_verification.defaults'), $savedRules);
    }

    public function save(array $rules): void
    {
        SiteSetting::query()->updateOrCreate(
            ['key' => 'ai_verification'],
            ['value' => $rules],
        );
    }
}
