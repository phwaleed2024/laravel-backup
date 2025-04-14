<?php

namespace Avcodewizard\LaravelBackup\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckLaravelBackupAccess
{
    public function handle(Request $request, Closure $next)
    {
        if (!config('laravelBackup.check_access')) {
            return $next($request);
        }

        $user = Auth::user();
        if (!$user) {
            abort(403, 'Unauthorized - no user authenticated.');
        }

        if (!method_exists($user, 'hasRole')) {
            abort(403, 'User Role Not Implemented!');
        }

        if (!$user->hasAnyRole(config('laravelBackup.allowed_roles'))) {
            abort(403, 'Unauthorized - insufficient permission.');
        }

        return $next($request);
    }
}
