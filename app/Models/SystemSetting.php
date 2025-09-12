<?php

namespace App\Models;

use App\Traits\HasFirmScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SystemSetting extends Model
{
    use HasFactory, HasFirmScope;

    protected $fillable = [
        'date_format',
        'time_format',
        'time_zone',
        'maintenance_mode',
        'maintenance_message',
        'session_timeout',
        'debug_mode',
        'firm_id'
    ];

    protected $casts = [
        'maintenance_mode' => 'boolean',
        'debug_mode' => 'boolean'
    ];

    /**
     * Get system settings for current firm context
     */
    public static function getSystemSettings()
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
            'date_format' => 'l, j F Y',
            'time_format' => 'g:i:s a',
            'time_zone' => 'Asia/Kuala_Lumpur',
            'maintenance_mode' => false,
            'maintenance_message' => null,
            'session_timeout' => 120,
            'debug_mode' => false,
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
