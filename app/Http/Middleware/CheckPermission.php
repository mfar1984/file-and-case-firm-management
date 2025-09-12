<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $permission): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();

        // Set firm context for permission checking if user has firm_id
        if ($user->firm_id) {
            setPermissionsTeamId($user->firm_id);
        }

        // Check permission with firm context
        if (!$user->hasPermissionTo($permission)) {
            abort(403, 'Unauthorized action.');
        }

        return $next($request);
    }
} 