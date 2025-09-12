<?php

namespace App\Models;

use App\Traits\HasFirmScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WeatherSetting extends Model
{
    use HasFactory, HasFirmScope;

    protected $fillable = [
        'api_provider',
        'api_key',
        'location_name',
        'postcode',
        'country',
        'state',
        'city',
        'latitude',
        'longitude',
        'units',
        'is_active',
        'notes',
        'firm_id'
    ];

    protected $casts = [
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'is_active' => 'boolean',
    ];

    public static function getActiveSettings()
    {
        return static::where('is_active', true)->first();
    }

    public function getLocationString()
    {
        $parts = [];
        if ($this->city) $parts[] = $this->city;
        if ($this->state) $parts[] = $this->state;
        if ($this->country) $parts[] = $this->country;

        return implode(', ', $parts);
    }

    public function getCoordinatesString()
    {
        return $this->latitude . ',' . $this->longitude;
    }

    /**
     * Get the firm that owns this setting
     */
    public function firm(): BelongsTo
    {
        return $this->belongsTo(Firm::class);
    }
}
