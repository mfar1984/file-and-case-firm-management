<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Symfony\Component\HttpFoundation\Response;

class FirmScope
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Only apply firm scope if user is authenticated
        if (Auth::check()) {
            $user = Auth::user();

            // Determine which firm to use
            $firmId = null;
            $currentFirm = null;

            // Super Administrator can switch firms via session
            if ($user->hasRole('Super Administrator')) {
                if (session('current_firm_id')) {
                    $firmId = session('current_firm_id');
                    $currentFirm = \App\Models\Firm::find($firmId);
                } elseif ($user->firm_id) {
                    // Super Admin hasn't selected a firm - default to their own firm
                    $firmId = $user->firm_id;
                    $currentFirm = $user->firm;
                    // Set session for consistency
                    session(['current_firm_id' => $firmId]);
                }
            } elseif ($user->firm_id) {
                // Regular users use their assigned firm
                $firmId = $user->firm_id;
                $currentFirm = $user->firm;
            }

            // Set current firm context for Spatie Permission teams
            if ($firmId && $currentFirm) {
                // Set the team context for Spatie Permission
                Config::set('permission.teams', true);
                setPermissionsTeamId($firmId);

                // Store firm context in session for easy access
                session(['current_firm_id' => $firmId]);

                // Share firm data with all views
                view()->share('currentFirm', $currentFirm);
            }
        }

        return $next($request);
    }
}
