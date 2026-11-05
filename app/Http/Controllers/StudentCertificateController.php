<?php

namespace App\Http\Controllers;

use App\Models\Certificate;
use Illuminate\Support\Facades\Auth;

class StudentCertificateController extends Controller
{
    public function index()
    {
        $certificates = Certificate::with('task')
            ->where('user_id', Auth::guard('student')->id())
            ->where('status', 'issued')
            ->latest('issued_at')
            ->paginate(10);

        return view('student.certificates.index', compact('certificates'));
    }

    public function show($certificateId)
    {
        $certificate = Certificate::with(['user', 'task', 'issuer'])
            ->whereKey($certificateId)
            ->where('user_id', Auth::guard('student')->id())
            ->where('status', 'issued')
            ->firstOrFail();

        return view('student.certificates.show', compact('certificate'));
    }
}
