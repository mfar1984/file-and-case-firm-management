<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClientAddress extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'address_line1',
        'address_line2',
        'address_line3',
        'postcode',
        'city',
        'state',
        'country',
        'is_primary'
    ];

    protected $casts = [
        'is_primary' => 'boolean',
    ];

    /**
     * Get the client that owns the address.
     */
    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    /**
     * Scope to get primary address
     */
    public function scopePrimary($query)
    {
        return $query->where('is_primary', true);
    }

    /**
     * Get full address as string
     */
    public function getFullAddressAttribute()
    {
        $parts = [];
        if (!empty($this->address_line1)) $parts[] = $this->address_line1;
        if (!empty($this->address_line2)) $parts[] = $this->address_line2;
        if (!empty($this->address_line3)) $parts[] = $this->address_line3;
        if (!empty($this->postcode)) $parts[] = $this->postcode;
        if (!empty($this->city)) $parts[] = $this->city;
        if (!empty($this->state)) $parts[] = $this->state;
        if (!empty($this->country)) $parts[] = $this->country;
        
        return implode(', ', $parts);
    }
}
