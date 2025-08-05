@extends('layouts.app')

@section('breadcrumb')
    Calendar
@endsection

@section('content')
<div class="px-6 pt-6 max-w-7xl mx-auto">
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
                                <label class="flex items-center px-3 py-2 text-xs hover:bg-gray-100">
                                    <input type="checkbox" class="mr-2" checked> All Events
                                </label>
                                <label class="flex items-center px-3 py-2 text-xs hover:bg-gray-100">
                                    <input type="checkbox" class="mr-2" checked> Court Hearings
                                </label>
                                <label class="flex items-center px-3 py-2 text-xs hover:bg-gray-100">
                                    <input type="checkbox" class="mr-2" checked> Client Meetings
                                </label>
                                <label class="flex items-center px-3 py-2 text-xs hover:bg-gray-100">
                                    <input type="checkbox" class="mr-2" checked> Deadlines
                                </label>
                                <label class="flex items-center px-3 py-2 text-xs hover:bg-gray-100">
                                    <input type="checkbox" class="mr-2" checked> Follow-ups
                                </label>
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
                            <p class="text-red-800 text-lg font-semibold">5</p>
                        </div>
                        <span class="material-icons text-red-600">gavel</span>
                    </div>
                </div>
                
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-3">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-blue-600 text-xs font-medium">Client Meetings</p>
                            <p class="text-blue-800 text-lg font-semibold">3</p>
                        </div>
                        <span class="material-icons text-blue-600">people</span>
                    </div>
                </div>
                
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-3">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-yellow-600 text-xs font-medium">Deadlines</p>
                            <p class="text-yellow-800 text-lg font-semibold">8</p>
                        </div>
                        <span class="material-icons text-yellow-600">schedule</span>
                    </div>
                </div>
                
                <div class="bg-green-50 border border-green-200 rounded-lg p-3">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-green-600 text-xs font-medium">Follow-ups</p>
                            <p class="text-green-800 text-lg font-semibold">2</p>
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
                    <div class="flex items-center p-3 bg-red-50 border border-red-200 rounded-lg">
                        <span class="material-icons text-red-600 mr-3 text-xs">gavel</span>
                        <div class="flex-1">
                            <p class="text-xs font-medium text-gray-800">Case #2024-010 - Court Hearing</p>
                            <p class="text-[10px] text-gray-500">Tomorrow, 10:00 AM - High Court</p>
                        </div>
                        <span class="text-[10px] text-red-600 font-medium">Urgent</span>
                    </div>
                    
                    <div class="flex items-center p-3 bg-blue-50 border border-blue-200 rounded-lg">
                        <span class="material-icons text-blue-600 mr-3 text-xs">people</span>
                        <div class="flex-1">
                            <p class="text-xs font-medium text-gray-800">Client Meeting - John Doe</p>
                            <p class="text-[10px] text-gray-500">Friday, 2:30 PM - Conference Room</p>
                        </div>
                        <span class="text-[10px] text-blue-600 font-medium">Confirmed</span>
                    </div>
                    
                    <div class="flex items-center p-3 bg-yellow-50 border border-yellow-200 rounded-lg">
                        <span class="material-icons text-yellow-600 mr-3 text-xs">schedule</span>
                        <div class="flex-1">
                            <p class="text-xs font-medium text-gray-800">Document Filing Deadline</p>
                            <p class="text-[10px] text-gray-500">Next Monday, 5:00 PM</p>
                        </div>
                        <span class="text-[10px] text-yellow-600 font-medium">Due Soon</span>
                    </div>
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
        events: [
            {
                title: 'Court Hearing - Case #2024-010',
                start: '2025-07-15T10:00:00',
                end: '2025-07-15T12:00:00',
                backgroundColor: '#ef4444',
                borderColor: '#dc2626',
                textColor: '#ffffff',
                extendedProps: {
                    type: 'court',
                    case: '2024-010',
                    location: 'High Court'
                }
            },
            {
                title: 'Client Meeting - John Doe',
                start: '2025-07-18T14:30:00',
                end: '2025-07-18T15:30:00',
                backgroundColor: '#3b82f6',
                borderColor: '#2563eb',
                textColor: '#ffffff',
                extendedProps: {
                    type: 'meeting',
                    client: 'John Doe',
                    location: 'Conference Room'
                }
            },
            {
                title: 'Document Filing Deadline',
                start: '2025-07-22T17:00:00',
                backgroundColor: '#f59e0b',
                borderColor: '#d97706',
                textColor: '#ffffff',
                extendedProps: {
                    type: 'deadline',
                    case: '2024-009'
                }
            },
            {
                title: 'Follow-up Call - Jane Smith',
                start: '2025-07-25T11:00:00',
                end: '2025-07-25T11:30:00',
                backgroundColor: '#10b981',
                borderColor: '#059669',
                textColor: '#ffffff',
                extendedProps: {
                    type: 'followup',
                    client: 'Jane Smith'
                }
            },
            {
                title: 'Case Filing - New Case',
                start: '2025-07-28T09:00:00',
                backgroundColor: '#8b5cf6',
                borderColor: '#7c3aed',
                textColor: '#ffffff',
                extendedProps: {
                    type: 'filing',
                    case: '2024-011'
                }
            },
            {
                title: 'Court Hearing - Case #2024-012',
                start: '2025-08-05T09:00:00',
                end: '2025-08-05T11:00:00',
                backgroundColor: '#ef4444',
                borderColor: '#dc2626',
                textColor: '#ffffff',
                extendedProps: {
                    type: 'court',
                    case: '2024-012',
                    location: 'Sessions Court'
                }
            },
            {
                title: 'Client Meeting - Ahmad Ali',
                start: '2025-08-08T15:00:00',
                end: '2025-08-08T16:00:00',
                backgroundColor: '#3b82f6',
                borderColor: '#2563eb',
                textColor: '#ffffff',
                extendedProps: {
                    type: 'meeting',
                    client: 'Ahmad Ali',
                    location: 'Meeting Room A'
                }
            },
            {
                title: 'Document Review Deadline',
                start: '2025-08-12T16:00:00',
                backgroundColor: '#f59e0b',
                borderColor: '#d97706',
                textColor: '#ffffff',
                extendedProps: {
                    type: 'deadline',
                    case: '2024-008'
                }
            },
            {
                title: 'Follow-up Call - Sarah Lim',
                start: '2025-08-15T10:30:00',
                end: '2025-08-15T11:00:00',
                backgroundColor: '#10b981',
                borderColor: '#059669',
                textColor: '#ffffff',
                extendedProps: {
                    type: 'followup',
                    client: 'Sarah Lim'
                }
            },
            {
                title: 'Case Filing - Property Dispute',
                start: '2025-08-20T08:30:00',
                backgroundColor: '#8b5cf6',
                borderColor: '#7c3aed',
                textColor: '#ffffff',
                extendedProps: {
                    type: 'filing',
                    case: '2024-013'
                }
            },
            {
                title: 'Court Mention - Case #2024-014',
                start: '2025-08-25T14:00:00',
                end: '2025-08-25T15:00:00',
                backgroundColor: '#ef4444',
                borderColor: '#dc2626',
                textColor: '#ffffff',
                extendedProps: {
                    type: 'court',
                    case: '2024-014',
                    location: 'Magistrate Court'
                }
            },
            {
                title: 'Client Consultation - Maria Tan',
                start: '2025-08-28T13:00:00',
                end: '2025-08-28T14:30:00',
                backgroundColor: '#3b82f6',
                borderColor: '#2563eb',
                textColor: '#ffffff',
                extendedProps: {
                    type: 'meeting',
                    client: 'Maria Tan',
                    location: 'Consultation Room'
                }
            }
        ],
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