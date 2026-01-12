<?php

namespace App\Models;

use App\Traits\HasFirmScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ApiSecuritySetting extends Model
{
    use HasFactory, HasFirmScope;

    protected $fillable = [
        'firm_id',
        'rate_limit_per_minute',
        'rate_limit_per_hour',
        'allowed_origins',
        'cors_enabled',
        'ip_blacklist_enabled',
        'auto_blacklist_threshold',
        'blacklist_duration_hours',
        'log_all_requests',
        'log_failed_attempts',
        'api_enabled',
    ];

    protected $casts = [
        'cors_enabled' => 'boolean',
        'ip_blacklist_enabled' => 'boolean',
        'log_all_requests' => 'boolean',
        'log_failed_attempts' => 'boolean',
        'api_enabled' => 'boolean',
    ];

    /**
     * Get API security settings for current firm context
     */
    public static function getApiSecuritySettings()
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
            'firm_id' => $firmId,
            'rate_limit_per_minute' => 60,
            'rate_limit_per_hour' => 1000,
            'allowed_origins' => json_encode([
                'https://naaelahsaleh.co',
                'https://www.naaelahsaleh.co',
                'http://localhost:8000',
                'http://localhost:3000',
            ]),
            'cors_enabled' => true,
            'ip_blacklist_enabled' => true,
            'auto_blacklist_threshold' => 10,
            'blacklist_duration_hours' => 24,
            'log_all_requests' => true,
            'log_failed_attempts' => true,
            'api_enabled' => true,
        ]);
    }

    /**
     * Get the firm that owns this setting
     */
    public function firm(): BelongsTo
    {
        return $this->belongsTo(Firm::class);
    }

    /**
     * Get allowed origins as array
     */
    public function getAllowedOriginsArray(): array
    {
        if (empty($this->allowed_origins)) {
            return [];
        }

        $origins = json_decode($this->allowed_origins, true);
        return is_array($origins) ? $origins : [];
    }

    /**
     * Set allowed origins from array
     */
    public function setAllowedOriginsArray(array $origins): void
    {
        $this->allowed_origins = json_encode($origins);
    }
}
