<?php

namespace App\Models;

use App\Traits\HasFirmScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Specialization extends Model
{
    use HasFactory, HasFirmScope;

    protected $fillable = [
        'specialist_name',
        'description',
        'status',
        'firm_id'
    ];

    protected $casts = [
        'status' => 'string'
    ];

    /**
     * Scope for active specializations
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope for inactive specializations
     */
    public function scopeInactive($query)
    {
        return $query->where('status', 'inactive');
    }

    /**
     * Get the firm that owns this specialization
     */
    public function firm(): BelongsTo
    {
        return $this->belongsTo(Firm::class);
    }
}
