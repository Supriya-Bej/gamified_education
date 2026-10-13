<?php

namespace App\Http\Controllers;

use App\Models\StudentBadge;
use App\Models\StudentPreference;
use App\Services\BadgeService;
use Illuminate\Support\Facades\Auth;

class StudentBadgeController extends Controller
{
    public function index(BadgeService $badgeService)
    {
        $student = Auth::guard('student')->user();

        if (!$student) {
            return redirect()
                ->route('login')
                ->with('error', 'Please login first.');
        }

        $badgeService->checkAndAward($student);

        // Get student's preference / interests
        $preference = StudentPreference::where(
            'user_id',
            $student->id
        )->first();

        // Get student's earned badges
        $badges = StudentBadge::with('badge')
            ->where('user_id', $student->id)
            ->latest('awarded_at')
            ->get();

        // Blade page expects variable named $user
        $user = $student;

        return view(
            'student.badges.index',
            compact(
                'user',
                'preference',
                'badges'
            )
        );
    }
}