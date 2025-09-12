<?php

namespace App\Services;

use App\Models\Firm;
use App\Models\FirmSetting;

class FirmSettingsService
{
    /**
     * Get firm settings for current firm context
     * This method provides consistent firm settings across all PDF generation
     */
    public static function getFirmSettingsForPdf()
    {
        // Get current firm context
        $user = auth()->user();
        $firmId = session('current_firm_id') ?? $user->firm_id;
        $firm = Firm::find($firmId);

        return (object) [
            'firm_name' => $firm ? $firm->name : 'Naeelah Saleh & Associates',
            'registration_number' => $firm ? $firm->registration_number : 'LLP0012345',
            'address' => $firm ? $firm->address : 'No. 123, Jalan Tun Razak, 50400 Kuala Lumpur, Malaysia',
            'phone_number' => $firm ? $firm->phone : '+6019-3186436',
            'email' => $firm ? $firm->email : 'info@naaelahsaleh.my',
            'tax_registration_number' => $firm && isset($firm->settings['tax_registration_number'])
                ? $firm->settings['tax_registration_number']
                : 'W24-2507-32000179',
            'fax_number' => $firm && isset($firm->settings['fax_number'])
                ? $firm->settings['fax_number']
                : '',
            'website' => $firm && isset($firm->settings['website'])
                ? $firm->settings['website']
                : '',
            'logo' => $firm ? $firm->logo : null
        ];
    }

    /**
     * Get firm settings for current firm context (legacy compatibility)
     * This method maintains backward compatibility with existing FirmSetting usage
     */
    public static function getFirmSettings()
    {
        return self::getFirmSettingsForPdf();
    }

    /**
     * Update firm settings for current firm
     */
    public static function updateFirmSettings(array $data)
    {
        $user = auth()->user();
        $firmId = session('current_firm_id') ?? $user->firm_id;
        
        if (!$firmId) {
            throw new \Exception('No firm context available');
        }

        $firm = Firm::findOrFail($firmId);
        
        // Update firm basic information
        $firm->update([
            'name' => $data['firm_name'] ?? $firm->name,
            'registration_number' => $data['registration_number'] ?? $firm->registration_number,
            'phone' => $data['phone_number'] ?? $firm->phone,
            'email' => $data['email'] ?? $firm->email,
            'address' => $data['address'] ?? $firm->address,
        ]);

        // Update firm settings JSON
        $settings = $firm->settings ?? [];
        $settings['fax_number'] = $data['fax_number'] ?? $settings['fax_number'] ?? '';
        $settings['website'] = $data['website'] ?? $settings['website'] ?? '';
        $settings['tax_registration_number'] = $data['tax_registration_number'] ?? $settings['tax_registration_number'] ?? '';

        $firm->update(['settings' => $settings]);

        return $firm;
    }

    /**
     * Get current firm ID from context
     */
    public static function getCurrentFirmId()
    {
        $user = auth()->user();
        return session('current_firm_id') ?? $user->firm_id;
    }

    /**
     * Get current firm instance
     */
    public static function getCurrentFirm()
    {
        $firmId = self::getCurrentFirmId();
        return $firmId ? Firm::find($firmId) : null;
    }
}
