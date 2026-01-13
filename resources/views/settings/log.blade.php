@extends('layouts.app')

@section('breadcrumb')
    Settings > Activity Logs
@endsection

@section('styles')
<style>
.custom-select {
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e");
    background-position: right 8px center;
    background-repeat: no-repeat;
    background-size: 16px 16px;
    padding-right: 32px;
    appearance: none;
    -webkit-appearance: none;
    -moz-appearance: none;
}
</style>
@endsection

@section('content')
<div class="px-4 md:px-6 pt-4 md:pt-6 pb-6 max-w-7xl mx-auto">
    <!-- Success/Error Messages -->
    {{-- Success messages now handled by layout toast notifications --}}

    @if(session('error'))
        <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
            {{ session('error') }}
        </div>
    @endif

    <div class="bg-white rounded shadow-md border border-gray-300">
        <div class="p-4 md:p-6 border-b border-gray-200">
            <div class="flex flex-col md:flex-row md:justify-between md:items-start">
                <div class="mb-4 md:mb-0">
                    <div class="flex items-center">
                        <span class="material-icons mr-2 text-red-600">history</span>
                        <h1 class="text-lg md:text-xl font-bold text-gray-800 text-[14px]">Activity Logs</h1>
                    </div>
                    <p class="text-xs text-gray-500 mt-1 ml-8 text-[11px]">View and manage system activity logs and user actions.</p>
                </div>
                
                <!-- Clear Log Button -->
                <button onclick="clearAllLogs()" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 md:px-3 md:py-1 rounded-md text-sm md:text-xs font-medium flex items-center justify-center md:justify-start w-full md:w-auto">
                    <span class="material-icons text-xs mr-1">delete_sweep</span>
                    Clear Logs
                </button>
            </div>
        </div>

        <!-- Desktop Table View -->
        <div class="hidden md:block p-6">
            <!-- Controls Above Table -->
            <div class="flex justify-between items-center mb-2">
                <!-- Left: Show Entries -->
                <div class="flex items-center gap-2">
                    <label for="perPage" class="text-xs text-gray-700">Show:</label>
                    <select id="perPage" onchange="changePerPage()" class="custom-select border border-gray-300 rounded pl-4 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white">
                        <option value="10">10</option>
                        <option value="25" selected>25</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                    </select>
                    <span class="text-xs text-gray-700">entries</span>
                </div>

                <!-- Right: Search and Filters -->
                <div class="flex gap-2 items-center">
                    <input type="text" id="searchFilter" placeholder="Search activity, user, or description..."
                           onkeyup="filterLogs()"
                           class="border border-gray-300 rounded px-3 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white w-64">

                    <select id="eventFilter" onchange="filterLogs()" class="custom-select border border-gray-300 rounded pl-4 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white">
                        <option value="">All Events</option>
                        <option value="created">Created</option>
                        <option value="updated">Updated</option>
                        <option value="deleted">Deleted</option>
                        <option value="login">Login</option>
                        <option value="logout">Logout</option>
                    </select>

                    <button onclick="filterLogs()" class="px-3 py-2 bg-blue-500 text-white rounded text-xs hover:bg-blue-600 transition-colors">
                        🔍 Search
                    </button>

                    <button onclick="resetFilters()" class="px-3 py-2 bg-gray-500 text-white rounded text-xs hover:bg-gray-600 transition-colors">
                        🔄 Reset
                    </button>
                </div>
            </div>
            <div class="overflow-visible border border-gray-200 rounded">
                <table class="min-w-full border-collapse">
                    <thead>
                        <tr class="bg-primary-light text-white uppercase text-xs">
                            <th class="py-3 px-4 text-left rounded-tl">Date/Time</th>
                            <th class="py-3 px-4 text-left">User</th>
                            <th class="py-3 px-4 text-left">Action</th>
                            <th class="py-3 px-4 text-left">Description</th>
                            <th class="py-3 px-4 text-left">Level</th>
                            <th class="py-3 px-4 text-center rounded-tr">IP Address</th>
                        </tr>
                    </thead>
                    <tbody id="logs-table-body">
                        <!-- Logs will be populated here -->
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Mobile Card View -->
        <div class="md:hidden p-4">
            <!-- Mobile Controls -->
            <div class="space-y-3 mb-4">
                <!-- Show Entries -->
                <div class="flex items-center gap-2">
                    <label for="perPageMobile" class="text-xs text-gray-700">Show:</label>
                    <select id="perPageMobile" onchange="changePerPage()" class="custom-select border border-gray-300 rounded pl-4 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white">
                        <option value="10">10</option>
                        <option value="25" selected>25</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                    </select>
                    <span class="text-xs text-gray-700">entries</span>
                </div>

                <!-- Search and Filters -->
                <div class="space-y-2">
                    <input type="text" id="searchFilterMobile" placeholder="Search activity, user, or description..."
                           onkeyup="filterLogs()"
                           class="w-full border border-gray-300 rounded px-3 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white">

                    <div class="flex gap-2">
                        <select id="eventFilterMobile" onchange="filterLogs()" class="custom-select flex-1 border border-gray-300 rounded pl-4 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white">
                            <option value="">All Events</option>
                            <option value="created">Created</option>
                            <option value="updated">Updated</option>
                            <option value="deleted">Deleted</option>
                            <option value="login">Login</option>
                            <option value="logout">Logout</option>
                        </select>

                        <button onclick="filterLogs()" class="px-3 py-2 bg-blue-500 text-white rounded text-xs hover:bg-blue-600 transition-colors">
                            🔍
                        </button>

                        <button onclick="resetFilters()" class="px-3 py-2 bg-gray-500 text-white rounded text-xs hover:bg-gray-600 transition-colors">
                            🔄
                        </button>
                    </div>
                </div>
            </div>

            <div class="space-y-4" id="logs-mobile-container">
                <!-- Mobile cards will be populated here -->
            </div>
        </div>

        <!-- Pagination Section -->
        <div class="p-6">
            <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4">
                <!-- Left: Page Info -->
                <div class="text-xs text-gray-600">
                    <span id="pageInfo">Showing 1 to 25 of 100 records</span>
                </div>

                <!-- Right on Desktop, Center on Mobile: Pagination -->
                <div class="flex items-center justify-center md:justify-end gap-1">
                    <button id="prevBtn" onclick="firstPage()"
                            class="w-8 h-8 flex items-center justify-center text-xs text-gray-600 hover:text-blue-600 hover:bg-blue-50 rounded-full disabled:opacity-50 disabled:cursor-not-allowed transition-colors">
                        &laquo;
                    </button>

                    <button id="prevSingleBtn" onclick="previousPage()"
                            class="w-8 h-8 flex items-center justify-center text-xs text-gray-600 hover:text-blue-600 hover:bg-blue-50 rounded-full disabled:opacity-50 disabled:cursor-not-allowed transition-colors">
                        &lsaquo;
                    </button>

                    <div id="pageNumbers" class="flex items-center gap-1 mx-2">
                        <!-- Page numbers will be populated here -->
                    </div>

                    <button id="nextSingleBtn" onclick="nextPage()"
                            class="w-8 h-8 flex items-center justify-center text-xs text-gray-600 hover:text-blue-600 hover:bg-blue-50 rounded-full disabled:opacity-50 disabled:cursor-not-allowed transition-colors">
                        &rsaquo;
                    </button>

                    <button id="nextBtn" onclick="lastPage()"
                            class="w-8 h-8 flex items-center justify-center text-xs text-gray-600 hover:text-blue-600 hover:bg-blue-50 rounded-full disabled:opacity-50 disabled:cursor-not-allowed transition-colors">
                        &raquo;
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
let activityLogs = [];
let filteredLogs = [];
let currentPage = 1;
let perPage = 25;

// Initialize page
document.addEventListener('DOMContentLoaded', function() {
    loadActivityLogs();
});

function loadActivityLogs() {
    fetch('{{ route("settings.log.data") }}', {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        activityLogs = data.logs;
        filteredLogs = activityLogs;
        currentPage = 1;
        displayLogs();
        updatePagination();
    })
    .catch(error => {
        console.error('Error loading activity logs:', error);
        showError('Failed to load activity logs');
    });
}

function displayLogs() {
    const tableBody = document.getElementById('logs-table-body');
    const mobileContainer = document.getElementById('logs-mobile-container');

    // Calculate pagination
    const startIndex = (currentPage - 1) * perPage;
    const endIndex = startIndex + perPage;
    const paginatedLogs = filteredLogs.slice(startIndex, endIndex);

    if (paginatedLogs.length === 0) {
        // Enhanced empty state for desktop table
        tableBody.innerHTML = `
            <tr>
                <td colspan="6" class="py-8 text-center text-gray-500">
                    <div class="flex flex-col items-center">
                        <span class="material-icons text-gray-400 text-4xl mb-2">history</span>
                        <p class="text-sm text-gray-500">No activity logs available</p>
                        <p class="text-xs text-gray-400">Activity logs will appear here as users perform actions</p>
                    </div>
                </td>
            </tr>
        `;

        // Enhanced empty state for mobile
        mobileContainer.innerHTML = `
            <div class="text-center py-8">
                <span class="material-icons text-gray-400 text-4xl mb-2">history</span>
                <p class="text-sm text-gray-500">No activity logs available</p>
                <p class="text-xs text-gray-400">Activity logs will appear here as users perform actions</p>
            </div>
        `;
        return;
    }

    // Desktop table
    let tableHtml = '';
    paginatedLogs.forEach(log => {
        const levelBadge = getLevelBadge(log.level);
        tableHtml += `
            <tr class="border-b border-gray-200 hover:bg-gray-50">
                <td class="py-3 px-4 text-xs">${log.datetime}</td>
                <td class="py-3 px-4 text-xs">${log.user}</td>
                <td class="py-3 px-4 text-xs">${log.action}</td>
                <td class="py-3 px-4 text-xs">${log.description}</td>
                <td class="py-3 px-4 text-xs">${levelBadge}</td>
                <td class="py-3 px-4 text-xs text-center">${log.ip_address}</td>
            </tr>
        `;
    });
    tableBody.innerHTML = tableHtml;

    // Mobile cards
    let mobileHtml = '';
    paginatedLogs.forEach(log => {
        const levelBadge = getLevelBadge(log.level);
        mobileHtml += `
            <div class="bg-gray-50 rounded border p-4">
                <div class="flex justify-between items-start mb-2">
                    <div>
                        <h3 class="font-medium text-sm">${log.action}</h3>
                        <p class="text-xs text-gray-600">${log.user}</p>
                    </div>
                    ${levelBadge}
                </div>
                <div class="space-y-1 text-xs">
                    <div><strong>Description:</strong> ${log.description}</div>
                    <div><strong>Date/Time:</strong> ${log.datetime}</div>
                    <div><strong>IP Address:</strong> ${log.ip_address}</div>
                </div>
            </div>
        `;
    });
    mobileContainer.innerHTML = mobileHtml;
}

function getLevelBadge(level) {
    switch (level) {
        case 'info':
            return '<span class="inline-block bg-blue-100 text-blue-800 px-2 py-1 rounded-full text-xs">Info</span>';
        case 'warning':
            return '<span class="inline-block bg-yellow-100 text-yellow-800 px-2 py-1 rounded-full text-xs">Warning</span>';
        case 'error':
            return '<span class="inline-block bg-red-100 text-red-800 px-2 py-1 rounded-full text-xs">Error</span>';
        default:
            return '<span class="inline-block bg-gray-100 text-gray-800 px-2 py-1 rounded-full text-xs">Unknown</span>';
    }
}

// Pagination Functions
function updatePagination() {
    const totalItems = filteredLogs.length;
    const totalPages = Math.ceil(totalItems / perPage);
    const startItem = totalItems === 0 ? 0 : (currentPage - 1) * perPage + 1;
    const endItem = Math.min(currentPage * perPage, totalItems);

    // Update page info
    document.getElementById('pageInfo').textContent = `Showing ${startItem} to ${endItem} of ${totalItems} records`;

    // Update pagination buttons
    const prevBtn = document.getElementById('prevBtn');
    const nextBtn = document.getElementById('nextBtn');
    const prevSingleBtn = document.getElementById('prevSingleBtn');
    const nextSingleBtn = document.getElementById('nextSingleBtn');

    prevBtn.disabled = currentPage === 1;
    prevSingleBtn.disabled = currentPage === 1;
    nextBtn.disabled = currentPage === totalPages || totalPages === 0;
    nextSingleBtn.disabled = currentPage === totalPages || totalPages === 0;

    // Update page numbers with ellipsis format: 1 2 ... 18 19
    const pageNumbers = document.getElementById('pageNumbers');
    let pageHtml = '';

    if (totalPages <= 7) {
        // Show all pages if 7 or less
        for (let i = 1; i <= totalPages; i++) {
            pageHtml += createPageButton(i);
        }
    } else {
        // Always show first 2 pages
        pageHtml += createPageButton(1);
        pageHtml += createPageButton(2);

        if (currentPage > 4) {
            pageHtml += '<span class="w-8 h-8 flex items-center justify-center text-xs text-gray-400">...</span>';
        }

        // Show pages around current page
        const startMiddle = Math.max(3, currentPage - 1);
        const endMiddle = Math.min(totalPages - 2, currentPage + 1);

        for (let i = startMiddle; i <= endMiddle; i++) {
            if (i > 2 && i < totalPages - 1) {
                pageHtml += createPageButton(i);
            }
        }

        if (currentPage < totalPages - 3) {
            pageHtml += '<span class="w-8 h-8 flex items-center justify-center text-xs text-gray-400">...</span>';
        }

        // Always show last 2 pages
        pageHtml += createPageButton(totalPages - 1);
        pageHtml += createPageButton(totalPages);
    }

    pageNumbers.innerHTML = pageHtml;
}

function createPageButton(page) {
    const isActive = page === currentPage;
    return `
        <button onclick="goToPage(${page})"
                style="border-radius: 50% !important;"
                class="w-8 h-8 flex items-center justify-center text-xs transition-colors ${isActive ? 'bg-blue-500 text-white' : 'text-gray-600 hover:text-blue-600 hover:bg-blue-50'}">
            ${page}
        </button>
    `;
}

function goToPage(page) {
    currentPage = page;
    displayLogs();
    updatePagination();
}

function previousPage() {
    if (currentPage > 1) {
        currentPage--;
        displayLogs();
        updatePagination();
    }
}

function nextPage() {
    const totalPages = Math.ceil(filteredLogs.length / perPage);
    if (currentPage < totalPages) {
        currentPage++;
        displayLogs();
        updatePagination();
    }
}

function firstPage() {
    currentPage = 1;
    displayLogs();
    updatePagination();
}

function lastPage() {
    const totalPages = Math.ceil(filteredLogs.length / perPage);
    currentPage = totalPages;
    displayLogs();
    updatePagination();
}

function changePerPage() {
    // Get value from either desktop or mobile
    const newPerPage = parseInt(document.getElementById('perPage')?.value ||
                               document.getElementById('perPageMobile')?.value || 25);

    // Sync values between desktop and mobile
    if (document.getElementById('perPage')) document.getElementById('perPage').value = newPerPage;
    if (document.getElementById('perPageMobile')) document.getElementById('perPageMobile').value = newPerPage;

    perPage = newPerPage;
    currentPage = 1;
    displayLogs();
    updatePagination();
}

// Filter Functions
function filterLogs() {
    // Get values from both desktop and mobile inputs
    const searchTerm = (document.getElementById('searchFilter')?.value ||
                       document.getElementById('searchFilterMobile')?.value || '').toLowerCase();
    const eventFilter = (document.getElementById('eventFilter')?.value ||
                        document.getElementById('eventFilterMobile')?.value || '').toLowerCase();

    // Sync values between desktop and mobile
    if (document.getElementById('searchFilter')) document.getElementById('searchFilter').value = searchTerm;
    if (document.getElementById('searchFilterMobile')) document.getElementById('searchFilterMobile').value = searchTerm;
    if (document.getElementById('eventFilter')) document.getElementById('eventFilter').value = eventFilter;
    if (document.getElementById('eventFilterMobile')) document.getElementById('eventFilterMobile').value = eventFilter;

    filteredLogs = activityLogs.filter(log => {
        // Search filter
        const matchesSearch = searchTerm === '' ||
            log.description.toLowerCase().includes(searchTerm) ||
            log.user.toLowerCase().includes(searchTerm) ||
            log.action.toLowerCase().includes(searchTerm) ||
            log.ip_address.toLowerCase().includes(searchTerm);

        // Event filter
        const matchesEvent = eventFilter === '' ||
            log.description.toLowerCase().includes(eventFilter) ||
            log.action.toLowerCase().includes(eventFilter);

        return matchesSearch && matchesEvent;
    });

    currentPage = 1;
    displayLogs();
    updatePagination();
}

function resetFilters() {
    // Reset both desktop and mobile inputs
    if (document.getElementById('searchFilter')) document.getElementById('searchFilter').value = '';
    if (document.getElementById('searchFilterMobile')) document.getElementById('searchFilterMobile').value = '';
    if (document.getElementById('eventFilter')) document.getElementById('eventFilter').value = '';
    if (document.getElementById('eventFilterMobile')) document.getElementById('eventFilterMobile').value = '';

    filteredLogs = activityLogs;
    currentPage = 1;
    displayLogs();
    updatePagination();
}

function exportLogs() {
    // Create CSV content
    const headers = ['Date/Time', 'User', 'Action', 'Description', 'Level', 'IP Address'];
    const csvContent = [
        headers.join(','),
        ...filteredLogs.map(log => [
            `"${log.datetime}"`,
            `"${log.user}"`,
            `"${log.action}"`,
            `"${log.description}"`,
            `"${log.level}"`,
            `"${log.ip_address}"`
        ].join(','))
    ].join('\n');

    // Download CSV
    const blob = new Blob([csvContent], { type: 'text/csv' });
    const url = window.URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url;
    a.download = `activity_logs_${new Date().toISOString().split('T')[0]}.csv`;
    document.body.appendChild(a);
    a.click();
    document.body.removeChild(a);
    window.URL.revokeObjectURL(url);
}

function clearAllLogs() {
    if (confirm('Are you sure you want to clear all activity logs? This action cannot be undone.')) {
        fetch('/settings/log/clear', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showNotification('All activity logs have been cleared successfully', 'success');
                loadActivityLogs();
            } else {
                showNotification('Failed to clear logs: ' + data.message, 'error');
            }
        })
        .catch(error => {
            showNotification('Error clearing logs: ' + error.message, 'error');
        });
    }
}

function clearAllLogs() {
    if (confirm('Are you sure you want to clear all activity logs? This action cannot be undone.')) {
        fetch('{{ route("settings.log.clear") }}', {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                loadActivityLogs(); // Reload logs
                showSuccess(data.message);
            } else {
                showError(data.message);
            }
        })
        .catch(error => {
            console.error('Error clearing logs:', error);
            showError('Failed to clear logs');
        });
    }
}

function showSuccess(message) {
    // Create success notification
    const notification = document.createElement('div');
    notification.className = 'fixed top-4 right-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded z-50';
    notification.textContent = message;
    document.body.appendChild(notification);

    setTimeout(() => {
        notification.remove();
    }, 3000);
}

function showError(message) {
    // Create error notification
    const notification = document.createElement('div');
    notification.className = 'fixed top-4 right-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded z-50';
    notification.textContent = message;
    document.body.appendChild(notification);

    setTimeout(() => {
        notification.remove();
    }, 5000);
}

// Refresh function
function refreshLogs() {
    const refreshIcon = document.getElementById('refreshIcon');
    refreshIcon.style.animation = 'spin 1s linear infinite';

    loadActivityLogs().then(() => {
        refreshIcon.style.animation = '';
    });
}

// Add CSS for spin animation
const style = document.createElement('style');
style.textContent = `
    @keyframes spin {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }
`;
document.head.appendChild(style);
</script>
@endsection
