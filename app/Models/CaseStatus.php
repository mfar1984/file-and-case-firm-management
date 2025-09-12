<?php

namespace App\Models;

use App\Traits\HasFirmScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CaseStatus extends Model
{
    use HasFactory, HasFirmScope;

    protected $fillable = [
        'name',
        'description',
        'color',
        'status',
        'firm_id',
    ];

    protected $casts = [
        'status' => 'string',
        'color' => 'string',
    ];

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Get the firm that owns this case status
     */
    public function firm(): BelongsTo
    {
        return $this->belongsTo(Firm::class);
    }
}
