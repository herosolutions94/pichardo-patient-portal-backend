<?php

namespace App\Http\Middleware;

use Closure;

class AdminPermissions
{
    public function handle($request, Closure $next)
    {
        $permissions = $request->session()->get('permissions');
        
        // You can also modify or validate the session data here

        $request->attributes->add(['permissions' => $permissions]);

        return $next($request);
    }
}
