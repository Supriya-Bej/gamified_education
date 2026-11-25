<?php

/*
|--------------------------------------------------------------------------
| AI Learning Themes
|--------------------------------------------------------------------------
|
| Each theme drives the whole look of the student dashboard, not just its
| colors:
|
| - badge_shape   the shape used for every "achievement badge" icon
|                  (hex | shield | diamond | circle | ribbon)
| - pattern       the background texture behind the whole page
|                  (dots | grid | checker | circuit | staff | waves | stars)
| - motion        how the floating theme symbols move
|                  (float | pulse | drift | flicker)
| - font_display  the heading/display typeface for this theme
| - font_url      the Google Fonts stylesheet link for font_display
| - glow          the color used for the soft halo behind the level ring
|
*/

return [

    'chess' => [

        'name' => 'Chess',
        'title' => 'The Grandmaster Arena',
        'subtitle' => 'Tactical Mind & Strategy',

        // High contrast Black & White Chessboard Theme
        'primary' => '#0A0A0C',
        'secondary' => '#FFFFFF',
        'accent' => '#CBD5E1',
        'background' => '#08080A',
        'surface' => '#121216',
        'surface_alt' => '#1C1C24',
        'border' => '#2E2E3E',
        'text' => '#F8FAFC',
        'muted' => '#94A3B8',
        'glow' => 'rgba(255, 255, 255, 0.4)',

        'icon' => '♞',

        'decorations' => [
            '♔',
            '♕',
            '♖',
            '♗',
            '♘',
            '♙',
        ],

        'badge_shape' => 'shield',
        'pattern' => 'checker',
        'motion' => 'flicker',
        'font_display' => 'Cinzel',
        'font_url' => 'https://fonts.googleapis.com/css2?family=Cinzel:wght@600;700;800;900&display=swap',

        'gamified_rank' => 'Knight Tactician',
        'gamified_motto' => 'Every move counts in the game of knowledge.',
        'gamified_labels' => [
            'level_prefix' => 'Tactical Tier',
            'quest_title' => "Today's Opening Gambit",
            'mission_title' => 'Grandmaster Quests',
            'challenge_title' => 'Tactical Challenges',
            'exp_label' => 'Tactical XP',
        ],

    ],


    'music' => [

        'name' => 'Music',
        'title' => 'The Music Room',
        'subtitle' => 'Find your rhythm. Create your sound.',

        'primary' => '#8E44AD',
        'secondary' => '#80449B',
        'accent' => '#C548A0',
        'background' => '#FAF9F6',
        'surface' => '#FFFFFF',
        'surface_alt' => '#F1ECF5',
        'border' => 'rgba(142,68,173,0.16)',
        'text' => '#26202B',
        'muted' => '#756B7D',
        'glow' => '#D6A2E8',

        'icon' => '♫',

        'decorations' => [
            '♪',
            '♫',
            '♩',
            '♬',
        ],

        'badge_shape' => 'circle',
        'pattern' => 'staff',
        'motion' => 'pulse',
        'font_display' => 'Playfair Display',
        'font_url' => 'https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700;800&display=swap',
        'gamified_rank' => 'Rhythm Maker',
        'gamified_motto' => 'Every great song starts with one note.',
        'welcome_message' => 'Welcome to your music room. Find your rhythm, explore new sounds, and compose your next success.',
        'daily_quest' => 'Warm up with a rhythm and note challenge',

    ],


    'sports' => [

        'name' => 'Sports',

        'primary' => '#FF5722',
        'secondary' => '#1E88E5',
        'background' => '#08111F',
        'surface' => '#111C2D',
        'surface_alt' => '#19263A',
        'text' => '#FFFFFF',
        'muted' => '#AAB7C8',
        'glow' => '#FF5722',

        'icon' => '🏆',

        'decorations' => [
            '⚡',
            '🏆',
            '🎯',
        ],

        'badge_shape' => 'shield',
        'pattern' => 'waves',
        'motion' => 'float',
        'font_display' => 'Anton',
        'font_url' => 'https://fonts.googleapis.com/css2?family=Anton&display=swap',

    ],


    'technology' => [

        'name' => 'Technology',

        'primary' => '#00E5FF',
        'secondary' => '#7C4DFF',
        'background' => '#05080D',
        'surface' => '#0C121A',
        'surface_alt' => '#121C27',
        'text' => '#EAFBFF',
        'muted' => '#8EA4B5',
        'glow' => '#00E5FF',

        'icon' => '</>',

        'decorations' => [
            '{ }',
            '</>',
            '01',
            '$_',
        ],

        'badge_shape' => 'hex',
        'pattern' => 'circuit',
        'motion' => 'pulse',
        'font_display' => 'JetBrains Mono',
        'font_url' => 'https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@500;600;700;800&display=swap',

    ],


    'science' => [

        'name' => 'Science',

        'primary' => '#16A085',
        'secondary' => '#3498DB',
        'background' => '#F4FAF9',
        'surface' => '#FFFFFF',
        'surface_alt' => '#EAF6F4',
        'text' => '#17332E',
        'muted' => '#6C8580',
        'glow' => '#3498DB',

        'icon' => '⚗',

        'decorations' => [
            '⚛',
            '⚗',
            '◉',
        ],

        'badge_shape' => 'hex',
        'pattern' => 'grid',
        'motion' => 'drift',
        'font_display' => 'Space Grotesk',
        'font_url' => 'https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@500;600;700&display=swap',

    ],


    'mathematics' => [

        'name' => 'Mathematics',

        'primary' => '#3949AB',
        'secondary' => '#7986CB',
        'background' => '#F5F7FF',
        'surface' => '#FFFFFF',
        'surface_alt' => '#E9EDFF',
        'text' => '#202640',
        'muted' => '#6C7390',
        'glow' => '#7986CB',

        'icon' => '∑',

        'decorations' => [
            '∑',
            'π',
            '√',
            '∞',
            'x²',
        ],

        'badge_shape' => 'diamond',
        'pattern' => 'grid',
        'motion' => 'drift',
        'font_display' => 'IBM Plex Serif',
        'font_url' => 'https://fonts.googleapis.com/css2?family=IBM+Plex+Serif:wght@600;700&display=swap',

    ],


    'creative' => [

        'name' => 'Creative',
        'title' => 'The Art Studio',
        'subtitle' => 'Imagine. Experiment. Create.',

        'primary' => '#E84393',
        'secondary' => '#B42374',
        'accent' => '#6C5CE7',
        'background' => '#FFF8FC',
        'surface' => '#FFFFFF',
        'surface_alt' => '#FFF0F7',
        'border' => 'rgba(180,35,116,0.16)',
        'text' => '#30202A',
        'muted' => '#806B75',
        'glow' => '#FDCB6E',

        'icon' => '🎨',

        'decorations' => [
            '✦',
            '✿',
            '✎',
            '★',
        ],

        'badge_shape' => 'diamond',
        'pattern' => 'stars',
        'motion' => 'float',
        'font_display' => 'Bricolage Grotesque',
        'font_url' => 'https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:opsz,wght@12..96,400..800&display=swap',
        'gamified_rank' => 'Color Creator',
        'gamified_motto' => 'Every idea begins with a splash of color.',
        'welcome_message' => 'Welcome to your art studio. Pick your colors, try something new, and make every quest your canvas.',
        'daily_quest' => 'Explore a new color and creativity challenge',

    ],


    'reading' => [

        'name' => 'Reading',

        'primary' => '#795548',
        'secondary' => '#A1887F',
        'background' => '#F7F1E8',
        'surface' => '#FFFDF8',
        'surface_alt' => '#EFE5D4',
        'text' => '#30271F',
        'muted' => '#76695D',
        'glow' => '#A1887F',

        'icon' => '📖',

        'decorations' => [
            '"',
            '"',
            '✦',
            '📖',
        ],

        'badge_shape' => 'circle',
        'pattern' => 'dots',
        'motion' => 'flicker',
        'font_display' => 'Lora',
        'font_url' => 'https://fonts.googleapis.com/css2?family=Lora:wght@600;700&display=swap',

    ],


    'business' => [

        'name' => 'Business',

        'primary' => '#1565C0',
        'secondary' => '#42A5F5',
        'background' => '#F4F7FB',
        'surface' => '#FFFFFF',
        'surface_alt' => '#EAF2FA',
        'text' => '#172033',
        'muted' => '#697386',
        'glow' => '#42A5F5',

        'icon' => '📈',

        'decorations' => [
            '↗',
            '◈',
            '$',
        ],

        'badge_shape' => 'ribbon',
        'pattern' => 'waves',
        'motion' => 'float',
        'font_display' => 'Manrope',
        'font_url' => 'https://fonts.googleapis.com/css2?family=Manrope:wght@600;700;800&display=swap',

    ],


    'gaming' => [

        'name' => 'Gaming',

        'primary' => '#7C4DFF',
        'secondary' => '#E040FB',
        'background' => '#080512',
        'surface' => '#140C20',
        'surface_alt' => '#211532',
        'text' => '#FFFFFF',
        'muted' => '#B8AFC5',
        'glow' => '#E040FB',

        'icon' => '🎮',

        'decorations' => [
            '✦',
            '◆',
            '★',
        ],

        'badge_shape' => 'diamond',
        'pattern' => 'stars',
        'motion' => 'pulse',
        'font_display' => 'Orbitron',
        'font_url' => 'https://fonts.googleapis.com/css2?family=Orbitron:wght@600;700;800&display=swap',

    ],


    'environment' => [
        'name' => 'Environment',
        'title' => 'The Green Earth Lab',
        'subtitle' => 'Protect. Restore. Sustain.',

        'primary' => '#1B5E20',
        'secondary' => '#4CAF50',
        'accent' => '#8BC34A',
        'background' => '#F1F8F1',
        'surface' => '#FFFFFF',
        'surface_alt' => '#E8F5E9',
        'border' => 'rgba(27,94,32,0.14)',
        'text' => '#1B2E1C',
        'muted' => '#5D7A5E',
        'glow' => 'rgba(76,175,80,0.45)',

        'icon' => '🌱',

        'decorations' => [
            '🌿',
            '🌍',
            '♻️',
            '🌱',
            '🌳',
        ],

        'badge_shape' => 'circle',
        'pattern' => 'dots',
        'motion' => 'float',
        'font_display' => 'DM Sans',
        'font_url' => 'https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700;800&display=swap',

        'gamified_rank' => 'Earth Guardian',
        'gamified_motto' => 'Every small act of care makes the world greener.',
        'welcome_message' => 'Welcome, Earth Guardian! Your missions protect the planet — one real-world action at a time.',
        'daily_quest' => 'Complete today\'s environmental action challenge',

        'gamified_labels' => [
            'level_prefix' => 'Guardian Tier',
            'quest_title' => "Today's Eco Mission",
            'mission_title' => 'Green Quests',
            'challenge_title' => 'Field Missions',
            'exp_label' => 'Eco XP',
        ],
    ],


    'history' => [
        'name' => 'History',
        'title' => 'The Time Vault',
        'subtitle' => 'Discover. Connect. Remember.',
        'primary' => '#6D4C41',
        'secondary' => '#A1887F',
        'accent' => '#D7CCC8',
        'background' => '#FBF7F4',
        'surface' => '#FFFFFF',
        'surface_alt' => '#F3ECE6',
        'border' => 'rgba(109,76,65,0.14)',
        'text' => '#2C1810',
        'muted' => '#7D6558',
        'glow' => 'rgba(161,136,127,0.5)',
        'icon' => '📜',
        'decorations' => ['⚔️', '🏛️', '📜', '🗺️'],
        'badge_shape' => 'shield',
        'pattern' => 'dots',
        'motion' => 'drift',
        'font_display' => 'Playfair Display',
        'font_url' => 'https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700;800&display=swap',
        'gamified_rank' => 'Time Scholar',
        'gamified_motto' => 'History is the greatest teacher — read it wisely.',
    ],


    'geography' => [
        'name' => 'Geography',
        'title' => 'The World Explorer',
        'subtitle' => 'Navigate. Discover. Map.',
        'primary' => '#1565C0',
        'secondary' => '#29B6F6',
        'accent' => '#80DEEA',
        'background' => '#F0F8FF',
        'surface' => '#FFFFFF',
        'surface_alt' => '#E3F2FD',
        'border' => 'rgba(21,101,192,0.14)',
        'text' => '#0D2137',
        'muted' => '#5C7A95',
        'glow' => 'rgba(41,182,246,0.45)',
        'icon' => '🌍',
        'decorations' => ['🌍', '🗺️', '🧭', '⛰️'],
        'badge_shape' => 'circle',
        'pattern' => 'waves',
        'motion' => 'float',
        'font_display' => 'Nunito',
        'font_url' => 'https://fonts.googleapis.com/css2?family=Nunito:wght@600;700;800&display=swap',
        'gamified_rank' => 'World Navigator',
        'gamified_motto' => 'The world is your classroom — go explore it.',
    ],


    'psychology' => [
        'name' => 'Psychology',
        'title' => 'The Mind Lab',
        'subtitle' => 'Think. Reflect. Understand.',
        'primary' => '#6A1B9A',
        'secondary' => '#AB47BC',
        'accent' => '#CE93D8',
        'background' => '#FDF8FF',
        'surface' => '#FFFFFF',
        'surface_alt' => '#F3E5F5',
        'border' => 'rgba(106,27,154,0.14)',
        'text' => '#1A0A2E',
        'muted' => '#7B598C',
        'glow' => 'rgba(171,71,188,0.4)',
        'icon' => '🧠',
        'decorations' => ['🧠', '💭', '🔬', '💡'],
        'badge_shape' => 'diamond',
        'pattern' => 'dots',
        'motion' => 'pulse',
        'font_display' => 'DM Serif Display',
        'font_url' => 'https://fonts.googleapis.com/css2?family=DM+Serif+Display&display=swap',
        'gamified_rank' => 'Mind Explorer',
        'gamified_motto' => 'Understanding yourself is the greatest quest.',
    ],


    'self_improvement' => [
        'name' => 'Self Improvement',
        'title' => 'The Growth Arena',
        'subtitle' => 'Habit. Focus. Become.',
        'primary' => '#E65100',
        'secondary' => '#FF8A65',
        'accent' => '#FFCCBC',
        'background' => '#FFF8F5',
        'surface' => '#FFFFFF',
        'surface_alt' => '#FBE9E7',
        'border' => 'rgba(230,81,0,0.14)',
        'text' => '#2E1200',
        'muted' => '#8A5440',
        'glow' => 'rgba(255,138,101,0.45)',
        'icon' => '🎯',
        'decorations' => ['🎯', '🔥', '⚡', '💪'],
        'badge_shape' => 'diamond',
        'pattern' => 'waves',
        'motion' => 'pulse',
        'font_display' => 'Nunito',
        'font_url' => 'https://fonts.googleapis.com/css2?family=Nunito:wght@600;700;800&display=swap',
        'gamified_rank' => 'Growth Champion',
        'gamified_motto' => 'Small daily improvements lead to stunning results.',
    ],


    'art' => [
        'name' => 'Art',
        'title' => 'The Art Studio',
        'subtitle' => 'Imagine. Experiment. Create.',
        'primary' => '#E84393',
        'secondary' => '#B42374',
        'accent' => '#6C5CE7',
        'background' => '#FFF8FC',
        'surface' => '#FFFFFF',
        'surface_alt' => '#FFF0F7',
        'border' => 'rgba(180,35,116,0.16)',
        'text' => '#30202A',
        'muted' => '#806B75',
        'glow' => '#FDCB6E',
        'icon' => '🎨',
        'decorations' => ['✦', '✿', '✎', '★'],
        'badge_shape' => 'diamond',
        'pattern' => 'stars',
        'motion' => 'float',
        'font_display' => 'Bricolage Grotesque',
        'font_url' => 'https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:opsz,wght@12..96,400..800&display=swap',
        'gamified_rank' => 'Color Creator',
        'gamified_motto' => 'Every idea begins with a splash of color.',
        'welcome_message' => 'Welcome to your art studio. Pick your colors, try something new, and make every quest your canvas.',
        'daily_quest' => 'Explore a new color and creativity challenge',
    ],


    'general' => [

        'name' => 'General',

        'primary' => '#6C5CE7',
        'secondary' => '#00CEC9',
        'background' => '#F7F7FB',
        'surface' => '#FFFFFF',
        'surface_alt' => '#F0F0FA',
        'text' => '#25243A',
        'muted' => '#77758D',
        'glow' => '#6C5CE7',

        'icon' => '✦',

        'decorations' => [
            '✦',
            '○',
            '◇',
        ],

        'badge_shape' => 'hex',
        'pattern' => 'dots',
        'motion' => 'float',
        'font_display' => 'Bricolage Grotesque',
        'font_url' => 'https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:opsz,wght@12..96,400..800&display=swap',

    ],

];
