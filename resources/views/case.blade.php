@extends('layouts.app')

@section('breadcrumb')
    Case
@endsection

@section('content')
<div class="px-4 md:px-6 pt-4 md:pt-6 pb-6 max-w-7xl mx-auto">
    <div class="bg-white rounded shadow-md border border-gray-300">
        <div class="p-4 md:p-6 border-b border-gray-200">
            <div class="flex flex-col md:flex-row md:justify-between md:items-start space-y-4 md:space-y-0">
                <div>
                    <div class="flex items-center">
                        <span class="material-icons mr-2 text-purple-600">folder</span>
                        <h1 class="text-lg md:text-xl font-bold text-gray-800">Case Management</h1>
                    </div>
                    <p class="text-xs text-gray-500 mt-1 ml-8">Manage all court cases, clients, and related documents.</p>
                </div>
                
                <!-- Action Buttons -->
                <div class="flex space-x-2">
                    <a href="{{ route('case.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 md:px-3 md:py-1 rounded-md text-sm md:text-xs font-medium flex items-center justify-center md:justify-start w-full md:w-auto">
                        <span class="material-icons text-sm md:text-xs mr-1">add</span>
                        Add Case
                    </a>
                    <a href="{{ route('file-management.index') }}" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 md:px-3 md:py-1 rounded-md text-sm md:text-xs font-medium flex items-center justify-center md:justify-start w-full md:w-auto">
                        <span class="material-icons text-sm md:text-xs mr-1">folder</span>
                        File Management
                    </a>
                </div>
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
                    <input type="text" id="searchFilter" placeholder="Search cases..."
                           onkeyup="filterCases()"
                           class="border border-gray-300 rounded px-3 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white w-64">

                    <select id="statusFilter" onchange="filterCases()" class="custom-select border border-gray-300 rounded pl-4 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white">
                        <option value="">All Status</option>
                        @foreach($caseStatuses as $status)
                            <option value="{{ $status->name }}">{{ $status->name }}</option>
                        @endforeach
                    </select>

                    <button onclick="filterCases()" class="px-3 py-2 bg-blue-500 text-white rounded text-xs hover:bg-blue-600 transition-colors">
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
                            <th class="py-3 px-4 text-left rounded-tl">Case Number</th>
                            <th class="py-3 px-4 text-left">Case Title</th>
                            <th class="py-3 px-4 text-left">Court Location</th>
                            <th class="py-3 px-4 text-left">Parties</th>
                            <th class="py-3 px-4 text-left">Partners</th>
                            <th class="py-3 px-4 text-left">Created Date</th>
                            <th class="py-3 px-4 text-left">Status</th>
                            <th class="py-3 px-4 text-center rounded-tr">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($cases as $case)
                        <tr class="hover:bg-gray-50" data-case-id="{{ $case->id }}">
                            <td class="py-1 px-4 text-[11px] font-medium">{{ $case->case_number }}</td>
                            <td class="py-1 px-4 text-[11px]">{{ $case->title }}</td>
                            <td class="py-1 px-4 text-[11px]">{{ $case->court_location ?? 'N/A' }}</td>
                            <td class="py-1 px-4 text-[11px]">
                                @if($case->parties->count() > 0)
                                    @php
                                        $applicants = $case->parties->where('party_type', 'plaintiff');
                                        $respondents = $case->parties->where('party_type', 'defendant');
                                    @endphp
                                    <div class="text-[10px]">
                                        @if($applicants->count() > 0)
                                            <div class="mb-1">
                                                <span class="font-medium text-green-700">Applicants:</span><br>
                                                @foreach($applicants->take(2) as $applicant)
                                                    {{ $applicant->name }}<br>
                                                @endforeach
                                                @if($applicants->count() > 2)
                                                    <span class="text-gray-500">+{{ $applicants->count() - 2 }} more</span>
                                                @endif
                                            </div>
                                        @endif
                                        @if($respondents->count() > 0)
                                            <div>
                                                <span class="font-medium text-red-700">Respondents:</span><br>
                                                @foreach($respondents->take(2) as $respondent)
                                                    {{ $respondent->name }}<br>
                                                @endforeach
                                                @if($respondents->count() > 2)
                                                    <span class="text-gray-500">+{{ $respondents->count() - 2 }} more</span>
                                                @endif
                                            </div>
                                        @endif
                                    </div>
                                @else
                                    <span class="text-gray-400">No parties</span>
                                @endif
                            </td>
                            <td class="py-1 px-4 text-[11px]">
                                @if($case->partners->count() > 0)
                                    @foreach($case->partners->take(2) as $casePartner)
                                        <div class="mb-1">
                                            <span class="font-medium">{{ $casePartner->partner->firm_name ?? 'N/A' }}</span><br>
                                            <span class="text-gray-500 text-[9px]">{{ $casePartner->partner->incharge_name ?? 'N/A' }}</span>
                                        </div>
                                    @endforeach
                                    @if($case->partners->count() > 2)
                                        <span class="text-gray-500 text-[9px]">+{{ $case->partners->count() - 2 }} more</span>
                                    @endif
                                @else
                                    <span class="text-gray-400">No partners</span>
                                @endif
                            </td>
                            <td class="py-1 px-4 text-[11px]">{{ $case->created_at->format('d M Y') }}</td>
                            <td class="py-1 px-4 text-[11px]">
                                @if($case->caseStatus)
                                    <span class="inline-block bg-green-100 text-green-800 px-1.5 py-0.5 rounded-full text-[10px]">
                                        {{ $case->caseStatus->name }}
                                    </span>
                                @else
                                    <span class="inline-block bg-gray-100 text-gray-800 px-1.5 py-0.5 rounded-full text-[10px]">
                                        Unknown
                                    </span>
                                @endif
                            </td>
                            <td class="py-1 px-4">
                                <div class="flex justify-center space-x-1 items-center" x-data="{ open: false }">
                                    <button @click="open = !open" class="p-0.5 text-purple-600 hover:text-purple-700" title="Change Status">
                                        <span class="material-icons text-base">add</span>
                                    </button>
                                                        <a href="{{ route('case.show', $case->id) }}" class="p-1 text-blue-600 hover:text-blue-700" title="View">
                        <span class="material-icons text-sm">visibility</span>
                    </a>
                                    <a href="{{ route('case.edit', $case->id) }}" class="p-0.5 text-yellow-600 hover:text-yellow-700" title="Edit">
                                        <span class="material-icons text-base">edit</span>
                                    </a>
                                    <button class="p-0.5 text-red-600 hover:text-red-700 delete-case-btn" data-case-id="{{ $case->id }}" title="Delete">
                                        <span class="material-icons text-base">delete</span>
                                    </button>
                                    <div x-show="open" @click.away="open = false" class="absolute z-50 mt-2 w-64 bg-white rounded shadow-xl border border-gray-200 p-3 text-[11px]">
                                        <div class="mb-2 font-bold text-gray-800">Change Status</div>
                                        <ul class="space-y-1">
                                            @foreach($caseStatuses as $status)
                                                <li>
                                                    <button class="w-full text-left px-2 py-1 hover:bg-gray-100 rounded change-status-btn" 
                                                            data-case-id="{{ $case->id }}" 
                                                            data-status-id="{{ $status->id }}"
                                                            data-status-name="{{ $status->name }}">
                                                        {{ $status->name }}
                                                    </button>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="py-8 px-4 text-center text-gray-500">
                                <div class="flex flex-col items-center">
                                    <span class="material-icons text-gray-400 text-4xl mb-2">folder_open</span>
                                    <p class="text-sm">No cases found</p>
                                    <p class="text-xs text-gray-400">Create your first case to get started</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pagination Section -->
        <div class="p-6">
            <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4">
                <!-- Left: Page Info -->
                <div class="text-xs text-gray-600">
                    <span id="pageInfo">Showing 1 to 25 of 100 records</span>
                </div>

                <!-- Right: Pagination -->
                <div class="flex items-center gap-1">
                    <button id="prevBtn" onclick="firstPage()"
                            class="px-2 py-1 text-xs text-gray-600 hover:text-blue-600 disabled:opacity-50 disabled:cursor-not-allowed transition-colors">
                        &lt;&lt;
                    </button>

                    <button id="prevSingleBtn" onclick="previousPage()"
                            class="px-2 py-1 text-xs text-gray-600 hover:text-blue-600 disabled:opacity-50 disabled:cursor-not-allowed transition-colors">
                        &lt;
                    </button>

                    <div id="pageNumbers" class="flex items-center gap-1 mx-2">
                        <!-- Page numbers will be populated here -->
                    </div>

                    <button id="nextSingleBtn" onclick="nextPage()"
                            class="px-2 py-1 text-xs text-gray-600 hover:text-blue-600 disabled:opacity-50 disabled:cursor-not-allowed transition-colors">
                        &gt;
                    </button>

                    <button id="nextBtn" onclick="lastPage()"
                            class="px-2 py-1 text-xs text-gray-600 hover:text-blue-600 disabled:opacity-50 disabled:cursor-not-allowed transition-colors">
                        &gt;&gt;
                    </button>
                </div>
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
                    <input type="text" id="searchFilterMobile" placeholder="Search cases..."
                           onkeyup="filterCases()"
                           class="w-full border border-gray-300 rounded px-3 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white">

                    <div class="flex gap-2">
                        <select id="statusFilterMobile" onchange="filterCases()" class="custom-select flex-1 border border-gray-300 rounded pl-4 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white">
                            <option value="">All Status</option>
                            @foreach($caseStatuses as $status)
                                <option value="{{ $status->name }}">{{ $status->name }}</option>
                            @endforeach
                        </select>

                        <button onclick="filterCases()" class="px-3 py-2 bg-blue-500 text-white rounded text-xs hover:bg-blue-600 transition-colors">
                            🔍
                        </button>

                        <button onclick="resetFilters()" class="px-3 py-2 bg-gray-500 text-white rounded text-xs hover:bg-gray-600 transition-colors">
                            🔄
                        </button>
                    </div>
                </div>
            </div>

            <div class="space-y-4" id="cases-mobile-container">
            @forelse($cases as $case)
            <div class="bg-gray-50 rounded-lg border border-gray-200 p-4" data-case-card-id="{{ $case->id }}" x-data="{ showStatusMenu: false }">
                <div class="flex justify-between items-start mb-3">
                    <div>
                        <h3 class="font-semibold text-gray-800 text-sm">{{ $case->case_number }}</h3>
                        <p class="text-xs text-gray-600">{{ $case->title }}</p>
                    </div>
                    @if($case->caseStatus)
                        <span class="inline-block bg-green-100 text-green-800 px-2 py-1 rounded-full text-xs">{{ $case->caseStatus->name }}</span>
                    @else
                        <span class="inline-block bg-gray-100 text-gray-800 px-2 py-1 rounded-full text-xs">Unknown</span>
                    @endif
                </div>
                
                <div class="space-y-2 mb-4">
                    <div class="flex justify-between">
                        <span class="text-xs text-gray-500">Parties:</span>
                        <span class="text-xs font-medium">
                            @if($case->parties->count() > 0)
                                {{ $case->parties->count() }} parties
                            @else
                                No parties
                            @endif
                        </span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-xs text-gray-500">Partners:</span>
                        <span class="text-xs font-medium">
                            @if($case->partners->count() > 0)
                                {{ $case->partners->count() }} partners
                            @else
                                No partners
                            @endif
                        </span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-xs text-gray-500">Court:</span>
                        <span class="text-xs font-medium">{{ $case->court_location ?? 'N/A' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-xs text-gray-500">Created:</span>
                        <span class="text-xs font-medium">{{ $case->created_at->format('d M Y') }}</span>
                    </div>
                </div>
                
                <!-- Mobile Action Buttons -->
                <div class="flex space-x-2">
                    <button @click="showStatusMenu = !showStatusMenu" class="flex-1 bg-white border border-gray-300 text-gray-700 py-2 px-3 rounded-md text-xs font-medium flex items-center justify-center hover:bg-gray-50">
                        <span class="material-icons text-sm mr-1">add</span>
                        Status
                    </button>
                    <a href="{{ route('case.show', $case->id) }}" class="flex-1 bg-blue-100 text-blue-700 py-2 px-3 rounded-md text-xs font-medium flex items-center justify-center">
                        <span class="material-icons text-sm mr-1">visibility</span>
                        View
                    </a>
                    <a href="{{ route('case.edit', $case->id) }}" class="flex-1 bg-yellow-100 text-yellow-700 py-2 px-3 rounded-md text-xs font-medium flex items-center justify-center">
                        <span class="material-icons text-sm mr-1">edit</span>
                        Edit
                    </a>
                    <button class="flex-1 bg-red-100 text-red-700 py-2 px-3 rounded-md text-xs font-medium flex items-center justify-center delete-case-btn" data-case-id="{{ $case->id }}">
                        <span class="material-icons text-sm mr-1">delete</span>
                        Delete
                    </button>
                </div>
                
                <!-- Mobile Status Dropdown -->
                <div x-show="showStatusMenu" @click.away="showStatusMenu = false" class="mt-3 bg-white rounded-md shadow-lg border border-gray-200 p-3">
                    <div class="mb-2 font-bold text-gray-800 text-xs">Change Status</div>
                    <div class="grid grid-cols-2 gap-2">
                        @foreach($caseStatuses as $status)
                            <button class="w-full text-left px-3 py-2 text-xs hover:bg-gray-100 rounded change-status-btn" 
                                    data-case-id="{{ $case->id }}" 
                                    data-status-id="{{ $status->id }}"
                                    data-status-name="{{ $status->name }}">
                                {{ $status->name }}
                            </button>
                        @endforeach
                    </div>
                </div>
            </div>
            @empty
            <div class="bg-gray-50 rounded-lg border border-gray-200 p-8 text-center">
                <span class="material-icons text-gray-400 text-4xl mb-2">folder_open</span>
                <p class="text-sm text-gray-600">No cases found</p>
                <p class="text-xs text-gray-400">Create your first case to get started</p>
            </div>
            @endforelse
            </div>
        </div>
    </div>
</div>

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

<script>
// Pagination variables
let currentPage = 1;
let perPage = 25;
let allCases = [];
let filteredCases = [];

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    // Get all cases data from the page
    const caseRows = document.querySelectorAll('tbody tr[data-case-id]');
    allCases = Array.from(caseRows).map(row => ({
        id: row.dataset.caseId,
        element: row,
        searchText: row.textContent.toLowerCase()
    }));

    // Get mobile cards
    const mobileCards = document.querySelectorAll('[data-case-card-id]');
    allCases.forEach((caseItem, index) => {
        if (mobileCards[index]) {
            caseItem.mobileElement = mobileCards[index];
        }
    });

    filteredCases = [...allCases];
    displayCases();
    updatePagination();

    // Handle status change buttons
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('change-status-btn')) {
            const caseId = e.target.dataset.caseId;
            const statusId = e.target.dataset.statusId;
            const statusName = e.target.dataset.statusName;

            if (confirm('Are you sure you want to change the status to "' + statusName + '"?')) {
                // Add your status change logic here
                console.log('Changing case', caseId, 'to status', statusId);
            }
        }
    });
});

// Display functions
function displayCases() {
    const startIndex = (currentPage - 1) * perPage;
    const endIndex = startIndex + perPage;

    // Hide all cases first
    allCases.forEach(caseItem => {
        if (caseItem.element) caseItem.element.style.display = 'none';
        if (caseItem.mobileElement) caseItem.mobileElement.style.display = 'none';
    });

    // Show filtered cases for current page
    const casesToShow = filteredCases.slice(startIndex, endIndex);
    casesToShow.forEach(caseItem => {
        if (caseItem.element) caseItem.element.style.display = '';
        if (caseItem.mobileElement) caseItem.mobileElement.style.display = '';
    });
}

function updatePagination() {
    const totalItems = filteredCases.length;
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

    // Update page numbers
    updatePageNumbers(totalPages);
}

function updatePageNumbers(totalPages) {
    const pageNumbersContainer = document.getElementById('pageNumbers');
    pageNumbersContainer.innerHTML = '';

    if (totalPages <= 1) return;

    const maxVisiblePages = 5;
    let startPage = Math.max(1, currentPage - Math.floor(maxVisiblePages / 2));
    let endPage = Math.min(totalPages, startPage + maxVisiblePages - 1);

    if (endPage - startPage + 1 < maxVisiblePages) {
        startPage = Math.max(1, endPage - maxVisiblePages + 1);
    }

    let pageHtml = '';
    for (let i = startPage; i <= endPage; i++) {
        const isActive = i === currentPage;
        pageHtml += `
            <button onclick="goToPage(${i})"
                    class="w-8 h-8 flex items-center justify-center text-xs transition-colors ${isActive ? 'bg-blue-500 text-white rounded-full' : 'text-gray-600 hover:text-blue-600 hover:bg-blue-50 rounded-full'}">
                ${i}
            </button>
        `;
    }
    pageNumbersContainer.innerHTML = pageHtml;
}

// Pagination functions
function changePerPage() {
    const newPerPage = parseInt(document.getElementById('perPage')?.value ||
                               document.getElementById('perPageMobile')?.value || 25);

    if (document.getElementById('perPage')) document.getElementById('perPage').value = newPerPage;
    if (document.getElementById('perPageMobile')) document.getElementById('perPageMobile').value = newPerPage;

    perPage = newPerPage;
    currentPage = 1;
    displayCases();
    updatePagination();
}

// Filter Functions
function filterCases() {
    const searchTerm = (document.getElementById('searchFilter')?.value ||
                       document.getElementById('searchFilterMobile')?.value || '').toLowerCase();
    const statusFilter = (document.getElementById('statusFilter')?.value ||
                        document.getElementById('statusFilterMobile')?.value || '');

    // Sync values between desktop and mobile
    if (document.getElementById('searchFilter')) document.getElementById('searchFilter').value = searchTerm;
    if (document.getElementById('searchFilterMobile')) document.getElementById('searchFilterMobile').value = searchTerm;
    if (document.getElementById('statusFilter')) document.getElementById('statusFilter').value = statusFilter;
    if (document.getElementById('statusFilterMobile')) document.getElementById('statusFilterMobile').value = statusFilter;

    filteredCases = allCases.filter(caseItem => {
        const matchesSearch = searchTerm === '' || caseItem.searchText.includes(searchTerm);
        const matchesStatus = statusFilter === '' || caseItem.searchText.includes(statusFilter.toLowerCase());

        return matchesSearch && matchesStatus;
    });

    currentPage = 1;
    displayCases();
    updatePagination();
}

function resetFilters() {
    if (document.getElementById('searchFilter')) document.getElementById('searchFilter').value = '';
    if (document.getElementById('searchFilterMobile')) document.getElementById('searchFilterMobile').value = '';
    if (document.getElementById('statusFilter')) document.getElementById('statusFilter').value = '';
    if (document.getElementById('statusFilterMobile')) document.getElementById('statusFilterMobile').value = '';

    filteredCases = [...allCases];
    currentPage = 1;
    displayCases();
    updatePagination();
}

function previousPage() {
    if (currentPage > 1) {
        currentPage--;
        displayCases();
        updatePagination();
    }
}

function nextPage() {
    const totalPages = Math.ceil(filteredCases.length / perPage);
    if (currentPage < totalPages) {
        currentPage++;
        displayCases();
        updatePagination();
    }
}

function firstPage() {
    currentPage = 1;
    displayCases();
    updatePagination();
}

function lastPage() {
    const totalPages = Math.ceil(filteredCases.length / perPage);
    currentPage = totalPages;
    displayCases();
    updatePagination();
}

function goToPage(page) {
    currentPage = page;
    displayCases();
    updatePagination();
}

// Handle status change buttons (already in DOMContentLoaded above)
document.addEventListener('DOMContentLoaded', function() {
    // Handle status change buttons
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('change-status-btn')) {
            const caseId = e.target.dataset.caseId;
            const statusId = e.target.dataset.statusId;
            const statusName = e.target.dataset.statusName;
            
            if (confirm('Are you sure you want to change the status to "' + statusName + '"?')) {
                changeCaseStatus(caseId, statusId, statusName);
            }
        }
        if (e.target.closest('.delete-case-btn')) {
            const btn = e.target.closest('.delete-case-btn');
            const caseId = btn.dataset.caseId;
            if (confirm('Delete this case? This action cannot be undone.')) {
                deleteCase(caseId, btn);
            }
        }
    });
    
    function changeCaseStatus(caseId, statusId, statusName) {
        fetch(`/case/${caseId}/change-status`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                status_id: statusId
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Status changed successfully to: ' + statusName);
                location.reload(); // Reload page to show updated status
            } else {
                alert('Failed to change status: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Failed to change status. Please try again.');
        });
    }

    function deleteCase(caseId, triggerEl) {
        fetch(`/case/${caseId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                // Remove desktop row
                const row = triggerEl.closest('tr');
                if (row) {
                    row.remove();
                }
                // Remove mobile card
                const card = triggerEl.closest('[data-case-card-id]');
                if (card) {
                    card.remove();
                }
            } else {
                alert('Failed to delete case: ' + (data.message || 'Unknown error'));
            }
        })
        .catch(err => {
            console.error(err);
            alert('Failed to delete case.');
        });
    }
});
</script>
@endsection 