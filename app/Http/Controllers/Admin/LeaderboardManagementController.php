<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LeaderboardXpAdjustment;
use App\Models\StudentProgress;
use App\Models\UserRegister;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class LeaderboardManagementController extends Controller
{
    public function index(Request $request): View
    {
        $validated = $request->validate([
            'search' => ['nullable', 'string', 'max:100'],
        ]);
        $search = trim($validated['search'] ?? '');

        $leaderboardQuery = UserRegister::query()
            ->leftJoin('student_progress', 'user_registers.id', '=', 'student_progress.user_id')
            ->where('user_registers.role', 'student')
            ->select([
                'user_registers.id',
                'user_registers.name',
                'user_registers.email',
            ])
            ->selectRaw('COALESCE(student_progress.total_xp, 0) as total_xp')
            ->selectRaw(
                'ROW_NUMBER() OVER (
                    ORDER BY COALESCE(student_progress.total_xp, 0) DESC,
                    user_registers.id ASC
                ) as leaderboard_rank'
            );

        $students = DB::query()
            ->fromSub($leaderboardQuery, 'ranked_students')
            ->when($search !== '', function ($query) use ($search): void {
                $query->where(function ($query) use ($search): void {
                    $query->where('ranked_students.name', 'like', '%'.$search.'%')
                        ->orWhere('ranked_students.email', 'like', '%'.$search.'%');
                });
            })
            ->orderBy('leaderboard_rank')
            ->paginate(15)
            ->withQueryString();

        $adjustments = LeaderboardXpAdjustment::query()
            ->with(['student:id,name,email', 'admin:id,name'])
            ->latest()
            ->paginate(10, ['*'], 'adjustments_page')
            ->withQueryString();

        return view('admin.leaderboard.index', compact('students', 'search', 'adjustments'));
    }

    public function adjustXp(Request $request, UserRegister $student): RedirectResponse
    {
        abort_unless($student->role === 'student', 404);

        $validated = $request->validate([
            'xp_change' => ['required', 'integer', 'min:-2147483647', 'max:2147483647', 'not_in:0'],
            'reason' => ['required', 'string', 'max:500'],
            'search' => ['nullable', 'string', 'max:100'],
        ]);

        DB::transaction(function () use ($request, $student, $validated): void {
            UserRegister::query()
                ->whereKey($student->id)
                ->lockForUpdate()
                ->firstOrFail();

            $progress = StudentProgress::query()->firstOrCreate(
                ['user_id' => $student->id],
                ['total_xp' => 0, 'level' => 1, 'completed_tasks' => 0]
            );
            $progress = StudentProgress::query()
                ->whereKey($progress->id)
                ->lockForUpdate()
                ->firstOrFail();

            $newTotalXp = (int) $progress->total_xp + (int) $validated['xp_change'];
            if ($newTotalXp < 0 || $newTotalXp > 2147483647) {
                throw ValidationException::withMessages([
                    'xp_change' => 'This adjustment would make the student XP invalid.',
                ]);
            }

            $progress->total_xp = $newTotalXp;
            $progress->save();

            LeaderboardXpAdjustment::create([
                'user_id' => $student->id,
                'admin_id' => $request->user('admin')->id,
                'xp_change' => $validated['xp_change'],
                'reason' => $validated['reason'],
            ]);
        });

        return redirect()
            ->route('admin.leaderboard.index', ['search' => $validated['search'] ?? null])
            ->with('success', "XP adjustment for {$student->name} was recorded.");
    }
}
