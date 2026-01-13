@extends('layouts.app')

@section('breadcrumb')
    Overview
@endsection

@section('content')
<div class="px-6 pt-6 pb-6 max-w-7xl mx-auto">
    <div class="bg-white rounded shadow-md border border-gray-300">
        <div class="p-6 border-b border-gray-200">
            <div class="flex justify-between items-start">
                <div>
                    <div class="flex items-center">
                        <span class="material-icons mr-2 text-blue-600">dashboard</span>
                        <h1 class="text-xl font-bold text-gray-800 text-[14px]">Overview</h1>
                    </div>
                    @if(isset($isClient) && $isClient)
                        <p class="text-xs text-gray-500 mt-1 ml-8 text-[11px]">Welcome, {{ $clientName ?? auth()->user()->name }}! Here's your case summary.</p>
                    @else
                        <p class="text-xs text-gray-500 mt-1 ml-8 text-[11px]">Comprehensive overview and statistics</p>
                    @endif
                </div>
                <!-- Filter Form -->
                <form action="{{ route('overview') }}" method="GET" class="flex items-center space-x-3">
                    <div>
                        <select name="period" id="period" class="text-xs rounded-md border-gray-300 shadow-sm text-[11px]">
                            <option value="this_month" {{ request('period', 'this_month') == 'this_month' ? 'selected' : '' }}>This Month</option>
                            <option value="last_month" {{ request('period') == 'last_month' ? 'selected' : '' }}>Last Month</option>
                            <option value="last_3_months" {{ request('period') == 'last_3_months' ? 'selected' : '' }}>Last 3 Months</option>
                            <option value="last_6_months" {{ request('period') == 'last_6_months' ? 'selected' : '' }}>Last 6 Months</option>
                            <option value="this_year" {{ request('period') == 'this_year' ? 'selected' : '' }}>This Year</option>
                            <option value="custom" {{ request('period') == 'custom' ? 'selected' : '' }}>Custom Range</option>
                        </select>
                    </div>
                    <div id="customDateContainer" class="flex items-center space-x-2 {{ request('period') == 'custom' ? '' : 'hidden' }}">
                        <div class="flex items-center">
                            <label for="start_date" class="text-xs mr-1 text-[11px]">From:</label>
                            <input type="date" name="start_date" id="start_date" value="{{ request('start_date') }}" class="text-xs rounded-md border-gray-300 shadow-sm text-[11px]">
                        </div>
                        <div class="flex items-center">
                            <label for="end_date" class="text-xs mr-1 text-[11px]">To:</label>
                            <input type="date" name="end_date" id="end_date" value="{{ request('end_date') }}" class="text-xs rounded-md border-gray-300 shadow-sm text-[11px]">
                        </div>
                    </div>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded shadow-sm text-xs text-[11px]">
                        <span class="material-icons text-xs mr-1">filter_alt</span>
                        Apply Filter
                    </button>
                </form>
            </div>
        </div>

        <div class="p-6">
            <!-- Summary Statistics Cards -->
            @if(isset($isClient) && $isClient)
                <!-- Client View - Show only their case stats -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                    <div class="bg-white border border-gray-200 rounded-lg shadow-sm p-4">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-500 text-xs font-medium text-[13px]">My Cases</p>
                                <h3 class="text-2xl font-semibold mt-1 text-[12px]">{{ $totalCases ?? 0 }}</h3>
                            </div>
                            <div class="p-3 bg-blue-100 rounded-full">
                                <span class="material-icons text-blue-600">folder</span>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white border border-gray-200 rounded-lg shadow-sm p-4">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-500 text-xs font-medium text-[13px]">Active</p>
                                <h3 class="text-2xl font-semibold mt-1 text-[12px]">{{ $activeCases ?? 0 }}</h3>
                            </div>
                            <div class="p-3 bg-green-100 rounded-full">
                                <span class="material-icons text-green-600">check_circle</span>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white border border-gray-200 rounded-lg shadow-sm p-4">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-500 text-xs font-medium text-[13px]">Pending</p>
                                <h3 class="text-2xl font-semibold mt-1 text-[12px]">{{ $pendingCases ?? 0 }}</h3>
                            </div>
                            <div class="p-3 bg-yellow-100 rounded-full">
                                <span class="material-icons text-yellow-600">pending</span>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white border border-gray-200 rounded-lg shadow-sm p-4">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-500 text-xs font-medium text-[13px]">Closed</p>
                                <h3 class="text-2xl font-semibold mt-1 text-[12px]">{{ $closedCases ?? 0 }}</h3>
                            </div>
                            <div class="p-3 bg-gray-100 rounded-full">
                                <span class="material-icons text-gray-600">archive</span>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <!-- Staff/Admin View - Show all firm stats -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                    <div class="bg-white border border-gray-200 rounded-lg shadow-sm p-4">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-500 text-xs font-medium text-[13px]">Total Cases</p>
                                <h3 class="text-2xl font-semibold mt-1 text-[12px]">{{ $totalCases ?? 0 }}</h3>
                            </div>
                            <div class="p-3 bg-blue-100 rounded-full">
                                <span class="material-icons text-blue-600">folder</span>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white border border-gray-200 rounded-lg shadow-sm p-4">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-500 text-xs font-medium text-[13px]">Active Cases</p>
                                <h3 class="text-2xl font-semibold mt-1 text-[12px]">{{ $activeCases ?? 0 }}</h3>
                            </div>
                            <div class="p-3 bg-green-100 rounded-full">
                                <span class="material-icons text-green-600">check_circle</span>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white border border-gray-200 rounded-lg shadow-sm p-4">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-500 text-xs font-medium text-[13px]">Total Clients</p>
                                <h3 class="text-2xl font-semibold mt-1 text-[12px]">{{ $totalClients ?? 0 }}</h3>
                            </div>
                            <div class="p-3 bg-purple-100 rounded-full">
                                <span class="material-icons text-purple-600">people</span>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white border border-gray-200 rounded-lg shadow-sm p-4">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-500 text-xs font-medium text-[13px]">Partners</p>
                                <h3 class="text-2xl font-semibold mt-1 text-[12px]">{{ $totalPartners ?? 0 }}</h3>
                            </div>
                            <div class="p-3 bg-orange-100 rounded-full">
                                <span class="material-icons text-orange-600">business</span>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Recent Activity -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="bg-white border border-gray-200 rounded-lg shadow-sm">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-lg font-semibold text-gray-800 text-[13px]">
                            {{ isset($isClient) && $isClient ? 'My Cases' : 'Recent Cases' }}
                        </h2>
                    </div>
                    <div class="p-6">
                        <div class="space-y-3">
                            @forelse($recentCases ?? [] as $case)
                                <a href="{{ route('case.show', $case->id) }}" class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                                    <div class="flex items-center">
                                        <span class="material-icons text-blue-600 mr-3">description</span>
                                        <div>
                                            <p class="text-sm font-medium text-gray-800 text-[11px]">{{ $case->case_number }}</p>
                                            <p class="text-xs text-gray-500 text-[11px]">{{ $case->sectionType->name ?? 'N/A' }} - {{ Str::limit($case->title ?? 'No Title', 30) }}</p>
                                        </div>
                                    </div>
                                    @php
                                        $statusName = strtolower($case->caseStatus->name ?? 'n/a');
                                        $badgeClass = 'badge-secondary';
                                        if (str_contains($statusName, 'active')) $badgeClass = 'badge-success';
                                        elseif (str_contains($statusName, 'pending')) $badgeClass = 'badge-warning';
                                        elseif (str_contains($statusName, 'closed') || str_contains($statusName, 'completed')) $badgeClass = 'badge-secondary';
                                    @endphp
                                    <span class="badge {{ $badgeClass }} text-[11px]">
                                        {{ ucfirst($case->caseStatus->name ?? 'N/A') }}
                                    </span>
                                </a>
                            @empty
                                <div class="text-center py-4 text-gray-500 text-xs">
                                    <span class="material-icons text-gray-400 text-3xl mb-2">folder_off</span>
                                    <p>No cases found</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>

                <div class="bg-white border border-gray-200 rounded-lg shadow-sm">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-lg font-semibold text-gray-800 text-[13px]">Upcoming Hearings</h2>
                    </div>
                    <div class="p-6">
                        <div class="space-y-3">
                            @forelse($upcomingHearings ?? [] as $hearing)
                                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                    <div class="flex items-center">
                                        <span class="material-icons text-red-600 mr-3">event</span>
                                        <div>
                                            <p class="text-sm font-medium text-gray-800 text-[11px]">{{ $hearing->case->case_number ?? 'N/A' }}</p>
                                            <p class="text-xs text-gray-500 text-[11px]">{{ \Carbon\Carbon::parse($hearing->start_date)->format('D, d M Y, h:i A') }}</p>
                                        </div>
                                    </div>
                                    <span class="badge {{ $hearing->status == 'confirmed' ? 'badge-info' : ($hearing->status == 'urgent' ? 'badge-danger' : 'badge-warning') }} text-[11px]">
                                        {{ ucfirst($hearing->status ?? 'Scheduled') }}
                                    </span>
                                </div>
                            @empty
                                <div class="text-center py-4 text-gray-500 text-xs">
                                    <span class="material-icons text-gray-400 text-3xl mb-2">event_busy</span>
                                    <p>No upcoming hearings</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Custom date range functionality
document.getElementById('period').addEventListener('change', function() {
    const customContainer = document.getElementById('customDateContainer');
    if (this.value === 'custom') {
        customContainer.classList.remove('hidden');
    } else {
        customContainer.classList.add('hidden');
    }
});
</script>
@endsection
