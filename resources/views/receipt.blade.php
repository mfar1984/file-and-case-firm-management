@extends('layouts.app')

@section('breadcrumb')
    Receipt
@endsection

@section('content')
<div class="px-4 md:px-6 pt-4 md:pt-6 pb-6 max-w-7xl mx-auto">
    <div class="bg-white rounded shadow-md border border-gray-300">
        <div class="p-4 md:p-6 border-b border-gray-200">
            <div class="flex flex-col md:flex-row md:justify-between md:items-start space-y-4 md:space-y-0">
                <div>
                    <div class="flex items-center">
                        <span class="material-icons mr-2 text-green-600">receipt</span>
                        <h1 class="text-lg md:text-xl font-bold text-gray-800">Receipt Management</h1>
                    </div>
                    <p class="text-xs text-gray-500 mt-1 ml-8">Track all customer payments and receipts.</p>
                </div>
                
                <!-- Add Receipt Button -->
                <a href="{{ route('receipt.create') }}" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 md:px-3 md:py-1 rounded-md text-sm md:text-xs font-medium flex items-center justify-center md:justify-start w-full md:w-auto">
                    <span class="material-icons text-sm md:text-xs mr-1">add</span>
                    Add Receipt
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
                    <input type="text" id="searchFilter" placeholder="Search receipts..."
                           onkeyup="filterReceipts()"
                           class="border border-gray-300 rounded px-3 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white w-64">

                    <select id="statusFilter" onchange="filterReceipts()" class="custom-select border border-gray-300 rounded pl-4 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white">
                        <option value="">All Status</option>
                        <option value="Draft">Draft</option>
                        <option value="Issued">Issued</option>
                        <option value="Cancelled">Cancelled</option>
                    </select>

                    <button onclick="filterReceipts()" class="px-3 py-2 bg-blue-500 text-white rounded text-xs hover:bg-blue-600 transition-colors">
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
                            <th class="py-3 px-4 text-left rounded-tl">Receipt No</th>
                            <th class="py-3 px-4 text-left">Date</th>
                            <th class="py-3 px-4 text-left">Reference</th>
                            <th class="py-3 px-4 text-left">Customer</th>
                            <th class="py-3 px-4 text-left">Amount Paid (RM)</th>
                            <th class="py-3 px-4 text-left">Payment Method</th>
                            <th class="py-3 px-4 text-left">Status</th>
                            <th class="py-3 px-4 text-center rounded-tr">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($receipts as $receipt)
                        <tr class="hover:bg-gray-50">
                            <td class="py-1 px-4 text-[11px] font-medium">{{ $receipt->receipt_no }}</td>
                            <td class="py-1 px-4 text-[11px]">{{ $receipt->receipt_date->format('d/m/Y') }}</td>
                            <td class="py-1 px-4 text-[11px]">
                                @if($receipt->quotation)
                                    <span class="inline-block px-1.5 py-0.5 rounded-full text-[10px] bg-blue-100 text-blue-800">
                                        {{ $receipt->quotation->quotation_no }}
                                    </span>
                                @elseif($receipt->taxInvoice)
                                    <span class="inline-block px-1.5 py-0.5 rounded-full text-[10px] bg-green-100 text-green-800">
                                        {{ $receipt->taxInvoice->invoice_no }}
                                    </span>
                                @elseif($receipt->case)
                                    <span class="inline-block px-1.5 py-0.5 rounded-full text-[10px] bg-gray-100 text-gray-800">
                                        {{ $receipt->case->case_number }}
                                    </span>
                                @else
                                    <span class="text-gray-500">-</span>
                                @endif
                            </td>
                            <td class="py-1 px-4 text-[11px]">{{ $receipt->customer_name }}</td>
                            <td class="py-1 px-4 text-[11px] font-medium">RM {{ number_format($receipt->amount_paid, 2) }}</td>
                            <td class="py-1 px-4 text-[11px]">{{ $receipt->payment_method_display }}</td>
                            <td class="py-1 px-4 text-[11px]">
                                <span class="inline-block px-1.5 py-0.5 rounded-full text-[10px] {{ $receipt->status_color }}">
                                    {{ ucfirst($receipt->status) }}
                                </span>
                            </td>
                            <td class="py-1 px-4">
                                <div class="flex justify-center space-x-1 items-center">
                                    <a href="{{ route('receipt.show', $receipt->id) }}" class="p-0.5 text-blue-600 hover:text-blue-700" title="View">
                                        <span class="material-icons text-base">visibility</span>
                                    </a>
                                    <a href="{{ route('receipt.edit', $receipt->id) }}" class="p-0.5 text-yellow-600 hover:text-yellow-700" title="Edit">
                                        <span class="material-icons text-base">edit</span>
                                    </a>
                                    <button class="p-0.5 text-red-600 hover:text-red-700" title="Delete" onclick="return window.__deleteReceipt({{ $receipt->id }}, this)">
                                        <span class="material-icons text-base">delete</span>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="py-8 px-4 text-center text-gray-500">
                                <div class="flex flex-col items-center">
                                    <span class="material-icons text-4xl text-gray-300 mb-2">receipt</span>
                                    <p class="text-sm">No receipts available</p>
                                    <p class="text-xs text-gray-400 mt-1">Create receipts to track customer payments</p>
                                    <a href="{{ route('receipt.create') }}" class="text-blue-600 hover:text-blue-800 text-xs mt-2">Create your first receipt</a>
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

        <!-- Mobile View -->
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
                    <input type="text" id="searchFilterMobile" placeholder="Search receipts..."
                           onkeyup="filterReceipts()"
                           class="w-full border border-gray-300 rounded px-3 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white">

                    <div class="flex gap-2">
                        <select id="statusFilterMobile" onchange="filterReceipts()" class="custom-select flex-1 border border-gray-300 rounded pl-4 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white">
                            <option value="">All Status</option>
                            <option value="Draft">Draft</option>
                            <option value="Issued">Issued</option>
                            <option value="Cancelled">Cancelled</option>
                        </select>

                        <button onclick="filterReceipts()" class="px-3 py-2 bg-blue-500 text-white rounded text-xs hover:bg-blue-600 transition-colors">
                            🔍
                        </button>

                        <button onclick="resetFilters()" class="px-3 py-2 bg-gray-500 text-white rounded text-xs hover:bg-gray-600 transition-colors">
                            🔄
                        </button>
                    </div>
                </div>
            </div>

            <div id="receipts-mobile-container">
            @forelse($receipts as $receipt)
            <div class="p-4 border-b border-gray-200 last:border-b-0">
                <div class="flex justify-between items-start mb-3">
                    <div>
                        <div class="font-medium text-gray-900">{{ $receipt->receipt_no }}</div>
                        <div class="text-sm text-gray-500">{{ $receipt->receipt_date->format('d/m/Y') }}</div>
                    </div>
                    <span class="inline-flex items-center px-1.5 py-0.5 rounded-full text-[10px] font-medium {{ $receipt->status_color }}">
                        <span class="material-icons text-xs mr-1">{{ $receipt->status_icon }}</span>
                        {{ ucfirst($receipt->status) }}
                    </span>
                </div>
                
                <div class="space-y-2 mb-3">
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-500">Reference:</span>
                        <span class="text-sm text-gray-900">
                            @if($receipt->quotation)
                                {{ $receipt->quotation->quotation_no }}
                            @elseif($receipt->taxInvoice)
                                {{ $receipt->taxInvoice->invoice_no }}
                            @elseif($receipt->case)
                                {{ $receipt->case->case_number }}
                            @else
                                -
                            @endif
                        </span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-500">Customer:</span>
                        <span class="text-sm text-gray-900">{{ $receipt->customer_name }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-500">Amount:</span>
                        <span class="text-sm font-medium text-gray-900">RM {{ number_format($receipt->amount_paid, 2) }}</span>
                    </div>
                    @if($receipt->outstanding_balance > 0)
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-500">Balance:</span>
                        <span class="text-sm text-gray-500">RM {{ number_format($receipt->outstanding_balance, 2) }}</span>
                    </div>
                    @endif
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-500">Method:</span>
                        <span class="text-sm text-gray-900">{{ $receipt->payment_method_display }}</span>
                    </div>
                </div>
                
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-2">
                        <a href="{{ route('receipt.show', $receipt->id) }}" class="text-blue-600 hover:text-blue-900 text-sm">
                            <span class="material-icons text-sm mr-1">visibility</span>
                            View
                        </a>
                        <a href="{{ route('receipt.edit', $receipt->id) }}" class="text-indigo-600 hover:text-indigo-900 text-sm">
                            <span class="material-icons text-sm mr-1">edit</span>
                            Edit
                        </a>
                    </div>
                    <button class="text-red-600 hover:text-red-900 text-sm" onclick="return window.__deleteReceipt({{ $receipt->id }}, this)">
                        <span class="material-icons text-sm mr-1">delete</span>
                        Delete
                    </button>
                </div>
            </div>
            @empty
            <div class="p-8 text-center text-gray-500">
                <span class="material-icons text-4xl text-gray-300 mb-2">receipt</span>
                <p class="text-sm">No receipts available</p>
                <p class="text-xs text-gray-400 mt-1">Create receipts to track customer payments</p>
                <a href="{{ route('receipt.create') }}" class="text-blue-600 hover:text-blue-800 text-xs mt-2 block">Create your first receipt</a>
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
let allReceipts = [];
let filteredReceipts = [];

// Initialize pagination
function initializePagination() {
    const receiptRows = document.querySelectorAll('tbody tr');
    allReceipts = Array.from(receiptRows).map((row, index) => ({
        id: index,
        element: row,
        searchText: row.textContent.toLowerCase()
    }));

    filteredReceipts = [...allReceipts];
    displayReceipts();
    updatePagination();
}

function displayReceipts() {
    const startIndex = (currentPage - 1) * perPage;
    const endIndex = startIndex + perPage;

    allReceipts.forEach(receipt => {
        if (receipt.element) receipt.element.style.display = 'none';
    });

    const receiptsToShow = filteredReceipts.slice(startIndex, endIndex);
    receiptsToShow.forEach(receipt => {
        if (receipt.element) receipt.element.style.display = '';
    });
}

function updatePagination() {
    const totalItems = filteredReceipts.length;
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

function changePerPage() {
    const newPerPage = parseInt(document.getElementById('perPage')?.value ||
                               document.getElementById('perPageMobile')?.value || 25);

    if (document.getElementById('perPage')) document.getElementById('perPage').value = newPerPage;
    if (document.getElementById('perPageMobile')) document.getElementById('perPageMobile').value = newPerPage;

    perPage = newPerPage;
    currentPage = 1;
    displayReceipts();
    updatePagination();
}

function filterReceipts() {
    const searchTerm = (document.getElementById('searchFilter')?.value ||
                       document.getElementById('searchFilterMobile')?.value || '').toLowerCase();
    const statusFilter = (document.getElementById('statusFilter')?.value ||
                        document.getElementById('statusFilterMobile')?.value || '');

    if (document.getElementById('searchFilter')) document.getElementById('searchFilter').value = searchTerm;
    if (document.getElementById('searchFilterMobile')) document.getElementById('searchFilterMobile').value = searchTerm;
    if (document.getElementById('statusFilter')) document.getElementById('statusFilter').value = statusFilter;
    if (document.getElementById('statusFilterMobile')) document.getElementById('statusFilterMobile').value = statusFilter;

    filteredReceipts = allReceipts.filter(receipt => {
        const matchesSearch = searchTerm === '' || receipt.searchText.includes(searchTerm);
        const matchesStatus = statusFilter === '' || receipt.searchText.includes(statusFilter.toLowerCase());

        return matchesSearch && matchesStatus;
    });

    currentPage = 1;
    displayReceipts();
    updatePagination();
}

function resetFilters() {
    if (document.getElementById('searchFilter')) document.getElementById('searchFilter').value = '';
    if (document.getElementById('searchFilterMobile')) document.getElementById('searchFilterMobile').value = '';
    if (document.getElementById('statusFilter')) document.getElementById('statusFilter').value = '';
    if (document.getElementById('statusFilterMobile')) document.getElementById('statusFilterMobile').value = '';

    filteredReceipts = [...allReceipts];
    currentPage = 1;
    displayReceipts();
    updatePagination();
}

function previousPage() {
    if (currentPage > 1) {
        currentPage--;
        displayReceipts();
        updatePagination();
    }
}

function nextPage() {
    const totalPages = Math.ceil(filteredReceipts.length / perPage);
    if (currentPage < totalPages) {
        currentPage++;
        displayReceipts();
        updatePagination();
    }
}

function firstPage() {
    currentPage = 1;
    displayReceipts();
    updatePagination();
}

function lastPage() {
    const totalPages = Math.ceil(filteredReceipts.length / perPage);
    currentPage = totalPages;
    displayReceipts();
    updatePagination();
}

function goToPage(page) {
    currentPage = page;
    displayReceipts();
    updatePagination();
}

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    initializePagination();
});
</script>

<script>
window.__deleteReceipt = function(id, element) {
    if (confirm('Are you sure you want to delete this receipt? This action cannot be undone.')) {
        fetch(`/receipt/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Remove the row from the table
                if (element.closest('tr')) {
                    element.closest('tr').remove();
                } else if (element.closest('.border-b')) {
                    element.closest('.border-b').remove();
                }
                
                // Show success message
                alert('Receipt deleted successfully');
                
                // Reload page if no more receipts
                if (document.querySelectorAll('tbody tr, .border-b').length === 0) {
                    location.reload();
                }
            } else {
                alert('Failed to delete receipt: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while deleting the receipt');
        });
    }
    return false;
};
</script>
@endsection
