<?php

namespace App\Services;

use App\Models\CalendarEvent;
use App\Models\Notification;
use App\Models\User;

class NotificationService
{
    /**
     * Create calendar reminder notification when event is created
     */
    public static function createCalendarReminder(CalendarEvent $event): void
    {
        // Only create reminder if reminder_minutes is set
        if (!$event->reminder_minutes) {
            return;
        }

        // Get the user who created the event
        $user = User::find($event->created_by);
        if (!$user) {
            return;
        }

        // Calculate reminder time
        $reminderTime = $event->start_date->subMinutes($event->reminder_minutes);
        
        // Don't create reminder for past events
        if ($reminderTime->isPast()) {
            return;
        }

        // Create the notification
        Notification::create([
            'user_id' => $user->id,
            'firm_id' => $event->firm_id,
            'type' => 'calendar_reminder',
            'title' => 'Upcoming Event: ' . $event->title,
            'message' => 'Event starts at ' . $event->start_date->format('l, M j \a\t g:i A'),
            'icon' => 'event',
            'url' => route('calendar') . '#event-' . $event->id,
            'notifiable_type' => CalendarEvent::class,
            'notifiable_id' => $event->id,
            'scheduled_at' => $reminderTime,
            'data' => [
                'event_id' => $event->id,
                'event_title' => $event->title,
                'event_start' => $event->start_date->toISOString(),
                'event_location' => $event->location,
                'reminder_minutes' => $event->reminder_minutes,
                'case_number' => $event->case->case_number ?? null,
            ],
        ]);
    }

    /**
     * Process pending notifications (to be called by scheduler)
     */
    public static function processPendingNotifications(): int
    {
        $pendingNotifications = Notification::pending()->get();
        $processedCount = 0;

        foreach ($pendingNotifications as $notification) {
            // Mark as sent (this makes it visible in the bell dropdown)
            $notification->markAsSent();
            $processedCount++;
        }

        return $processedCount;
    }

    /**
     * Create case update notification
     */
    public static function createCaseUpdateNotification(
        User $user,
        $case,
        string $message,
        ?string $url = null
    ): void {
        Notification::create([
            'user_id' => $user->id,
            'firm_id' => $user->firm_id,
            'type' => 'case_update',
            'title' => 'Case Update: ' . $case->case_number,
            'message' => $message,
            'icon' => 'gavel',
            'url' => $url ?? route('case.show', $case->id),
            'notifiable_type' => get_class($case),
            'notifiable_id' => $case->id,
            'scheduled_at' => now(), // Send immediately
        ]);
    }

    /**
     * Create document upload notification
     */
    public static function createDocumentUploadNotification(
        User $user, 
        $case, 
        string $fileName
    ): void {
        Notification::create([
            'user_id' => $user->id,
            'firm_id' => $user->firm_id,
            'type' => 'document_upload',
            'title' => 'New Document: ' . $fileName,
            'message' => 'Document uploaded for case ' . $case->case_number,
            'icon' => 'description',
            'url' => route('case.show', $case->id) . '#documents',
            'notifiable_type' => get_class($case),
            'notifiable_id' => $case->id,
            'scheduled_at' => now(), // Send immediately
        ]);
    }

    /**
     * Clean up old notifications (older than 30 days)
     */
    public static function cleanupOldNotifications(): int
    {
        return Notification::where('created_at', '<', now()->subDays(30))
            ->delete();
    }

    /**
     * Get notification statistics for a user
     */
    public static function getUserNotificationStats(User $user): array
    {
        return [
            'total' => Notification::where('user_id', $user->id)->count(),
            'unread' => Notification::where('user_id', $user->id)->unread()->count(),
            'calendar_reminders' => Notification::where('user_id', $user->id)
                ->where('type', 'calendar_reminder')
                ->count(),
            'case_updates' => Notification::where('user_id', $user->id)
                ->where('type', 'case_update')
                ->count(),
            'document_uploads' => Notification::where('user_id', $user->id)
                ->where('type', 'document_upload')
                ->count(),
        ];
    }
}
