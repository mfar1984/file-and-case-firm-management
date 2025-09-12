<?php

namespace App\Models;

use App\Traits\HasFirmScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SecuritySetting extends Model
{
    use HasFactory, HasFirmScope;

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
        'audit_log_enabled',
        'firm_id'
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
     * Get security settings for current firm context
     */
    public static function getSecuritySettings()
    {
        // Get current firm context
        $user = auth()->user();
        $firmId = session('current_firm_id') ?? $user->firm_id;

        if ($firmId) {
            $settings = static::where('firm_id', $firmId)->first();
            if ($settings) {
                return $settings;
            }
        }

        // Create default settings for current firm
        return static::create([
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
            'audit_log_enabled' => true,
            'firm_id' => $firmId
        ]);
    }

    /**
     * Get the firm that owns this setting
     */
    public function firm(): BelongsTo
    {
        return $this->belongsTo(Firm::class);
    }
}
