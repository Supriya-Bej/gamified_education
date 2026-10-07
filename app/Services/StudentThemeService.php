<?php

namespace App\Services;

use App\Models\UserRegister;

class StudentThemeService
{
    /**
     * Get the active theme for the logged-in student.
     *
     * Theme priority:
     *
     * 1. Dashboard focus selected by student
     * 2. Previously selected focus stored in session
     * 3. Single registered interest
     * 4. Chess if chess is one of multiple interests
     * 5. AI visual theme
     * 6. General theme
     */
    public function getTheme(
        UserRegister $user,
        ?string $focus = null
    ): array {
        /*
        |--------------------------------------------------------------------------
        | Get Student Preference
        |--------------------------------------------------------------------------
        */

        $preference = $user->preferences;

        /*
        |--------------------------------------------------------------------------
        | If preference does not exist
        |--------------------------------------------------------------------------
        */

        if (!$preference) {
            return $this->generalTheme();
        }

        /*
        |--------------------------------------------------------------------------
        | Get Student Interests
        |--------------------------------------------------------------------------
        */

        $interests = [];

        if (is_array($preference->interests)) {
            $interests = array_values(
                array_filter(
                    $preference->interests,
                    fn ($item) =>
                        is_string($item) &&
                        trim($item) !== ''
                )
            );
        }

        /*
        |--------------------------------------------------------------------------
        | If interests are stored as a string
        |--------------------------------------------------------------------------
        */

        if (
            empty($interests) &&
            is_string($preference->interests) &&
            trim($preference->interests) !== ''
        ) {
            $interests = [
                trim($preference->interests)
            ];
        }

        /*
        |--------------------------------------------------------------------------
        | Get AI Profile
        |--------------------------------------------------------------------------
        */

        $aiProfile = is_array($preference->ai_profile)
            ? $preference->ai_profile
            : [];

        /*
        |--------------------------------------------------------------------------
        | Get Available Themes
        |--------------------------------------------------------------------------
        */

        $themes = config('learning_themes', []);

        /*
        |--------------------------------------------------------------------------
        | Interest → Theme Mapping
        |--------------------------------------------------------------------------
        |
        | Different words can represent the same theme.
        |
        */

        $themeAliases = [

            // Chess
            'chess' => 'chess',

            // Technology
            'coding' => 'technology',
            'programming' => 'technology',
            'computer' => 'technology',
            'computers' => 'technology',
            'software' => 'technology',
            'technology' => 'technology',
            'web development' => 'technology',

            // Music
            'singing' => 'music',
            'song' => 'music',
            'songs' => 'music',
            'music' => 'music',
            'guitar' => 'music',
            'piano' => 'music',

            // Sports
            'football' => 'sports',
            'cricket' => 'sports',
            'basketball' => 'sports',
            'sports' => 'sports',

            // Mathematics
            'math' => 'mathematics',
            'mathematics' => 'mathematics',

            // Science
            'science' => 'science',
            'environment' => 'science',

            // Creative
            'drawing' => 'creative',
            'painting' => 'creative',
            'art' => 'creative',
            'art & design' => 'creative',

            // Reading
            'reading' => 'reading',
            'books' => 'reading',

            // Puzzles
            'puzzles' => 'chess',

            // Business
            'business' => 'business',

            // Gaming
            'gaming' => 'gaming',

            // General
            'gk' => 'general',
            'general knowledge' => 'general',
        ];

        /*
        |--------------------------------------------------------------------------
        | Convert Interest Name → Theme Key
        |--------------------------------------------------------------------------
        */

        $mapInterestToTheme = function ($interestName) use (
            $themeAliases,
            $themes
        ): string {

            $clean = strtolower(
                trim((string) $interestName)
            );

            /*
            | Check alias first
            */

            $clean = $themeAliases[$clean] ?? $clean;

            /*
            | Make sure theme actually exists
            */

            return isset($themes[$clean])
                ? $clean
                : 'general';
        };

        /*
        |--------------------------------------------------------------------------
        | Dashboard Focus
        |--------------------------------------------------------------------------
        |
        | Example:
        |
        | /student/dashboard?focus=chess
        |
        | The selected focus is stored in session so that:
        |
        | Dashboard
        | Learning Materials
        | Progress
        | Badges
        | Profile
        | Games
        |
        | all use the same theme.
        |
        */

        $requestedFocus = is_string($focus)
            ? trim($focus)
            : '';

        /*
        |--------------------------------------------------------------------------
        | Save newly selected focus
        |--------------------------------------------------------------------------
        */

        if ($requestedFocus !== '') {

            $requestedTheme = $mapInterestToTheme(
                $requestedFocus
            );

            /*
            | Store the original interest name.
            |
            | Example:
            | coding → technology
            |
            | We store "coding", not only "technology".
            */

            session([
                'student_theme_focus' => $requestedFocus
            ]);

            /*
            | This variable is intentionally calculated here
            | so the requested theme is validated.
            */

            $themeKey = $requestedTheme;
        }

        /*
        |--------------------------------------------------------------------------
        | Get Previously Selected Focus
        |--------------------------------------------------------------------------
        */

        $storedFocus = session(
            'student_theme_focus'
        );

        /*
        |--------------------------------------------------------------------------
        | Determine Active Focus
        |--------------------------------------------------------------------------
        */

        $activeFocus = $requestedFocus !== ''
            ? $requestedFocus
            : $storedFocus;

        /*
        |--------------------------------------------------------------------------
        | Determine Final Theme
        |--------------------------------------------------------------------------
        */

        if (
            is_string($activeFocus) &&
            trim($activeFocus) !== ''
        ) {

            /*
            | Highest priority:
            | student's explicitly selected focus
            */

            $themeKey = $mapInterestToTheme(
                $activeFocus
            );

        } elseif (count($interests) === 1) {

            /*
            | If student has only one interest,
            | automatically use that interest.
            */

            $themeKey = $mapInterestToTheme(
                $interests[0]
            );

        } else {

            /*
            |--------------------------------------------------------------------------
            | Multiple Interests
            |--------------------------------------------------------------------------
            |
            | If Chess exists among multiple interests,
            | Chess gets priority as the default visual theme.
            |
            */

            $hasChessInterest = collect($interests)
                ->contains(
                    fn ($item) =>
                        stripos(
                            (string) $item,
                            'chess'
                        ) !== false
                );

            if ($hasChessInterest) {

                $themeKey = 'chess';

            } else {

                /*
                |--------------------------------------------------------------------------
                | Otherwise use Gemini AI's visual theme
                |--------------------------------------------------------------------------
                */

                $themeKey = $mapInterestToTheme(
                    $aiProfile['visual_theme'] ?? 'general'
                );
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Safety Check
        |--------------------------------------------------------------------------
        */

        if (!isset($themes[$themeKey])) {
            $themeKey = 'general';
        }

        /*
        |--------------------------------------------------------------------------
        | Get Final Theme Configuration
        |--------------------------------------------------------------------------
        */

        $themeConfig = $themes[$themeKey]
            ?? ($themes['general'] ?? []);

        /*
        |--------------------------------------------------------------------------
        | Return Everything Needed by Student Pages
        |--------------------------------------------------------------------------
        */

        return [

            /*
            | Example:
            | chess
            | technology
            | music
            */

            'themeKey' => $themeKey,

            /*
            | Complete configuration from
            | config/learning_themes.php
            */

            'themeConfig' => $themeConfig,

            /*
            | Human readable theme name
            */

            'theme' => $themeConfig['name']
                ?? 'General',

            /*
            | Theme icon
            */

            'themeIcon' => $themeConfig['icon']
                ?? '✦',

            /*
            | AI generated rank title if available
            | otherwise use theme's default rank
            */

            'rankTitle' =>
                $aiProfile['rank_title']
                ?? ($themeConfig['gamified_rank']
                ?? 'Explorer'),

            /*
            | Student difficulty / experience level
            */

            'difficulty' =>
                $preference->experience_level
                ?? 'beginner',

            /*
            | Theme-specific labels
            */

            'labels' =>
                $themeConfig['gamified_labels']
                ?? [],

            /*
            | Complete AI profile
            */

            'aiProfile' => $aiProfile,

            /*
            | Student's registered interests
            */

            'interests' => $interests,
        ];
    }


    /**
     * Default theme when student preference
     * is not available.
     */
    private function generalTheme(): array
    {
        /*
        |--------------------------------------------------------------------------
        | Get General Theme Configuration
        |--------------------------------------------------------------------------
        */

        $themeConfig = config(
            'learning_themes.general',
            []
        );

        /*
        |--------------------------------------------------------------------------
        | Return General Theme
        |--------------------------------------------------------------------------
        */

        return [

            'themeKey' => 'general',

            'themeConfig' => $themeConfig,

            'theme' =>
                $themeConfig['name']
                ?? 'General',

            'themeIcon' =>
                $themeConfig['icon']
                ?? '✦',

            'rankTitle' =>
                $themeConfig['gamified_rank']
                ?? 'Explorer',

            'difficulty' =>
                'beginner',

            'labels' =>
                $themeConfig['gamified_labels']
                ?? [],

            'aiProfile' => [],

            'interests' => [],
        ];
    }
}