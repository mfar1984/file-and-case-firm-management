<?php

namespace App\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Facades\Auth;

class FirmScope implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     */
    public function apply(Builder $builder, Model $model): void
    {
        // Skip only for specific console commands that don't need firm scope (migrations, etc.)
        // But allow firm scope for tinker and other interactive commands
        if (app()->runningInConsole()) {
            $command = $_SERVER['argv'][1] ?? '';
            $skipCommands = ['migrate', 'migrate:fresh', 'migrate:reset', 'migrate:rollback', 'db:seed', 'queue:work', 'schedule:run'];

            if (in_array($command, $skipCommands)) {
                return;
            }
        }

        // Skip if we're already processing firm scope to prevent recursion
        if (app()->bound('firm_scope_active')) {
            return;
        }

        // Only apply firm scope if user is authenticated
        if (!Auth::check()) {
            // If not authenticated, no data should be visible
            $builder->whereRaw('1 = 0');
            return;
        }

        // Mark that firm scope is active
        app()->instance('firm_scope_active', true);

        try {
            $user = Auth::user();

            // Get firm ID from user or session
            $firmId = null;

            // Check if user has Super Administrator role using direct DB query to avoid recursion
            $isSuperAdmin = \DB::table('model_has_roles')
                ->join('roles', 'model_has_roles.role_id', '=', 'roles.id')
                ->where('model_has_roles.model_type', 'App\\Models\\User')
                ->where('model_has_roles.model_id', $user->id)
                ->where('roles.name', 'Super Administrator')
                ->exists();

            if ($isSuperAdmin) {
                // Super Administrator can switch firms via session
                if (session('current_firm_id')) {
                    $firmId = session('current_firm_id');
                } else {
                    // Super Admin hasn't selected a firm - show all data
                    return;
                }
            } elseif ($user->firm_id) {
                // Regular users use their assigned firm
                $firmId = $user->firm_id;
            }

            // Apply firm filtering if we have a firm ID
            if ($firmId) {
                $builder->where($model->getTable() . '.firm_id', $firmId);
            } else {
                // If no firm context, they can't see any data
                $builder->whereRaw('1 = 0');
            }
        } finally {
            // Always clean up
            app()->forgetInstance('firm_scope_active');
        }
    }
}
