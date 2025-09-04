<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CaseTimeline extends Model
{
    use HasFactory;

    protected $fillable = [
        'case_id',
        'event_type',
        'title',
        'description',
        'status',
        'event_date',
        'metadata',
        'created_by'
    ];

    protected $casts = [
        'event_date' => 'datetime',
        'metadata' => 'array'
    ];

    // Relationships
    public function case()
    {
        return $this->belongsTo(CourtCase::class, 'case_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Scopes
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeByCase($query, $caseId)
    {
        return $query->where('case_id', $caseId);
    }

    // Helper methods
    public function getStatusColor()
    {
        return match($this->status) {
            'completed' => 'bg-green-500',
            'active' => 'bg-blue-500',
            'cancelled' => 'bg-red-500',
            'pending' => 'bg-yellow-500', // Keep for existing data, but not selectable
            default => 'bg-gray-500'
        };
    }

    public function getStatusIcon()
    {
        return match($this->status) {
            'completed' => 'check',
            'active' => 'radio_button_checked',
            'cancelled' => 'cancel',
            'pending' => 'schedule', // Keep for existing data, but not selectable
            default => 'circle'
        };
    }

    public function isCompleted()
    {
        return $this->status === 'completed';
    }

    public function isActive()
    {
        return $this->status === 'active';
    }

    // Removed isPending() as 'pending' is no longer a primary selectable status
}
