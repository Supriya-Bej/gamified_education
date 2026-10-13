<?php

namespace Database\Seeders;

use App\Models\LearningMaterial;
use App\Models\StudentPreference;
use App\Models\Topic;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class InterestLearningMaterialsSeeder extends Seeder
{
    /**
     * Create starter lessons only for interests students have actually registered.
     */
    public function run(): void
    {
        $registeredInterests = StudentPreference::query()
            ->get(['interests'])
            ->pluck('interests')
            ->flatten()
            ->filter(fn ($interest) => is_string($interest) && trim($interest) !== '')
            ->map(fn (string $interest) => Str::slug($interest))
            ->unique()
            ->all();

        $catalog = $this->lessonCatalog();
        $createdTopics = 0;
        $createdMaterials = 0;

        foreach ($catalog as $interestKeys => $topicData) {
            if (empty(array_intersect(explode('|', $interestKeys), $registeredInterests))) {
                continue;
            }

            $topic = Topic::firstOrCreate(
                ['name' => $topicData['name']],
                [
                    'description' => $topicData['description'],
                    'is_active' => true,
                ]
            );

            $createdTopics += (int) $topic->wasRecentlyCreated;

            foreach ($topicData['materials'] as $material) {
                $learningMaterial = LearningMaterial::firstOrCreate(
                    [
                        'topic_id' => $topic->id,
                        'title' => $material['title'],
                    ],
                    [
                        'topic' => $topic->name,
                        'description' => $material['description'],
                        'content' => $material['content'],
                        'difficulty' => 'beginner',
                        'is_published' => true,
                    ]
                );

                $createdMaterials += (int) $learningMaterial->wasRecentlyCreated;
            }
        }

        $this->command?->info(
            "Interest learning materials complete: {$createdTopics} topics and {$createdMaterials} lessons added."
        );
    }

    /**
     * @return array<string, array{name: string, description: string, materials: array<int, array{title: string, description: string, content: string}>}>
     */
    private function lessonCatalog(): array
    {
        return [
            'coding|programming' => [
                'name' => 'Coding',
                'description' => 'Learn how instructions, algorithms, and code turn ideas into working programs.',
                'materials' => [
                    [
                        'title' => 'Think Like a Programmer: Algorithms',
                        'description' => 'Break a problem into clear steps and practise tracing an algorithm.',
                        'content' => <<<'LESSON'
An algorithm is a clear sequence of steps for solving a problem. A recipe, a route plan, and instructions for sorting books are all everyday examples. In programming, an algorithm helps us decide what a computer should do before we write the code.

Start by identifying the input (the information you have), the process (the steps you follow), and the output (the result you want). For example, to find the larger of two numbers, the inputs are the two numbers, the process compares them, and the output is the larger value.

Algorithms often use sequence, selection, and repetition. Sequence means doing steps in order. Selection means choosing a path with a condition such as “if the score is at least 10.” Repetition means doing a step again, such as checking every item in a list.

Try it: write numbered steps for finding the largest number in a list of five numbers. Test your steps with a list where the largest number is first, last, and repeated. If a step is unclear or gives the wrong result, revise it. This is tracing and debugging: carefully following a solution to find where it needs improvement.
LESSON,
                    ],
                    [
                        'title' => 'JavaScript Variables and Decisions',
                        'description' => 'Meet variables, values, and if/else decisions through simple examples.',
                        'content' => <<<'LESSON'
A variable gives a program a name for information it needs to remember. In JavaScript, use const when a variable should keep the same reference, and let when you plan to assign it a new value. For example: const learnerName = "Ari"; let points = 0;

Values have different types. Text is a string, whole numbers and decimals are numbers, and true or false values are booleans. Choosing clear names and suitable types makes a program easier to understand.

Programs can make decisions with if and else. For example:

const score = 8;
if (score >= 5) {
    console.log("Challenge complete");
} else {
    console.log("Keep practising");
}

The condition score >= 5 is checked first. Only one matching branch runs. Comparisons include === (equal value and type), !== (not equal), <, >, <=, and >=.

Try it: create a variable called temperature and write a condition that prints “Bring a jacket” when it is below 15. Test values on both sides of the boundary. Good programmers test ordinary values and edge cases, then fix the cause of any unexpected result.
LESSON,
                    ],
                ],
            ],
            'art' => [
                'name' => 'Art & Design',
                'description' => 'Explore visual ideas, colour, composition, and thoughtful design choices.',
                'materials' => [
                    [
                        'title' => 'Colour: Mood, Contrast, and the Colour Wheel',
                        'description' => 'Use colour relationships to make artwork clearer and more expressive.',
                        'content' => <<<'LESSON'
Colour is both a visual tool and a way to guide attention. A traditional colour wheel places primary colours near the centre of many colour relationships; mixing neighbouring colours creates secondary and intermediate colours. Designers often compare warm colours, such as reds and yellows, with cool colours, such as blues and greens.

Contrast is the difference between elements. Strong contrast can make a subject stand out, while low contrast can make a composition feel quieter. Contrast is not only about hue: light and dark values, size, shape, and texture can contrast too. Text and important symbols need enough contrast from their background to remain readable.

A limited palette can make an artwork feel unified. Choose one dominant colour, one supporting colour, and a small accent colour. Consider the feeling you want to communicate, but remember that colour associations vary between people and cultures.

Try it: create three small versions of the same poster using different palettes. In each version, make the title easy to find. Compare the results: which palette feels calm, energetic, or serious, and what visual choices created that effect?
LESSON,
                    ],
                    [
                        'title' => 'Composition: Balance and Focal Points',
                        'description' => 'Arrange shapes and space so viewers can understand your visual idea.',
                        'content' => <<<'LESSON'
Composition is the way visual elements are arranged. A focal point is the area you want viewers to notice first. You can create one with size, contrast, placement, colour, or detail. If every part competes for attention, the main idea may be hard to find.

Balance describes how visual weight is distributed. Symmetrical balance uses similar elements on both sides. Asymmetrical balance uses different elements that still feel stable together. Empty space, sometimes called negative space, is not wasted: it gives important shapes room to breathe and helps separate ideas.

Before adding detail, make a few thumbnail sketches. Use simple shapes to test where the main subject, supporting information, and open spaces will go. Step back or view the work at a smaller size; the strongest composition should still communicate its main idea.

Try it: design a book cover using only three shapes and two colours. Make one shape the focal point, leave deliberate negative space for a title, and check whether the design still feels balanced if the largest shape is moved to another corner.
LESSON,
                    ],
                ],
            ],
            'music' => [
                'name' => 'Music',
                'description' => 'Discover rhythm, melody, and the building blocks used to listen to and create music.',
                'materials' => [
                    [
                        'title' => 'Finding the Beat: Rhythm and Meter',
                        'description' => 'Understand pulse, rhythm, and how beats are grouped.',
                        'content' => <<<'LESSON'
The beat is the steady pulse you can tap along to. Rhythm is the pattern of long and short sounds and silences placed over that pulse. A song can keep the same beat while its rhythm changes.

Meter groups beats into repeating sets. In a common 4/4 meter, count “1, 2, 3, 4” and then begin again. A quarter note commonly receives one beat in this example; two eighth notes can fit into the same time as one quarter note. A rest marks silence, and silence is part of the rhythm too.

Listen for the strongest recurring pulse, then count evenly without speeding up. Clap a simple pattern such as clap, clap, pause, clap. Keep the beat steady while changing the rhythm. This helps separate pulse from the pattern played over it.

Try it: choose a familiar song and tap the beat. Count groups of four if that fits, then clap a different rhythm while keeping your tapping steady. If you lose the beat, pause and find the pulse again rather than rushing to catch up.
LESSON,
                    ],
                    [
                        'title' => 'Melody and Harmony: How Notes Work Together',
                        'description' => 'Learn how a tune and supporting notes combine to shape a musical idea.',
                        'content' => <<<'LESSON'
Melody is a sequence of notes heard as a musical line. It may move upward, downward, repeat a note, or make a larger leap. These changes in pitch create a contour that can make a tune feel settled, playful, or suspenseful.

Harmony is created when notes sound together or support a melody. A chord is a group of notes played at the same time. Some combinations can feel stable, while others create tension that seems to want to move onward. The effect depends on musical style and context.

A scale is an ordered set of pitches that can provide notes for a melody. You do not need an instrument to notice musical structure: hum a short tune and listen for repeated phrases, high and low notes, and where the melody seems to rest.

Try it: make a four-note melody using humming, a keyboard, or another instrument. Repeat it with a different final note. Compare how the ending changes the feeling. If you can, add a steady beat and notice how rhythm supports the melody.
LESSON,
                    ],
                ],
            ],
            'chess' => [
                'name' => 'Chess',
                'description' => 'Build chess confidence with board basics, safe play, and introductory tactics.',
                'materials' => [
                    [
                        'title' => 'The Chessboard and How the Pieces Move',
                        'description' => 'Learn board coordinates, piece movement, and the aim of the game.',
                        'content' => <<<'LESSON'
A chessboard has 64 squares arranged in eight rows and eight columns. When setting up the board, each player should have a light-coloured square at the right-hand corner. Files are named a through h, and ranks are numbered 1 through 8. A square can therefore be named with a coordinate such as e4.

The rook moves horizontally or vertically; the bishop moves diagonally; and the queen can move in either of those directions. The knight moves in an L-shape and can jump over pieces. The king moves one square in any direction, while pawns usually move forward and capture diagonally. Pieces cannot move through another piece, except that a knight can jump.

The goal is checkmate: the opposing king is under attack and has no legal way to escape. A player must never make a move that leaves their own king in check. Chess is not about capturing every piece; protecting the king and improving your position matter.

Try it: set up a board and practise moving each piece from the centre and from a corner. Before each move, name the starting square and destination square. This builds accurate board vision and helps you notice when a path is blocked.
LESSON,
                    ],
                    [
                        'title' => 'Chess Tactics: Spotting a Fork',
                        'description' => 'Recognize a fork and practise checking for threats before moving.',
                        'content' => <<<'LESSON'
A fork is a move that attacks two or more targets at the same time. Knights are well known for forks because their unusual L-shaped move can attack pieces that are not on the same line. Queens and pawns can also create forks.

To look for a fork, first check whether one of your pieces can move to a square that attacks two valuable targets. Then ask what your opponent can do next. A threat only works if the move is legal and the attacking piece cannot simply be captured without consequence.

Before choosing any move, use a short safety routine: What is my opponent threatening? Is my king safe? What will my move attack? What can the opponent capture or check afterward? This habit prevents many simple blunders and makes tactical ideas easier to spot.

Try it: arrange a knight so it attacks both an opposing king and rook, with the king required to move out of check. Find the knight’s destination square and explain why the rook may then be lost. If no legal square works, change the starting positions and try again.
LESSON,
                    ],
                ],
            ],
            'sports' => [
                'name' => 'Sports',
                'description' => 'Learn the foundations of preparation, teamwork, fair play, and reflective practice.',
                'materials' => [
                    [
                        'title' => 'Preparing to Play: Warm-Ups and Recovery',
                        'description' => 'Understand how preparation and rest support safe, enjoyable activity.',
                        'content' => <<<'LESSON'
A warm-up helps you move from rest into activity gradually. It can begin with easy movement, followed by controlled movements related to the activity. For example, a game involving running might include gentle jogging and direction changes. Follow your coach’s or teacher’s instructions and choose movements suitable for your ability.

During activity, pay attention to how you feel, use appropriate equipment, and take breaks when needed. Drink water when thirsty and follow the venue’s safety rules. Stop and tell a responsible adult if you feel unwell or are hurt; do not try to hide an injury to keep playing.

Recovery matters too. A cool-down can involve slowing down gradually. Rest, sleep, and regular meals help the body recover from everyday activity. People differ, so training should be age-appropriate and increased gradually rather than suddenly.

Try it: plan a short, safe warm-up for a school activity. Include an easy start, two movements that prepare you for the game, and a reminder to check the playing area and equipment with an adult.
LESSON,
                    ],
                    [
                        'title' => 'Teamwork, Tactics, and Fair Play',
                        'description' => 'Explore communication, shared roles, and respectful competition.',
                        'content' => <<<'LESSON'
Teamwork means coordinating effort toward a shared goal. Useful habits include listening, communicating clearly, noticing teammates’ positions, and encouraging others. A team works better when each person understands their role and can ask for help without being blamed.

A tactic is a plan for a particular situation. Teams may spread out to create passing options, protect an open space, or move together when possession changes. Tactics should fit the rules, the players, and the situation; there is rarely one plan that works every time.

Fair play means respecting rules, opponents, officials, and teammates. Winning is not the only measure of success. Learning a skill, making a thoughtful decision, or helping a teammate can also be progress. After a game, a calm review can identify one thing that worked and one thing to practise next.

Try it: after your next team activity, write down one clear communication that helped the group and one moment where a different choice might have helped. Focus on actions you can practise rather than blaming a person.
LESSON,
                    ],
                ],
            ],
            'reading' => [
                'name' => 'Reading',
                'description' => 'Strengthen comprehension and discover how stories and information texts are structured.',
                'materials' => [
                    [
                        'title' => 'Active Reading: Questions, Clues, and Evidence',
                        'description' => 'Use predictions and evidence to understand a text more deeply.',
                        'content' => <<<'LESSON'
Active reading means thinking while you read, not just moving your eyes across the page. Before starting, look at the title and headings. Ask what you already know and what you expect to learn. While reading, pause to clarify unfamiliar words and notice details that support or change your first prediction.

Readers can ask questions such as: What is happening? Why might the author include this detail? What evidence supports this idea? A prediction is a reasoned guess based on clues; it can change when new information appears.

For an informational text, identify the main idea and the details that support it. For a story, notice what characters do and how events affect them. If a paragraph is confusing, reread it more slowly, look for context clues, or summarize it in your own words.

Try it: read one page and write a one-sentence summary. Then underline one detail that supports your summary. If the detail does not support it, adjust the summary or choose stronger evidence.
LESSON,
                    ],
                    [
                        'title' => 'Stories: Character, Setting, and Conflict',
                        'description' => 'Notice the elements that help a story move and create meaning.',
                        'content' => <<<'LESSON'
Stories often bring together characters, a setting, and a conflict. Characters make choices. The setting tells us where and when events happen and can influence what choices are possible. Conflict is a challenge or problem that pushes events forward; it may be external, such as a difficult journey, or internal, such as a character deciding what is right.

Plot is the sequence of important events. A character’s response to a problem can reveal their values and help the reader understand how they change. Not every story resolves every question, and different readers may interpret an ending in different ways.

Look for evidence rather than guessing. A character may say one thing but act differently. Notice repeated images, important decisions, and changes in relationships. These details can support an interpretation, but explain how they connect to your idea.

Try it: choose a story you know and describe the main character, the setting, and the central challenge in three sentences. Add one detail from the story that shows how the character responds to that challenge.
LESSON,
                    ],
                ],
            ],
            'math|mathematics' => [
                'name' => 'Mathematics',
                'description' => 'Build number sense and problem-solving skills with clear examples and practice.',
                'materials' => [
                    [
                        'title' => 'Fractions as Parts and Numbers',
                        'description' => 'Understand equivalent fractions and compare parts of a whole.',
                        'content' => <<<'LESSON'
A fraction describes equal parts of a whole or a point on a number line. In 3/4, the denominator 4 tells us the whole is divided into four equal parts; the numerator 3 tells us how many of those parts we mean.

Fractions can look different but represent the same amount. For example, 1/2 and 2/4 are equivalent because both describe half of the same-sized whole. Multiplying or dividing the numerator and denominator by the same non-zero number creates an equivalent fraction.

To compare fractions with the same denominator, compare the numerators. With different denominators, you can use a common denominator or think about the size of each part. A larger denominator does not automatically mean a larger fraction: one eighth is smaller than one fourth.

Try it: draw two equal rectangles. Divide one into halves and the other into fourths. Shade one half in the first rectangle and two fourths in the second. Explain why the shaded amounts match. Then compare 3/8 and 1/2 using a drawing or a common denominator.
LESSON,
                    ],
                    [
                        'title' => 'Patterns and the First Steps of Algebra',
                        'description' => 'Describe number patterns and use a variable to represent a changing value.',
                        'content' => <<<'LESSON'
A pattern follows a rule. In 3, 6, 9, 12, each term increases by 3. The next term is 15 if the same rule continues. Before extending a pattern, describe the rule clearly and check it against more than one pair of terms.

Algebra uses symbols to represent numbers and relationships. A variable is a letter or symbol whose value may be unknown or may change. If a pattern starts at 2 and adds 4 each time, the term at position n can be described by 2 + 4(n - 1). At position 1, the expression gives 2; at position 2, it gives 6.

An equation says two expressions have the same value. To solve a simple equation such as x + 5 = 12, ask which number plus 5 equals 12. Subtracting 5 from both sides gives x = 7. Checking by substitution confirms the result.

Try it: make a pattern that starts at 5 and adds 3. Write its first four terms, describe the rule in words, and use your rule to find the tenth term. Substitute your answer back into the rule to check it.
LESSON,
                    ],
                ],
            ],
            'science' => [
                'name' => 'Science',
                'description' => 'Ask testable questions and explore how living things interact with their environments.',
                'materials' => [
                    [
                        'title' => 'Fair Tests: Questions, Evidence, and Results',
                        'description' => 'Plan a simple investigation and distinguish evidence from a guess.',
                        'content' => <<<'LESSON'
Science helps us investigate questions about the natural world. A useful investigation begins with a question that can be explored using observations or measurements. A prediction is a possible answer that can be tested; it is not a guaranteed result.

In a fair test, change one factor you are investigating while keeping other important conditions as similar as possible. Decide what you will measure and how you will record it. Repeat observations when practical, because one result may be unusual or affected by chance.

Evidence is the information you collect. A conclusion should describe what the evidence suggests and mention limits or unexpected results. If the evidence does not support your prediction, that is still useful: revise the explanation or design another investigation. Science improves through careful checking and sharing methods.

Try it: ask whether a paper helicopter’s drop time changes when its blades are longer. Decide what to change, what to measure, and which conditions to keep similar. Do not drop objects from unsafe heights; ask an adult to help choose a suitable space.
LESSON,
                    ],
                    [
                        'title' => 'Ecosystems: Connections Between Living Things',
                        'description' => 'Explore habitats, food chains, and why biodiversity matters.',
                        'content' => <<<'LESSON'
An ecosystem includes living organisms and the non-living parts of their environment, such as water, soil, air, and sunlight. A habitat is the place where an organism finds the conditions and resources it needs. Organisms interact with one another and with their surroundings.

A food chain shows one path of energy transfer. Plants and some other organisms make their own food using energy from sunlight. Animals obtain energy by eating plants or other animals. A food web connects many food chains and shows that changes can affect several species.

Biodiversity means the variety of life in an area. Different species can contribute in different ways, and healthy ecosystems often depend on many relationships. Pollution, habitat loss, and changes in climate can put pressure on organisms. Protecting habitats and reducing waste are examples of actions communities can take.

Try it: choose a local habitat and list one plant, one herbivore, and one predator that could be connected in a food chain. Add sunlight and explain how energy reaches the predator. Remember that real food webs are more complex than one chain.
LESSON,
                    ],
                ],
            ],
            'gk|general-knowledge' => [
                'name' => 'General Knowledge',
                'description' => 'Build everyday knowledge and practise checking information thoughtfully.',
                'materials' => [
                    [
                        'title' => 'Reading Maps and Understanding Place',
                        'description' => 'Use map symbols, direction, and scale to interpret a place.',
                        'content' => <<<'LESSON'
A map is a simplified representation of a place. Its title tells you what area or subject it shows. A legend explains symbols, a compass rose shows direction, and a scale helps compare map distance with real distance. Always check the legend because symbols can differ between maps.

The cardinal directions are north, east, south, and west. Many maps place north at the top, but this is a convention, not a rule. Latitude lines run east to west and measure positions north or south of the Equator. Longitude lines run from pole to pole and measure positions east or west of the Prime Meridian.

Maps can show roads, terrain, population, weather, or other information. The map’s purpose affects what details it includes. A map is useful, but it is not the place itself: it leaves information out and may become outdated.

Try it: find a simple map of your neighbourhood or region. Use its legend to identify two symbols, describe a route using cardinal directions, and check the scale before estimating how far the route is.
LESSON,
                    ],
                    [
                        'title' => 'Checking Facts and Reliable Sources',
                        'description' => 'Learn a simple routine for verifying a claim before sharing it.',
                        'content' => <<<'LESSON'
Information can come from books, news, websites, videos, and people. Before trusting or sharing a claim, pause and ask who created it, when it was published, what evidence it gives, and whether the author has relevant expertise. A confident tone or a large number of views does not prove that a claim is accurate.

Look for the original source when possible. Compare the claim with more than one trustworthy source that explains where its information came from. Notice whether a page separates evidence from opinion and whether it corrects mistakes. Be careful with headlines that make a surprising claim but provide little detail.

Some questions do not have a simple answer, and sources can disagree. In that case, describe what is known, what remains uncertain, and what evidence would help. For personal, health, or safety decisions, ask a trusted adult or qualified professional rather than relying on a random post.

Try it: choose a claim from a school topic. Write down the source, date, evidence, and one independent source that checks it. If you cannot verify it, do not repeat it as fact.
LESSON,
                    ],
                ],
            ],
        ];
    }
}
