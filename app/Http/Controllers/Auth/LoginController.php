<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class LoginController extends Controller
{
    /**
     * Display the login form.
     */
    public function showLoginForm(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function login(LoginRequest $request): RedirectResponse
    {
        // Throttle login attempts to prevent brute force attacks
        $throttleKey = str()->transliterate($request->input('email')).'|'.$request->ip();
        if (app('cache')->has('login:attempts:'.$throttleKey) && app('cache')->get('login:attempts:'.$throttleKey) >= 5) {
            return back()->withErrors([
                'email' => __('Too many login attempts. Please try again in :seconds seconds.', [
                    'seconds' => app('cache')->get('login:timeout:'.$throttleKey, 60),
                ]),
            ]);
        }

        try {
            $request->authenticate();
        } catch (AuthenticationException $e) {
            app('cache')->put('login:attempts:'.$throttleKey, app('cache')->get('login:attempts:'.$throttleKey, 0) + 1, 60);
            return back()->withErrors([
                'email' => $e->getMessage(),
            ]);
        }

        $request->session()->regenerate();

        return redirect()->intended('/');
    }

    /**
     * Log the user out of the application.
     */
    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
