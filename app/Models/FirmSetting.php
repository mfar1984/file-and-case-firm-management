<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FirmSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'firm_name',
        'registration_number',
        'phone_number',
        'fax_number',
        'email',
        'address',
        'website',
        'tax_registration_number'
    ];

    /**
     * Get the first (and only) firm settings record
     */
    public static function getFirmSettings()
    {
        return static::first() ?? static::create([
            'firm_name' => 'Naeelah Saleh & Associates',
            'registration_number' => 'LLP0012345',
            'phone_number' => '+603-1234-5678',
            'fax_number' => '+603-1234-5679',
            'email' => 'info@naaelahsaleh.my',
            'address' => 'No. 123, Jalan Tun Razak, 50400 Kuala Lumpur, Malaysia',
            'website' => 'https://www.naaelahsaleh.my',
            'tax_registration_number' => '123456789012'
        ]);
    }
}
