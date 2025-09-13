<?php

namespace App\Models;

use App\Traits\HasFirmScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FirmSetting extends Model
{
    use HasFactory, HasFirmScope;

    protected $fillable = [
        'firm_name',
        'registration_number',
        'phone_number',
        'email',
        'address',
        'website',
        'tax_registration_number',
        'firm_id'
    ];

    /**
     * Get firm settings for current firm context
     */
    public static function getFirmSettings()
    {
        // Get current firm context
        $user = auth()->user();
        $firmId = session('current_firm_id') ?? $user->firm_id;

        if ($firmId) {
            // Try to get existing settings for this firm
            $settings = static::where('firm_id', $firmId)->first();

            if ($settings) {
                return $settings;
            }

            // Get firm data to create default settings
            $firm = Firm::find($firmId);

            if ($firm) {
                return static::create([
                    'firm_name' => $firm->name,
                    'registration_number' => $firm->registration_number ?? 'REG-' . str_pad($firmId, 7, '0', STR_PAD_LEFT),
                    'phone_number' => $firm->phone ?? '+603-0000-0000',
                    'email' => $firm->email ?? 'info@' . strtolower(str_replace(' ', '', $firm->name)) . '.com',
                    'address' => $firm->address ?? 'Address not set',
                    'website' => '',
                    'tax_registration_number' => $firm->settings['tax_registration_number'] ?? '',
                    'firm_id' => $firmId
                ]);
            }
        }

        // Fallback for backward compatibility
        return static::first() ?? static::create([
            'firm_name' => 'Default Firm',
            'registration_number' => 'DEFAULT001',
            'phone_number' => '+603-0000-0000',
            'email' => 'info@defaultfirm.com',
            'address' => 'Default Address',
            'website' => '',
            'tax_registration_number' => '',
            'firm_id' => 1
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
