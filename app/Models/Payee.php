<?php

namespace App\Models;

use App\Traits\HasFirmScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payee extends Model
{
    use HasFactory, HasFirmScope;

    protected $fillable = [
        'name',
        'address',
        'contact_person',
        'phone',
        'email',
        'category',
        'is_active',
        'firm_id'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function getDisplayNameAttribute()
    {
        return $this->name . ' (' . $this->category . ')';
    }

    /**
     * Get the firm that owns this payee
     */
    public function firm(): BelongsTo
    {
        return $this->belongsTo(Firm::class);
    }
}
