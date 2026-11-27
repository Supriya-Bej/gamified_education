<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Feedback;
use Illuminate\Http\Request;

class FeedbackManagementController extends Controller
{
    public function index(Request $request)
    {
        $query = Feedback::with('student')->latest();

        if (in_array($request->status, ['new', 'read'], true)) {
            $query->where('status', $request->status);
        }

        if (in_array($request->category, ['general', 'bug', 'suggestion', 'content'], true)) {
            $query->where('category', $request->category);
        }

        $feedbacks = $query->paginate(12)->withQueryString();

        $stats = [
            'total'   => Feedback::count(),
            'new'     => Feedback::where('status', 'new')->count(),
            'average' => round((float) Feedback::whereNotNull('rating')->avg('rating'), 1),
        ];

        return view('admin.feedback.index', compact('feedbacks', 'stats'));
    }

    public function markRead(Feedback $feedback)
    {
        $feedback->update(['status' => 'read']);

        return back()->with('success', 'Feedback marked as read.');
    }

    public function destroy(Feedback $feedback)
    {
        $feedback->delete();

        return back()->with('success', 'Feedback deleted.');
    }
}