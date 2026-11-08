<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class RegisterController extends Controller
{
    public function show()
    {
        return view('auth.register');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:25', 'unique:users,phone'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
            'address' => ['nullable', 'string', 'max:255'],
        ], [
            'phone.required' => 'আপনার মোবাইল নাম্বার দিন।',
            'phone.unique' => 'এই মোবাইল নাম্বার দিয়ে ইতিমধ্যে একটি অ্যাকাউন্ট তৈরি করা হয়েছে।',
            'email.required' => 'আপনার ইমেইল অ্যাড্রেস দিন।',
            'email.unique' => 'এই ইমেইল দিয়ে ইতিমধ্যে একটি অ্যাকাউন্ট তৈরি করা হয়েছে।',
            'password.min' => 'পাসওয়ার্ড কমপক্ষে ৬ অক্ষরের হতে হবে।',
            'password.confirmed' => 'পাসওয়ার্ড নিশ্চিতকরণ মিলছে না।',
        ]);

        $user = User::create([
            'name' => $data['name'],
            'phone' => $data['phone'],
            'email' => $data['email'],
            'address' => $data['address'] ?? null,
            'password' => $data['password'],
            'is_admin' => false,
        ]);

        $role = Role::firstOrCreate(['name' => 'User']);
        $user->assignRole($role);

        Auth::login($user);

        return redirect()->intended(route('dashboard'))
            ->with('success', 'স্বাগতম! আপনার অ্যাকাউন্ট সফলভাবে তৈরি হয়েছে।');
    }
}
