<?php

use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\QuestController;
use App\Models\TaskCompletion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\StudentDashboardController;
use App\Http\Controllers\StudentProfileController;
use App\Http\Controllers\StudentGameController;
use App\Http\Controllers\Admin\StudentManagementController;
use App\Http\Controllers\Admin\GameManagementController;
use App\Http\Controllers\Admin\LearningTaskManagementController;
use App\Http\Controllers\AdminGameController;
use App\Http\Controllers\Admin\GameEngineManagementController;
use App\Http\Controllers\Admin\GameContentManagementController;
use App\Http\Controllers\Admin\RewardManagementController;
use App\Http\Controllers\Admin\BadgeManagementController;
use App\Http\Controllers\StudentBadgeController;
use App\Models\Subject;
use App\Models\Task;

Route::get('/', function () {
    return view('user.index');
});

// Registration
Route::get('/student/register', [UserController::class, 'registration'])->name('user.register');
Route::post('/student/register', [UserController::class, 'registerUser'])->name('register-user');

// Login
Route::get('/student/login', [UserController::class, 'login'])->name('login');
Route::post('/student/login', [UserController::class, 'loginUser'])->name('login-user');

// Dashboard
Route::middleware('auth:student')->group(function () {

    // Dashboard
    Route::get('/student/dashboard', [StudentDashboardController::class, 'index'])
        ->name('student.dashboard');

    // Profile
    Route::get('/student/profile', [StudentProfileController::class, 'edit'])
        ->name('student.profile');

    // Update Profile
    Route::put('/student/profile', [StudentProfileController::class, 'update'])
        ->name('student.profile.update');

    // Delete Picture
    Route::delete('/student/profile/picture', [StudentProfileController::class, 'deletePicture'])
        ->name('student.profile.picture.delete');

    // Game Play
    Route::get('/student/game/{task}', [StudentGameController::class, 'play'])
        ->name('student.game.play');

    // Game complete
    Route::post('/student/game/{task}/complete', [StudentGameController::class, 'complete'])
        ->name('student.game.complete');

    // Badge
    Route::get('/student/badges', [StudentBadgeController::class, 'index'])
        ->name('student.badges');
});


// ADMIN AUTHENTICATION
Route::get('/admin/login', [AdminAuthController::class, 'showLogin'])
    ->name('admin.login');

Route::post('/admin/login', [AdminAuthController::class, 'login'])
    ->name('admin.login.submit');

Route::post('/admin/logout', [AdminAuthController::class, 'logout'])
    ->name('admin.logout');


// ===============================
// ADMIN PANEL
// ===============================

Route::middleware(['admin'])->prefix('admin')->group(function () {

    // Admin Dashboard
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])
        ->name('admin.dashboard');

    // Dashboard er students page
    Route::get('/students', [StudentManagementController::class, 'index'])
        ->name('admin.students.index');

    // Dashboard er students er view button
    Route::get('/students/{student}', [StudentManagementController::class, 'show'])
        ->name('admin.students.show');

    // Dashboard e games section open
    Route::get('/games', [GameManagementController::class, 'index'])
        ->name('admin.games.index');

    // Dashboard e games section view button
    Route::get('/games/{game}', [GameManagementController::class, 'show'])
        ->name('admin.games.show');

    // Game create
    Route::get('/games/create', [GameManagementController::class, 'create'])
        ->name('admin.games.create');

    // 
    Route::post('/games', [GameManagementController::class, 'store'])
        ->name('admin.games.store');

    // Edit game
    Route::get('/games/{game}/edit', [GameManagementController::class, 'edit'])
        ->name('admin.games.edit');

    // Update game
    Route::put('/games/{game}', [GameManagementController::class, 'update'])
        ->name('admin.games.update');

    // Delete game
    Route::delete('/games/{game}', [GameManagementController::class, 'destroy'])
        ->name('admin.games.destroy');

    // Task view
    Route::get('/tasks', [LearningTaskManagementController::class, 'index'])
        ->name('admin.tasks.index');

    // Task show
    Route::get('/tasks/{task}', [LearningTaskManagementController::class, 'show'])
        ->name('admin.tasks.show');

    // Game engine
    Route::get('/engines', [GameEngineManagementController::class, 'index'])
        ->name('admin.engines.index');

    Route::get('/engines/{gameType}', [GameEngineManagementController::class, 'show'])
        ->name('admin.engines.show');

    // Game content
    Route::get('/game-content', [GameContentManagementController::class, 'index'])
        ->name('admin.game-content.index');

    Route::get('/game-content/{game}', [GameContentManagementController::class, 'show'])
        ->name('admin.game-content.show');

    // xp & Rewards
    Route::get('/rewards', [RewardManagementController::class, 'index'])
        ->name('admin.rewards.index');

    // Badges Management
    Route::get('/badges', [BadgeManagementController::class, 'index'])
        ->name('admin.badges.index');

    Route::get('/badges/create', [BadgeManagementController::class, 'create'])
        ->name('admin.badges.create');

    Route::post('/badges', [BadgeManagementController::class, 'store'])
        ->name('admin.badges.store');

    Route::get('/badges/{badge}/edit', [BadgeManagementController::class, 'edit'])
        ->name('admin.badges.edit');

    Route::put('/badges/{badge}', [BadgeManagementController::class, 'update'])
        ->name('admin.badges.update');

    Route::delete('/badges/{badge}', [BadgeManagementController::class, 'destroy'])
        ->name('admin.badges.destroy');
});


// Logout
Route::post('/student/logout', [UserController::class, 'logoutUser'])->name('logout-user');



// Admin 
// Route::prefix('admin')->group(function () {

//     Route::get('/games', [AdminGameController::class, 'index'])
//         ->name('admin.games.index');

//     Route::get('/games/create', [AdminGameController::class, 'create'])
//         ->name('admin.games.create');

//     Route::post('/games', [AdminGameController::class, 'store'])
//         ->name('admin.games.store');

// });