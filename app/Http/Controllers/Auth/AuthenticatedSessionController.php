<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use App\Models\User;
use App\Models\ActivityLog;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        // 1. Attempt authentication
        $request->authenticate();

        // 2. Regenerate session to prevent session fixation
        $request->session()->regenerate();

        // 3. Get the currently authenticated user
        /** @var User|null $user */
        $user = Auth::user();
        if ($user && $user->isAdmin()) {
            ActivityLog::record('Admin Login', "{$user->name} logged in.", $user->id);
        }

        // 4. Redirect based on role
        return redirect()->intended($user && $user->isAdmin() ? '/admin' : ($user && $user->isManager() ? '/admin/task-dashboard' : '/dashboard'));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
