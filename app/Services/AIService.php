<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class AIService
{
    public function generateProfile(array $studentData)
    {
        $prompt = "
You are an AI learning personalization system.

Analyze the student's information and create a personalized learning dashboard profile.

Student Information:

Education Level: {$studentData['education_level']}
Class/Semester: {$studentData['class_semester']}
Experience Level: {$studentData['experience_level']}
Interests: " . implode(', ', $studentData['interests']) . "
Learning Goal: {$studentData['learning_goal']}
Learning Preference: {$studentData['experience_preference']}

The most important factor is the student's INTERESTS.

Your job is to choose the visual theme that best represents the student's strongest interest.

Return ONLY valid JSON.

The JSON must contain exactly these fields:

{
    \"visual_theme\": \"\",
    \"dashboard_title\": \"\",
    \"theme_description\": \"\",
    \"primary_color\": \"\",
    \"secondary_color\": \"\",
    \"background_style\": \"\",
    \"icon_style\": \"\",
    \"difficulty\": \"\",
    \"motivational_line\": \"\",
    \"recommended_subjects\": [],
    \"recommended_games\": [],
    \"recommended_tasks\": []
}

THEME SELECTION RULES:

If the student has Chess, chess strategy, board games or similar interests:
→ visual_theme = chess

If the student has Music, Song, Singing, Guitar, Piano, instruments or similar interests:
→ visual_theme = music

If the student has Programming, Coding, Computer Science, Software or Web Development:
→ visual_theme = technology

If the student has Mathematics, Algebra, Statistics or Calculation:
→ visual_theme = mathematics

If the student has Physics, Chemistry, Biology or Science:
→ visual_theme = science

If the student has Sports, Football, Cricket, Fitness, Athletics or similar:
→ visual_theme = sports

If the student has Art, Drawing, Design, Creativity, Photography or similar:
→ visual_theme = creative

If the student has Business, Finance, Entrepreneurship or Management:
→ visual_theme = business

If the student has English, Writing, Literature, Communication or Languages:
→ visual_theme = language

Otherwise:
→ visual_theme = general


IMPORTANT THEME RULE:

If the student has multiple interests, select the theme that represents the strongest or most visually distinctive interest.

For example:

Chess + Sports + Reading
→ chess

Music + Reading + Creativity
→ music

Programming + Mathematics
→ technology

Physics + Mathematics
→ science


DASHBOARD TITLE:

Create a short attractive title related to the selected theme.

Examples:

Chess:
\"The Grandmaster's Learning Arena\"

Music:
\"Your Learning Symphony\"

Technology:
\"The Digital Learning Lab\"

Science:
\"The Discovery Lab\"

Mathematics:
\"The Mathematics Arena\"

Sports:
\"The Champion's Learning Arena\"

Creative:
\"The Creative Studio\"

Do not use HTML.


THEME DESCRIPTION:

Write one short sentence describing the visual experience.

Example:

\"A strategic chess-inspired learning environment built around focus, tactics and progression.\"


COLORS:

Choose suitable HEX colors for the theme.

Chess should generally use dark brown, black, cream or gold tones.

Music should generally use white, cream, soft purple, blue or elegant musical colors.

Technology should generally use dark navy, blue, cyan or green.

Science should generally use blue, teal, white or laboratory-inspired colors.

Mathematics should generally use navy, blue, white or purple.

Sports should generally use energetic colors.

Creative should generally use artistic colors.

Return HEX values only.

Do not generate gradients.


BACKGROUND STYLE:

Choose either:

light
dark


ICON STYLE:

Return the same value as visual_theme.

Allowed values:

chess
music
technology
mathematics
science
sports
creative
business
language
general


DIFFICULTY:

Choose based on experience level:

beginner
intermediate
advanced


MOTIVATIONAL LINE:

Create one short original motivational sentence related to the theme.

For example for chess:

\"Think ahead, make your move and conquer today's challenge.\"

For music:

\"Every lesson is another note in your learning journey.\"

Do not use copyrighted song lyrics.


RECOMMENDED SUBJECTS:

Recommend subjects relevant to the student's interests, education level and learning goal.

Return subject names only.


RECOMMENDED GAMES:

Return short educational game names.


RECOMMENDED TASKS:

Return short learning task names.


IMPORTANT:

Do not generate HTML.
Do not generate CSS.
Do not generate Markdown.
Do not include explanations.
Return ONLY valid JSON.
";

        $apiKey = config('services.gemini.key');

        if (!$apiKey) {
            throw new \Exception('Gemini API key is missing.');
        }

        $url = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-3.5-flash-lite:generateContent';

        for ($attempt = 1; $attempt <= 3; $attempt++) {

            $response = Http::timeout(60)
                ->withHeaders([
                    'Content-Type' => 'application/json',
                    'x-goog-api-key' => $apiKey,
                ])
                ->post($url, [
                    'contents' => [
                        [
                            'parts' => [
                                [
                                    'text' => $prompt
                                ]
                            ]
                        ]
                    ],

                    'generationConfig' => [
                        'responseMimeType' => 'application/json',
                        'temperature' => 0.7,
                        'maxOutputTokens' => 1200,
                    ],
                ]);

            if ($response->successful()) {

                $text = $response->json(
                    'candidates.0.content.parts.0.text'
                );

                if (!$text) {
                    throw new \Exception(
                        'Gemini returned an empty response.'
                    );
                }

                $profile = json_decode($text, true);

                if (!$profile) {
                    throw new \Exception(
                        'Gemini returned invalid JSON: ' . $text
                    );
                }

                return $profile;
            }

            if ($response->status() === 503) {

                if ($attempt < 3) {
                    sleep($attempt * 2);
                    continue;
                }
            }

            throw new \Exception(
                'Gemini API Error: ' . $response->body()
            );
        }

        throw new \Exception(
            'Gemini API is temporarily unavailable.'
        );
    }
}