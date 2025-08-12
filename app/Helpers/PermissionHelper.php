<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Auth;

class PermissionHelper
{
    /**
     * Check if current user has a specific permission
     */
    public static function hasPermission($permission)
    {
        if (!Auth::check()) {
            return false;
        }

        return Auth::user()->hasPermissionTo($permission);
    }

    /**
     * Check if current user has any of the given permissions
     */
    public static function hasAnyPermission($permissions)
    {
        if (!Auth::check()) {
            return false;
        }

        return Auth::user()->hasAnyPermission($permissions);
    }

    /**
     * Check if current user has all of the given permissions
     */
    public static function hasAllPermissions($permissions)
    {
        if (!Auth::check()) {
            return false;
        }

        return Auth::user()->hasAllPermissions($permissions);
    }

    /**
     * Check if current user has a specific role
     */
    public static function hasRole($role)
    {
        if (!Auth::check()) {
            return false;
        }

        return Auth::user()->hasRole($role);
    }

    /**
     * Check if current user has any of the given roles
     */
    public static function hasAnyRole($roles)
    {
        if (!Auth::check()) {
            return false;
        }

        return Auth::user()->hasAnyRole($roles);
    }

    /**
     * Get current user's permissions
     */
    public static function getUserPermissions()
    {
        if (!Auth::check()) {
            return collect();
        }

        return Auth::user()->getAllPermissions();
    }

    /**
     * Get current user's roles
     */
    public static function getUserRoles()
    {
        if (!Auth::check()) {
            return collect();
        }

        return Auth::user()->getRoleNames();
    }
} 