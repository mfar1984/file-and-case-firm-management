<?php

namespace App\Models;

use App\Traits\HasFirmScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EventStatus extends Model
{
    use HasFactory, HasFirmScope;

    protected $fillable = [
        'name',
        'description',
        'background_color',
        'icon',
        'status',
        'sort_order',
        'firm_id',
    ];

    protected $casts = [
        'status' => 'string',
        'sort_order' => 'integer',
    ];

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('name');
    }

    // Relationships
    public function timelineEvents()
    {
        return $this->hasMany(CaseTimeline::class, 'status', 'name');
    }

    // Helper methods
    public function getDisplayNameAttribute()
    {
        return ucfirst(str_replace('_', ' ', $this->name));
    }

    public function isActive()
    {
        return $this->status === 'active';
    }

    /**
     * Get the firm that owns this event status
     */
    public function firm(): BelongsTo
    {
        return $this->belongsTo(Firm::class);
    }
}
