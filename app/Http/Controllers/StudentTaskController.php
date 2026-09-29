<?php

namespace App\Http\Controllers;

use App\Models\LearningTask;
use App\Models\StudentProgress;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class StudentTaskController extends Controller
{
    public function complete($id)
    {
        $user = Auth::guard('student')->user();

        return DB::transaction(function () use ($id, $user) {

            /*
             * 1. Current student's task খুঁজছি
             */
            $task = LearningTask::where('id', $id)
                ->where('user_id', $user->id)
                ->firstOrFail();


            /*
             * 2. একই task আবার complete করতে পারবে না
             */
            if ($task->status === 'completed') {

                return back()->with(
                    'info',
                    'This task is already completed.'
                );
            }


            /*
             * 3. Task থেকে XP নিচ্ছি
             */
            $xp = (int) $task->xp;


            /*
             * 4. Task কোন interest/category-এর সেটা নিচ্ছি
             */
            $interest = strtolower(trim($task->category));


            /*
             * 5. Student progress খুঁজছি
             */
            $progress = StudentProgress::firstOrCreate(
                [
                    'user_id' => $user->id
                ],
                [
                    'total_xp' => 0,
                    'level' => 1,
                    'completed_tasks' => 0,
                    'subject_xp' => [],
                ]
            );


            /*
             * 6. Existing subject XP নিচ্ছি
             */
            $subjectXp = $progress->subject_xp ?? [];


            /*
             * 7. এই interest-এর data না থাকলে তৈরি করছি
             */
            if (!isset($subjectXp[$interest])) {

                $subjectXp[$interest] = [
                    'xp' => 0,
                    'level' => 1,
                    'completed_tasks' => 0,
                ];
            }


            /*
             * 8. ONLY current interest-এর XP বাড়াচ্ছি
             */
            $subjectXp[$interest]['xp'] += $xp;


            /*
             * 9. ONLY current interest-এর completed task বাড়াচ্ছি
             */
            $subjectXp[$interest]['completed_tasks']++;


            /*
             * 10. Current interest-এর level calculate
             */
            $subjectXp[$interest]['level']
                = $this->calculateLevel(
                    $subjectXp[$interest]['xp']
                );


            /*
             * 11. Overall XP বাড়াচ্ছি
             */
            $progress->total_xp += $xp;


            /*
             * 12. Overall completed task বাড়াচ্ছি
             */
            $progress->completed_tasks++;


            /*
             * 13. Overall level calculate
             */
            $progress->level
                = $this->calculateLevel(
                    $progress->total_xp
                );


            /*
             * 14. Subject XP database-এ save
             */
            $progress->subject_xp = $subjectXp;

            $progress->save();


            /*
             * 15. Task completed করে দিচ্ছি
             */
            $task->status = 'completed';

            $task->save();


            /*
             * 16. Dashboard-এ ফিরে যাচ্ছি
             */
            return back()->with(
                'success',
                "Great! You earned {$xp} XP in {$interest}."
            );
        });
    }


    /*
     * Level calculation
     */
    private function calculateLevel($xp)
    {
        if ($xp >= 100) {
            return 3;
        }

        if ($xp >= 50) {
            return 2;
        }

        return 1;
    }
}