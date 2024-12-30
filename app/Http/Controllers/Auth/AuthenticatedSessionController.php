<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

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
        // Authenticate the user
        $request->authenticate();

        // Regenerate session after authentication
        $request->session()->regenerate();

        // Get the authenticated user
        $user = Auth::user();

        // Debug log the user's role
        \Log::info('Logged-in user role: ' . $user->role);  // Debug line

        // Check the role of the authenticated user and redirect accordingly
        switch ($user->role) {
            case 'admin':
                return redirect()->intended(route('admin.dashboard'));
            case 'developer':
                // If developers should not access the dashboard, apply the restriction here.
                return redirect()->intended(route('developer.dashboard'));
            case 'staff':
                return redirect()->intended(route('staff.dashboard'));
            default:
                // Log out the user if the role is invalid
                Auth::logout();
                return redirect()->route('login')->withErrors(['access' => 'Access Denied. Invalid role.']);
        }
    }


    public function destroy(Request $request)
    {
        Auth::logout();

        // Invalidate the session
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Redirect to the login page or any other page
        return redirect()->route('login');
    }

}

