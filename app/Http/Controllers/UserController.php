<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\UserRegister;
use App\Models\StudentPreference;
use App\Models\StudentProgress;
// Import AIService from Service
use App\Services\AIService;
use App\Models\Subject;


class UserController extends Controller
{
    //Registration
    public function registration()
    {
        return view('user.register');
    }

    // Store Registration Data
    public function registerUser(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|unique:user_registers,email',
            'password' => 'required|min:8|confirmed',
            'education_level' => 'required|string',
            'class_semester' => 'required|string',
            'institution' => 'required|string|max:150',
            'experience_level' => 'required|string',
            'interests' => 'required|array|min:1',
            'learning_goal' => 'required|string',
            'experience_preference' => 'required|string',
            'terms' => 'accepted',
        ]);


        // Create Student Account
        $user = UserRegister::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),

            // role will not taken from Public registration
            'role' => 'student',
        ]);

        StudentProgress::create([
            'user_id' => $user->id,
            'total_xp' => 0,
            'level' => 1,
            'completed_tasks' => 0,
        ]);

        // Save Student Preferences
        StudentPreference::create([
            'user_id' => $user->id,

            'education_level' => $request->education_level,
            'class_semester' => $request->class_semester,
            'institution' => $request->institution,
            'experience_level' => $request->experience_level,

            'interests' => $request->interests,

            'learning_goal' => $request->learning_goal,
            'experience_preference' => $request->experience_preference,
        ]);
        return redirect()->route('login')->with('success', 'Account created successfully! Please login.');
    }


    // Login
    public function login()
    {
        return view('user.login');
    }

    public function loginUser(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::guard('student')->attempt($credentials)) {

            // Carete a new session
            $request->session()->regenerate();
            return redirect()->route('student.dashboard');
        }
        // return back()--mane agger page e fire jay jei page theke login korchilo
        return back()->withErrors([
            'email' => 'Invalid email or password.',
        ])->onlyInput('email');
    }

    // Logout
    public function logoutUser(Request $request)
    {
        Auth::guard('student')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'You have been logged out successfully.');
    }

    // public function dashboard(AIService $aiService)
    // {
    //     //user() eta student guard use kore je user login kora ache take khuje ber kore
    //     $user = Auth::guard('student')->user();

    //     $preferences = $user->preferences;

    //     $progress = $user->progress;

    //     if (!$progress) {
    //         $progress = StudentProgress::create([
    //             'user_id' => $user->id,
    //             'total_xp' => 0,
    //             'level' => 1,
    //             'completed_tasks' => 0,
    //         ]);
    //     }

    //     /*
    //     |--------------------------------------------------------------------------
    //     | Generate AI Profile
    //     |--------------------------------------------------------------------------
    //     */
    //     if (!$preferences->ai_profile) {

    //         $studentData = [
    //             'education_level' => $preferences->education_level,
    //             'class_semester' => $preferences->class_semester,
    //             'experience_level' => $preferences->experience_level,
    //             'interests' => $preferences->interests,
    //             'learning_goal' => $preferences->learning_goal,
    //             'experience_preference' => $preferences->experience_preference,
    //         ];

    //         $aiProfile = $aiService->generateProfile($studentData);

    //         // preferences is a method that is called without () 
    //         // UserRegister model-e amra preferences() eii method baniyechi ekhon eii bhabe likhe sei student-er preference pawa jabe
    //         $preferences->update([
    //             'ai_profile' => $aiProfile
    //         ]);
    //     } else {

    //         $aiProfile = $preferences->ai_profile;
    //     }

    //     /*
    //     |--------------------------------------------------------------------------
    //     | Find Recommended Subjects
    //     |--------------------------------------------------------------------------
    //     */

    //     $recommendedSubjects = Subject::whereIn(
    //         'name',
    //         $aiProfile['recommended_subjects'] ?? []
    //     )->with(['games', 'tasks'])->get(); //get() run query run koranor jonno use hoy


    //     // Dashboard Mission dynamic
    //     $todayMission = null;
    //     foreach ($recommendedSubjects as $subject) {

    //         if ($subject->tasks->count() > 0) {

    //             $todayMission = $subject->tasks->first();

    //             break;
    //         }
    //     }

    //     // Dashboard e AI profile pawar pore 
    //     $themeKey = $aiProfile['visual_theme']
    //         ?? $aiProfile['theme']
    //         ?? 'general';

    //     $themeConfig = config(
    //         'learning_themes.' . $themeKey,
    //         config('learning_themes.general')
    //     );

    //     // Student progress
    //     $progress = $user->progress;

    //     /*
    //     |--------------------------------------------------------------------------
    //     | Send Data To Dashboard
    //     |--------------------------------------------------------------------------
    //     */
    //     // compact() is a PHP built-in function which make multiple variable into array where the variable name become array key


    //     return view('user.dashboard', compact(
    //         'user',
    //         'preferences',
    //         'aiProfile',
    //         'recommendedSubjects',
    //         'progress',
    //         'themeKey',
    //         'themeConfig'
    //     ));
    // }
}
