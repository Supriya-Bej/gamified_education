<?php

namespace Tests\Feature;

use App\Models\SiteSetting;
use App\Models\UserRegister;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class HomepageContentManagementTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Schema::create('user_registers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('role')->default('student');
            $table->timestamps();
        });

        Schema::create('site_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->json('value');
            $table->timestamps();
        });

        Schema::create('game_engines', function (Blueprint $table) {
            $table->id();
            $table->string('engine_key')->unique();
            $table->boolean('is_enabled')->default(false);
            $table->timestamps();
        });

        Schema::create('games', function (Blueprint $table) {
            $table->id();
            $table->string('game_type')->unique();
            $table->string('category')->nullable();
            $table->timestamps();
        });

        Schema::create('learning_tasks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('title');
            $table->string('review_status')->default('approved');
            $table->string('category')->nullable();
            $table->string('difficulty')->default('beginner');
            $table->timestamps();
        });

        Schema::create('project_challenges', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('interest');
            $table->string('title');
            $table->text('project_brief');
            $table->json('objectives');
            $table->json('deliverables');
            $table->string('difficulty');
            $table->unsignedSmallInteger('xp');
            $table->string('review_status')->default('pending_review');
            $table->timestamps();
        });
    }

    public function test_homepage_renders_default_content(): void
    {
        $this->get(route('home'))
            ->assertOk()
            ->assertSee('Learning is better when it feels like a game.')
            ->assertSee('Explore Learning')
            ->assertSee('Ready to make learning more exciting?');
    }

    public function test_admin_can_update_homepage_content(): void
    {
        $admin = UserRegister::create([
            'name' => 'Admin',
            'email' => 'admin@example.test',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);
        $content = $this->defaultContent();
        $content['hero_title'] = 'Build a brighter future';
        $content['subject_math_description'] = 'New mathematics description';
        $content['hero_stat_2_value'] = '50+';

        $this->actingAs($admin, 'admin')
            ->put(route('admin.homepage.update'), $content)
            ->assertRedirect(route('admin.homepage.edit'))
            ->assertSessionHas('success');

        $savedSetting = SiteSetting::query()->where('key', 'homepage')->firstOrFail();
        $this->assertSame('Build a brighter future', $savedSetting->value['hero_title']);
        $this->assertSame('New mathematics description', $savedSetting->value['subject_math_description']);
        $this->assertArrayNotHasKey('hero_stat_2_value', $savedSetting->value);

        $this->get(route('home'))
            ->assertOk()
            ->assertSee('Build a brighter future')
            ->assertSee('New mathematics description');
    }

    public function test_homepage_metrics_use_current_enabled_catalog_and_published_challenges(): void
    {
        \Illuminate\Support\Facades\DB::table('game_engines')->insert([
            ['engine_key' => 'math_quiz', 'is_enabled' => true, 'created_at' => now(), 'updated_at' => now()],
            ['engine_key' => 'disabled_quiz', 'is_enabled' => false, 'created_at' => now(), 'updated_at' => now()],
        ]);
        \Illuminate\Support\Facades\DB::table('games')->insert([
            ['game_type' => 'math_quiz', 'category' => 'mathematics', 'created_at' => now(), 'updated_at' => now()],
            ['game_type' => 'math_quiz_extra', 'category' => 'mathematics', 'created_at' => now(), 'updated_at' => now()],
            ['game_type' => 'disabled_quiz', 'category' => 'science', 'created_at' => now(), 'updated_at' => now()],
        ]);
        \Illuminate\Support\Facades\DB::table('game_engines')->insert([
            'engine_key' => 'math_quiz_extra',
            'is_enabled' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        \Illuminate\Support\Facades\DB::table('learning_tasks')->insert([
            ['user_id' => 1, 'title' => 'Fractions quest', 'review_status' => 'approved', 'created_at' => now(), 'updated_at' => now()],
            ['user_id' => 2, 'title' => 'Fractions quest', 'review_status' => 'approved', 'created_at' => now(), 'updated_at' => now()],
            ['user_id' => 1, 'title' => 'Pending quest', 'review_status' => 'pending_review', 'created_at' => now(), 'updated_at' => now()],
        ]);
        \Illuminate\Support\Facades\DB::table('project_challenges')->insert([
            [
                'user_id' => 1,
                'interest' => 'Environment',
                'title' => 'Plant a native seedling',
                'project_brief' => 'Plant and document a seedling.',
                'objectives' => '[]',
                'deliverables' => '[]',
                'difficulty' => 'beginner',
                'xp' => 20,
                'review_status' => 'approved',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        $this->get(route('home'))
            ->assertOk()
            ->assertSee('1')
            ->assertSee('Learning Areas')
            ->assertSee('2')
            ->assertSee('Games & Quizzes')
            ->assertSee('Challenges');
    }

    public function test_admin_can_view_ai_reports_and_configure_verification_rules(): void
    {
        $admin = UserRegister::create([
            'name' => 'AI Admin',
            'email' => 'ai-admin@example.test',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        $this->actingAs($admin, 'admin')
            ->get(route('admin.ai.reports'))
            ->assertOk()
            ->assertSee('Generated learning quests')
            ->assertSee('AI verification rules')
            ->assertSee('Maximum project XP');

        $this->put(route('admin.ai.rules.update'), [
            'minimum_objectives' => 4,
            'minimum_brief_characters' => 120,
            'maximum_xp' => 75,
        ])->assertRedirect(route('admin.ai.reports'));

        $this->assertSame([
            'minimum_objectives' => 4,
            'minimum_brief_characters' => 120,
            'maximum_xp' => 75,
        ], SiteSetting::query()->where('key', 'ai_verification')->firstOrFail()->value);

        $this->put(route('admin.ai.rules.update'), [
            'minimum_objectives' => 2,
            'minimum_brief_characters' => 120,
            'maximum_xp' => 75,
        ])->assertSessionHasErrors('minimum_objectives');
    }

    public function test_homepage_content_editor_requires_admin_authentication(): void
    {
        $this->get(route('admin.homepage.edit'))
            ->assertRedirect(route('admin.login'));

        $this->put(route('admin.homepage.update'), $this->defaultContent())
            ->assertRedirect(route('admin.login'));
    }

    public function test_authenticated_admin_cannot_reopen_admin_login_page(): void
    {
        $admin = UserRegister::create([
            'name' => 'Panel Admin',
            'email' => 'panel-admin@example.test',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        $this->actingAs($admin, 'admin')
            ->get(route('admin.login'))
            ->assertRedirect(route('admin.dashboard'));

        $this->post(route('admin.login.submit'), [])
            ->assertRedirect(route('admin.dashboard'));
    }

    public function test_admin_login_page_is_not_cached(): void
    {
        $this->get(route('admin.login'))
            ->assertOk()
            ->assertHeader('Cache-Control', 'must-revalidate, no-cache, no-store, private')
            ->assertHeader('Pragma', 'no-cache')
            ->assertHeader('Expires', '0');
    }

    public function test_logged_out_admin_cannot_reopen_a_protected_admin_page(): void
    {
        $admin = UserRegister::create([
            'name' => 'Panel Admin',
            'email' => 'logged-out-admin@example.test',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        $this->actingAs($admin, 'admin')
            ->post(route('admin.logout'))
            ->assertRedirect(route('admin.login'));

        $this->get(route('admin.homepage.edit'))
            ->assertRedirect(route('admin.login'));
    }

    private function defaultContent(): array
    {
        $content = [];

        foreach (config('homepage.sections') as $fields) {
            foreach ($fields as $key => $field) {
                $content[$key] = $field['default'];
            }
        }

        return $content;
    }
}
