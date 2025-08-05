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
                    <p class="text-xs text-gray-500 mt-1 ml-8 text-[11px]">Comprehensive overview and statistics</p>
                </div>
                <!-- Filter Form -->
                <form action="{{ route('overview') }}" method="GET" class="flex items-center space-x-3">
                    <div>
                        <select name="period" id="period" class="text-xs rounded-md border-gray-300 shadow-sm text-[11px]">
                            <option value="this_month">This Month</option>
                            <option value="last_month">Last Month</option>
                            <option value="last_3_months">Last 3 Months</option>
                            <option value="last_6_months">Last 6 Months</option>
                            <option value="this_year">This Year</option>
                            <option value="custom">Custom Range</option>
                        </select>
                    </div>
                    <div id="customDateContainer" class="flex items-center space-x-2 hidden">
                        <div class="flex items-center">
                            <label for="start_date" class="text-xs mr-1 text-[11px]">From:</label>
                            <input type="date" name="start_date" id="start_date" class="text-xs rounded-md border-gray-300 shadow-sm text-[11px]">
                        </div>
                        <div class="flex items-center">
                            <label for="end_date" class="text-xs mr-1 text-[11px]">To:</label>
                            <input type="date" name="end_date" id="end_date" class="text-xs rounded-md border-gray-300 shadow-sm text-[11px]">
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
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                <div class="bg-white border border-gray-200 rounded-lg shadow-sm p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500 text-xs font-medium text-[13px]">Total Cases</p>
                            <h3 class="text-2xl font-semibold mt-1 text-[12px]">1,234</h3>
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
                            <h3 class="text-2xl font-semibold mt-1 text-[12px]">856</h3>
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
                            <h3 class="text-2xl font-semibold mt-1 text-[12px]">567</h3>
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
                            <h3 class="text-2xl font-semibold mt-1 text-[12px]">12</h3>
                        </div>
                        <div class="p-3 bg-orange-100 rounded-full">
                            <span class="material-icons text-orange-600">business</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Activity -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="bg-white border border-gray-200 rounded-lg shadow-sm">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-lg font-semibold text-gray-800 text-[13px]">Recent Cases</h2>
                    </div>
                    <div class="p-6">
                        <div class="space-y-3">
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                <div class="flex items-center">
                                    <span class="material-icons text-blue-600 mr-3">description</span>
                                    <div>
                                        <p class="text-sm font-medium text-gray-800 text-[11px]">Case #2024-001</p>
                                        <p class="text-xs text-gray-500 text-[11px]">Civil Case - Property Dispute</p>
                                    </div>
                                </div>
                                <span class="badge badge-success text-[11px]">Active</span>
                            </div>
                            
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                <div class="flex items-center">
                                    <span class="material-icons text-green-600 mr-3">description</span>
                                    <div>
                                        <p class="text-sm font-medium text-gray-800 text-[11px]">Case #2024-002</p>
                                        <p class="text-xs text-gray-500 text-[11px]">Criminal Case - Theft</p>
                                    </div>
                                </div>
                                <span class="badge badge-warning text-[11px]">Pending</span>
                            </div>
                            
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                <div class="flex items-center">
                                    <span class="material-icons text-purple-600 mr-3">description</span>
                                    <div>
                                        <p class="text-sm font-medium text-gray-800 text-[11px]">Case #2024-003</p>
                                        <p class="text-xs text-gray-500 text-[11px]">Sharia Case - Family Law</p>
                                    </div>
                                </div>
                                <span class="badge badge-success text-[11px]">Active</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white border border-gray-200 rounded-lg shadow-sm">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-lg font-semibold text-gray-800 text-[13px]">Upcoming Hearings</h2>
                    </div>
                    <div class="p-6">
                        <div class="space-y-3">
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                <div class="flex items-center">
                                    <span class="material-icons text-red-600 mr-3">event</span>
                                    <div>
                                        <p class="text-sm font-medium text-gray-800 text-[11px]">Case #2024-001</p>
                                        <p class="text-xs text-gray-500 text-[11px]">Tomorrow, 10:00 AM</p>
                                    </div>
                                </div>
                                <span class="badge badge-danger text-[11px]">Urgent</span>
                            </div>
                            
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                <div class="flex items-center">
                                    <span class="material-icons text-orange-600 mr-3">event</span>
                                    <div>
                                        <p class="text-sm font-medium text-gray-800 text-[11px]">Case #2024-002</p>
                                        <p class="text-xs text-gray-500 text-[11px]">Friday, 2:30 PM</p>
                                    </div>
                                </div>
                                <span class="badge badge-warning text-[11px]">Scheduled</span>
                            </div>
                            
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                <div class="flex items-center">
                                    <span class="material-icons text-blue-600 mr-3">event</span>
                                    <div>
                                        <p class="text-sm font-medium text-gray-800 text-[11px]">Case #2024-003</p>
                                        <p class="text-xs text-gray-500 text-[11px]">Next Week, 9:00 AM</p>
                                    </div>
                                </div>
                                <span class="badge badge-info text-[11px]">Confirmed</span>
                            </div>
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