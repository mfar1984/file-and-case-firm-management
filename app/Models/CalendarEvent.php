<?php

namespace App\Models;

use App\Traits\HasFirmScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CalendarEvent extends Model
{
    use HasFirmScope;
    protected $fillable = [
        'title',
        'description',
        'start_date',
        'end_date',
        'location',
        'category',
        'reminder_minutes',
        'case_id',
        'timeline_event_id',
        'created_by',
        'status',
        'firm_id',
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'reminder_minutes' => 'integer',
    ];

    /**
     * Get the case that owns the calendar event.
     */
    public function case(): BelongsTo
    {
        return $this->belongsTo(CourtCase::class, 'case_id');
    }

    /**
     * Get the timeline event that owns the calendar event.
     */
    public function timelineEvent(): BelongsTo
    {
        return $this->belongsTo(CaseTimeline::class, 'timeline_event_id');
    }

    /**
     * Get the user who created the calendar event.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Scope for active events
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope for events in date range
     */
    public function scopeInDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('start_date', [$startDate, $endDate]);
    }

    /**
     * Scope for events by category
     */
    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    /**
     * Get the firm that owns this calendar event
     */
    public function firm(): BelongsTo
    {
        return $this->belongsTo(Firm::class);
    }
}
