<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UserDashboardController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();

        if ($user?->is_admin) {
            return redirect()->route('admin.dashboard');
        }

        return view('user.dashboard', compact('user'));
    }
}
