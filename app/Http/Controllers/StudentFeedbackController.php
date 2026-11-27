<?php

namespace App\Http\Controllers;

use App\Models\Feedback;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentFeedbackController extends Controller
{
    public function index()
    {
        $student = Auth::guard('student')->user();

        $myFeedback = Feedback::where('user_id', $student->id)->latest()->take(10)->get();

        return view('student.feedback.index', compact('myFeedback'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category' => ['required', 'in:general,bug,suggestion,content'],
            'rating'   => ['nullable', 'integer', 'between:1,5'],
            'message'  => ['required', 'string', 'min:5', 'max:2000'],
        ]);

        Feedback::create([
            'user_id'  => Auth::guard('student')->id(),
            'category' => $validated['category'],
            'rating'   => $validated['rating'] ?? null,
            'message'  => trim($validated['message']),
        ]);

        return redirect()
            ->route('student.feedback')
            ->with('success', 'Thank you! Your feedback was sent to the admin.');
    }
}
