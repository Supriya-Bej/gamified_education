<?php

namespace App\Http\Controllers;

use App\Services\HelpChatService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class StudentHelpChatController extends Controller
{
    public function send(Request $request, HelpChatService $helper)
    {
        $validated = $request->validate([
            'message' => ['required', 'string', 'max:1000'],
            'history' => ['sometimes', 'array', 'max:10'],
            'history.*.role' => ['required', Rule::in(['user', 'assistant'])],
            'history.*.content' => ['required', 'string', 'max:4000'],
        ]);

        try {
            $result = $helper->reply(
                Auth::guard('student')->user(),
                $validated['history'] ?? [],
                trim($validated['message'])
            );
        } catch (\Throwable $e) {
            Log::error('HelpChat failed.', ['exception' => $e::class, 'message' => $e->getMessage()]);

            return response()->json([
                'success' => false,
                'message' => 'The helper could not reply. Please try again.',
            ], 500);
        }

        return response()->json([
            'success' => true,
            'reply' => $result['reply'],
            'mode' => $result['mode'],
        ]);
    }
}