<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\DdosSetting;

class DdosSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            // General Settings
            [
                'key' => 'enabled',
                'value' => 'true',
                'type' => 'boolean',
                'group' => 'general',
                'description' => 'Enable DDoS protection system'
            ],
            
            // Rate Limiting Settings
            [
                'key' => 'requests_per_minute',
                'value' => '60',
                'type' => 'integer',
                'group' => 'rate_limiting',
                'description' => 'Maximum requests per minute per IP'
            ],
            [
                'key' => 'requests_per_hour',
                'value' => '1000',
                'type' => 'integer',
                'group' => 'rate_limiting',
                'description' => 'Maximum requests per hour per IP'
            ],
            [
                'key' => 'block_duration',
                'value' => '3600',
                'type' => 'integer',
                'group' => 'rate_limiting',
                'description' => 'Block duration in seconds when limit exceeded'
            ],
            
            // Shieldon Settings
            [
                'key' => 'shieldon_enabled',
                'value' => 'false',
                'type' => 'boolean',
                'group' => 'shieldon',
                'description' => 'Enable Shieldon advanced protection'
            ],
            
            // Logging Settings
            [
                'key' => 'logging_enabled',
                'value' => 'true',
                'type' => 'boolean',
                'group' => 'logging',
                'description' => 'Enable security logging'
            ],

            // Layer 7 Protection Settings
            [
                'key' => 'layer7_protection.enabled',
                'value' => 'true',
                'type' => 'boolean',
                'group' => 'layer7_protection',
                'description' => 'Enable advanced Layer 7 protection'
            ],
            [
                'key' => 'layer7_protection.http_flood.enabled',
                'value' => 'true',
                'type' => 'boolean',
                'group' => 'layer7_protection',
                'description' => 'Enable HTTP flood protection'
            ],
            [
                'key' => 'layer7_protection.http_flood.max_requests_per_10s',
                'value' => '20',
                'type' => 'integer',
                'group' => 'layer7_protection',
                'description' => 'Maximum requests per 10 seconds'
            ],
            [
                'key' => 'layer7_protection.http_flood.max_concurrent_connections',
                'value' => '5',
                'type' => 'integer',
                'group' => 'layer7_protection',
                'description' => 'Maximum concurrent connections per IP'
            ],
            [
                'key' => 'layer7_protection.session_protection.enabled',
                'value' => 'true',
                'type' => 'boolean',
                'group' => 'layer7_protection',
                'description' => 'Enable session protection'
            ],
            [
                'key' => 'layer7_protection.session_protection.max_session_attempts',
                'value' => '10',
                'type' => 'integer',
                'group' => 'layer7_protection',
                'description' => 'Maximum session attempts per 5 minutes'
            ],
            [
                'key' => 'layer7_protection.session_protection.max_session_creations',
                'value' => '5',
                'type' => 'integer',
                'group' => 'layer7_protection',
                'description' => 'Maximum session creations per minute'
            ],
            [
                'key' => 'layer7_protection.header_validation.enabled',
                'value' => 'true',
                'type' => 'boolean',
                'group' => 'layer7_protection',
                'description' => 'Enable header validation'
            ],
            [
                'key' => 'layer7_protection.payload_analysis.enabled',
                'value' => 'true',
                'type' => 'boolean',
                'group' => 'layer7_protection',
                'description' => 'Enable payload analysis'
            ],
            [
                'key' => 'layer7_protection.progressive_blocking.enabled',
                'value' => 'true',
                'type' => 'boolean',
                'group' => 'layer7_protection',
                'description' => 'Enable progressive blocking'
            ],
            [
                'key' => 'layer7_protection.progressive_blocking.base_duration',
                'value' => '3600',
                'type' => 'integer',
                'group' => 'layer7_protection',
                'description' => 'Base block duration in seconds'
            ],
            [
                'key' => 'layer7_protection.progressive_blocking.max_duration',
                'value' => '86400',
                'type' => 'integer',
                'group' => 'layer7_protection',
                'description' => 'Maximum block duration in seconds'
            ],
            [
                'key' => 'layer7_protection.progressive_blocking.multiplier',
                'value' => '2',
                'type' => 'numeric',
                'group' => 'layer7_protection',
                'description' => 'Block duration multiplier per violation'
            ],
        ];

        foreach ($settings as $setting) {
            DdosSetting::updateOrCreate(
                ['key' => $setting['key'], 'group' => $setting['group']],
                $setting
            );
        }
    }
}
