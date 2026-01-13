@extends('layouts.app')

@section('breadcrumb')
    Pre-Quotation
@endsection

@section('content')
<div class="px-4 md:px-6 pt-4 md:pt-6 pb-6 max-w-7xl mx-auto">
    <div class="bg-white rounded shadow-md border border-gray-300">
        <div class="p-4 md:p-6 border-b border-gray-200">
            <div class="flex flex-col md:flex-row md:justify-between md:items-start space-y-4 md:space-y-0">
                <div>
                    <div class="flex items-center">
                        <span class="material-icons mr-2 text-blue-600">description</span>
                        <h1 class="text-lg md:text-xl font-bold text-gray-800">Pre-Quotation Management</h1>
                    </div>
                    <p class="text-xs text-gray-500 mt-1 ml-8">Manage all pre-quotations before converting to quotations.</p>
                </div>
                
                <!-- Add Pre-Quotation Button -->
                <a href="{{ route('pre-quotation.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 md:px-3 md:py-1 rounded-md text-sm md:text-xs font-medium flex items-center justify-center md:justify-start w-full md:w-auto">
                    <span class="material-icons text-sm md:text-xs mr-1">add</span>
                    Add Pre-Quotation
                </a>
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
                    <input type="text" id="searchFilter" placeholder="Search pre-quotations..."
                           onkeyup="filterPreQuotations()"
                           class="border border-gray-300 rounded px-3 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white w-64">

                    <select id="statusFilter" onchange="filterPreQuotations()" class="custom-select border border-gray-300 rounded pl-4 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white">
                        <option value="">All Status</option>
                        <option value="Draft">Draft</option>
                        <option value="Sent">Sent</option>
                        <option value="Approved">Approved</option>
                        <option value="Rejected">Rejected</option>
                    </select>

                    <button onclick="filterPreQuotations()" class="px-3 py-2 bg-blue-500 text-white rounded text-xs hover:bg-blue-600 transition-colors">
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
                            <th class="py-3 px-4 text-left rounded-tl">Pre-Quotation No</th>
                            <th class="py-3 px-4 text-left">Full Name</th>
                            <th class="py-3 px-4 text-left">Phone</th>
                            <th class="py-3 px-4 text-left">Amount (RM)</th>
                            <th class="py-3 px-4 text-left">Issue Date</th>
                            <th class="py-3 px-4 text-left">Valid Until</th>
                            <th class="py-3 px-4 text-left">Status</th>
                            <th class="py-3 px-4 text-center rounded-tr">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($preQuotations as $pq)
                        <tr class="hover:bg-gray-50">
                            <td class="py-1 px-4 text-[11px] font-medium">{{ $pq->quotation_no }}</td>
                            <td class="py-1 px-4 text-[11px]">{{ $pq->full_name ?? 'N/A' }}</td>
                            <td class="py-1 px-4 text-[11px]">{{ $pq->customer_phone ?? 'N/A' }}</td>
                            <td class="py-1 px-4 text-[11px] font-medium">RM {{ number_format($pq->total, 2) }}</td>
                            <td class="py-1 px-4 text-[11px]">{{ $pq->quotation_date->format('d/m/Y') }}</td>
                            <td class="py-1 px-4 text-[11px]">{{ $pq->valid_until ? $pq->valid_until->format('d/m/Y') : 'N/A' }}</td>
                            <td class="py-1 px-4">
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-[10px] font-medium {{ $pq->status_color }}">
                                    <span class="material-icons text-[10px] mr-1">{{ $pq->status_icon }}</span>
                                    {{ $pq->status_display }}
                                </span>
                            </td>
                            <td class="py-1 px-4 text-center">
                                <div class="flex items-center justify-center space-x-1" x-data="{ open: false }">
                                    <button @click="open = !open" class="p-0.5 text-purple-600 hover:text-purple-700" title="Change Status">
                                        <span class="material-icons text-base">add</span>
                                    </button>
                                    <a href="{{ route('pre-quotation.show', $pq->id) }}" class="text-blue-600 hover:text-blue-800" title="View">
                                        <span class="material-icons text-sm">visibility</span>
                                    </a>
                                    <a href="{{ route('pre-quotation.edit', $pq->id) }}" class="text-green-600 hover:text-green-800" title="Edit">
                                        <span class="material-icons text-sm">edit</span>
                                    </a>
                                    <form action="{{ route('pre-quotation.destroy', $pq->id) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this pre-quotation?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-800" title="Delete">
                                            <span class="material-icons text-sm">delete</span>
                                        </button>
                                    </form>
                                    <div x-show="open" @click.away="open = false" class="absolute z-50 mt-2 w-48 bg-white rounded shadow-xl border border-gray-200 p-3 text-[11px]">
                                        <div class="mb-2 font-bold text-gray-800">Change Status</div>
                                        <ul class="space-y-1">
                                            <li>
                                                <button class="w-full text-left px-2 py-1 hover:bg-gray-100 rounded change-prequotation-status-btn"
                                                        data-prequotation-id="{{ $pq->id }}"
                                                        data-status="pending">
                                                    Pending
                                                </button>
                                            </li>
                                            <li>
                                                <button class="w-full text-left px-2 py-1 hover:bg-gray-100 rounded change-prequotation-status-btn"
                                                        data-prequotation-id="{{ $pq->id }}"
                                                        data-status="accepted">
                                                    Accepted
                                                </button>
                                            </li>
                                            <li>
                                                <button class="w-full text-left px-2 py-1 hover:bg-gray-100 rounded change-prequotation-status-btn"
                                                        data-prequotation-id="{{ $pq->id }}"
                                                        data-status="rejected">
                                                    Rejected
                                                </button>
                                            </li>
                                            <li>
                                                <button class="w-full text-left px-2 py-1 hover:bg-gray-100 rounded change-prequotation-status-btn"
                                                        data-prequotation-id="{{ $pq->id }}"
                                                        data-status="converted">
                                                    Converted
                                                </button>
                                            </li>
                                            <li>
                                                <button class="w-full text-left px-2 py-1 hover:bg-gray-100 rounded change-prequotation-status-btn"
                                                        data-prequotation-id="{{ $pq->id }}"
                                                        data-status="expired">
                                                    Expired
                                                </button>
                                            </li>
                                            <li>
                                                <button class="w-full text-left px-2 py-1 hover:bg-gray-100 rounded change-prequotation-status-btn"
                                                        data-prequotation-id="{{ $pq->id }}"
                                                        data-status="cancelled">
                                                    Cancelled
                                                </button>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="py-8 text-center text-gray-500 text-sm">
                                <div class="flex flex-col items-center">
                                    <span class="material-icons text-4xl text-gray-300 mb-2">description</span>
                                    <p>No pre-quotations found.</p>
                                    <a href="{{ route('pre-quotation.create') }}" class="text-blue-600 hover:text-blue-800 text-xs mt-2">Create your first pre-quotation</a>
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

                <!-- Right on Desktop, Center on Mobile: Pagination -->
                <div class="flex items-center justify-center md:justify-end gap-1">
                    <button id="prevBtn" onclick="firstPage()"
                            style="border-radius: 50% !important;"
                            class="w-8 h-8 flex items-center justify-center text-xs text-gray-600 hover:text-blue-600 hover:bg-blue-50 disabled:opacity-50 disabled:cursor-not-allowed transition-colors">
                        &laquo;
                    </button>

                    <button id="prevSingleBtn" onclick="previousPage()"
                            style="border-radius: 50% !important;"
                            class="w-8 h-8 flex items-center justify-center text-xs text-gray-600 hover:text-blue-600 hover:bg-blue-50 disabled:opacity-50 disabled:cursor-not-allowed transition-colors">
                        &lsaquo;
                    </button>

                    <div id="pageNumbers" class="flex items-center gap-1 mx-2">
                        <!-- Page numbers will be populated here -->
                    </div>

                    <button id="nextSingleBtn" onclick="nextPage()"
                            style="border-radius: 50% !important;"
                            class="w-8 h-8 flex items-center justify-center text-xs text-gray-600 hover:text-blue-600 hover:bg-blue-50 disabled:opacity-50 disabled:cursor-not-allowed transition-colors">
                        &rsaquo;
                    </button>

                    <button id="nextBtn" onclick="lastPage()"
                            style="border-radius: 50% !important;"
                            class="w-8 h-8 flex items-center justify-center text-xs text-gray-600 hover:text-blue-600 hover:bg-blue-50 disabled:opacity-50 disabled:cursor-not-allowed transition-colors">
                        &raquo;
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
                    <input type="text" id="searchFilterMobile" placeholder="Search pre-quotations..."
                           onkeyup="filterPreQuotations()"
                           class="w-full border border-gray-300 rounded px-3 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white">

                    <div class="flex gap-2">
                        <select id="statusFilterMobile" onchange="filterPreQuotations()" class="custom-select flex-1 border border-gray-300 rounded pl-4 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white">
                            <option value="">All Status</option>
                            <option value="Draft">Draft</option>
                            <option value="Sent">Sent</option>
                            <option value="Approved">Approved</option>
                            <option value="Rejected">Rejected</option>
                        </select>

                        <button onclick="filterPreQuotations()" class="px-3 py-2 bg-blue-500 text-white rounded text-xs hover:bg-blue-600 transition-colors">
                            🔍
                        </button>

                        <button onclick="resetFilters()" class="px-3 py-2 bg-gray-500 text-white rounded text-xs hover:bg-gray-600 transition-colors">
                            🔄
                        </button>
                    </div>
                </div>
            </div>

            <div class="space-y-4" id="prequotations-mobile-container">
            @forelse($preQuotations as $pq)
            <div class="bg-gray-50 rounded border p-4" x-data="{ showStatusMenu: false }">
                <div class="flex justify-between items-start mb-2">
                    <div>
                        <h3 class="font-medium text-sm">{{ $pq->quotation_no }}</h3>
                        <p class="text-xs text-gray-600">{{ $pq->full_name ?? 'N/A' }}</p>
                    </div>
                    <span class="inline-flex items-center px-2 py-1 rounded-full text-[10px] font-medium {{ $pq->status_color }}">
                        <span class="material-icons text-[10px] mr-1">{{ $pq->status_icon }}</span>
                        {{ $pq->status_display }}
                    </span>
                </div>
                
                <div class="grid grid-cols-2 gap-2 text-xs mb-3">
                    <div>
                        <span class="text-gray-500">Phone:</span>
                        <span class="block">{{ $pq->customer_phone ?? 'N/A' }}</span>
                    </div>
                    <div>
                        <span class="text-gray-500">Amount:</span>
                        <span class="block font-medium">RM {{ number_format($pq->total, 2) }}</span>
                    </div>
                    <div>
                        <span class="text-gray-500">Issue Date:</span>
                        <span class="block">{{ $pq->quotation_date->format('d/m/Y') }}</span>
                    </div>
                    <div>
                        <span class="text-gray-500">Valid Until:</span>
                        <span class="block">{{ $pq->valid_until ? $pq->valid_until->format('d/m/Y') : 'N/A' }}</span>
                    </div>
                </div>
                
                <div class="flex space-x-2">
                    <button @click="showStatusMenu = !showStatusMenu" class="flex-1 bg-white border border-gray-300 text-gray-700 py-2 px-3 rounded-md text-xs font-medium flex items-center justify-center hover:bg-gray-50">
                        <span class="material-icons text-sm mr-1">add</span>
                        Status
                    </button>
                    <a href="{{ route('pre-quotation.show', $pq->id) }}" class="flex-1 bg-blue-600 text-white text-center py-2 rounded text-xs">
                        View
                    </a>
                    <a href="{{ route('pre-quotation.edit', $pq->id) }}" class="flex-1 bg-green-600 text-white text-center py-2 rounded text-xs">
                        Edit
                    </a>
                    <form action="{{ route('pre-quotation.destroy', $pq->id) }}" method="POST" class="flex-1" onsubmit="return confirm('Are you sure?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full bg-red-600 text-white py-2 rounded text-xs">
                            Delete
                        </button>
                    </form>
                </div>

                <!-- Mobile Status Dropdown -->
                <div x-show="showStatusMenu" @click.away="showStatusMenu = false" class="mt-3 bg-white rounded-md shadow-lg border border-gray-200 p-3">
                    <div class="mb-2 font-bold text-gray-800 text-xs">Change Status</div>
                    <div class="grid grid-cols-2 gap-2">
                        <button class="w-full text-left px-3 py-2 text-xs hover:bg-gray-100 rounded change-prequotation-status-btn"
                                data-prequotation-id="{{ $pq->id }}"
                                data-status="pending">
                            Pending
                        </button>
                        <button class="w-full text-left px-3 py-2 text-xs hover:bg-gray-100 rounded change-prequotation-status-btn"
                                data-prequotation-id="{{ $pq->id }}"
                                data-status="accepted">
                            Accepted
                        </button>
                        <button class="w-full text-left px-3 py-2 text-xs hover:bg-gray-100 rounded change-prequotation-status-btn"
                                data-prequotation-id="{{ $pq->id }}"
                                data-status="rejected">
                            Rejected
                        </button>
                        <button class="w-full text-left px-3 py-2 text-xs hover:bg-gray-100 rounded change-prequotation-status-btn"
                                data-prequotation-id="{{ $pq->id }}"
                                data-status="converted">
                            Converted
                        </button>
                        <button class="w-full text-left px-3 py-2 text-xs hover:bg-gray-100 rounded change-prequotation-status-btn"
                                data-prequotation-id="{{ $pq->id }}"
                                data-status="expired">
                            Expired
                        </button>
                        <button class="w-full text-left px-3 py-2 text-xs hover:bg-gray-100 rounded change-prequotation-status-btn"
                                data-prequotation-id="{{ $pq->id }}"
                                data-status="cancelled">
                            Cancelled
                        </button>
                    </div>
                </div>
            </div>
            @empty
            <div class="text-center py-8">
                <span class="material-icons text-4xl text-gray-300 mb-2">description</span>
                <p class="text-gray-500 text-sm">No pre-quotations found.</p>
                <a href="{{ route('pre-quotation.create') }}" class="text-blue-600 hover:text-blue-800 text-xs mt-2 block">Create your first pre-quotation</a>
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
let allPreQuotations = [];
let filteredPreQuotations = [];

// Initialize pagination
function initializePagination() {
    // Get all pre-quotation data from the page
    const preQuotationRows = document.querySelectorAll('tbody tr');
    allPreQuotations = Array.from(preQuotationRows).map((row, index) => ({
        id: index,
        element: row,
        searchText: row.textContent.toLowerCase()
    }));

    filteredPreQuotations = [...allPreQuotations];
    displayPreQuotations();
    updatePagination();
}

// Display functions
function displayPreQuotations() {
    const startIndex = (currentPage - 1) * perPage;
    const endIndex = startIndex + perPage;

    // Hide all pre-quotations first
    allPreQuotations.forEach(pq => {
        if (pq.element) pq.element.style.display = 'none';
    });

    // Show filtered pre-quotations for current page
    const pqsToShow = filteredPreQuotations.slice(startIndex, endIndex);
    pqsToShow.forEach(pq => {
        if (pq.element) pq.element.style.display = '';
    });
}

function updatePagination() {
    const totalItems = filteredPreQuotations.length;
    const totalPages = Math.ceil(totalItems / perPage);
    const startItem = totalItems === 0 ? 0 : (currentPage - 1) * perPage + 1;
    const endItem = Math.min(currentPage * perPage, totalItems);

    // Update page info
    if (document.getElementById('pageInfo')) {
        document.getElementById('pageInfo').textContent = `Showing ${startItem} to ${endItem} of ${totalItems} records`;
    }

    // Update pagination buttons
    const prevBtn = document.getElementById('prevBtn');
    const nextBtn = document.getElementById('nextBtn');
    const prevSingleBtn = document.getElementById('prevSingleBtn');
    const nextSingleBtn = document.getElementById('nextSingleBtn');

    if (prevBtn) prevBtn.disabled = currentPage === 1;
    if (prevSingleBtn) prevSingleBtn.disabled = currentPage === 1;
    if (nextBtn) nextBtn.disabled = currentPage === totalPages || totalPages === 0;
    if (nextSingleBtn) nextSingleBtn.disabled = currentPage === totalPages || totalPages === 0;

    // Update page numbers
    updatePageNumbers(totalPages);
}

function updatePageNumbers(totalPages) {
    const pageNumbersContainer = document.getElementById('pageNumbers');
    if (!pageNumbersContainer) return;
    pageNumbersContainer.innerHTML = '';

    if (totalPages <= 1) return;

    let pageHtml = '';
    
    if (totalPages <= 7) {
        for (let i = 1; i <= totalPages; i++) {
            const isActive = i === currentPage;
            pageHtml += `
                <button onclick="goToPage(${i})"
                        style="border-radius: 50% !important; width: 32px !important; height: 32px !important; min-width: 32px !important;"
                        class="flex items-center justify-center text-xs transition-colors rounded-full ${isActive ? 'bg-blue-500 text-white' : 'text-gray-600 hover:text-blue-600 hover:bg-blue-50'}">
                    ${i}
                </button>
            `;
        }
    } else {
        for (let i = 1; i <= 2; i++) {
            const isActive = i === currentPage;
            pageHtml += `
                <button onclick="goToPage(${i})"
                        style="border-radius: 50% !important; width: 32px !important; height: 32px !important; min-width: 32px !important;"
                        class="flex items-center justify-center text-xs transition-colors rounded-full ${isActive ? 'bg-blue-500 text-white' : 'text-gray-600 hover:text-blue-600 hover:bg-blue-50'}">
                    ${i}
                </button>
            `;
        }
        
        if (currentPage > 4) {
            pageHtml += '<span style="width: 32px; height: 32px; border-radius: 50%;" class="flex items-center justify-center text-xs text-gray-400">...</span>';
        }
        
        const startMiddle = Math.max(3, currentPage - 1);
        const endMiddle = Math.min(totalPages - 2, currentPage + 1);
        for (let i = startMiddle; i <= endMiddle; i++) {
            if (i > 2 && i < totalPages - 1) {
                const isActive = i === currentPage;
                pageHtml += `
                    <button onclick="goToPage(${i})"
                            style="border-radius: 50% !important; width: 32px !important; height: 32px !important; min-width: 32px !important;"
                            class="flex items-center justify-center text-xs transition-colors rounded-full ${isActive ? 'bg-blue-500 text-white' : 'text-gray-600 hover:text-blue-600 hover:bg-blue-50'}">
                        ${i}
                    </button>
                `;
            }
        }
        
        if (currentPage < totalPages - 3) {
            pageHtml += '<span style="width: 32px; height: 32px; border-radius: 50%;" class="flex items-center justify-center text-xs text-gray-400">...</span>';
        }
        
        for (let i = totalPages - 1; i <= totalPages; i++) {
            const isActive = i === currentPage;
            pageHtml += `
                <button onclick="goToPage(${i})"
                        style="border-radius: 50% !important; width: 32px !important; height: 32px !important; min-width: 32px !important;"
                        class="flex items-center justify-center text-xs transition-colors rounded-full ${isActive ? 'bg-blue-500 text-white' : 'text-gray-600 hover:text-blue-600 hover:bg-blue-50'}">
                    ${i}
                </button>
            `;
        }
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
    displayPreQuotations();
    updatePagination();
}

// Filter Functions
function filterPreQuotations() {
    const searchTerm = (document.getElementById('searchFilter')?.value ||
                       document.getElementById('searchFilterMobile')?.value || '').toLowerCase();
    const statusFilter = (document.getElementById('statusFilter')?.value ||
                        document.getElementById('statusFilterMobile')?.value || '');

    // Sync values between desktop and mobile
    if (document.getElementById('searchFilter')) document.getElementById('searchFilter').value = searchTerm;
    if (document.getElementById('searchFilterMobile')) document.getElementById('searchFilterMobile').value = searchTerm;
    if (document.getElementById('statusFilter')) document.getElementById('statusFilter').value = statusFilter;
    if (document.getElementById('statusFilterMobile')) document.getElementById('statusFilterMobile').value = statusFilter;

    filteredPreQuotations = allPreQuotations.filter(pq => {
        const matchesSearch = searchTerm === '' || pq.searchText.includes(searchTerm);
        const matchesStatus = statusFilter === '' || pq.searchText.includes(statusFilter.toLowerCase());

        return matchesSearch && matchesStatus;
    });

    currentPage = 1;
    displayPreQuotations();
    updatePagination();
}

function resetFilters() {
    if (document.getElementById('searchFilter')) document.getElementById('searchFilter').value = '';
    if (document.getElementById('searchFilterMobile')) document.getElementById('searchFilterMobile').value = '';
    if (document.getElementById('statusFilter')) document.getElementById('statusFilter').value = '';
    if (document.getElementById('statusFilterMobile')) document.getElementById('statusFilterMobile').value = '';

    filteredPreQuotations = [...allPreQuotations];
    currentPage = 1;
    displayPreQuotations();
    updatePagination();
}

function previousPage() {
    if (currentPage > 1) {
        currentPage--;
        displayPreQuotations();
        updatePagination();
    }
}

function nextPage() {
    const totalPages = Math.ceil(filteredPreQuotations.length / perPage);
    if (currentPage < totalPages) {
        currentPage++;
        displayPreQuotations();
        updatePagination();
    }
}

function firstPage() {
    currentPage = 1;
    displayPreQuotations();
    updatePagination();
}

function lastPage() {
    const totalPages = Math.ceil(filteredPreQuotations.length / perPage);
    currentPage = totalPages;
    displayPreQuotations();
    updatePagination();
}

function goToPage(page) {
    currentPage = page;
    displayPreQuotations();
    updatePagination();
}
</script>

@if(session('success'))
<script>
    document.addEventListener('DOMContentLoaded', function() {
        alert('{{ session('success') }}');
        initializePagination();
    });
</script>
@else
<script>
    document.addEventListener('DOMContentLoaded', function() {
        initializePagination();
    });
</script>
@endif

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle pre-quotation status change buttons
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('change-prequotation-status-btn')) {
            const preQuotationId = e.target.dataset.prequotationId;
            const status = e.target.dataset.status;
            const statusName = status.charAt(0).toUpperCase() + status.slice(1);

            if (confirm('Are you sure you want to change the status to "' + statusName + '"?')) {
                changePreQuotationStatus(preQuotationId, status);
            }
        }
    });

    function changePreQuotationStatus(preQuotationId, newStatus) {
        // Create a form and submit it
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/pre-quotation/${preQuotationId}/status`;

        // Add CSRF token
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = '{{ csrf_token() }}';
        form.appendChild(csrfToken);

        // Add method override for PATCH
        const methodInput = document.createElement('input');
        methodInput.type = 'hidden';
        methodInput.name = '_method';
        methodInput.value = 'PATCH';
        form.appendChild(methodInput);

        // Add status input
        const statusInput = document.createElement('input');
        statusInput.type = 'hidden';
        statusInput.name = 'status';
        statusInput.value = newStatus;
        form.appendChild(statusInput);

        // Submit form
        document.body.appendChild(form);
        form.submit();
    }
});
</script>
@endsection
