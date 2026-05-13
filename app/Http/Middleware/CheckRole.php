<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    /**
     * Handle an incoming request.
     * Usage in routes: ->middleware('role:Administrador') or ->middleware('role:Patient,Admin')
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        $user = Auth::user();

        if (! $user) {
            // not authenticated
            return redirect()->route('login');
        }

        // If User model has 'role' relation returning Role model
        $userRole = null;
        if (method_exists($user, 'role')) {
            try {
                $r = $user->role;
                $userRole = $r ? ($r->nombre_rol ?? $r->name ?? null) : null;
            } catch (\Throwable $e) {
                $userRole = null;
            }
        }

        // if roles not provided, allow
        if (empty($roles)) {
            return $next($request);
        }

        foreach ($roles as $role) {
            if (strcasecmp(trim($role), trim($userRole)) === 0) {
                return $next($request);
            }
        }

        // not authorized
        abort(403, 'No autorizado');
    }
}
