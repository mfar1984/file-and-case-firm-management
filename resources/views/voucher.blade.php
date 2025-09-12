@extends('layouts.app')

@section('breadcrumb')
    Voucher
@endsection

@section('content')
<div class="px-4 md:px-6 pt-4 md:pt-6 pb-6 max-w-7xl mx-auto">
    <div class="bg-white rounded shadow-md border border-gray-300">
        <div class="p-4 md:p-6 border-b border-gray-200">
            <div class="flex flex-col md:flex-row md:justify-between md:items-start space-y-4 md:space-y-0">
                <div>
                    <div class="flex items-center">
                        <span class="material-icons mr-2 text-purple-600">payment</span>
                        <h1 class="text-lg md:text-xl font-bold text-gray-800">Payment Voucher Management</h1>
                    </div>
                    <p class="text-xs text-gray-500 mt-1 ml-8">Manage all payment vouchers and expense records.</p>
                </div>
                
                <!-- Add Voucher Button -->
                <a href="{{ route('voucher.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 md:px-3 md:py-1 rounded-md text-sm md:text-xs font-medium flex items-center justify-center md:justify-start w-full md:w-auto">
                    <span class="material-icons text-sm md:text-xs mr-1">add</span>
                    Add Voucher
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
                    <input type="text" id="searchFilter" placeholder="Search vouchers..."
                           onkeyup="filterVouchers()"
                           class="border border-gray-300 rounded px-3 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white w-64">

                    <select id="statusFilter" onchange="filterVouchers()" class="custom-select border border-gray-300 rounded pl-4 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white">
                        <option value="">All Status</option>
                        <option value="Draft">Draft</option>
                        <option value="Approved">Approved</option>
                        <option value="Paid">Paid</option>
                        <option value="Cancelled">Cancelled</option>
                    </select>

                    <button onclick="filterVouchers()" class="px-3 py-2 bg-blue-500 text-white rounded text-xs hover:bg-blue-600 transition-colors">
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
                            <th class="py-3 px-4 text-left rounded-tl">Voucher No</th>
                            <th class="py-3 px-4 text-left">Payee Name</th>
                            <th class="py-3 px-4 text-left">Expense Category</th>
                            <th class="py-3 px-4 text-left">Amount (RM)</th>
                            <th class="py-3 px-4 text-left">Payment Method</th>
                            <th class="py-3 px-4 text-left">Payment Date</th>
                            <th class="py-3 px-4 text-left">Approved By</th>
                            <th class="py-3 px-4 text-left">Status</th>
                            <th class="py-3 px-4 text-center rounded-tr">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($vouchers as $voucher)
                        <tr class="hover:bg-gray-50">
                            <td class="py-1 px-4 text-[11px] font-medium">{{ $voucher->voucher_no }}</td>
                            <td class="py-1 px-4 text-[11px]">{{ $voucher->payee_name }}</td>
                            <td class="py-1 px-4 text-[11px]">{{ $voucher->items->first()->category ?? 'N/A' }}</td>
                            <td class="py-1 px-4 text-[11px] font-medium">RM {{ number_format($voucher->total_amount, 2) }}</td>
                            <td class="py-1 px-4 text-[11px]">{{ $voucher->payment_method_display }}</td>
                            <td class="py-1 px-4 text-[11px]">{{ $voucher->payment_date->format('d/m/Y') }}</td>
                            <td class="py-1 px-4 text-[11px]">{{ $voucher->approved_by }}</td>
                            <td class="py-1 px-4 text-[11px]">
                                <span class="inline-block {{ $voucher->status_color }} px-1.5 py-0.5 rounded-full text-[10px]">{{ $voucher->status_display }}</span>
                            </td>
                            <td class="py-1 px-4">
                                <div class="flex justify-center space-x-1 items-center" x-data="{ open: false }">
                                    <button @click="open = !open" class="p-0.5 text-purple-600 hover:text-purple-700" title="Change Status">
                                        <span class="material-icons text-base">add</span>
                                    </button>
                                    <a href="{{ route('voucher.show', $voucher->id) }}" class="p-0.5 text-blue-600 hover:text-blue-700" title="View">
                                        <span class="material-icons text-base">visibility</span>
                                    </a>
                                    <a href="{{ route('voucher.edit', $voucher->id) }}" class="p-0.5 text-yellow-600 hover:text-yellow-700" title="Edit">
                                        <span class="material-icons text-base">edit</span>
                                    </a>
                                    <form action="{{ route('voucher.destroy', $voucher->id) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this voucher?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-0.5 text-red-600 hover:text-red-700" title="Delete">
                                            <span class="material-icons text-base">delete</span>
                                        </button>
                                    </form>
                                    <div x-show="open" @click.away="open = false" class="absolute z-50 mt-2 w-48 bg-white rounded shadow-xl border border-gray-200 p-3 text-[11px]">
                                        <div class="mb-2 font-bold text-gray-800">Change Status</div>
                                        <ul class="space-y-1">
                                            <li>
                                                <button class="w-full text-left px-2 py-1 hover:bg-gray-100 rounded change-voucher-status-btn"
                                                        data-voucher-id="{{ $voucher->id }}"
                                                        data-status="draft">
                                                    Draft
                                                </button>
                                            </li>
                                            <li>
                                                <button class="w-full text-left px-2 py-1 hover:bg-gray-100 rounded change-voucher-status-btn"
                                                        data-voucher-id="{{ $voucher->id }}"
                                                        data-status="pending">
                                                    Pending
                                                </button>
                                            </li>
                                            <li>
                                                <button class="w-full text-left px-2 py-1 hover:bg-gray-100 rounded change-voucher-status-btn"
                                                        data-voucher-id="{{ $voucher->id }}"
                                                        data-status="approved">
                                                    Approved
                                                </button>
                                            </li>
                                            <li>
                                                <button class="w-full text-left px-2 py-1 hover:bg-gray-100 rounded change-voucher-status-btn"
                                                        data-voucher-id="{{ $voucher->id }}"
                                                        data-status="paid">
                                                    Paid
                                                </button>
                                            </li>
                                            <li>
                                                <button class="w-full text-left px-2 py-1 hover:bg-gray-100 rounded change-voucher-status-btn"
                                                        data-voucher-id="{{ $voucher->id }}"
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
                            <td colspan="9" class="py-8 px-4 text-center text-gray-500">
                                <div class="flex flex-col items-center">
                                    <span class="material-icons text-gray-400 text-4xl mb-2">confirmation_number</span>
                                    <p class="text-sm text-gray-500">No vouchers available</p>
                                    <p class="text-xs text-gray-400">Create payment vouchers to track expenses</p>
                                    <a href="{{ route('voucher.create') }}" class="text-blue-600 hover:text-blue-800 text-xs mt-2">Create your first voucher</a>
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
                    <input type="text" id="searchFilterMobile" placeholder="Search vouchers..."
                           onkeyup="filterVouchers()"
                           class="w-full border border-gray-300 rounded px-3 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white">

                    <div class="flex gap-2">
                        <select id="statusFilterMobile" onchange="filterVouchers()" class="custom-select flex-1 border border-gray-300 rounded pl-4 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white">
                            <option value="">All Status</option>
                            <option value="Draft">Draft</option>
                            <option value="Approved">Approved</option>
                            <option value="Paid">Paid</option>
                            <option value="Cancelled">Cancelled</option>
                        </select>

                        <button onclick="filterVouchers()" class="px-3 py-2 bg-blue-500 text-white rounded text-xs hover:bg-blue-600 transition-colors">
                            🔍
                        </button>

                        <button onclick="resetFilters()" class="px-3 py-2 bg-gray-500 text-white rounded text-xs hover:bg-gray-600 transition-colors">
                            🔄
                        </button>
                    </div>
                </div>
            </div>

            <div class="space-y-4" id="vouchers-mobile-container">
            @forelse($vouchers as $voucher)
            <div class="bg-gray-50 rounded-lg border border-gray-200 p-4" x-data="{ showStatusMenu: false }">
                <div class="flex justify-between items-start mb-3">
                    <div>
                        <h3 class="font-semibold text-gray-800 text-sm">{{ $voucher->voucher_no }}</h3>
                        <p class="text-xs text-gray-600">{{ $voucher->payee_name }}</p>
                    </div>
                    <span class="inline-block {{ $voucher->status_color }} px-1.5 py-0.5 rounded-full text-[10px]">{{ $voucher->status_display }}</span>
                </div>

                <div class="space-y-2 mb-4">
                    <div class="flex justify-between">
                        <span class="text-xs text-gray-500">Category:</span>
                        <span class="text-xs font-medium">{{ $voucher->items->first()->category ?? 'N/A' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-xs text-gray-500">Amount:</span>
                        <span class="text-xs font-medium">RM {{ number_format($voucher->total_amount, 2) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-xs text-gray-500">Payment Method:</span>
                        <span class="text-xs font-medium">{{ $voucher->payment_method_display }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-xs text-gray-500">Payment Date:</span>
                        <span class="text-xs font-medium">{{ $voucher->payment_date->format('d/m/Y') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-xs text-gray-500">Approved By:</span>
                        <span class="text-xs font-medium">{{ $voucher->approved_by }}</span>
                    </div>
                </div>

                <!-- Mobile Action Buttons -->
                <div class="flex space-x-2">
                    <button @click="showStatusMenu = !showStatusMenu" class="flex-1 bg-white border border-gray-300 text-gray-700 py-2 px-3 rounded-md text-xs font-medium flex items-center justify-center hover:bg-gray-50">
                        <span class="material-icons text-sm mr-1">add</span>
                        Status
                    </button>
                    <a href="{{ route('voucher.show', $voucher->id) }}" class="flex-1 bg-blue-100 text-blue-700 py-2 px-3 rounded-md text-xs font-medium flex items-center justify-center">
                        <span class="material-icons text-sm mr-1">visibility</span>
                        View
                    </a>
                    <a href="{{ route('voucher.edit', $voucher->id) }}" class="flex-1 bg-yellow-100 text-yellow-700 py-2 px-3 rounded-md text-xs font-medium flex items-center justify-center">
                        <span class="material-icons text-sm mr-1">edit</span>
                        Edit
                    </a>
                    <form action="{{ route('voucher.destroy', $voucher->id) }}" method="POST" class="flex-1" onsubmit="return confirm('Are you sure you want to delete this voucher?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full bg-red-100 text-red-700 py-2 px-3 rounded-md text-xs font-medium flex items-center justify-center">
                            <span class="material-icons text-sm mr-1">delete</span>
                            Delete
                        </button>
                    </form>
                </div>

                <!-- Mobile Status Dropdown -->
                <div x-show="showStatusMenu" @click.away="showStatusMenu = false" class="mt-3 bg-white rounded-md shadow-lg border border-gray-200 p-3">
                    <div class="mb-2 font-bold text-gray-800 text-xs">Change Status</div>
                    <div class="grid grid-cols-2 gap-2">
                        <button class="w-full text-left px-3 py-2 text-xs hover:bg-gray-100 rounded change-voucher-status-btn"
                                data-voucher-id="{{ $voucher->id }}"
                                data-status="draft">
                            Draft
                        </button>
                        <button class="w-full text-left px-3 py-2 text-xs hover:bg-gray-100 rounded change-voucher-status-btn"
                                data-voucher-id="{{ $voucher->id }}"
                                data-status="pending">
                            Pending
                        </button>
                        <button class="w-full text-left px-3 py-2 text-xs hover:bg-gray-100 rounded change-voucher-status-btn"
                                data-voucher-id="{{ $voucher->id }}"
                                data-status="approved">
                            Approved
                        </button>
                        <button class="w-full text-left px-3 py-2 text-xs hover:bg-gray-100 rounded change-voucher-status-btn"
                                data-voucher-id="{{ $voucher->id }}"
                                data-status="paid">
                            Paid
                        </button>
                        <button class="w-full text-left px-3 py-2 text-xs hover:bg-gray-100 rounded change-voucher-status-btn"
                                data-voucher-id="{{ $voucher->id }}"
                                data-status="cancelled">
                            Cancelled
                        </button>
                    </div>
                </div>
            </div>
            @empty
            <div class="text-center py-8">
                <span class="material-icons text-gray-400 text-4xl mb-2">confirmation_number</span>
                <p class="text-sm text-gray-500">No vouchers available</p>
                <p class="text-xs text-gray-400">Create payment vouchers to track expenses</p>
                <a href="{{ route('voucher.create') }}" class="text-blue-600 hover:text-blue-800 text-xs mt-2 block">Create your first voucher</a>
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
let allVouchers = [];
let filteredVouchers = [];

// Initialize pagination
function initializePagination() {
    const voucherRows = document.querySelectorAll('tbody tr');
    allVouchers = Array.from(voucherRows).map((row, index) => ({
        id: index,
        element: row,
        searchText: row.textContent.toLowerCase()
    }));

    filteredVouchers = [...allVouchers];
    displayVouchers();
    updatePagination();
}

function displayVouchers() {
    const startIndex = (currentPage - 1) * perPage;
    const endIndex = startIndex + perPage;

    allVouchers.forEach(voucher => {
        if (voucher.element) voucher.element.style.display = 'none';
    });

    const vouchersToShow = filteredVouchers.slice(startIndex, endIndex);
    vouchersToShow.forEach(voucher => {
        if (voucher.element) voucher.element.style.display = '';
    });
}

function updatePagination() {
    const totalItems = filteredVouchers.length;
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
    displayVouchers();
    updatePagination();
}

function filterVouchers() {
    const searchTerm = (document.getElementById('searchFilter')?.value ||
                       document.getElementById('searchFilterMobile')?.value || '').toLowerCase();
    const statusFilter = (document.getElementById('statusFilter')?.value ||
                        document.getElementById('statusFilterMobile')?.value || '');

    if (document.getElementById('searchFilter')) document.getElementById('searchFilter').value = searchTerm;
    if (document.getElementById('searchFilterMobile')) document.getElementById('searchFilterMobile').value = searchTerm;
    if (document.getElementById('statusFilter')) document.getElementById('statusFilter').value = statusFilter;
    if (document.getElementById('statusFilterMobile')) document.getElementById('statusFilterMobile').value = statusFilter;

    filteredVouchers = allVouchers.filter(voucher => {
        const matchesSearch = searchTerm === '' || voucher.searchText.includes(searchTerm);
        const matchesStatus = statusFilter === '' || voucher.searchText.includes(statusFilter.toLowerCase());

        return matchesSearch && matchesStatus;
    });

    currentPage = 1;
    displayVouchers();
    updatePagination();
}

function resetFilters() {
    if (document.getElementById('searchFilter')) document.getElementById('searchFilter').value = '';
    if (document.getElementById('searchFilterMobile')) document.getElementById('searchFilterMobile').value = '';
    if (document.getElementById('statusFilter')) document.getElementById('statusFilter').value = '';
    if (document.getElementById('statusFilterMobile')) document.getElementById('statusFilterMobile').value = '';

    filteredVouchers = [...allVouchers];
    currentPage = 1;
    displayVouchers();
    updatePagination();
}

function previousPage() {
    if (currentPage > 1) {
        currentPage--;
        displayVouchers();
        updatePagination();
    }
}

function nextPage() {
    const totalPages = Math.ceil(filteredVouchers.length / perPage);
    if (currentPage < totalPages) {
        currentPage++;
        displayVouchers();
        updatePagination();
    }
}

function firstPage() {
    currentPage = 1;
    displayVouchers();
    updatePagination();
}

function lastPage() {
    const totalPages = Math.ceil(filteredVouchers.length / perPage);
    currentPage = totalPages;
    displayVouchers();
    updatePagination();
}

function goToPage(page) {
    currentPage = page;
    displayVouchers();
    updatePagination();
}
</script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize pagination
    initializePagination();

    // Handle voucher status change buttons
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('change-voucher-status-btn')) {
            const voucherId = e.target.dataset.voucherId;
            const status = e.target.dataset.status;
            const statusName = status.charAt(0).toUpperCase() + status.slice(1);

            if (confirm('Are you sure you want to change the status to "' + statusName + '"?')) {
                changeVoucherStatus(voucherId, status);
            }
        }
    });

    function changeVoucherStatus(voucherId, newStatus) {
        // Create a form and submit it
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/voucher/${voucherId}/status`;

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