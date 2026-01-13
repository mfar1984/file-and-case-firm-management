@extends('layouts.app')

@section('breadcrumb')
    Settings > Firm Management
@endsection

@php
use Illuminate\Support\Facades\Storage;
@endphp

@section('content')
<div class="px-4 md:px-6 pt-4 md:pt-6 pb-6 max-w-7xl mx-auto">
    <!-- Success/Error Messages -->
    @if(session('success'))
        <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
            {{ session('success') }}
        </div>
    @endif

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
                        <span class="material-icons mr-2 text-blue-600">business</span>
                        <h1 class="text-lg md:text-xl font-bold text-gray-800 text-[14px]">Firm Management</h1>
                    </div>
                    <p class="text-xs text-gray-500 mt-1 ml-8 text-[11px]">Manage multiple firms and their settings.</p>
                </div>

                <!-- Add Firm Button -->
                <a href="{{ route('settings.firms.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 md:px-3 md:py-1 rounded-md text-sm md:text-xs font-medium flex items-center justify-center md:justify-start w-full md:w-auto">
                    <span class="material-icons text-xs mr-1">add_business</span>
                    Add Firm
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
                    <input type="text" id="searchFilter" placeholder="Search firms..."
                           onkeyup="filterFirms()"
                           class="border border-gray-300 rounded px-3 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white w-64">

                    <select id="statusFilter" onchange="filterFirms()" class="custom-select border border-gray-300 rounded pl-4 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white">
                        <option value="">All Status</option>
                        <option value="Active">Active</option>
                        <option value="Inactive">Inactive</option>
                    </select>

                    <button onclick="filterFirms()" class="px-3 py-2 bg-blue-500 text-white rounded text-xs hover:bg-blue-600 transition-colors">
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
                            <th class="py-3 px-4 text-left rounded-tl">Firm</th>
                            <th class="py-3 px-4 text-left">Registration</th>
                            <th class="py-3 px-4 text-left">Contact</th>
                            <th class="py-3 px-4 text-left">Status</th>
                            <th class="py-3 px-4 text-left">Users</th>
                            <th class="py-3 px-4 text-left">Created</th>
                            <th class="py-3 px-4 text-center rounded-tr">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($firms as $firm)
                        <tr class="hover:bg-gray-50">
                            <td class="py-1 px-4">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-8 w-8">
                                        @if($firm->logo)
                                            <img class="h-8 w-8 rounded-full object-cover"
                                                 src="{{ Storage::url($firm->logo) }}"
                                                 alt="{{ $firm->name }}">
                                        @else
                                            <div class="h-8 w-8 rounded-full bg-blue-100 flex items-center justify-center">
                                                <span class="material-icons text-sm text-blue-600">business</span>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="ml-3">
                                        <div class="text-[11px] font-medium text-gray-900">{{ $firm->name }}</div>
                                        <div class="text-[10px] text-gray-500">ID: {{ $firm->id }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="py-1 px-4 text-[11px] text-gray-600">
                                {{ $firm->registration_number ?: 'N/A' }}
                            </td>
                            <td class="py-1 px-4 text-[11px]">
                                <div class="space-y-1">
                                    @if($firm->email)
                                        <div class="flex items-center">
                                            <span class="material-icons text-xs text-gray-400 mr-1">email</span>
                                            <span class="text-gray-900">{{ $firm->email }}</span>
                                        </div>
                                    @endif
                                    @if($firm->phone)
                                        <div class="flex items-center">
                                            <span class="material-icons text-xs text-gray-400 mr-1">phone</span>
                                            <span class="text-gray-900">{{ $firm->phone }}</span>
                                        </div>
                                    @endif
                                    @if(!$firm->email && !$firm->phone)
                                        <span class="text-gray-400 italic">No contact info</span>
                                    @endif
                                </div>
                            </td>
                            <td class="py-1 px-4 text-[11px]">
                                @if($firm->status === 'active')
                                    <span class="inline-block bg-green-100 text-green-800 px-1.5 py-0.5 rounded-full text-[10px]">Active</span>
                                @else
                                    <span class="inline-block bg-red-100 text-red-800 px-1.5 py-0.5 rounded-full text-[10px]">Inactive</span>
                                @endif
                            </td>
                            <td class="py-1 px-4 text-[11px]">
                                <div class="flex items-center">
                                    <span class="material-icons text-xs text-blue-600 mr-1">people</span>
                                    <span class="text-gray-900">{{ $firm->users()->count() }} users</span>
                                </div>
                            </td>
                            <td class="py-1 px-4 text-[11px] text-gray-500">
                                {{ $firm->created_at->diffForHumans() }}
                            </td>
                            <td class="py-1 px-4">
                                <div class="flex justify-center space-x-1 items-center">
                                    <a href="{{ route('settings.firms.show', $firm) }}" class="p-0.5 text-blue-600 hover:text-blue-700" title="View">
                                        <span class="material-icons text-base">visibility</span>
                                    </a>
                                    <a href="{{ route('settings.firms.edit', $firm) }}" class="p-0.5 text-yellow-600 hover:text-yellow-700" title="Edit">
                                        <span class="material-icons text-base">edit</span>
                                    </a>
                                    @if($firm->users()->count() === 0)
                                        <button onclick="deleteFirm({{ $firm->id }})" class="p-0.5 text-red-600 hover:text-red-700" title="Delete">
                                            <span class="material-icons text-base">delete</span>
                                        </button>
                                    @else
                                        <span class="p-0.5 text-gray-400" title="Cannot delete firm with users">
                                            <span class="material-icons text-base">delete</span>
                                        </span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="py-12 text-center">
                                <div class="text-gray-500">
                                    <span class="material-icons text-4xl mb-4 block">business</span>
                                    <p class="text-lg font-medium">No firms found</p>
                                    <p class="text-sm">Get started by creating your first firm.</p>
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
        <div class="md:hidden p-4 space-y-4">
            @forelse($firms as $firm)
            <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                <div class="flex items-center justify-between mb-3">
                    <div class="flex items-center">
                        <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center mr-3">
                            @if($firm->logo)
                                <img class="h-10 w-10 rounded-full object-cover"
                                     src="{{ Storage::url($firm->logo) }}"
                                     alt="{{ $firm->name }}">
                            @else
                                <span class="material-icons text-blue-600">business</span>
                            @endif
                        </div>
                        <div>
                            <div class="text-sm font-medium text-gray-900">{{ $firm->name }}</div>
                            <div class="text-xs text-gray-500">{{ $firm->registration_number ?: 'No registration' }}</div>
                            @if($firm->email)
                                <div class="text-xs text-gray-400">{{ $firm->email }}</div>
                            @endif
                        </div>
                    </div>
                    <div class="flex space-x-1">
                        <a href="{{ route('settings.firms.show', $firm) }}" class="p-2 bg-blue-50 rounded hover:bg-blue-100">
                            <span class="material-icons text-blue-700 text-sm">visibility</span>
                        </a>
                        <a href="{{ route('settings.firms.edit', $firm) }}" class="p-2 bg-yellow-50 rounded hover:bg-yellow-100">
                            <span class="material-icons text-yellow-700 text-sm">edit</span>
                        </a>
                        @if($firm->users()->count() === 0)
                            <button onclick="deleteFirm({{ $firm->id }})" class="p-2 bg-red-50 rounded hover:bg-red-100">
                                <span class="material-icons text-red-600 text-sm">delete</span>
                            </button>
                        @else
                            <span class="p-2 bg-gray-50 rounded text-gray-400" title="Cannot delete firm with users">
                                <span class="material-icons text-sm">delete</span>
                            </span>
                        @endif
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3 text-xs">
                    <div>
                        <span class="text-gray-500">Status:</span>
                        <div class="mt-1">
                            @if($firm->status === 'active')
                                <span class="inline-block bg-green-100 text-green-800 px-2 py-1 rounded-full text-xs">Active</span>
                            @else
                                <span class="inline-block bg-red-100 text-red-800 px-2 py-1 rounded-full text-xs">Inactive</span>
                            @endif
                        </div>
                    </div>
                    <div>
                        <span class="text-gray-500">Users:</span>
                        <div class="mt-1">
                            <span class="inline-block bg-blue-100 text-blue-800 px-2 py-1 rounded text-xs">{{ $firm->users()->count() }} users</span>
                        </div>
                    </div>
                </div>

                <div class="mt-3 text-xs text-gray-500">
                    Created: {{ $firm->created_at->diffForHumans() }}
                </div>
            </div>
            @empty
            <div class="text-center py-12">
                <div class="text-gray-500">
                    <span class="material-icons text-4xl mb-4 block">business</span>
                    <p class="text-lg font-medium">No firms found</p>
                    <p class="text-sm">Get started by creating your first firm.</p>
                    <a href="{{ route('settings.firms.create') }}" class="mt-4 inline-block bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded text-sm">
                        <span class="material-icons text-xs mr-1">add_business</span>
                        Add First Firm
                    </a>
                </div>
            </div>
            @endforelse
        </div>
    </div>
</div>

<script>
function deleteFirm(firmId) {
    if (confirm('Are you sure you want to delete this firm? This action cannot be undone.')) {
        // Create a form and submit it
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/settings/firms/${firmId}`;

        const methodField = document.createElement('input');
        methodField.type = 'hidden';
        methodField.name = '_method';
        methodField.value = 'DELETE';

        const tokenField = document.createElement('input');
        tokenField.type = 'hidden';
        tokenField.name = '_token';
        tokenField.value = '{{ csrf_token() }}';

        form.appendChild(methodField);
        form.appendChild(tokenField);
        document.body.appendChild(form);
        form.submit();
    }
}

// Pagination variables
let currentPage = 1;
let perPage = 25;
let allFirms = [];
let filteredFirms = [];

// Initialize pagination
function initializePagination() {
    const firmRows = document.querySelectorAll('tbody tr');
    allFirms = Array.from(firmRows).map((row, index) => ({
        id: index,
        element: row,
        searchText: row.textContent.toLowerCase()
    }));

    filteredFirms = [...allFirms];
    displayFirms();
    updatePagination();
}

function displayFirms() {
    const startIndex = (currentPage - 1) * perPage;
    const endIndex = startIndex + perPage;

    allFirms.forEach(firm => {
        if (firm.element) firm.element.style.display = 'none';
    });

    const firmsToShow = filteredFirms.slice(startIndex, endIndex);
    firmsToShow.forEach(firm => {
        if (firm.element) firm.element.style.display = '';
    });
}

function updatePagination() {
    const totalItems = filteredFirms.length;
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
    const newPerPage = parseInt(document.getElementById('perPage')?.value || 25);

    if (document.getElementById('perPage')) document.getElementById('perPage').value = newPerPage;

    perPage = newPerPage;
    currentPage = 1;
    displayFirms();
    updatePagination();
}

function filterFirms() {
    const searchTerm = (document.getElementById('searchFilter')?.value || '').toLowerCase();
    const statusFilter = (document.getElementById('statusFilter')?.value || '');

    filteredFirms = allFirms.filter(firm => {
        const matchesSearch = searchTerm === '' || firm.searchText.includes(searchTerm);
        const matchesStatus = statusFilter === '' || firm.searchText.includes(statusFilter.toLowerCase());

        return matchesSearch && matchesStatus;
    });

    currentPage = 1;
    displayFirms();
    updatePagination();
}

function resetFilters() {
    if (document.getElementById('searchFilter')) document.getElementById('searchFilter').value = '';
    if (document.getElementById('statusFilter')) document.getElementById('statusFilter').value = '';

    filteredFirms = [...allFirms];
    currentPage = 1;
    displayFirms();
    updatePagination();
}

function previousPage() {
    if (currentPage > 1) {
        currentPage--;
        displayFirms();
        updatePagination();
    }
}

function nextPage() {
    const totalPages = Math.ceil(filteredFirms.length / perPage);
    if (currentPage < totalPages) {
        currentPage++;
        displayFirms();
        updatePagination();
    }
}

function firstPage() {
    currentPage = 1;
    displayFirms();
    updatePagination();
}

function lastPage() {
    const totalPages = Math.ceil(filteredFirms.length / perPage);
    currentPage = totalPages;
    displayFirms();
    updatePagination();
}

function goToPage(page) {
    currentPage = page;
    displayFirms();
    updatePagination();
}

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    initializePagination();
});
</script>

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
