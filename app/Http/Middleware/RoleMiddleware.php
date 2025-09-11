<?php

namespace App\Http\Middleware;

use Closure;

class RoleMiddleware
{
    public function handle($request, Closure $next, ...$roles)
    {
        $u = $request->user();
        if (!$u || !$u->role || !in_array($u->role->naziv_role, $roles)) {
            abort(403);
        }
        return $next($request);
    }
}
