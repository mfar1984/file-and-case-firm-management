<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SecuritySetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'two_factor_auth',
        'password_expiry',
        'password_expiry_days',
        'force_password_change',
        'max_login_attempts',
        'lockout_duration',
        'session_timeout_enabled',
        'session_timeout_minutes',
        'ip_whitelist_enabled',
        'ip_whitelist',
        'audit_log_enabled'
    ];

    protected $casts = [
        'two_factor_auth' => 'boolean',
        'password_expiry' => 'boolean',
        'force_password_change' => 'boolean',
        'session_timeout_enabled' => 'boolean',
        'ip_whitelist_enabled' => 'boolean',
        'audit_log_enabled' => 'boolean'
    ];

    /**
     * Get the first (and only) security settings record
     */
    public static function getSecuritySettings()
    {
        return static::first() ?? static::create([
            'two_factor_auth' => false,
            'password_expiry' => false,
            'password_expiry_days' => 90,
            'force_password_change' => false,
            'max_login_attempts' => 5,
            'lockout_duration' => 30,
            'session_timeout_enabled' => true,
            'session_timeout_minutes' => 120,
            'ip_whitelist_enabled' => false,
            'ip_whitelist' => null,
            'audit_log_enabled' => true
        ]);
    }
}
