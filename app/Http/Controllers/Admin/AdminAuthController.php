<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminAuthController extends Controller
{
    public function showLogin()
    {
        if ($redirect = $this->redirectAuthenticatedAdmin()) {
            return $redirect;
        }

        return response()
            ->view('admin.auth.login')
            ->header('Cache-Control', 'private, no-store, no-cache, must-revalidate')
            ->header('Pragma', 'no-cache')
            ->header('Expires', '0');
    }

    public function login(Request $request)
    {
        if ($redirect = $this->redirectAuthenticatedAdmin()) {
            return $redirect;
        }

        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (
            Auth::guard('admin')->attempt([
                'email' => $request->email,
                'password' => $request->password,
                'role' => 'admin',
            ])
        ) {

            $request->session()->regenerate();

            return redirect()->route('admin.dashboard');
        }

        return back()
            ->withErrors([
                'email' => 'Invalid admin email or password.',
            ])
            ->withInput($request->only('email'));
    }

    private function redirectAuthenticatedAdmin()
    {
        $admin = Auth::guard('admin')->user();

        if (!$admin) {
            return null;
        }

        if ($admin->role !== 'admin') {
            Auth::guard('admin')->logout();

            return null;
        }

        return redirect()->route('admin.dashboard');
    }

    // public function logout(Request $request)
    // {
    //     Auth::guard('admin')->logout();

    //     $request->session()->invalidate();
    //     $request->session()->regenerateToken();

    //     return redirect()
    //         ->route('admin.login')
    //         ->with('success', 'Admin logged out successfully.');
    // }

    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();

        // Keep other guards logged in
        $request->session()->regenerate();

        $request->session()->regenerateToken();

        return redirect()
            ->route('admin.login')
            ->with('success', 'Admin logged out successfully.');
    }
}
