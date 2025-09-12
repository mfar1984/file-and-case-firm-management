<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Traits\HasFirmScope;

class Agency extends Model
{
    use HasFactory, HasFirmScope;

    protected $fillable = [
        'name',
        'status',
        'firm_id',
    ];

    /**
     * Get the firm that owns this agency
     */
    public function firm(): BelongsTo
    {
        return $this->belongsTo(Firm::class);
    }

    /**
     * Scope for active agencies
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope for inactive agencies
     */
    public function scopeInactive($query)
    {
        return $query->where('status', 'inactive');
    }
}