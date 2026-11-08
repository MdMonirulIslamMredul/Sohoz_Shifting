<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function show(): View
    {
        return view('auth.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $request->validate([
            'login' => ['required', 'string'],
            'password' => ['required', 'string'],
        ], [
            'login.required' => 'আপনার মোবাইল নাম্বার অথবা ইমেইল দিন।',
            'password.required' => 'আপনার পাসওয়ার্ড দিন।',
        ]);

        $loginInput = trim($request->input('login'));
        $fieldType = filter_var($loginInput, FILTER_VALIDATE_EMAIL) ? 'email' : 'phone';

        $credentials = [
            $fieldType => $loginInput,
            'password' => $request->input('password'),
        ];

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            $user = $request->user();

            if ($user->is_admin) {
                return redirect()->intended(route('admin.dashboard'));
            }

            return redirect()->intended(route('dashboard'))
                ->with('success', 'স্বাগতম, ' . $user->name . '!');
        }

        return back()->withErrors([
            'login' => 'প্রদত্ত মোবাইল/ইমেইল অথবা পাসওয়ার্ড সঠিক নয়।',
        ])->onlyInput('login');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')->with('success', 'সফলভাবে লগআউট হয়েছেন।');
    }
}
