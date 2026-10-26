<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function showLogin(): View
    {
        return view('admin.auth.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            $user = $request->user();
            $adminRoles = ['Super Admin', 'Admin', 'Branch Admin', 'Product Admin'];

            if (! $user?->is_admin && ! $user?->hasRole($adminRoles) && ! $user?->hasPermissionTo('access admin panel')) {
                Auth::logout();

                return back()->withErrors(['email' => 'This account is not an admin account.']);
            }

            return redirect()->route('admin.dashboard')->with('success', 'Welcome back!');
        }

        return back()->withErrors(['email' => 'Invalid email or password.'])->onlyInput('email');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login')->with('success', 'Logged out successfully.');
    }

    public function showPasswordForm(): View
    {
        return view('admin.auth.password');
    }

    public function showProfile(): View
    {
        return view('admin.auth.profile');
    }

    public function updatePassword(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', Password::min(8)],
        ]);

        $request->user()->update(['password' => $data['password']]);

        return back()->with('success', 'Password updated successfully.');
    }
}
