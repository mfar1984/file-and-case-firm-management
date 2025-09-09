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

    public function calendarEvent()
    {
        return $this->hasOne(CalendarEvent::class, 'timeline_event_id');
    }

    public function eventStatus()
    {
        return $this->belongsTo(EventStatus::class, 'status', 'name');
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
        // Try to get from EventStatus first
        $eventStatus = \App\Models\EventStatus::where('name', $this->status)->first();
        if ($eventStatus) {
            return $eventStatus->background_color;
        }

        // Fallback to hardcoded values for backward compatibility
        return match($this->status) {
            'completed' => 'bg-green-500',
            'active' => 'bg-blue-500',
            'in_progress' => 'bg-blue-500',
            'processing' => 'bg-yellow-500',
            'cancelled' => 'bg-red-500',
            'pending' => 'bg-yellow-500', // Keep for existing data, but not selectable
            default => 'bg-gray-500'
        };
    }

    public function getStatusIcon()
    {
        // Try to get from EventStatus first
        $eventStatus = \App\Models\EventStatus::where('name', $this->status)->first();
        if ($eventStatus) {
            return $eventStatus->icon;
        }

        // Fallback to hardcoded values for backward compatibility
        return match($this->status) {
            'completed' => 'check',
            'active' => 'radio_button_checked',
            'in_progress' => 'trending_up',
            'processing' => 'schedule',
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
        return in_array($this->status, ['active', 'in_progress', 'processing']);
    }

    // Removed isPending() as 'pending' is no longer a primary selectable status
}
