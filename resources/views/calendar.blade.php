@extends('layouts.app')

@section('breadcrumb')
    Calendar
@endsection

@section('content')
<div class="px-6 pt-6 pb-6 max-w-7xl mx-auto">
    <div class="bg-white rounded shadow-md border border-gray-300">
        <div class="p-6 border-b border-gray-200">
            <div class="flex justify-between items-start">
                <div>
                    <div class="flex items-center">
                        <span class="material-icons mr-2 text-green-600">calendar_today</span>
                        <h1 class="text-xl font-bold text-gray-800 text-[14px]">Calendar Management</h1>
                    </div>
                    <p class="text-xs text-gray-500 mt-1 ml-8 text-[11px]">Manage court hearings, client meetings, and important deadlines.</p>
                </div>
                
                <!-- Calendar Controls -->
                <div class="flex items-center space-x-3">
                    <!-- Filter Dropdown -->
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" class="flex items-center space-x-2 px-3 py-1 text-xs border border-gray-300 rounded-md bg-white hover:bg-gray-50">
                            <span class="material-icons text-xs">filter_list</span>
                            <span>Filter</span>
                            <span class="material-icons text-xs">expand_more</span>
                        </button>
                        <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg border border-gray-200 z-10">
                            <div class="py-1">
                                <a href="{{ route('calendar') }}" class="flex items-center px-3 py-2 text-xs hover:bg-gray-100">
                                    <span class="material-icons text-xs mr-2">visibility</span> All Events
                                </a>
                                <a href="{{ route('calendar', ['category' => 'court_hearing']) }}" class="flex items-center px-3 py-2 text-xs hover:bg-gray-100">
                                    <span class="material-icons text-xs mr-2 text-red-600">gavel</span> Court Hearings
                                </a>
                                <a href="{{ route('calendar', ['category' => 'consultation']) }}" class="flex items-center px-3 py-2 text-xs hover:bg-gray-100">
                                    <span class="material-icons text-xs mr-2 text-blue-600">people</span> Consultations
                                </a>
                                <a href="{{ route('calendar', ['category' => 'document_filing']) }}" class="flex items-center px-3 py-2 text-xs hover:bg-gray-100">
                                    <span class="material-icons text-xs mr-2 text-yellow-600">description</span> Document Filing
                                </a>
                                <a href="{{ route('calendar', ['category' => 'follow_up']) }}" class="flex items-center px-3 py-2 text-xs hover:bg-gray-100">
                                    <span class="material-icons text-xs mr-2 text-green-600">phone</span> Follow-ups
                                </a>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Add Event Button -->
                    <button class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded-md text-xs font-medium flex items-center">
                        <span class="material-icons text-xs mr-1">add</span>
                        Add Event
                    </button>
                </div>
            </div>
        </div>
        
        <div class="p-6">
            <!-- Quick Stats -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                <div class="bg-red-50 border border-red-200 rounded-lg p-3">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-red-600 text-xs font-medium">Court Hearings</p>
                            <p class="text-red-800 text-lg font-semibold">{{ $stats['court_hearings'] ?? 0 }}</p>
                        </div>
                        <span class="material-icons text-red-600">gavel</span>
                    </div>
                </div>

                <div class="bg-blue-50 border border-blue-200 rounded-lg p-3">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-blue-600 text-xs font-medium">Client Meetings</p>
                            <p class="text-blue-800 text-lg font-semibold">{{ $stats['client_meetings'] ?? 0 }}</p>
                        </div>
                        <span class="material-icons text-blue-600">people</span>
                    </div>
                </div>

                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-3">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-yellow-600 text-xs font-medium">Deadlines</p>
                            <p class="text-yellow-800 text-lg font-semibold">{{ $stats['deadlines'] ?? 0 }}</p>
                        </div>
                        <span class="material-icons text-yellow-600">schedule</span>
                    </div>
                </div>

                <div class="bg-green-50 border border-green-200 rounded-lg p-3">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-green-600 text-xs font-medium">Follow-ups</p>
                            <p class="text-green-800 text-lg font-semibold">{{ $stats['follow_ups'] ?? 0 }}</p>
                        </div>
                        <span class="material-icons text-green-600">phone</span>
                    </div>
                </div>
            </div>
            
            <!-- Calendar Container -->
            <div class="bg-white border border-gray-200 rounded-lg">
                <div id="calendar" class="p-4"></div>
            </div>
            
            <!-- Upcoming Events -->
            <div class="mt-6">
                <h3 class="text-sm font-semibold text-gray-800 mb-3">Upcoming Events</h3>
                <div class="space-y-2">
                    @forelse($upcomingEvents as $event)
                        @php
                            $categoryColors = [
                                'court_hearing' => ['bg' => 'bg-red-50', 'border' => 'border-red-200', 'text' => 'text-red-600', 'icon' => 'gavel'],
                                'consultation' => ['bg' => 'bg-blue-50', 'border' => 'border-blue-200', 'text' => 'text-blue-600', 'icon' => 'people'],
                                'client_meeting' => ['bg' => 'bg-blue-50', 'border' => 'border-blue-200', 'text' => 'text-blue-600', 'icon' => 'people'],
                                'document_filing' => ['bg' => 'bg-yellow-50', 'border' => 'border-yellow-200', 'text' => 'text-yellow-600', 'icon' => 'description'],
                                'deadline' => ['bg' => 'bg-yellow-50', 'border' => 'border-yellow-200', 'text' => 'text-yellow-600', 'icon' => 'schedule'],
                                'follow_up' => ['bg' => 'bg-green-50', 'border' => 'border-green-200', 'text' => 'text-green-600', 'icon' => 'phone'],
                                'payment' => ['bg' => 'bg-purple-50', 'border' => 'border-purple-200', 'text' => 'text-purple-600', 'icon' => 'payment'],
                                'settlement' => ['bg' => 'bg-emerald-50', 'border' => 'border-emerald-200', 'text' => 'text-emerald-600', 'icon' => 'handshake'],
                                'other' => ['bg' => 'bg-gray-50', 'border' => 'border-gray-200', 'text' => 'text-gray-600', 'icon' => 'event']
                            ];
                            $colors = $categoryColors[$event->category] ?? $categoryColors['other'];
                            $urgency = $event->start_date->isToday() ? 'Today' : ($event->start_date->isTomorrow() ? 'Tomorrow' : $event->start_date->diffForHumans());
                        @endphp

                        <div class="flex items-center p-3 {{ $colors['bg'] }} border {{ $colors['border'] }} rounded-lg">
                            <span class="material-icons {{ $colors['text'] }} mr-3 text-xs">{{ $colors['icon'] }}</span>
                            <div class="flex-1">
                                <p class="text-xs font-medium text-gray-800">
                                    @if($event->case)
                                        {{ $event->case->case_number }} -
                                    @endif
                                    {{ $event->title }}
                                </p>
                                <p class="text-[10px] text-gray-500">
                                    {{ $event->start_date->format('l, g:i A') }}
                                    @if($event->location)
                                        - {{ $event->location }}
                                    @endif
                                </p>
                            </div>
                            <span class="text-[10px] {{ $colors['text'] }} font-medium">
                                @if($event->start_date->isToday())
                                    Today
                                @elseif($event->start_date->isTomorrow())
                                    Tomorrow
                                @elseif($event->start_date->diffInDays() <= 3)
                                    {{ $event->start_date->diffForHumans() }}
                                @else
                                    {{ $event->start_date->format('M j') }}
                                @endif
                            </span>
                        </div>
                    @empty
                        <div class="flex items-center justify-center p-6 bg-gray-50 border border-gray-200 rounded-lg">
                            <div class="text-center">
                                <span class="material-icons text-gray-400 text-2xl mb-2">event_available</span>
                                <p class="text-xs text-gray-500">No upcoming events in the next 7 days</p>
                            </div>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

<!-- FullCalendar CSS -->
<link href='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.css' rel='stylesheet' />

<style>
/* Custom styles to make calendar header smaller */
.fc-header-toolbar {
    font-size: 12px !important;
}

.fc-header-toolbar .fc-button {
    font-size: 11px !important;
    padding: 4px 8px !important;
    height: 28px !important;
}

.fc-header-toolbar .fc-toolbar-title {
    font-size: 14px !important;
    font-weight: 600 !important;
}

.fc-header-toolbar .fc-button-group .fc-button {
    font-size: 10px !important;
    padding: 3px 6px !important;
    height: 24px !important;
}

.fc-header-toolbar .fc-prev-button,
.fc-header-toolbar .fc-next-button {
    width: 24px !important;
    height: 24px !important;
    padding: 2px !important;
}

.fc-header-toolbar .fc-today-button {
    font-size: 10px !important;
    padding: 3px 8px !important;
    height: 24px !important;
}

/* Custom colors for navigation arrows and view buttons */
.fc-header-toolbar .fc-prev-button,
.fc-header-toolbar .fc-next-button {
    background-color: #3b82f6 !important;
    border-color: #2563eb !important;
    color: white !important;
}

.fc-header-toolbar .fc-prev-button:hover,
.fc-header-toolbar .fc-next-button:hover {
    background-color: #2563eb !important;
    border-color: #1d4ed8 !important;
}

.fc-header-toolbar .fc-button-group .fc-button {
    background-color: #3b82f6 !important;
    border-color: #2563eb !important;
    color: white !important;
}

.fc-header-toolbar .fc-button-group .fc-button:hover {
    background-color: #2563eb !important;
    border-color: #1d4ed8 !important;
}

.fc-header-toolbar .fc-button-group .fc-button.fc-button-active {
    background-color: #1d4ed8 !important;
    border-color: #1e40af !important;
    color: white !important;
}

.fc-header-toolbar .fc-today-button {
    background-color: #10b981 !important;
    border-color: #059669 !important;
    color: white !important;
}

.fc-header-toolbar .fc-today-button:hover {
    background-color: #059669 !important;
    border-color: #047857 !important;
}

/* Make calendar day headers smaller and lighter */
.fc-col-header-cell {
    font-size: 11px !important;
    font-weight: 400 !important;
}

.fc-col-header-cell .fc-col-header-cell-cushion {
    font-size: 11px !important;
    font-weight: 400 !important;
}

/* Make day headers smaller for week and day views */
.fc-timegrid-axis,
.fc-timegrid-slot-label {
    font-size: 11px !important;
    font-weight: 400 !important;
}

/* Make list view headers smaller */
.fc-list-day-text,
.fc-list-day-side-text {
    font-size: 11px !important;
    font-weight: 400 !important;
}

/* Make time labels smaller in week/day views */
.fc-timegrid-slot-label-cushion {
    font-size: 11px !important;
    font-weight: 400 !important;
}

/* Make calendar event text smaller */
.fc-event {
    font-size: 10px !important;
}

.fc-event-title {
    font-size: 10px !important;
    font-weight: 500 !important;
}

.fc-event-time {
    font-size: 9px !important;
    font-weight: 400 !important;
}

/* Make day numbers smaller */
.fc-daygrid-day-number {
    font-size: 11px !important;
    font-weight: 400 !important;
}

/* Make event dots smaller */
.fc-daygrid-event-dot {
    width: 6px !important;
    height: 6px !important;
}
</style>

<!-- FullCalendar JS -->
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js'></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');
    
    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay,listWeek'
        },
        height: 600,
        views: {
            dayGridMonth: {
                dayMaxEvents: 3,
                eventDisplay: 'block'
            },
            timeGridWeek: {
                slotMinTime: '08:00:00',
                slotMaxTime: '18:00:00'
            },
            timeGridDay: {
                slotMinTime: '08:00:00',
                slotMaxTime: '18:00:00'
            },
            listWeek: {
                listDayFormat: { weekday: 'long', month: 'long', day: 'numeric' },
                listDaySideFormat: { year: 'numeric', month: 'long', day: 'numeric' }
            }
        },
        events: {
            url: '{{ route("calendar.events") }}',
            method: 'GET',
            extraParams: {
                category: '{{ request("category", "all") }}'
            },
            success: function(events) {
                console.log('Calendar events loaded:', events);
            },
            failure: function(error) {
                console.error('Failed to load calendar events:', error);
                alert('There was an error while fetching events!');
            }
        },
        eventClick: function(info) {
            // Handle event click
            console.log('Event clicked:', info.event.title);
            // You can open a modal here to show event details
            alert('Event: ' + info.event.title + '\nTime: ' + info.event.start.toLocaleString());
        },
        eventDidMount: function(info) {
            // Add tooltips or custom styling
            info.el.title = info.event.title;
        },
        dayMaxEvents: true,
        moreLinkClick: 'popover'
    });
    
    calendar.render();
    
    // Note: Using built-in FullCalendar header buttons for view switching
    // The buttons in the calendar header (month, week, day, list) are now functional
});
</script>
@endsection 