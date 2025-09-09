<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CalendarEvent;
use App\Models\CourtCase;
use App\Models\CaseTimeline;
use Carbon\Carbon;

class CalendarController extends Controller
{
    /**
     * Display the calendar page with events
     */
    public function index(Request $request)
    {
        // Get filter parameters
        $category = $request->get('category');
        $startDate = $request->get('start', now()->startOfMonth());
        $endDate = $request->get('end', now()->endOfMonth());

        // Get calendar events with relationships
        $eventsQuery = CalendarEvent::with(['case', 'timelineEvent', 'creator'])
            ->active()
            ->inDateRange($startDate, $endDate);

        if ($category) {
            $eventsQuery->byCategory($category);
        }

        $events = $eventsQuery->orderBy('start_date')->get();

        // Get statistics
        $stats = $this->getCalendarStats();

        // Get upcoming events (next 7 days)
        $upcomingEvents = CalendarEvent::with(['case', 'timelineEvent'])
            ->active()
            ->where('start_date', '>=', now())
            ->where('start_date', '<=', now()->addDays(7))
            ->orderBy('start_date')
            ->limit(10)
            ->get();

        return view('calendar', compact('events', 'stats', 'upcomingEvents'));
    }

    /**
     * Get calendar events as JSON for FullCalendar
     */
    public function getEvents(Request $request)
    {
        $start = $request->get('start');
        $end = $request->get('end');
        $category = $request->get('category');

        $eventsQuery = CalendarEvent::with(['case', 'timelineEvent'])
            ->active();

        if ($start && $end) {
            $eventsQuery->inDateRange($start, $end);
        }

        if ($category && $category !== 'all') {
            $eventsQuery->byCategory($category);
        }

        $events = $eventsQuery->get();

        // Format events for FullCalendar
        $calendarEvents = $events->map(function ($event) {
            return [
                'id' => $event->id,
                'title' => $event->title,
                'start' => $event->start_date->toISOString(),
                'end' => $event->end_date->toISOString(),
                'description' => $event->description,
                'location' => $event->location,
                'category' => $event->category,
                'backgroundColor' => $this->getCategoryColor($event->category),
                'borderColor' => $this->getCategoryColor($event->category),
                'textColor' => '#ffffff',
                'extendedProps' => [
                    'case_number' => $event->case->case_number ?? null,
                    'case_id' => $event->case_id,
                    'timeline_event_id' => $event->timeline_event_id,
                    'reminder_minutes' => $event->reminder_minutes,
                    'status' => $event->status,
                    'created_by' => $event->creator->name ?? 'System',
                ]
            ];
        });

        return response()->json($calendarEvents);
    }

    /**
     * Get calendar statistics
     */
    private function getCalendarStats()
    {
        $now = now();
        $startOfMonth = $now->copy()->startOfMonth();
        $endOfMonth = $now->copy()->endOfMonth();

        return [
            'court_hearings' => CalendarEvent::active()
                ->byCategory('court_hearing')
                ->inDateRange($startOfMonth, $endOfMonth)
                ->count(),
            'client_meetings' => CalendarEvent::active()
                ->whereIn('category', ['consultation', 'client_meeting'])
                ->inDateRange($startOfMonth, $endOfMonth)
                ->count(),
            'deadlines' => CalendarEvent::active()
                ->whereIn('category', ['document_filing', 'deadline'])
                ->inDateRange($startOfMonth, $endOfMonth)
                ->count(),
            'follow_ups' => CalendarEvent::active()
                ->whereIn('category', ['follow_up', 'other'])
                ->inDateRange($startOfMonth, $endOfMonth)
                ->count(),
        ];
    }

    /**
     * Get color for event category
     */
    private function getCategoryColor($category)
    {
        $colors = [
            'court_hearing' => '#dc2626', // red-600
            'consultation' => '#2563eb', // blue-600
            'client_meeting' => '#2563eb', // blue-600
            'document_filing' => '#d97706', // amber-600
            'deadline' => '#d97706', // amber-600
            'follow_up' => '#16a34a', // green-600
            'payment' => '#7c3aed', // violet-600
            'settlement' => '#059669', // emerald-600
            'other' => '#6b7280', // gray-500
        ];

        return $colors[$category] ?? $colors['other'];
    }

    /**
     * Create a new calendar event
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'location' => 'nullable|string|max:255',
            'category' => 'required|string|max:100',
            'reminder_minutes' => 'nullable|integer|min:0',
            'case_id' => 'nullable|exists:cases,id',
        ]);

        $event = CalendarEvent::create([
            'title' => $request->title,
            'description' => $request->description,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'location' => $request->location,
            'category' => $request->category,
            'reminder_minutes' => $request->reminder_minutes,
            'case_id' => $request->case_id,
            'created_by' => auth()->id(),
            'status' => 'active',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Event created successfully',
            'event' => $event
        ]);
    }

    /**
     * Update calendar event
     */
    public function update(Request $request, CalendarEvent $event)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'location' => 'nullable|string|max:255',
            'category' => 'required|string|max:100',
            'reminder_minutes' => 'nullable|integer|min:0',
        ]);

        $event->update($request->only([
            'title', 'description', 'start_date', 'end_date',
            'location', 'category', 'reminder_minutes'
        ]));

        return response()->json([
            'success' => true,
            'message' => 'Event updated successfully',
            'event' => $event
        ]);
    }

    /**
     * Delete calendar event
     */
    public function destroy(CalendarEvent $event)
    {
        $event->delete();

        return response()->json([
            'success' => true,
            'message' => 'Event deleted successfully'
        ]);
    }
}
