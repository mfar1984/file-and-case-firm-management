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
                                <a href="javascript:void(0)" @click="filterEvents('all'); open = false" class="flex items-center px-3 py-2 text-xs hover:bg-gray-100">
                                    <span class="material-icons text-xs mr-2">visibility</span> All Events
                                </a>
                                <a href="javascript:void(0)" @click="filterEvents('court_hearing'); open = false" class="flex items-center px-3 py-2 text-xs hover:bg-gray-100">
                                    <span class="material-icons text-xs mr-2 text-red-600">gavel</span> Court Hearings
                                </a>
                                <a href="javascript:void(0)" @click="filterEvents('consultation'); open = false" class="flex items-center px-3 py-2 text-xs hover:bg-gray-100">
                                    <span class="material-icons text-xs mr-2 text-blue-600">people</span> Consultations
                                </a>
                                <a href="javascript:void(0)" @click="filterEvents('document_filing'); open = false" class="flex items-center px-3 py-2 text-xs hover:bg-gray-100">
                                    <span class="material-icons text-xs mr-2 text-yellow-600">description</span> Document Filing
                                </a>
                                <a href="javascript:void(0)" @click="filterEvents('follow_up'); open = false" class="flex items-center px-3 py-2 text-xs hover:bg-gray-100">
                                    <span class="material-icons text-xs mr-2 text-green-600">phone</span> Follow-ups
                                </a>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Add Event Button -->
                    <button id="addEventBtn" class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded-md text-xs font-medium flex items-center">
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

<!-- Add Event Modal -->
<div id="addEventModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-10 mx-auto p-6 border w-full max-w-2xl shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900">Add New Event</h3>
                <button id="closeModalBtn" class="text-gray-400 hover:text-gray-600">
                    <span class="material-icons">close</span>
                </button>
            </div>

            <form id="addEventForm" class="space-y-5">
                <!-- Event Details Section -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div class="space-y-4">
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-2">Event Title *</label>
                            <input type="text" id="eventTitle" name="title" required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-2">Description</label>
                            <textarea id="eventDescription" name="description" rows="5"
                                      class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-2">Location</label>
                            <input type="text" id="eventLocation" name="location"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                    </div>

                    <div class="space-y-4">
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-2">Start Date *</label>
                                <input type="datetime-local" id="eventStartDate" name="start_date" required
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-2">End Date *</label>
                                <input type="datetime-local" id="eventEndDate" name="end_date" required
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-2">Category *</label>
                            <select id="eventCategory" name="category" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="">Select Category</option>
                                <option value="court_hearing">Court Hearing</option>
                                <option value="consultation">Consultation</option>
                                <option value="client_meeting">Client Meeting</option>
                                <option value="document_filing">Document Filing</option>
                                <option value="deadline">Deadline</option>
                                <option value="follow_up">Follow Up</option>
                                <option value="payment">Payment</option>
                                <option value="settlement">Settlement</option>
                                <option value="other">Other</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-2">Related Case</label>
                            <select id="eventCase" name="case_id"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="">No Case Selected</option>
                                @foreach(\App\Models\CourtCase::orderBy('case_number')->get() as $case)
                                    <option value="{{ $case->id }}">{{ $case->case_number }} - {{ $case->case_title }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-2">Reminder (minutes before)</label>
                            <select id="eventReminder" name="reminder_minutes"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="">No Reminder</option>
                                <option value="15">15 minutes</option>
                                <option value="30">30 minutes</option>
                                <option value="60">1 hour</option>
                                <option value="120">2 hours</option>
                                <option value="1440">1 day</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200">
                    <button type="button" id="cancelBtn" class="px-6 py-2 text-xs font-medium text-gray-700 bg-gray-200 rounded-md hover:bg-gray-300 transition duration-150">
                        Cancel
                    </button>
                    <button type="submit" class="px-6 py-2 text-xs font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700 transition duration-150 flex items-center">
                        <span class="material-icons text-xs mr-1">add</span>
                        Create Event
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Event Detail Modal -->
<div id="eventDetailModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-10 mx-auto p-6 border w-full max-w-lg shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900">Event Details</h3>
                <button id="closeEventDetailBtn" class="text-gray-400 hover:text-gray-600">
                    <span class="material-icons">close</span>
                </button>
            </div>

            <div id="eventDetailContent" class="space-y-4">
                <!-- Event details will be populated here -->
            </div>

            <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200">
                <button id="editEventBtn" class="px-4 py-2 text-xs font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700 transition duration-150 flex items-center">
                    <span class="material-icons text-xs mr-1">edit</span>
                    Edit Event
                </button>
                <button id="deleteEventBtn" class="px-4 py-2 text-xs font-medium text-white bg-red-600 rounded-md hover:bg-red-700 transition duration-150 flex items-center">
                    <span class="material-icons text-xs mr-1">delete</span>
                    Delete
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Edit Event Modal -->
<div id="editEventModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-10 mx-auto p-6 border w-full max-w-2xl shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900">Edit Event</h3>
                <button id="closeEditModalBtn" class="text-gray-400 hover:text-gray-600">
                    <span class="material-icons">close</span>
                </button>
            </div>

            <form id="editEventForm" class="space-y-5">
                <!-- Event Details Section -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div class="space-y-4">
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-2">Event Title *</label>
                            <input type="text" id="editEventTitle" name="title" required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-2">Description</label>
                            <textarea id="editEventDescription" name="description" rows="5"
                                      class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-2">Location</label>
                            <input type="text" id="editEventLocation" name="location"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                    </div>

                    <div class="space-y-4">
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-2">Start Date *</label>
                                <input type="datetime-local" id="editEventStartDate" name="start_date" required
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-2">End Date *</label>
                                <input type="datetime-local" id="editEventEndDate" name="end_date" required
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-2">Category *</label>
                            <select id="editEventCategory" name="category" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="">Select Category</option>
                                <option value="court_hearing">Court Hearing</option>
                                <option value="consultation">Consultation</option>
                                <option value="client_meeting">Client Meeting</option>
                                <option value="document_filing">Document Filing</option>
                                <option value="deadline">Deadline</option>
                                <option value="follow_up">Follow Up</option>
                                <option value="payment">Payment</option>
                                <option value="settlement">Settlement</option>
                                <option value="other">Other</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-2">Related Case</label>
                            <select id="editEventCase" name="case_id"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="">No Case Selected</option>
                                @foreach(\App\Models\CourtCase::orderBy('case_number')->get() as $case)
                                    <option value="{{ $case->id }}">{{ $case->case_number }} - {{ $case->case_title }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-2">Reminder (minutes before)</label>
                            <select id="editEventReminder" name="reminder_minutes"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="">No Reminder</option>
                                <option value="15">15 minutes</option>
                                <option value="30">30 minutes</option>
                                <option value="60">1 hour</option>
                                <option value="120">2 hours</option>
                                <option value="1440">1 day</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200">
                    <button type="button" id="cancelEditBtn" class="px-6 py-2 text-xs font-medium text-gray-700 bg-gray-200 rounded-md hover:bg-gray-300 transition duration-150">
                        Cancel
                    </button>
                    <button type="submit" class="px-6 py-2 text-xs font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700 transition duration-150 flex items-center">
                        <span class="material-icons text-xs mr-1">save</span>
                        Update Event
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- FullCalendar uses built-in styles in v6; external CSS not required -->
<noscript>
    <style>
        /* Basic FullCalendar fallback styles */
        .fc { direction: ltr; text-align: left; }
        .fc table { border-collapse: collapse; border-spacing: 0; }
        .fc th, .fc td { padding: 0; vertical-align: top; }
        .fc .fc-header-toolbar { display: flex; justify-content: space-between; align-items: center; }
        .fc .fc-toolbar-chunk { display: flex; align-items: center; }
        .fc .fc-button-group { position: relative; display: inline-flex; vertical-align: middle; }
        .fc .fc-button { margin: 0; padding: 0.4em 0.65em; font-size: 1em; line-height: 1.5; border-radius: 0.25em; border: 1px solid transparent; }
        .fc .fc-button:not(:disabled) { cursor: pointer; }
        .fc .fc-button:focus { outline: 1px dotted; outline: 5px auto -webkit-focus-ring-color; }
        .fc .fc-button-primary { background-color: #2c3e50; border-color: #2c3e50; color: #fff; }
        .fc .fc-button-primary:hover:not(:disabled) { background-color: #1e2b37; border-color: #1a252f; color: #fff; }
        .fc .fc-button-primary:disabled { background-color: #2c3e50; border-color: #2c3e50; color: #fff; opacity: 0.65; }
        .fc .fc-button-primary:focus { box-shadow: 0 0 0 0.2rem rgba(44, 62, 80, 0.25); }
        .fc .fc-button-primary:not(:disabled):active, .fc .fc-button-primary:not(:disabled).fc-button-active { background-color: #1e2b37; border-color: #1a252f; color: #fff; }
        .fc .fc-button-primary:not(:disabled):active:focus, .fc .fc-button-primary:not(:disabled).fc-button-active:focus { box-shadow: 0 0 0 0.2rem rgba(44, 62, 80, 0.25); }
        .fc .fc-today-button { text-transform: capitalize; }
        .fc .fc-prev-button, .fc .fc-next-button { text-decoration: none; }
        .fc .fc-prev-button:hover, .fc .fc-next-button:hover { text-decoration: none; }
        .fc .fc-view-harness { flex-grow: 1; max-height: 100%; }
        .fc .fc-view-harness-active > .fc-view { position: absolute; top: 0; right: 0; bottom: 0; left: 0; }
        .fc .fc-col-header-cell { text-align: center; }
        .fc .fc-col-header-cell-cushion { display: inline-block; padding: 2px 4px; }
        .fc a[data-navlink] { cursor: pointer; }
        .fc a[data-navlink]:hover { text-decoration: underline; }
        .fc .fc-daygrid { position: relative; }
        .fc .fc-daygrid-body { position: relative; z-index: 1; }
        .fc .fc-daygrid-day-events { margin-top: 1px; }
        .fc .fc-daygrid-body-balanced .fc-daygrid-day-events { position: absolute; left: 0; right: 0; }
        .fc .fc-daygrid-body-unbalanced .fc-daygrid-day-events { position: relative; min-height: 2em; }
        .fc .fc-daygrid-body-natural .fc-daygrid-day-events { margin-bottom: 1em; }
        .fc .fc-daygrid-event-harness { position: absolute; top: 0; left: 0; right: 0; }
        .fc .fc-daygrid-event-harness-abs { position: absolute; top: 0; left: 0; right: 0; }
        .fc .fc-daygrid-bg-harness { position: absolute; top: 0; bottom: 0; }
        .fc .fc-daygrid-day-bg .fc-non-business { z-index: 1; }
        .fc .fc-daygrid-day-bg .fc-bg-event { z-index: 2; }
        .fc .fc-daygrid-day-bg .fc-highlight { z-index: 3; }
        .fc .fc-daygrid-event { position: relative; white-space: nowrap; border-radius: 3px; font-size: 0.85em; }
        .fc .fc-daygrid-block-event .fc-event-time { font-weight: bold; }
        .fc .fc-daygrid-block-event .fc-event-title { margin-top: 1px; }
        .fc .fc-daygrid-dot-event { display: flex; align-items: center; padding: 2px 0; }
        .fc .fc-daygrid-dot-event .fc-event-title { flex-grow: 1; flex-shrink: 1; min-width: 0; overflow: hidden; font-weight: bold; }
        .fc .fc-daygrid-dot-event:hover, .fc .fc-daygrid-dot-event.fc-event-mirror { background: rgba(0, 0, 0, 0.1); }
        .fc .fc-daygrid-dot-event .fc-event-time { font-weight: bold; font-size: 0.85em; margin: 0 2px; }
        .fc .fc-daygrid-more-link { position: relative; z-index: 4; cursor: pointer; margin-top: 1px; font-size: 0.85em; font-weight: bold; color: inherit; }
        .fc .fc-daygrid-more-link:hover { background-color: rgba(0, 0, 0, 0.1); }
        .fc .fc-daygrid-week-number { position: absolute; z-index: 5; top: 0; left: 0; right: 0; margin: 0 1px; color: #808080; font-size: 0.85em; text-align: center; background: rgba(0, 0, 0, 0.025); border-radius: 3px; }
        .fc .fc-more-popover .fc-popover-body { min-width: 220px; padding: 10px; }
        .fc-direction-ltr .fc-daygrid-event.fc-event-start, .fc-direction-rtl .fc-daygrid-event.fc-event-end { margin-left: 2px; }
        .fc-direction-ltr .fc-daygrid-event.fc-event-end, .fc-direction-rtl .fc-daygrid-event.fc-event-start { margin-right: 2px; }
        .fc-direction-ltr .fc-daygrid-more-link { float: left; }
        .fc-direction-rtl .fc-daygrid-more-link { float: right; }
        .fc-direction-ltr .fc-daygrid-week-number { float: left; border-radius: 0 3px 3px 0; }
        .fc-direction-rtl .fc-daygrid-week-number { float: right; border-radius: 3px 0 0 3px; }
        .fc .fc-event { display: block; font-size: 0.85em; line-height: 1.3; border-radius: 3px; border: 1px solid #3788d8; }
        .fc .fc-event .fc-event-main { position: relative; z-index: 2; }
        .fc .fc-event .fc-event-main-frame { display: flex; flex-direction: column; }
        .fc .fc-event .fc-event-time { overflow: hidden; font-weight: bold; }
        .fc .fc-event .fc-event-title-container { flex-grow: 1; flex-shrink: 1; min-width: 0; }
        .fc .fc-event .fc-event-title { top: 0; bottom: 0; left: 0; right: 0; overflow: hidden; }
        .fc .fc-event .fc-event-resizer { display: none; position: absolute; z-index: 4; }
        .fc .fc-event .fc-event-resizer-start { cursor: n-resize; top: -3px; left: 0; right: 0; height: 7px; }
        .fc .fc-event .fc-event-resizer-end { cursor: s-resize; bottom: -3px; left: 0; right: 0; height: 7px; }
        .fc .fc-event.fc-event-selected .fc-event-resizer { display: block; }
        .fc .fc-event.fc-event-selected { box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2); }
        .fc .fc-event.fc-event-dragging { box-shadow: 0 2px 7px rgba(0, 0, 0, 0.3); }
        .fc .fc-event.fc-event-dragging.fc-event-selected { box-shadow: 0 2px 7px rgba(0, 0, 0, 0.3); }
        .fc .fc-event.fc-event-mirror { visibility: hidden; box-shadow: 0 2px 7px rgba(0, 0, 0, 0.3); }
        .fc .fc-event-bg { z-index: 1; background: #3788d8; opacity: 0.25; }
        .fc .fc-non-business { background: #d7d7d7; }
        .fc .fc-bg-event { z-index: 1; background: rgb(143, 223, 130); opacity: 0.3; }
        .fc .fc-bg-event .fc-event-title { margin: 0.5em; color: #000; font-weight: bold; font-size: 0.85em; }
        .fc .fc-highlight { background: rgba(188, 232, 241, 0.3); }
        .fc .fc-cell-shaded, .fc .fc-day-disabled { background: #f5f5f5; }
        .fc-direction-ltr .fc-event.fc-event-start, .fc-direction-rtl .fc-event.fc-event-end { margin-left: 2px; }
        .fc-direction-ltr .fc-event.fc-event-end, .fc-direction-rtl .fc-event.fc-event-start { margin-right: 2px; }
        .fc .fc-popover { position: absolute; z-index: 9999; box-shadow: 0 2px 6px rgba(0, 0, 0, 0.15); background: #fff; border: 1px solid #ccc; }
        .fc .fc-popover-header { display: flex; flex-direction: row; justify-content: space-between; align-items: center; padding: 3px 4px; border-bottom: 1px solid #ddd; }
        .fc .fc-popover-title { margin: 0 2px; }
        .fc .fc-popover-close { cursor: pointer; opacity: 0.65; font-size: 1.1em; }
        .fc .fc-popover-close:hover { opacity: 1; }
    </style>
</noscript>

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

<!-- FullCalendar JS with fallback -->
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js' crossorigin="anonymous" onerror="this.onerror=null;this.src='https://unpkg.com/fullcalendar@6.1.10/index.global.min.js';"></script>

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
                // Events loaded successfully
            },
            failure: function(error) {
                showToast('There was an error while fetching events!', 'error');
            }
        },
        eventClick: function(info) {
            // Handle event click - show detailed modal
            showEventDetailModal(info.event);
        },
        editable: true,
        eventDrop: function(info) {
            // Handle event drag and drop
            updateEventDateTime(info.event);
        },
        eventResize: function(info) {
            // Handle event resize
            updateEventDateTime(info.event);
        },
        dateClick: function(info) {
            // Handle date click - quick event creation
            if (info.jsEvent.detail === 2) { // Double click
                showQuickEventModal(info.date);
            }
        },
        eventDidMount: function(info) {
            // Add tooltips or custom styling
            info.el.title = info.event.title;
        },
        dayMaxEvents: true,
        moreLinkClick: 'popover'
    });
    
    calendar.render();

    // Check for URL parameter or hash to show specific event
    window.addEventListener('load', function() {
        // Check URL parameter first
        const urlParams = new URLSearchParams(window.location.search);
        const eventParam = urlParams.get('event');

        // Check hash as fallback
        const hash = window.location.hash;
        const hashEventId = hash.startsWith('#event-') ? hash.replace('#event-', '') : null;

        const eventId = eventParam || hashEventId;

        if (eventId) {
            // Wait for calendar to load events, then show the specific event
            setTimeout(() => {
                const event = calendar.getEventById(eventId);
                if (event) {
                    showEventDetailModal(event);
                    // Clear URL parameter after showing modal
                    const newUrl = window.location.pathname;
                    window.history.replaceState({}, document.title, newUrl);
                } else {
                    // If event not found, try to fetch it directly
                    // Refresh calendar and try again
                    calendar.refetchEvents();
                    setTimeout(() => {
                        const retryEvent = calendar.getEventById(eventId);
                        if (retryEvent) {
                            showEventDetailModal(retryEvent);
                        } else {
                            // Try one more time with longer delay
                            setTimeout(() => {
                                const finalRetryEvent = calendar.getEventById(eventId);
                                if (finalRetryEvent) {
                                    showEventDetailModal(finalRetryEvent);
                                } else {
                                    showToast('Event not found or you do not have permission to view it', 'error');
                                }
                            }, 1000);
                        }
                    }, 1500);
                }
            }, 1000);
        }
    });

    // Also check for server-side event parameter
    @if(isset($showEventId) && $showEventId)
    window.addEventListener('load', function() {
        setTimeout(() => {
            const event = calendar.getEventById('{{ $showEventId }}');
            if (event) {
                showEventDetailModal(event);
            }
        }, 1500);
    });
    @endif

    // Add Event Modal Functionality
    const addEventBtn = document.getElementById('addEventBtn');
    const addEventModal = document.getElementById('addEventModal');
    const closeModalBtn = document.getElementById('closeModalBtn');
    const cancelBtn = document.getElementById('cancelBtn');
    const addEventForm = document.getElementById('addEventForm');

    // Show modal
    addEventBtn.addEventListener('click', function() {
        addEventModal.classList.remove('hidden');
        // Set default start date to now
        const now = new Date();
        now.setMinutes(now.getMinutes() - now.getTimezoneOffset());
        document.getElementById('eventStartDate').value = now.toISOString().slice(0, 16);

        // Set default end date to 1 hour later
        const endDate = new Date(now.getTime() + 60 * 60 * 1000);
        document.getElementById('eventEndDate').value = endDate.toISOString().slice(0, 16);
    });

    // Hide modal
    function hideModal() {
        addEventModal.classList.add('hidden');
        addEventForm.reset();
    }

    closeModalBtn.addEventListener('click', hideModal);
    cancelBtn.addEventListener('click', hideModal);

    // Close modal when clicking outside
    addEventModal.addEventListener('click', function(e) {
        if (e.target === addEventModal) {
            hideModal();
        }
    });

    // Handle form submission
    addEventForm.addEventListener('submit', function(e) {
        e.preventDefault();

        const formData = new FormData(addEventForm);
        const eventData = Object.fromEntries(formData.entries());

        // Show loading state
        const submitBtn = addEventForm.querySelector('button[type="submit"]');
        const originalText = submitBtn.textContent;
        submitBtn.textContent = 'Creating...';
        submitBtn.disabled = true;

        fetch('{{ route("calendar.store") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify(eventData)
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Hide modal
                hideModal();

                // Refresh calendar
                calendar.refetchEvents();

                // Show success message
                showToast('Event created successfully!', 'success');
            } else {
                showToast('Error creating event: ' + (data.message || 'Unknown error'), 'error');
            }
        })
        .catch(error => {
            showToast('Error creating event. Please try again.', 'error');
        })
        .finally(() => {
            // Reset button state
            submitBtn.textContent = originalText;
            submitBtn.disabled = false;
        });
    });

    // Event Detail Modal Functions
    const eventDetailModal = document.getElementById('eventDetailModal');
    const closeEventDetailBtn = document.getElementById('closeEventDetailBtn');
    const editEventBtn = document.getElementById('editEventBtn');
    const deleteEventBtn = document.getElementById('deleteEventBtn');
    let currentEventId = null;

    // Show event detail modal
    window.showEventDetailModal = function(event) {
        currentEventId = event.id;
        const extendedProps = event.extendedProps || {};

        // Format dates
        const startDate = new Date(event.start);
        const endDate = new Date(event.end);
        const startFormatted = startDate.toLocaleDateString('en-US', {
            weekday: 'long',
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        }) + ' at ' + startDate.toLocaleTimeString('en-US', {
            hour: 'numeric',
            minute: '2-digit',
            hour12: true
        });

        const endFormatted = endDate.toLocaleTimeString('en-US', {
            hour: 'numeric',
            minute: '2-digit',
            hour12: true
        });

        // Get category display name and color
        const categoryInfo = getCategoryInfo(extendedProps.category || event.category);

        // Build reminder text
        let reminderText = 'No reminder set';
        if (extendedProps.reminder_minutes) {
            const minutes = extendedProps.reminder_minutes;
            if (minutes < 60) {
                reminderText = `${minutes} minutes before event`;
            } else if (minutes < 1440) {
                const hours = Math.floor(minutes / 60);
                reminderText = `${hours} hour${hours > 1 ? 's' : ''} before event`;
            } else {
                const days = Math.floor(minutes / 1440);
                reminderText = `${days} day${days > 1 ? 's' : ''} before event`;
            }
        }

        // Populate modal content
        document.getElementById('eventDetailContent').innerHTML = `
            <div class="space-y-4">
                <!-- Event Title -->
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Event Title</label>
                    <div class="flex items-center">
                        <span class="material-icons text-sm mr-2" style="color: ${categoryInfo.color}">${categoryInfo.icon}</span>
                        <p class="text-sm font-medium text-gray-900">${event.title}</p>
                    </div>
                </div>

                <!-- Category -->
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Category</label>
                    <div class="flex items-center">
                        <span class="inline-block w-3 h-3 rounded-full mr-2" style="background-color: ${categoryInfo.color}"></span>
                        <p class="text-sm text-gray-900">${categoryInfo.name}</p>
                    </div>
                </div>

                <!-- Date & Time -->
                <div class="grid grid-cols-1 gap-3">
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Start Date & Time</label>
                        <div class="flex items-center">
                            <span class="material-icons text-sm mr-2 text-green-600">schedule</span>
                            <p class="text-sm text-gray-900">${startFormatted}</p>
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">End Time</label>
                        <div class="flex items-center">
                            <span class="material-icons text-sm mr-2 text-red-600">schedule</span>
                            <p class="text-sm text-gray-900">${endFormatted}</p>
                        </div>
                    </div>
                </div>

                ${event.extendedProps?.description ? `
                <!-- Description -->
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Description</label>
                    <div class="bg-gray-50 p-3 rounded-md">
                        <p class="text-sm text-gray-900">${event.extendedProps.description}</p>
                    </div>
                </div>
                ` : ''}

                ${extendedProps.location ? `
                <!-- Location -->
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Location</label>
                    <div class="flex items-center">
                        <span class="material-icons text-sm mr-2 text-blue-600">location_on</span>
                        <p class="text-sm text-gray-900">${extendedProps.location}</p>
                    </div>
                </div>
                ` : ''}

                <!-- Reminder -->
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Reminder</label>
                    <div class="flex items-center">
                        <span class="material-icons text-sm mr-2 ${extendedProps.reminder_minutes ? 'text-orange-600' : 'text-gray-400'}">notifications</span>
                        <p class="text-sm text-gray-900">${reminderText}</p>
                    </div>
                </div>

                ${extendedProps.case_number ? `
                <!-- Related Case -->
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Related Case</label>
                    <div class="flex items-center">
                        <span class="material-icons text-sm mr-2 text-purple-600">gavel</span>
                        <p class="text-sm text-gray-900">${extendedProps.case_number}</p>
                    </div>
                </div>
                ` : ''}

                <!-- Created By -->
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Created By</label>
                    <div class="flex items-center">
                        <span class="material-icons text-sm mr-2 text-gray-600">person</span>
                        <p class="text-sm text-gray-900">${extendedProps.created_by || 'System'}</p>
                    </div>
                </div>
            </div>
        `;

        eventDetailModal.classList.remove('hidden');
    };

    // Get category information
    function getCategoryInfo(category) {
        const categories = {
            'court_hearing': { name: 'Court Hearing', color: '#dc2626', icon: 'gavel' },
            'consultation': { name: 'Consultation', color: '#2563eb', icon: 'chat' },
            'client_meeting': { name: 'Client Meeting', color: '#2563eb', icon: 'people' },
            'document_filing': { name: 'Document Filing', color: '#d97706', icon: 'description' },
            'deadline': { name: 'Deadline', color: '#d97706', icon: 'warning' },
            'follow_up': { name: 'Follow Up', color: '#16a34a', icon: 'phone' },
            'payment': { name: 'Payment', color: '#7c3aed', icon: 'payment' },
            'settlement': { name: 'Settlement', color: '#059669', icon: 'handshake' },
            'other': { name: 'Other', color: '#6b7280', icon: 'event' }
        };

        return categories[category] || categories['other'];
    }

    // Hide event detail modal
    function hideEventDetailModal() {
        eventDetailModal.classList.add('hidden');
        currentEventId = null;
    }

    // Event listeners for modal
    closeEventDetailBtn.addEventListener('click', hideEventDetailModal);

    // Close modal when clicking outside
    eventDetailModal.addEventListener('click', function(e) {
        if (e.target === eventDetailModal) {
            hideEventDetailModal();
        }
    });

    // Edit event button
    editEventBtn.addEventListener('click', function() {
        if (currentEventId) {
            const event = calendar.getEventById(currentEventId);
            if (event) {
                showEditEventModal(event);
                hideEventDetailModal();
            }
        }
    });

    // Delete event button
    deleteEventBtn.addEventListener('click', function() {
        if (currentEventId && confirm('Are you sure you want to delete this event?')) {
            deleteEvent(currentEventId);
        }
    });

    // Delete event function
    function deleteEvent(eventId) {
        fetch(`/calendar/events/${eventId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                hideEventDetailModal();
                calendar.refetchEvents();
                showToast('Event deleted successfully!', 'success');
            } else {
                showToast('Error deleting event: ' + (data.message || 'Unknown error'), 'error');
            }
        })
        .catch(error => {
            showToast('Error deleting event. Please try again.', 'error');
        });
    }

    // Edit Event Modal Functions
    const editEventModal = document.getElementById('editEventModal');
    const closeEditModalBtn = document.getElementById('closeEditModalBtn');
    const cancelEditBtn = document.getElementById('cancelEditBtn');
    const editEventForm = document.getElementById('editEventForm');
    let currentEditEventId = null;

    // Show edit event modal
    function showEditEventModal(event) {
        currentEditEventId = event.id;
        const extendedProps = event.extendedProps || {};

        // Populate form fields
        document.getElementById('editEventTitle').value = event.title || '';
        document.getElementById('editEventDescription').value = extendedProps.description || '';
        document.getElementById('editEventLocation').value = extendedProps.location || '';

        // Format dates for datetime-local input
        const startDate = new Date(event.start);
        const endDate = new Date(event.end);

        // Convert to local timezone and format for datetime-local
        startDate.setMinutes(startDate.getMinutes() - startDate.getTimezoneOffset());
        endDate.setMinutes(endDate.getMinutes() - endDate.getTimezoneOffset());

        document.getElementById('editEventStartDate').value = startDate.toISOString().slice(0, 16);
        document.getElementById('editEventEndDate').value = endDate.toISOString().slice(0, 16);

        document.getElementById('editEventCategory').value = extendedProps.category || '';
        document.getElementById('editEventCase').value = extendedProps.case_id || '';
        document.getElementById('editEventReminder').value = extendedProps.reminder_minutes || '';

        editEventModal.classList.remove('hidden');
    }

    // Hide edit event modal
    function hideEditEventModal() {
        editEventModal.classList.add('hidden');
        editEventForm.reset();
        currentEditEventId = null;
    }

    // Event listeners for edit modal
    closeEditModalBtn.addEventListener('click', hideEditEventModal);
    cancelEditBtn.addEventListener('click', hideEditEventModal);

    // Close modal when clicking outside
    editEventModal.addEventListener('click', function(e) {
        if (e.target === editEventModal) {
            hideEditEventModal();
        }
    });

    // Handle edit form submission
    editEventForm.addEventListener('submit', function(e) {
        e.preventDefault();

        if (!currentEditEventId) return;

        const formData = new FormData(editEventForm);
        const eventData = Object.fromEntries(formData.entries());

        // Show loading state
        const submitBtn = editEventForm.querySelector('button[type="submit"]');
        const originalText = submitBtn.textContent;
        submitBtn.textContent = 'Updating...';
        submitBtn.disabled = true;

        fetch(`/calendar/events/${currentEditEventId}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify(eventData)
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Hide modal
                hideEditEventModal();

                // Refresh calendar
                calendar.refetchEvents();

                // Show success message
                showToast('Event updated successfully!', 'success');
            } else {
                showToast('Error updating event: ' + (data.message || 'Unknown error'), 'error');
            }
        })
        .catch(error => {
            showToast('Error updating event. Please try again.', 'error');
        })
        .finally(() => {
            // Reset button state
            submitBtn.textContent = originalText;
            submitBtn.disabled = false;
        });
    });

    // Quick event creation (double click on date)
    function showQuickEventModal(date) {
        // Pre-fill the add event modal with the clicked date
        const clickedDate = new Date(date);
        clickedDate.setMinutes(clickedDate.getMinutes() - clickedDate.getTimezoneOffset());

        // Set start date to clicked date
        document.getElementById('eventStartDate').value = clickedDate.toISOString().slice(0, 16);

        // Set end date to 1 hour later
        const endDate = new Date(clickedDate.getTime() + 60 * 60 * 1000);
        document.getElementById('eventEndDate').value = endDate.toISOString().slice(0, 16);

        // Show the add event modal
        addEventModal.classList.remove('hidden');

        // Focus on title field
        document.getElementById('eventTitle').focus();

        // Show toast to inform user
        showToast('Double-clicked to create event. Fill in the details!', 'info');
    }

    // Update event date/time via drag & drop or resize
    function updateEventDateTime(event) {
        const eventData = {
            title: event.title,
            start_date: event.start.toISOString(),
            end_date: event.end.toISOString(),
            description: event.extendedProps.description || '',
            location: event.extendedProps.location || '',
            category: event.extendedProps.category || 'other',
            reminder_minutes: event.extendedProps.reminder_minutes || null
        };

        fetch(`/calendar/events/${event.id}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify(eventData)
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Show subtle success indication
                showToast('Event updated successfully', 'success');
            } else {
                // Revert the event if update failed
                event.revert();
                showToast('Error updating event: ' + (data.message || 'Unknown error'), 'error');
            }
        })
        .catch(error => {
            event.revert();
            showToast('Error updating event. Please try again.', 'error');
        });
    }

    // Toast notification function
    function showToast(message, type = 'info') {
        const toast = document.createElement('div');
        toast.className = `fixed top-4 right-4 px-4 py-2 rounded-md text-white text-sm z-50 transition-opacity duration-300 ${
            type === 'success' ? 'bg-green-500' :
            type === 'error' ? 'bg-red-500' :
            'bg-blue-500'
        }`;
        toast.textContent = message;

        document.body.appendChild(toast);

        // Auto remove after 3 seconds
        setTimeout(() => {
            toast.style.opacity = '0';
            setTimeout(() => {
                document.body.removeChild(toast);
            }, 300);
        }, 3000);
    }

    // Event filtering function
    window.filterEvents = function(category) {
        // Update calendar events source with filter
        calendar.removeAllEventSources();
        calendar.addEventSource({
            url: '{{ route("calendar.events") }}',
            method: 'GET',
            extraParams: {
                category: category === 'all' ? '' : category
            },
            success: function(events) {
                // Events filtered successfully
            },
            failure: function(error) {
                showToast('Error loading filtered events', 'error');
            }
        });

        // Show toast
        const categoryName = category === 'all' ? 'All Events' :
                           category.replace('_', ' ').replace(/\b\w/g, l => l.toUpperCase());
        showToast(`Showing: ${categoryName}`, 'info');
    };

    // Calendar is initialized with default events from the events URL

    // Note: Using built-in FullCalendar header buttons for view switching
    // The buttons in the calendar header (month, week, day, list) are now functional
});
</script>
@endsection 