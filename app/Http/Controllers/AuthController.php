<?php

namespace App\Http\Controllers;

use App\Http\Requests\AdminLoginRequest;
use App\Http\Requests\VoterLoginRequest;
use App\Models\Voter;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthController extends Controller
{
    /**
     * Show the admin login form.
     */
    public function showAdminLoginForm(): View
    {
        return view('auth.admin-login');
    }

    /**
     * Show the voter login form.
     */
    public function showVoterLoginForm(): View
    {
        return view('auth.voter-login');
    }

    /**
     * Handle admin login request.
     *
     * Uses Laravel's built-in authentication with username field.
     * Protected against SQL injection via Eloquent/Query Builder parameter binding,
     * XSS via Blade's {{ }} escaping, and CSRF via Laravel's CSRF token middleware.
     */
    public function adminLogin(AdminLoginRequest $request): RedirectResponse
    {
        $credentials = $request->only('username', 'password');
        $credentials['is_admin'] = true;

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            return redirect()->intended(route('admin.dashboard'))
                ->with('success', 'Welcome back, ' . Auth::user()->name . '!');
        }

        return back()
            ->withInput($request->only('username'))
            ->withErrors([
                'username' => 'The provided credentials do not match our records.',
            ]);
    }

    /**
     * Handle voter login using Rwanda National ID Numbers.
     *
     * Voters authenticate via their unique Rwanda National ID Numbers.
     * The session stores the voter_id for vote casting.
     */
    public function voterLogin(VoterLoginRequest $request): RedirectResponse
    {
        $voter = Voter::where('national_id', $request->national_id)->first();

        if (!$voter) {
            return back()
                ->withInput($request->only('national_id'))
                ->withErrors([
                    'national_id' => 'No voter record found with this Rwanda National ID Numbers.',
                ]);
        }

        // Store voter in session
        $request->session()->put('voter_id', $voter->voter_id);
        $request->session()->put('voter_name', $voter->name);

        return redirect()->route('voting.index')
            ->with('success', 'Welcome, ' . $voter->name . '! You can now cast your vote.');
    }

    /**
     * Handle admin logout.
     */
    public function adminLogout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login')
            ->with('success', 'You have been logged out successfully.');
    }

    /**
     * Handle voter logout.
     */
    public function voterLogout(Request $request): RedirectResponse
    {
        $request->session()->forget(['voter_id', 'voter_name']);

        return redirect()->route('voter.login')
            ->with('success', 'You have been logged out successfully.');
    }
}
