@extends('layouts.app')

@section('breadcrumb')
    Quotation
@endsection

@section('content')
<style>
    .quotation-management-style {
        font-family: 'Poppins', sans-serif;
        font-size: 12px;
        line-height: 1.4;
        color: #333;
    }
    .page-header {
        background: #f8f9fa;
        border-bottom: 2px solid #e9ecef;
        padding: 20px;
        margin-bottom: 0;
    }
    .page-title {
        display: flex;
        align-items: center;
        margin-bottom: 8px;
    }
    .page-title h1 {
        font-size: 18px;
        font-weight: bold;
        color: #333;
        margin: 0;
        margin-left: 8px;
    }
    .page-subtitle {
        font-size: 12px;
        color: #666;
        margin: 0;
        margin-left: 32px;
    }

</style>

<div class="quotation-management-style">
    <div class="px-4 md:px-6 pt-4 md:pt-6 pb-6 max-w-7xl mx-auto">
        <div class="bg-white rounded shadow-md border border-gray-300">
            <div class="page-header">
                <div class="flex flex-col md:flex-row md:justify-between md:items-start space-y-4 md:space-y-0">
                    <div>
                        <div class="page-title">
                            <span class="material-icons text-blue-600">description</span>
                            <h1>Quotation Management</h1>
                        </div>
                        <p class="page-subtitle">Manage all quotations and convert them to invoices.</p>
                    </div>

                    <!-- Add Quotation Button -->
                    <a href="{{ route('quotation.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 md:px-3 md:py-1 rounded-md text-sm md:text-xs font-medium flex items-center justify-center md:justify-start w-full md:w-auto">
                        <span class="material-icons text-sm md:text-xs mr-1">add</span>
                        Add Quotation
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
                        <input type="text" id="searchFilter" placeholder="Search quotations..."
                               onkeyup="filterQuotations()"
                               class="border border-gray-300 rounded px-3 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white w-64">

                        <select id="statusFilter" onchange="filterQuotations()" class="custom-select border border-gray-300 rounded pl-4 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white">
                            <option value="">All Status</option>
                            <option value="Draft">Draft</option>
                            <option value="Sent">Sent</option>
                            <option value="Approved">Approved</option>
                            <option value="Rejected">Rejected</option>
                        </select>

                        <button onclick="filterQuotations()" class="px-3 py-2 bg-blue-500 text-white rounded text-xs hover:bg-blue-600 transition-colors">
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
                            <th class="py-3 px-4 text-left rounded-tl">Quotation No</th>
                            <th class="py-3 px-4 text-left">Client Name</th>
                            <th class="py-3 px-4 text-left">Case Ref</th>
                            <th class="py-3 px-4 text-left">Amount (RM)</th>
                            <th class="py-3 px-4 text-left">Issue Date</th>
                            <th class="py-3 px-4 text-left">Valid Until</th>
                            <th class="py-3 px-4 text-left">Status</th>
                            <th class="py-3 px-4 text-center rounded-tr">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($quotations as $q)
                        <tr class="hover:bg-gray-50">
                            <td class="py-1 px-4 text-[11px] font-medium">{{ $q->quotation_no }}</td>
                            <td class="py-1 px-4 text-[11px]">{{ $q->customer_name ?? 'N/A' }}</td>
                            <td class="py-1 px-4 text-[11px]">{{ $q->case->case_number ?? 'N/A' }}</td>
                            <td class="py-1 px-4 text-[11px] font-medium">RM {{ number_format($q->total, 2) }}</td>
                            <td class="py-1 px-4 text-[11px]">{{ optional($q->quotation_date)->format('d/m/Y') }}</td>
                            <td class="py-1 px-4 text-[11px]">{{ optional($q->valid_until)->format('d/m/Y') }}</td>
                            <td class="py-1 px-4 text-[11px]">
                                <span class="inline-block px-1.5 py-0.5 rounded-full text-[10px] {{ $q->status_color }}">
                                    {{ $q->status_display }}
                                </span>
                            </td>
                            <td class="py-1 px-4">
                                <div class="flex justify-center space-x-1 items-center">
                                    <a href="{{ route('quotation.show', $q->id) }}" class="p-0.5 text-blue-600 hover:text-blue-700" title="View">
                                        <span class="material-icons text-base">visibility</span>
                                    </a>
                                    <a href="{{ route('quotation.create', ['case_id' => $q->case_id, 'case_number' => $q->case->case_number ?? null, 'from_quotation' => $q->id]) }}" class="p-0.5 text-yellow-600 hover:text-yellow-700" title="Prefill/Edit">
                                        <span class="material-icons text-base">edit</span>
                                    </a>
                                    
                                    <!-- Status Change Buttons -->
                                    @if($q->status === 'pending')
                                        <button class="p-0.5 text-green-600 hover:text-green-700" title="Accept" onclick="return window.__acceptQuotation({{ $q->id }}, this)">
                                            <span class="material-icons text-base">check_circle</span>
                                        </button>
                                        <button class="p-0.5 text-red-600 hover:text-red-700" title="Reject" onclick="return window.__rejectQuotation({{ $q->id }}, this)">
                                            <span class="material-icons text-base">cancel</span>
                                        </button>
                                    @endif
                                    
                                    @if(in_array($q->status, ['pending', 'accepted', 'rejected']))
                                        <button class="p-0.5 text-gray-600 hover:text-gray-700" title="Cancel" onclick="return window.__cancelQuotation({{ $q->id }}, this)">
                                            <span class="material-icons text-base">block</span>
                                        </button>
                                    @endif
                                    
                                    @if(in_array($q->status, ['cancelled', 'rejected']))
                                        <button class="p-0.5 text-blue-600 hover:text-blue-700" title="Reactivate" onclick="return window.__reactivateQuotation({{ $q->id }}, this)">
                                            <span class="material-icons text-base">refresh</span>
                                        </button>
                                    @endif
                                    
                                    @if($q->status === 'accepted')
                                        <a href="{{ route('tax-invoice.create', ['from_quotation' => $q->id]) }}" class="p-0.5 text-purple-600 hover:text-purple-700" title="Convert to Invoice">
                                            <span class="material-icons text-base">receipt_long</span>
                                        </a>
                                    @endif
                                    
                                    <button class="p-0.5 text-red-600 hover:text-red-700" title="Delete" onclick="return window.__deleteQuotation({{ $q->id }}, this)">
                                        <span class="material-icons text-base">delete</span>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="py-8 px-4 text-center text-gray-500">
                                <div class="flex flex-col items-center">
                                    <span class="material-icons text-gray-400 text-4xl mb-2">description</span>
                                    <p class="text-sm text-gray-500">No quotations available</p>
                                    <p class="text-xs text-gray-400">Create quotations to manage client proposals</p>
                                    <a href="{{ route('quotation.create') }}" class="text-blue-600 hover:text-blue-800 text-xs mt-2">Create your first quotation</a>
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
                    <input type="text" id="searchFilterMobile" placeholder="Search quotations..."
                           onkeyup="filterQuotations()"
                           class="w-full border border-gray-300 rounded px-3 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white">

                    <div class="flex gap-2">
                        <select id="statusFilterMobile" onchange="filterQuotations()" class="custom-select flex-1 border border-gray-300 rounded pl-4 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white">
                            <option value="">All Status</option>
                            <option value="Draft">Draft</option>
                            <option value="Sent">Sent</option>
                            <option value="Approved">Approved</option>
                            <option value="Rejected">Rejected</option>
                        </select>

                        <button onclick="filterQuotations()" class="px-3 py-2 bg-blue-500 text-white rounded text-xs hover:bg-blue-600 transition-colors">
                            🔍
                        </button>

                        <button onclick="resetFilters()" class="px-3 py-2 bg-gray-500 text-white rounded text-xs hover:bg-gray-600 transition-colors">
                            🔄
                        </button>
                    </div>
                </div>
            </div>

            <div class="space-y-4" id="quotations-mobile-container">
            @forelse($quotations as $q)
            <div class="bg-gray-50 rounded-lg border border-gray-200 p-4">
                <div class="flex justify-between items-start mb-3">
                    <div>
                        <h3 class="font-semibold text-gray-800 text-sm">{{ $q->quotation_no }}</h3>
                        <p class="text-xs text-gray-600">{{ $q->customer_name ?? 'N/A' }}</p>
                    </div>
                    <span class="inline-block px-1.5 py-0.5 rounded-full text-[10px] {{ $q->status_color }}">
                        {{ $q->status_display }}
                    </span>
                </div>
                
                <div class="space-y-2 mb-4">
                    <div class="flex justify-between">
                        <span class="text-xs text-gray-500">Case Ref:</span>
                        <span class="text-xs font-medium">{{ $q->case->case_number ?? 'N/A' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-xs text-gray-500">Amount:</span>
                        <span class="text-xs font-medium">RM {{ number_format($q->total, 2) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-xs text-gray-500">Issue Date:</span>
                        <span class="text-xs font-medium">{{ optional($q->quotation_date)->format('d/m/Y') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-xs text-gray-500">Valid Until:</span>
                        <span class="text-xs font-medium">{{ optional($q->valid_until)->format('d/m/Y') }}</span>
                    </div>
                </div>
                
                <!-- Mobile Action Buttons -->
                <div class="flex flex-wrap gap-2">
                    <a href="{{ route('quotation.show', $q->id) }}" class="flex-1 bg-blue-100 text-blue-700 py-2 px-3 rounded-md text-xs font-medium flex items-center justify-center">
                        <span class="material-icons text-sm mr-1">visibility</span>
                        View
                    </a>
                    <a href="{{ route('quotation.create', ['case_id' => $q->case_id, 'case_number' => $q->case->case_number ?? null, 'from_quotation' => $q->id]) }}" class="flex-1 bg-yellow-100 text-yellow-700 py-2 px-3 rounded-md text-xs font-medium flex items-center justify-center">
                        <span class="material-icons text-sm mr-1">edit</span>
                        Edit
                    </a>
                    
                    <!-- Status Change Buttons -->
                    @if($q->status === 'pending')
                        <button class="flex-1 bg-green-100 text-green-700 py-2 px-3 rounded-md text-xs font-medium flex items-center justify-center" onclick="return window.__acceptQuotation({{ $q->id }}, this)">
                            <span class="material-icons text-sm mr-1">check_circle</span>
                            Accept
                        </button>
                        <button class="flex-1 bg-red-100 text-red-700 py-2 px-3 rounded-md text-xs font-medium flex items-center justify-center" onclick="return window.__rejectQuotation({{ $q->id }}, this)">
                            <span class="material-icons text-sm mr-1">cancel</span>
                            Reject
                        </button>
                    @endif
                    
                    @if(in_array($q->status, ['pending', 'accepted', 'rejected']))
                        <button class="flex-1 bg-gray-100 text-gray-700 py-2 px-3 rounded-md text-xs font-medium flex items-center justify-center" onclick="return window.__cancelQuotation({{ $q->id }}, this)">
                            <span class="material-icons text-sm mr-1">block</span>
                            Cancel
                        </button>
                    @endif
                    
                    @if(in_array($q->status, ['cancelled', 'rejected']))
                        <button class="flex-1 bg-blue-100 text-blue-700 py-2 px-3 rounded-md text-xs font-medium flex items-center justify-center" onclick="return window.__reactivateQuotation({{ $q->id }}, this)">
                            <span class="material-icons text-sm mr-1">refresh</span>
                            Reactivate
                        </button>
                    @endif
                    
                    @if($q->status === 'accepted')
                        <a href="{{ route('tax-invoice.create', ['from_quotation' => $q->id]) }}" class="flex-1 bg-purple-100 text-purple-700 py-2 px-3 rounded-md text-xs font-medium flex items-center justify-center">
                            <span class="material-icons text-sm mr-1">receipt_long</span>
                            Invoice
                        </a>
                    @endif
                    
                    <button class="flex-1 bg-red-100 text-red-700 py-2 px-3 rounded-md text-xs font-medium flex items-center justify-center" onclick="return window.__deleteQuotation({{ $q->id }}, this)">
                        <span class="material-icons text-sm mr-1">delete</span>
                        Delete
                    </button>
                </div>
            </div>
            @empty
            <div class="text-center py-8">
                <span class="material-icons text-gray-400 text-4xl mb-2">description</span>
                <p class="text-sm text-gray-500">No quotations available</p>
                <p class="text-xs text-gray-400">Create quotations to manage client proposals</p>
                <a href="{{ route('quotation.create') }}" class="text-blue-600 hover:text-blue-800 text-xs mt-2 block">Create your first quotation</a>
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
let allQuotations = [];
let filteredQuotations = [];

// Initialize pagination
function initializePagination() {
    const quotationRows = document.querySelectorAll('tbody tr');
    allQuotations = Array.from(quotationRows).map((row, index) => ({
        id: index,
        element: row,
        searchText: row.textContent.toLowerCase()
    }));

    filteredQuotations = [...allQuotations];
    displayQuotations();
    updatePagination();
}

function displayQuotations() {
    const startIndex = (currentPage - 1) * perPage;
    const endIndex = startIndex + perPage;

    allQuotations.forEach(quotation => {
        if (quotation.element) quotation.element.style.display = 'none';
    });

    const quotationsToShow = filteredQuotations.slice(startIndex, endIndex);
    quotationsToShow.forEach(quotation => {
        if (quotation.element) quotation.element.style.display = '';
    });
}

function updatePagination() {
    const totalItems = filteredQuotations.length;
    const totalPages = Math.ceil(totalItems / perPage);
    const startItem = totalItems === 0 ? 0 : (currentPage - 1) * perPage + 1;
    const endItem = Math.min(currentPage * perPage, totalItems);

    if (document.getElementById('pageInfo')) {
        document.getElementById('pageInfo').textContent = `Showing ${startItem} to ${endItem} of ${totalItems} records`;
    }

    const prevBtn = document.getElementById('prevBtn');
    const nextBtn = document.getElementById('nextBtn');
    const prevSingleBtn = document.getElementById('prevSingleBtn');
    const nextSingleBtn = document.getElementById('nextSingleBtn');

    if (prevBtn) prevBtn.disabled = currentPage === 1;
    if (prevSingleBtn) prevSingleBtn.disabled = currentPage === 1;
    if (nextBtn) nextBtn.disabled = currentPage === totalPages || totalPages === 0;
    if (nextSingleBtn) nextSingleBtn.disabled = currentPage === totalPages || totalPages === 0;

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

function changePerPage() {
    const newPerPage = parseInt(document.getElementById('perPage')?.value ||
                               document.getElementById('perPageMobile')?.value || 25);

    if (document.getElementById('perPage')) document.getElementById('perPage').value = newPerPage;
    if (document.getElementById('perPageMobile')) document.getElementById('perPageMobile').value = newPerPage;

    perPage = newPerPage;
    currentPage = 1;
    displayQuotations();
    updatePagination();
}

function filterQuotations() {
    const searchTerm = (document.getElementById('searchFilter')?.value ||
                       document.getElementById('searchFilterMobile')?.value || '').toLowerCase();
    const statusFilter = (document.getElementById('statusFilter')?.value ||
                        document.getElementById('statusFilterMobile')?.value || '');

    if (document.getElementById('searchFilter')) document.getElementById('searchFilter').value = searchTerm;
    if (document.getElementById('searchFilterMobile')) document.getElementById('searchFilterMobile').value = searchTerm;
    if (document.getElementById('statusFilter')) document.getElementById('statusFilter').value = statusFilter;
    if (document.getElementById('statusFilterMobile')) document.getElementById('statusFilterMobile').value = statusFilter;

    filteredQuotations = allQuotations.filter(quotation => {
        const matchesSearch = searchTerm === '' || quotation.searchText.includes(searchTerm);
        const matchesStatus = statusFilter === '' || quotation.searchText.includes(statusFilter.toLowerCase());

        return matchesSearch && matchesStatus;
    });

    currentPage = 1;
    displayQuotations();
    updatePagination();
}

function resetFilters() {
    if (document.getElementById('searchFilter')) document.getElementById('searchFilter').value = '';
    if (document.getElementById('searchFilterMobile')) document.getElementById('searchFilterMobile').value = '';
    if (document.getElementById('statusFilter')) document.getElementById('statusFilter').value = '';
    if (document.getElementById('statusFilterMobile')) document.getElementById('statusFilterMobile').value = '';

    filteredQuotations = [...allQuotations];
    currentPage = 1;
    displayQuotations();
    updatePagination();
}

function previousPage() {
    if (currentPage > 1) {
        currentPage--;
        displayQuotations();
        updatePagination();
    }
}

function nextPage() {
    const totalPages = Math.ceil(filteredQuotations.length / perPage);
    if (currentPage < totalPages) {
        currentPage++;
        displayQuotations();
        updatePagination();
    }
}

function firstPage() {
    currentPage = 1;
    displayQuotations();
    updatePagination();
}

function lastPage() {
    const totalPages = Math.ceil(filteredQuotations.length / perPage);
    currentPage = totalPages;
    displayQuotations();
    updatePagination();
}

function goToPage(page) {
    currentPage = page;
    displayQuotations();
    updatePagination();
}

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    initializePagination();
});
</script>

<script>
window.__deleteQuotation = function(id, el) {
    if (confirm('Are you sure you want to delete this quotation? This action cannot be undone.')) {
        fetch(`/quotation/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                el.closest('tr, div').remove(); // Remove the row/card from the DOM
                alert(data.message);
            } else {
                alert('Failed to delete quotation: ' + (data.message || 'Unknown error'));
            }
        })
        .catch(error => {
            console.error('Error deleting quotation:', error);
            alert('An error occurred while deleting the quotation.');
        });
    }
};

window.__acceptQuotation = function(id, el) {
    if (confirm('Are you sure you want to accept this quotation?')) {
        fetch(`/quotation/${id}/accept`, {
            method: 'PATCH',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload(); // Reload to update status and buttons
                alert(data.message);
            } else {
                alert('Failed to accept quotation: ' + (data.message || 'Unknown error'));
            }
        })
        .catch(error => {
            console.error('Error accepting quotation:', error);
            alert('An error occurred while accepting the quotation.');
        });
    }
};

window.__rejectQuotation = function(id, el) {
    if (confirm('Are you sure you want to reject this quotation?')) {
        fetch(`/quotation/${id}/reject`, {
            method: 'PATCH',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload(); // Reload to update status and buttons
                alert(data.message);
            } else {
                alert('Failed to reject quotation: ' + (data.message || 'Unknown error'));
            }
        })
        .catch(error => {
            console.error('Error rejecting quotation:', error);
            alert('An error occurred while rejecting the quotation.');
        });
    }
};

window.__cancelQuotation = function(id, el) {
    if (confirm('Are you sure you want to cancel this quotation?')) {
        fetch(`/quotation/${id}/cancel`, {
            method: 'PATCH',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload(); // Reload to update status and buttons
                alert(data.message);
            } else {
                alert('Failed to cancel quotation: ' + (data.message || 'Unknown error'));
            }
        })
        .catch(error => {
            console.error('Error cancelling quotation:', error);
            alert('An error occurred while cancelling the quotation.');
        });
    }
};

window.__reactivateQuotation = function(id, el) {
    if (confirm('Are you sure you want to reactivate this quotation?')) {
        fetch(`/quotation/${id}/reactivate`, {
            method: 'PATCH',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload(); // Reload to update status and buttons
                alert(data.message);
            } else {
                alert('Failed to reactivate quotation: ' + (data.message || 'Unknown error'));
            }
        })
        .catch(error => {
            console.error('Error reactivating quotation:', error);
            alert('An error occurred while reactivating the quotation.');
        });
    }
};
</script>
@endsection
