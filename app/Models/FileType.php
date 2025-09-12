<?php

namespace App\Models;

use App\Traits\HasFirmScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FileType extends Model
{
    use HasFactory, HasFirmScope;

    protected $fillable = [
        'code',
        'description',
        'status',
        'firm_id',
    ];

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Get the firm that owns this file type
     */
    public function firm(): BelongsTo
    {
        return $this->belongsTo(Firm::class);
    }
}
