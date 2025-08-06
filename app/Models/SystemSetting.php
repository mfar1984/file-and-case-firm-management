<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SystemSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'date_format',
        'time_format',
        'time_zone',
        'maintenance_mode',
        'maintenance_message',
        'session_timeout',
        'debug_mode'
    ];

    protected $casts = [
        'maintenance_mode' => 'boolean',
        'debug_mode' => 'boolean'
    ];

    /**
     * Get the first (and only) system settings record
     */
    public static function getSystemSettings()
    {
        return static::first() ?? static::create([
            'date_format' => 'l, j F Y',
            'time_format' => 'g:i:s a',
            'time_zone' => 'Asia/Kuala_Lumpur',
            'maintenance_mode' => false,
            'maintenance_message' => null,
            'session_timeout' => 120,
            'debug_mode' => false
        ]);
    }
}
