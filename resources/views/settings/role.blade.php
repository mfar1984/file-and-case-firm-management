@extends('layouts.app')

@section('breadcrumb')
    Settings > Role Management
@endsection

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
                        <span class="material-icons mr-2 text-purple-600">admin_panel_settings</span>
                        <h1 class="text-lg md:text-xl font-bold text-gray-800 text-[14px]">Role Management</h1>
                    </div>
                    <p class="text-xs text-gray-500 mt-1 ml-8 text-[11px]">Manage user roles and their permissions.</p>
                </div>

                <!-- Add Role Button -->
                <a href="{{ route('settings.role.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 md:px-3 md:py-1 rounded-md text-sm md:text-xs font-medium flex items-center justify-center md:justify-start w-full md:w-auto">
                    <span class="material-icons text-xs mr-1">add</span>
                    Add Role
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
                    <input type="text" id="searchFilter" placeholder="Search roles..."
                           onkeyup="filterRoles()"
                           class="border border-gray-300 rounded px-3 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white w-64">

                    <select id="statusFilter" onchange="filterRoles()" class="custom-select border border-gray-300 rounded pl-4 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white">
                        <option value="">All Status</option>
                        <option value="Active">Active</option>
                        <option value="Inactive">Inactive</option>
                    </select>

                    @if(auth()->user()->hasRole('Super Administrator') && $firms->count() > 0)
                        <select id="firmFilter" onchange="filterRoles()" class="custom-select border border-gray-300 rounded pl-4 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white">
                            <option value="">All Firms</option>
                            @foreach($firms as $firm)
                                <option value="{{ $firm->name }}">{{ $firm->name }}</option>
                            @endforeach
                        </select>
                    @endif

                    <button onclick="filterRoles()" class="px-3 py-2 bg-blue-500 text-white rounded text-xs hover:bg-blue-600 transition-colors">
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
                            <th class="py-3 px-4 text-left rounded-tl">Role Name</th>
                            <th class="py-3 px-4 text-left">Description</th>
                            <th class="py-3 px-4 text-left">Permissions</th>
                            <th class="py-3 px-4 text-left">Users Count</th>
                            @if(auth()->user()->hasRole('Super Administrator'))
                                <th class="py-3 px-4 text-left">Firm</th>
                            @endif
                            <th class="py-3 px-4 text-left">Status</th>
                            <th class="py-3 px-4 text-center rounded-tr">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($roles as $role)
                        <tr class="hover:bg-gray-50">
                            <td class="py-1 px-4 text-[11px] font-medium">{{ $role->name }}</td>
                            <td class="py-1 px-4 text-[11px]">{{ $role->description ?? 'No description' }}</td>
                            <td class="py-1 px-4 text-[11px]">
                                <div class="flex flex-wrap gap-1">
                                    @if($role->permissions->count() > 0)
                                        @foreach($role->permissions->take(3) as $permission)
                                            <span class="inline-block bg-green-100 text-green-800 px-1.5 py-0.5 rounded text-[10px]">{{ $permission->name }}</span>
                                        @endforeach
                                        @if($role->permissions->count() > 3)
                                            <span class="inline-block bg-gray-100 text-gray-800 px-1.5 py-0.5 rounded text-[10px]">+{{ $role->permissions->count() - 3 }} more</span>
                                        @endif
                                    @else
                                        <span class="inline-block bg-gray-100 text-gray-800 px-1.5 py-0.5 rounded text-[10px]">No permissions</span>
                                    @endif
                                </div>
                            </td>
                            <td class="py-1 px-4 text-[11px]">{{ $roleUserCounts[$role->id] ?? 0 }}</td>
                            @if(auth()->user()->hasRole('Super Administrator'))
                                <td class="py-1 px-4 text-[11px]">
                                    @if($role->firm_id)
                                        @php
                                            $firm = \App\Models\Firm::find($role->firm_id);
                                        @endphp
                                        @if($firm)
                                            <div class="flex items-center">
                                                <span class="material-icons text-xs text-blue-600 mr-1">business</span>
                                                <span class="text-gray-900">{{ $firm->name }}</span>
                                            </div>
                                        @else
                                            <span class="text-gray-400 italic">Firm not found</span>
                                        @endif
                                    @else
                                        <span class="text-gray-400 italic">Global role</span>
                                    @endif
                                </td>
                            @endif
                            <td class="py-1 px-4 text-[11px]">
                                <span class="inline-block bg-green-100 text-green-800 px-1.5 py-0.5 rounded-full text-[10px]">Active</span>
                            </td>
                            <td class="py-1 px-4">
                                <div class="flex justify-center space-x-1 items-center">
                                    <a href="{{ route('settings.role.show', $role->id) }}" class="p-0.5 text-blue-600 hover:text-blue-700" title="Show">
                                        <span class="material-icons text-base">visibility</span>
                                    </a>
                                    <a href="{{ route('settings.role.edit', $role->id) }}" class="p-0.5 text-yellow-600 hover:text-yellow-700" title="Edit">
                                        <span class="material-icons text-base">edit</span>
                                    </a>
                                    <button onclick="deleteRole({{ $role->id }})" class="p-0.5 text-red-600 hover:text-red-700" title="Delete">
                                        <span class="material-icons text-base">delete</span>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @endforeach
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
            @foreach($roles as $role)
            <div class="bg-white border border-gray-200 rounded-lg p-4 space-y-3">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <span class="text-sm font-medium text-gray-800">{{ $role->name }}</span>
                        <span class="inline-block bg-green-100 text-green-800 px-2 py-1 rounded-full text-xs">Active</span>
                    </div>
                    <div class="flex space-x-2">
                        <a href="{{ route('settings.role.show', $role->id) }}" class="p-2 bg-blue-50 rounded hover:bg-blue-100 border border-blue-100">
                            <span class="material-icons text-blue-700 text-sm">visibility</span>
                        </a>
                        <a href="{{ route('settings.role.edit', $role->id) }}" class="p-2 bg-yellow-50 rounded hover:bg-yellow-100 border border-yellow-100">
                            <span class="material-icons text-yellow-700 text-sm">edit</span>
                        </a>
                        <button onclick="deleteRole({{ $role->id }})" class="p-2 bg-red-50 rounded hover:bg-red-100 border border-red-100">
                            <span class="material-icons text-red-600 text-sm">delete</span>
                        </button>
                    </div>
                </div>
                <div class="space-y-2">
                    <div class="flex justify-between">
                        <span class="text-xs font-medium text-gray-600">Description:</span>
                        <span class="text-xs text-gray-800">{{ $role->description ?? 'No description' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-xs font-medium text-gray-600">Users:</span>
                        <span class="text-xs text-gray-800">{{ $roleUserCounts[$role->id] ?? 0 }}</span>
                    </div>
                    <div class="pt-2 border-t border-gray-100">
                        <div class="flex flex-wrap gap-1">
                            @if($role->permissions->count() > 0)
                                @foreach($role->permissions->take(3) as $permission)
                                    <span class="inline-block bg-green-100 text-green-800 px-1 py-0.5 rounded text-xs">{{ $permission->name }}</span>
                                @endforeach
                                @if($role->permissions->count() > 3)
                                    <span class="inline-block bg-gray-100 text-gray-800 px-1 py-0.5 rounded text-xs">+{{ $role->permissions->count() - 3 }} more</span>
                                @endif
                            @else
                                <span class="inline-block bg-gray-100 text-gray-800 px-1 py-0.5 rounded text-xs">No permissions</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
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
let allRoles = [];
let filteredRoles = [];

// Initialize pagination
function initializePagination() {
    const roleRows = document.querySelectorAll('tbody tr');
    allRoles = Array.from(roleRows).map((row, index) => ({
        id: index,
        element: row,
        searchText: row.textContent.toLowerCase()
    }));

    filteredRoles = [...allRoles];
    displayRoles();
    updatePagination();
}

function displayRoles() {
    const startIndex = (currentPage - 1) * perPage;
    const endIndex = startIndex + perPage;

    allRoles.forEach(role => {
        if (role.element) role.element.style.display = 'none';
    });

    const rolesToShow = filteredRoles.slice(startIndex, endIndex);
    rolesToShow.forEach(role => {
        if (role.element) role.element.style.display = '';
    });
}

function updatePagination() {
    const totalItems = filteredRoles.length;
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
    displayRoles();
    updatePagination();
}

function filterRoles() {
    const searchTerm = (document.getElementById('searchFilter')?.value || '').toLowerCase();
    const statusFilter = (document.getElementById('statusFilter')?.value || '');
    const firmFilter = (document.getElementById('firmFilter')?.value || '');

    filteredRoles = allRoles.filter(role => {
        const matchesSearch = searchTerm === '' || role.searchText.includes(searchTerm);
        const matchesStatus = statusFilter === '' || role.searchText.includes(statusFilter.toLowerCase());
        const matchesFirm = firmFilter === '' || role.searchText.includes(firmFilter.toLowerCase());

        return matchesSearch && matchesStatus && matchesFirm;
    });

    currentPage = 1;
    displayRoles();
    updatePagination();
}

function resetFilters() {
    if (document.getElementById('searchFilter')) document.getElementById('searchFilter').value = '';
    if (document.getElementById('statusFilter')) document.getElementById('statusFilter').value = '';
    if (document.getElementById('firmFilter')) document.getElementById('firmFilter').value = '';

    filteredRoles = [...allRoles];
    currentPage = 1;
    displayRoles();
    updatePagination();
}

function previousPage() {
    if (currentPage > 1) {
        currentPage--;
        displayRoles();
        updatePagination();
    }
}

function nextPage() {
    const totalPages = Math.ceil(filteredRoles.length / perPage);
    if (currentPage < totalPages) {
        currentPage++;
        displayRoles();
        updatePagination();
    }
}

function firstPage() {
    currentPage = 1;
    displayRoles();
    updatePagination();
}

function lastPage() {
    const totalPages = Math.ceil(filteredRoles.length / perPage);
    currentPage = totalPages;
    displayRoles();
    updatePagination();
}

function goToPage(page) {
    currentPage = page;
    displayRoles();
    updatePagination();
}

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    initializePagination();
});
</script>
<script>
function deleteRole(roleId) {
    if (confirm('Are you sure you want to delete this role?')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/settings/role/${roleId}`;

        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = '{{ csrf_token() }}';

        const methodField = document.createElement('input');
        methodField.type = 'hidden';
        methodField.name = '_method';
        methodField.value = 'DELETE';

        form.appendChild(csrfToken);
        form.appendChild(methodField);
        document.body.appendChild(form);
        form.submit();
    }
}
</script>
@endsection