@extends('layouts.app')

@section('breadcrumb')
    Tax Invoice
@endsection

@section('content')
<div class="px-4 md:px-6 pt-4 md:pt-6 pb-6 max-w-7xl mx-auto">
    <div class="bg-white rounded shadow-md border border-gray-300">
        <div class="p-4 md:p-6 border-b border-gray-200">
            <div class="flex flex-col md:flex-row md:justify-between md:items-start space-y-4 md:space-y-0">
                <div>
                    <div class="flex items-center">
                        <span class="material-icons mr-2 text-blue-600">receipt</span>
                        <h1 class="text-lg md:text-xl font-bold text-gray-800">Tax Invoice Management</h1>
                    </div>
                    <p class="text-xs text-gray-500 mt-1 ml-8">Manage all tax invoices and billing.</p>
                </div>
                
                <!-- Add Tax Invoice Button -->
                <a href="{{ route('tax-invoice.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 md:px-3 md:py-1 rounded-md text-sm md:text-xs font-medium flex items-center justify-center md:justify-start w-full md:w-auto">
                    <span class="material-icons text-sm md:text-xs mr-1">add</span>
                    Add Tax Invoice
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
                    <input type="text" id="searchFilter" placeholder="Search tax invoices..."
                           onkeyup="filterTaxInvoices()"
                           class="border border-gray-300 rounded px-3 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white w-64">

                    <select id="statusFilter" onchange="filterTaxInvoices()" class="custom-select border border-gray-300 rounded pl-4 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white">
                        <option value="">All Status</option>
                        <option value="Draft">Draft</option>
                        <option value="Sent">Sent</option>
                        <option value="Paid">Paid</option>
                        <option value="Overdue">Overdue</option>
                    </select>

                    <button onclick="filterTaxInvoices()" class="px-3 py-2 bg-blue-500 text-white rounded text-xs hover:bg-blue-600 transition-colors">
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
                            <th class="py-3 px-4 text-left rounded-tl">Invoice No</th>
                            <th class="py-3 px-4 text-left">Client Name</th>
                            <th class="py-3 px-4 text-left">Case Ref</th>
                            <th class="py-3 px-4 text-left">Amount (RM)</th>
                            <th class="py-3 px-4 text-left">Issue Date</th>
                            <th class="py-3 px-4 text-left">Due Date</th>
                            <th class="py-3 px-4 text-left">Status</th>
                            <th class="py-3 px-4 text-center rounded-tr">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($taxInvoices as $inv)
                        <tr class="hover:bg-gray-50">
                            <td class="py-1 px-4 text-[11px] font-medium">{{ $inv->invoice_no }}</td>
                            <td class="py-1 px-4 text-[11px]">{{ $inv->customer_name ?? 'N/A' }}</td>
                            <td class="py-1 px-4 text-[11px]">{{ $inv->case->case_number ?? 'N/A' }}</td>
                            <td class="py-1 px-4 text-[11px] font-medium">RM {{ number_format($inv->total, 2) }}</td>
                            <td class="py-1 px-4 text-[11px]">{{ optional($inv->invoice_date)->format('d/m/Y') }}</td>
                            <td class="py-1 px-4 text-[11px]">{{ optional($inv->due_date)->format('d/m/Y') }}</td>
                            <td class="py-1 px-4 text-[11px]">
                                <span class="inline-block px-1.5 py-0.5 rounded-full text-[10px] font-medium {{ $inv->status_color }}">
                                    {{ $inv->status_display }}
                                </span>
                            </td>
                            <td class="py-1 px-4">
                                <div class="flex justify-center space-x-1 items-center">
                                    <a href="{{ route('tax-invoice.show', $inv->id) }}" class="p-0.5 text-blue-600 hover:text-blue-700" title="View">
                                        <span class="material-icons text-base">visibility</span>
                                    </a>
                                    <a href="{{ route('tax-invoice.edit', $inv->id) }}" class="p-0.5 text-yellow-600 hover:text-yellow-700" title="Edit">
                                        <span class="material-icons text-base">edit</span>
                                    </a>
                                    
                                    <!-- Status Change Icons -->
                                    @if($inv->status === 'draft')
                                        <button onclick="window.__sendTaxInvoice({{ $inv->id }})" class="p-0.5 text-blue-600 hover:text-blue-700" title="Send Invoice">
                                            <span class="material-icons text-base">send</span>
                                        </button>
                                    @endif
                                    
                                    @if(in_array($inv->status, ['sent', 'partially_paid', 'overdue']))
                                        <button onclick="window.__markAsPaid({{ $inv->id }})" class="p-0.5 text-green-600 hover:text-green-700" title="Mark as Paid">
                                            <span class="material-icons text-base">check_circle</span>
                                        </button>
                                    @endif
                                    
                                    @if(in_array($inv->status, ['sent', 'overdue']))
                                        <button onclick="window.__markAsPartiallyPaid({{ $inv->id }})" class="p-0.5 text-yellow-600 hover:text-yellow-700" title="Mark as Partially Paid">
                                            <span class="material-icons text-base">schedule</span>
                                        </button>
                                    @endif
                                    
                                    @if(in_array($inv->status, ['sent', 'partially_paid']))
                                        <button onclick="window.__markAsOverdue({{ $inv->id }})" class="p-0.5 text-orange-600 hover:text-orange-700" title="Mark as Overdue">
                                            <span class="material-icons text-base">warning</span>
                                        </button>
                                    @endif
                                    
                                    @if(in_array($inv->status, ['draft', 'sent', 'partially_paid', 'overdue']))
                                        <button onclick="window.__cancelTaxInvoice({{ $inv->id }})" class="p-0.5 text-red-600 hover:text-red-700" title="Cancel">
                                            <span class="material-icons text-base">cancel</span>
                                        </button>
                                    @endif
                                    
                                    <button class="p-0.5 text-red-600 hover:text-red-700" title="Delete" onclick="return window.__deleteTaxInvoice({{ $inv->id }}, this)">
                                        <span class="material-icons text-base">delete</span>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="py-8 px-4 text-center text-gray-500">
                                <div class="flex flex-col items-center">
                                    <span class="material-icons text-4xl text-gray-300 mb-2">receipt_long</span>
                                    <p class="text-sm">No tax invoices available</p>
                                    <p class="text-xs text-gray-400 mt-1">Create tax invoices to manage client billing</p>
                                    <a href="{{ route('tax-invoice.create') }}" class="text-blue-600 hover:text-blue-800 text-xs mt-2">Create your first tax invoice</a>
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
                    <input type="text" id="searchFilterMobile" placeholder="Search tax invoices..."
                           onkeyup="filterTaxInvoices()"
                           class="w-full border border-gray-300 rounded px-3 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white">

                    <div class="flex gap-2">
                        <select id="statusFilterMobile" onchange="filterTaxInvoices()" class="custom-select flex-1 border border-gray-300 rounded pl-4 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white">
                            <option value="">All Status</option>
                            <option value="Draft">Draft</option>
                            <option value="Sent">Sent</option>
                            <option value="Paid">Paid</option>
                            <option value="Overdue">Overdue</option>
                        </select>

                        <button onclick="filterTaxInvoices()" class="px-3 py-2 bg-blue-500 text-white rounded text-xs hover:bg-blue-600 transition-colors">
                            🔍
                        </button>

                        <button onclick="resetFilters()" class="px-3 py-2 bg-gray-500 text-white rounded text-xs hover:bg-gray-600 transition-colors">
                            🔄
                        </button>
                    </div>
                </div>
            </div>

            <div class="space-y-4" id="taxinvoices-mobile-container">
            @forelse($taxInvoices as $inv)
            <div class="bg-gray-50 rounded-lg border border-gray-200 p-4">
                <div class="flex justify-between items-start mb-3">
                    <div>
                        <h3 class="font-semibold text-gray-800 text-sm">{{ $inv->invoice_no }}</h3>
                        <p class="text-xs text-gray-600">{{ $inv->customer_name ?? 'N/A' }}</p>
                    </div>
                    <span class="inline-block px-1.5 py-0.5 rounded-full text-[10px] font-medium {{ $inv->status_color }}">
                        {{ $inv->status_display }}
                    </span>
                </div>
                
                <div class="space-y-2 mb-4">
                    <div class="flex justify-between">
                        <span class="text-xs text-gray-500">Case Ref:</span>
                        <span class="text-xs font-medium">{{ $inv->case->case_number ?? 'N/A' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-xs text-gray-500">Amount:</span>
                        <span class="text-xs font-medium">RM {{ number_format($inv->total, 2) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-xs text-gray-500">Issue Date:</span>
                        <span class="text-xs font-medium">{{ optional($inv->invoice_date)->format('d/m/Y') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-xs text-gray-500">Due Date:</span>
                        <span class="text-xs font-medium">{{ optional($inv->due_date)->format('d/m/Y') }}</span>
                    </div>
                </div>
                
                <!-- Mobile Action Buttons -->
                <div class="flex flex-wrap gap-2">
                    <a href="{{ route('tax-invoice.show', $inv->id) }}" class="p-2 bg-blue-100 text-blue-700 rounded-md hover:bg-blue-200" title="View">
                        <span class="material-icons text-sm">visibility</span>
                    </a>
                    <a href="{{ route('tax-invoice.edit', $inv->id) }}" class="p-2 bg-yellow-100 text-yellow-700 rounded-md hover:bg-yellow-200" title="Edit">
                        <span class="material-icons text-sm">edit</span>
                    </a>
                    
                    <!-- Status Change Icons -->
                    @if($inv->status === 'draft')
                        <button onclick="window.__sendTaxInvoice({{ $inv->id }})" class="p-2 bg-blue-100 text-blue-700 rounded-md hover:bg-blue-200" title="Send Invoice">
                            <span class="material-icons text-sm">send</span>
                        </button>
                    @endif
                    
                    @if(in_array($inv->status, ['sent', 'partially_paid', 'overdue']))
                        <button onclick="window.__markAsPaid({{ $inv->id }})" class="p-2 bg-green-100 text-green-700 rounded-md hover:bg-green-200" title="Mark as Paid">
                            <span class="material-icons text-sm">check_circle</span>
                        </button>
                    @endif
                    
                    @if(in_array($inv->status, ['sent', 'overdue']))
                        <button onclick="window.__markAsPartiallyPaid({{ $inv->id }})" class="p-2 bg-yellow-100 text-yellow-700 rounded-md hover:bg-yellow-200" title="Mark as Partially Paid">
                            <span class="material-icons text-sm">schedule</span>
                        </button>
                    @endif
                    
                    @if(in_array($inv->status, ['sent', 'partially_paid']))
                        <button onclick="window.__markAsOverdue({{ $inv->id }})" class="p-2 bg-orange-100 text-orange-700 rounded-md hover:bg-orange-200" title="Mark as Overdue">
                            <span class="material-icons text-sm">warning</span>
                        </button>
                    @endif
                    
                    @if(in_array($inv->status, ['draft', 'sent', 'partially_paid', 'overdue']))
                        <button onclick="window.__cancelTaxInvoice({{ $inv->id }})" class="p-2 bg-red-100 text-red-700 rounded-md hover:bg-red-200" title="Cancel">
                            <span class="material-icons text-sm">cancel</span>
                        </button>
                    @endif
                    
                    <button class="p-2 bg-red-100 text-red-700 rounded-md hover:bg-red-200" title="Delete" onclick="return window.__deleteTaxInvoice({{ $inv->id }}, this)">
                        <span class="material-icons text-sm">delete</span>
                    </button>
                </div>
            </div>
            @empty
            <div class="text-center text-gray-500 py-8">
                <div class="flex flex-col items-center">
                    <span class="material-icons text-4xl text-gray-300 mb-2">receipt_long</span>
                    <p class="text-sm">No tax invoices available</p>
                    <p class="text-xs text-gray-400 mt-1">Create tax invoices to manage client billing</p>
                    <a href="{{ route('tax-invoice.create') }}" class="text-blue-600 hover:text-blue-800 text-xs mt-2 block">Create your first tax invoice</a>
                </div>
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
let allTaxInvoices = [];
let filteredTaxInvoices = [];

// Initialize pagination
function initializePagination() {
    const taxInvoiceRows = document.querySelectorAll('tbody tr');
    allTaxInvoices = Array.from(taxInvoiceRows).map((row, index) => ({
        id: index,
        element: row,
        searchText: row.textContent.toLowerCase()
    }));

    filteredTaxInvoices = [...allTaxInvoices];
    displayTaxInvoices();
    updatePagination();
}

function displayTaxInvoices() {
    const startIndex = (currentPage - 1) * perPage;
    const endIndex = startIndex + perPage;

    allTaxInvoices.forEach(invoice => {
        if (invoice.element) invoice.element.style.display = 'none';
    });

    const invoicesToShow = filteredTaxInvoices.slice(startIndex, endIndex);
    invoicesToShow.forEach(invoice => {
        if (invoice.element) invoice.element.style.display = '';
    });
}

function updatePagination() {
    const totalItems = filteredTaxInvoices.length;
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
    displayTaxInvoices();
    updatePagination();
}

function filterTaxInvoices() {
    const searchTerm = (document.getElementById('searchFilter')?.value ||
                       document.getElementById('searchFilterMobile')?.value || '').toLowerCase();
    const statusFilter = (document.getElementById('statusFilter')?.value ||
                        document.getElementById('statusFilterMobile')?.value || '');

    if (document.getElementById('searchFilter')) document.getElementById('searchFilter').value = searchTerm;
    if (document.getElementById('searchFilterMobile')) document.getElementById('searchFilterMobile').value = searchTerm;
    if (document.getElementById('statusFilter')) document.getElementById('statusFilter').value = statusFilter;
    if (document.getElementById('statusFilterMobile')) document.getElementById('statusFilterMobile').value = statusFilter;

    filteredTaxInvoices = allTaxInvoices.filter(invoice => {
        const matchesSearch = searchTerm === '' || invoice.searchText.includes(searchTerm);
        const matchesStatus = statusFilter === '' || invoice.searchText.includes(statusFilter.toLowerCase());

        return matchesSearch && matchesStatus;
    });

    currentPage = 1;
    displayTaxInvoices();
    updatePagination();
}

function resetFilters() {
    if (document.getElementById('searchFilter')) document.getElementById('searchFilter').value = '';
    if (document.getElementById('searchFilterMobile')) document.getElementById('searchFilterMobile').value = '';
    if (document.getElementById('statusFilter')) document.getElementById('statusFilter').value = '';
    if (document.getElementById('statusFilterMobile')) document.getElementById('statusFilterMobile').value = '';

    filteredTaxInvoices = [...allTaxInvoices];
    currentPage = 1;
    displayTaxInvoices();
    updatePagination();
}

function previousPage() {
    if (currentPage > 1) {
        currentPage--;
        displayTaxInvoices();
        updatePagination();
    }
}

function nextPage() {
    const totalPages = Math.ceil(filteredTaxInvoices.length / perPage);
    if (currentPage < totalPages) {
        currentPage++;
        displayTaxInvoices();
        updatePagination();
    }
}

function firstPage() {
    currentPage = 1;
    displayTaxInvoices();
    updatePagination();
}

function lastPage() {
    const totalPages = Math.ceil(filteredTaxInvoices.length / perPage);
    currentPage = totalPages;
    displayTaxInvoices();
    updatePagination();
}

function goToPage(page) {
    currentPage = page;
    displayTaxInvoices();
    updatePagination();
}

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    initializePagination();
});
</script>

<script>
window.__deleteTaxInvoice = function(id, el) {
    if (confirm('Are you sure you want to delete this tax invoice? This action cannot be undone.')) {
        fetch(`/tax-invoice/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                // Find the table row (desktop) or card (mobile)
                const tableRow = el.closest('tr');
                const mobileCard = el.closest('div.bg-white.rounded-lg');

                if (tableRow) {
                    tableRow.remove();
                } else if (mobileCard) {
                    mobileCard.remove();
                } else {
                    // Fallback: reload the page
                    window.location.reload();
                }

                alert(data.message);
            } else {
                alert('Failed to delete tax invoice: ' + (data.message || 'Unknown error'));
            }
        })
        .catch(error => {
            console.error('Error deleting tax invoice:', error);
            alert('An error occurred while deleting the tax invoice.');
        });
    }
    return false;
};

// Status Change Functions
window.__sendTaxInvoice = function(id) {
    if (confirm('Are you sure you want to send this tax invoice? This will change the status to "sent".')) {
        fetch(`/tax-invoice/${id}/send`, {
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
                alert(data.message);
                location.reload(); // Reload to show updated status
            } else {
                alert('Failed to send tax invoice: ' + (data.message || 'Unknown error'));
            }
        })
        .catch(error => {
            console.error('Error sending tax invoice:', error);
            alert('An error occurred while sending the tax invoice.');
        });
    }
};

window.__markAsPaid = function(id) {
    if (confirm('Are you sure you want to mark this tax invoice as paid?')) {
        fetch(`/tax-invoice/${id}/mark-as-paid`, {
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
                alert(data.message);
                location.reload(); // Reload to show updated status
            } else {
                alert('Failed to mark as paid: ' + (data.message || 'Unknown error'));
            }
        })
        .catch(error => {
            console.error('Error marking as paid:', error);
            alert('An error occurred while updating the status.');
        });
    }
};

window.__markAsPartiallyPaid = function(id) {
    if (confirm('Are you sure you want to mark this tax invoice as partially paid?')) {
        fetch(`/tax-invoice/${id}/mark-as-partially-paid`, {
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
                alert(data.message);
                location.reload(); // Reload to show updated status
            } else {
                alert('Failed to mark as partially paid: ' + (data.message || 'Unknown error'));
            }
        })
        .catch(error => {
            console.error('Error marking as partially paid:', error);
            alert('An error occurred while updating the status.');
        });
    }
};

window.__markAsOverdue = function(id) {
    if (confirm('Are you sure you want to mark this tax invoice as overdue?')) {
        fetch(`/tax-invoice/${id}/mark-as-overdue`, {
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
                alert(data.message);
                location.reload(); // Reload to show updated status
            } else {
                alert('Failed to mark as overdue: ' + (data.message || 'Unknown error'));
            }
        })
        .catch(error => {
            console.error('Error marking as overdue:', error);
            alert('An error occurred while updating the status.');
        });
    }
};

window.__cancelTaxInvoice = function(id) {
    if (confirm('Are you sure you want to cancel this tax invoice? This action cannot be undone.')) {
        fetch(`/tax-invoice/${id}/cancel`, {
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
                alert(data.message);
                location.reload(); // Reload to show updated status
            } else {
                alert('Failed to cancel tax invoice: ' + (data.message || 'Unknown error'));
            }
        })
        .catch(error => {
            console.error('Error cancelling tax invoice:', error);
            alert('An error occurred while cancelling the tax invoice.');
        });
    }
};
</script>
@endsection 