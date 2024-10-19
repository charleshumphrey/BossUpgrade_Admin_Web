<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Session;

class CheckPermissions
{
    public function handle($request, Closure $next, $permission)
    {
        $user = Session::get('staff');

        if (!$user || !isset($user['permissions'][$permission]) || $user['permissions'][$permission] !== 'true') {
            return redirect()->route('unauthorized');
        }

        return $next($request);
    }
}
