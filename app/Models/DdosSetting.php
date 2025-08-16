<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DdosSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'value',
        'type',
        'group',
        'description'
    ];

    protected $casts = [
        'value' => 'string',
    ];

    /**
     * Get setting value by key
     */
    public static function getValue($key, $default = null)
    {
        $setting = static::where('key', $key)->first();
        return $setting ? $setting->value : $default;
    }

    /**
     * Set setting value by key
     */
    public static function setValue($key, $value, $type = 'string', $group = 'general', $description = null)
    {
        return static::updateOrCreate(
            ['key' => $key],
            [
                'value' => $value,
                'type' => $type,
                'group' => $group,
                'description' => $description
            ]
        );
    }

    /**
     * Get all settings by group
     */
    public static function getByGroup($group)
    {
        return static::where('group', $group)->get();
    }

    /**
     * Get all settings as array
     */
    public static function getAllAsArray()
    {
        $settings = static::all();
        $result = [];
        
        foreach ($settings as $setting) {
            $result[$setting->key] = $setting->value;
        }
        
        return $result;
    }

    /**
     * Get settings in nested structure
     */
    public static function getNestedStructure()
    {
        $settings = static::all();
        $result = [
            'enabled' => false,
            'rate_limiting' => [
                'requests_per_minute' => 60,
                'requests_per_hour' => 1000,
                'block_duration' => 3600,
            ],
            'shieldon' => [
                'enabled' => false,
            ],
            'logging' => [
                'enabled' => false,
            ],
            'layer7_protection' => [
                'enabled' => false,
                'http_flood' => [
                    'enabled' => false,
                    'max_requests_per_10s' => 20,
                    'max_concurrent_connections' => 5,
                ],
                'session_protection' => [
                    'enabled' => false,
                    'max_session_attempts' => 10,
                    'max_session_creations' => 5,
                ],
                'header_validation' => [
                    'enabled' => false,
                ],
                'payload_analysis' => [
                    'enabled' => false,
                ],
                'progressive_blocking' => [
                    'enabled' => false,
                    'base_duration' => 3600,
                    'max_duration' => 86400,
                    'multiplier' => 2,
                ],
            ]
        ];
        
        foreach ($settings as $setting) {
            if ($setting->group === 'general') {
                $result[$setting->key] = $setting->value;
            } elseif ($setting->group === 'rate_limiting') {
                $result['rate_limiting'][$setting->key] = $setting->value;
            } elseif ($setting->group === 'shieldon') {
                if ($setting->key === 'shieldon_enabled') {
                    $result['shieldon']['enabled'] = $setting->value;
                }
            } elseif ($setting->group === 'logging') {
                if ($setting->key === 'logging_enabled') {
                    $result['logging']['enabled'] = $setting->value;
                }
            } elseif ($setting->group === 'layer7_protection') {
                // Handle nested Layer 7 protection settings
                $keys = explode('.', $setting->key);
                if (count($keys) === 2) {
                    // Main setting like 'enabled'
                    $result['layer7_protection'][$keys[1]] = $setting->value;
                } elseif (count($keys) === 3) {
                    // Nested setting like 'http_flood.enabled'
                    $result['layer7_protection'][$keys[1]][$keys[2]] = $setting->value;
                }
            }
        }
        
        return $result;
    }
}
