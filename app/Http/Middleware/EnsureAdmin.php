<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->user()) {
            abort(403, 'Unauthorized access.');
        }

        $user = $request->user();
        $adminRoles = ['Super Admin', 'Admin', 'Branch Admin', 'Product Admin'];

        if (
            ! $user->is_admin &&
            ! $user->hasRole($adminRoles) &&
            ! $user->hasPermissionTo('access admin panel')
        ) {
            abort(403, 'Unauthorized access.');
        }

        return $next($request);
    }
}
