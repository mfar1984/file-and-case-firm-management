<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Auth;

class PermissionHelper
{
    /**
     * Set firm/team context for Spatie Permission
     * REQUIRED because config/permission.php has teams => true
     */
    private static function setFirmContext()
    {
        if (Auth::check()) {
            $firmId = session('current_firm_id') ?? Auth::user()->firm_id;
            if ($firmId) {
                setPermissionsTeamId($firmId);
            }
        }
    }

    /**
     * Check if current user has a specific permission
     */
    public static function hasPermission($permission)
    {
        if (!Auth::check()) {
            return false;
        }

        self::setFirmContext();
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

        self::setFirmContext();
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

        self::setFirmContext();
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

        self::setFirmContext();
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

        self::setFirmContext();
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

        self::setFirmContext();
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

        self::setFirmContext();
        return Auth::user()->getRoleNames();
    }
} 