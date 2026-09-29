<?php

use App\Models\TaskCompletion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\UserController;
use App\Http\Controllers\StudentDashboardController;
use App\Http\Controllers\StudentProfileController;
use App\Http\Controllers\StudentTaskController;
use Illuminate\Support\Facades\Route;
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

    // Task Complete
    Route::post('/student/task/{id}/complete', [StudentTaskController::class, 'complete'])
    ->name('student.task.complete');
});

// Route::get('/student/dashboard', [UserController::class, 'dashboard'])
//     ->middleware('auth:student')
//     ->name('user.dashboard'); //middleware bojhacche je sudhu authenticable user aste parbe

// Logout
Route::post('/student/logout', [UserController::class, 'logoutUser'])->name('logout-user');
