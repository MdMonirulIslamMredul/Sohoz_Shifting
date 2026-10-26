<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function index()
    {
        return view('admin.users.index', [
            'users' => User::with('roles')->orderBy('name')->paginate(15),
        ]);
    }

    public function create()
    {
        return view('admin.users.create', [
            'roles' => Role::orderBy('name')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'roles' => ['nullable', 'array'],
            'roles.*' => ['string', 'exists:roles,name'],
        ]);

        $adminRoles = ['Super Admin', 'Admin', 'Branch Admin', 'Product Admin'];

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => $data['password'],
            'is_admin' => ! empty(array_intersect($adminRoles, $data['roles'] ?? [])),
        ]);

        $user->syncRoles($data['roles'] ?? []);

        return redirect()->route('admin.users.index')->with('success', 'User registered successfully.');
    }

    public function edit(User $user)
    {
        return view('admin.users.edit', [
            'user' => $user,
            'roles' => Role::orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'roles' => ['nullable', 'array'],
            'roles.*' => ['string', 'exists:roles,name'],
        ]);

        $user->syncRoles($data['roles'] ?? []);

        return redirect()->route('admin.users.index')->with('success', 'User roles updated successfully.');
    }

    public function destroy(User $user)
    {
        if ($user->email === 'admin@admin.com') {
            return back()->with('error', 'The default admin user cannot be deleted.');
        }

        $user->delete();

        return back()->with('success', 'User deleted successfully.');
    }
}
