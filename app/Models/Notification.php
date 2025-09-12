<?php

namespace App\Models;

use App\Traits\HasFirmScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Notification extends Model
{
    use HasFirmScope;

    protected $fillable = [
        'user_id',
        'firm_id',
        'type',
        'title',
        'message',
        'icon',
        'url',
        'notifiable_type',
        'notifiable_id',
        'read_at',
        'scheduled_at',
        'sent_at',
        'data',
    ];

    protected $casts = [
        'read_at' => 'datetime',
        'scheduled_at' => 'datetime',
        'sent_at' => 'datetime',
        'data' => 'array',
    ];

    /**
     * Get the user that owns the notification
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the firm that owns the notification
     */
    public function firm(): BelongsTo
    {
        return $this->belongsTo(Firm::class);
    }

    /**
     * Get the notifiable model (CalendarEvent, Case, etc.)
     */
    public function notifiable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Scope for unread notifications
     */
    public function scopeUnread($query)
    {
        return $query->whereNull('read_at');
    }

    /**
     * Scope for read notifications
     */
    public function scopeRead($query)
    {
        return $query->whereNotNull('read_at');
    }

    /**
     * Scope for pending notifications (scheduled but not sent)
     */
    public function scopePending($query)
    {
        return $query->whereNotNull('scheduled_at')
                    ->whereNull('sent_at')
                    ->where('scheduled_at', '<=', now());
    }

    /**
     * Mark notification as read
     */
    public function markAsRead()
    {
        $this->update(['read_at' => now()]);
    }

    /**
     * Mark notification as sent
     */
    public function markAsSent()
    {
        $this->update(['sent_at' => now()]);
    }

    /**
     * Check if notification is read
     */
    public function isRead(): bool
    {
        return !is_null($this->read_at);
    }

    /**
     * Check if notification is unread
     */
    public function isUnread(): bool
    {
        return is_null($this->read_at);
    }

    /**
     * Get formatted time for display
     */
    public function getTimeAttribute(): string
    {
        if ($this->sent_at) {
            return $this->sent_at->diffForHumans();
        }
        
        return $this->created_at->diffForHumans();
    }

    /**
     * Create calendar reminder notification
     */
    public static function createCalendarReminder(CalendarEvent $event, User $user): self
    {
        return static::create([
            'user_id' => $user->id,
            'firm_id' => $event->firm_id,
            'type' => 'calendar_reminder',
            'title' => 'Upcoming Event: ' . $event->title,
            'message' => 'Event starts at ' . $event->start_date->format('g:i A'),
            'icon' => 'event',
            'url' => route('calendar') . '#event-' . $event->id,
            'notifiable_type' => CalendarEvent::class,
            'notifiable_id' => $event->id,
            'scheduled_at' => $event->start_date->subMinutes($event->reminder_minutes ?? 15),
            'data' => [
                'event_id' => $event->id,
                'event_title' => $event->title,
                'event_start' => $event->start_date->toISOString(),
                'reminder_minutes' => $event->reminder_minutes,
            ],
        ]);
    }
}
