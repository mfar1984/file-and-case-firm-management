@extends('layouts.app')

@section('breadcrumb')
    Settings > User Management
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
                        <span class="material-icons mr-2 text-blue-600">people</span>
                        <h1 class="text-lg md:text-xl font-bold text-gray-800 text-[14px]">User Management</h1>
                    </div>
                    <p class="text-xs text-gray-500 mt-1 ml-8 text-[11px]">Manage system users, their roles, and access permissions.</p>
                </div>
                
                <!-- Add User Button -->
                <a href="{{ route('settings.user.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 md:px-3 md:py-1 rounded-md text-sm md:text-xs font-medium flex items-center justify-center md:justify-start w-full md:w-auto">
                    <span class="material-icons text-xs mr-1">person_add</span>
                    Add User
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
                    <input type="text" id="searchFilter" placeholder="Search users..."
                           onkeyup="filterUsers()"
                           class="border border-gray-300 rounded px-3 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white w-64">

                    @if(auth()->user()->hasRole('Super Administrator') && $firms->count() > 0)
                        <select id="firmFilter" onchange="filterUsers()" class="custom-select border border-gray-300 rounded pl-4 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white">
                            <option value="">All Firms</option>
                            @foreach($firms as $firm)
                                <option value="{{ $firm->name }}">{{ $firm->name }}</option>
                            @endforeach
                        </select>
                    @endif

                    <select id="statusFilter" onchange="filterUsers()" class="custom-select border border-gray-300 rounded pl-4 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white">
                        <option value="">All Status</option>
                        <option value="Active">Active</option>
                        <option value="Inactive">Inactive</option>
                    </select>

                    <button onclick="filterUsers()" class="px-3 py-2 bg-blue-500 text-white rounded text-xs hover:bg-blue-600 transition-colors">
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
                            <th class="py-3 px-4 text-left rounded-tl">User</th>
                            <th class="py-3 px-4 text-left">Username</th>
                            <th class="py-3 px-4 text-left">Email</th>
                            <th class="py-3 px-4 text-left">Firm</th>
                            <th class="py-3 px-4 text-left">Roles</th>
                            <th class="py-3 px-4 text-left">Status</th>
                            <th class="py-3 px-4 text-left">Last Login</th>
                            <th class="py-3 px-4 text-center rounded-tr">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($users as $user)
                        <tr class="hover:bg-gray-50">
                            <td class="py-1 px-4">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-8 w-8">
                                        <div class="h-8 w-8 rounded-full bg-blue-100 flex items-center justify-center">
                                            <span class="text-sm font-medium text-blue-600">{{ substr($user->name, 0, 1) }}</span>
                                        </div>
                                    </div>
                                    <div class="ml-3">
                                        <div class="text-[11px] font-medium text-gray-900">{{ $user->name }}</div>
                                        <div class="text-[10px] text-gray-500">ID: {{ $user->id }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="py-1 px-4 text-[11px] text-gray-600">{{ $user->username }}</td>
                            <td class="py-1 px-4 text-[11px] text-gray-600">{{ $user->email }}</td>
                            <td class="py-1 px-4 text-[11px]">
                                @if($user->firm)
                                    <div class="flex items-center">
                                        <span class="material-icons text-xs text-blue-600 mr-1">business</span>
                                        <span class="text-gray-900">{{ $user->firm->name }}</span>
                                    </div>
                                @else
                                    <span class="text-gray-400 italic">No firm assigned</span>
                                @endif
                            </td>
                            <td class="py-1 px-4 text-[11px]">
                                <div class="flex flex-wrap gap-1">
                                    @if($user->roles->count() > 0)
                                        @foreach($user->roles->take(3) as $role)
                                            <span class="inline-block bg-purple-100 text-purple-800 px-1.5 py-0.5 rounded text-[10px]">{{ $role->name }}</span>
                                        @endforeach
                                        @if($user->roles->count() > 3)
                                            <span class="inline-block bg-gray-100 text-gray-800 px-1.5 py-0.5 rounded text-[10px]">+{{ $user->roles->count() - 3 }} more</span>
                                        @endif
                                    @else
                                        <span class="inline-block bg-gray-100 text-gray-800 px-1.5 py-0.5 rounded text-[10px]">No roles</span>
                                    @endif
                                </div>
                            </td>
                            <td class="py-1 px-4 text-[11px]">
                                @if($user->email_verified_at)
                                    <span class="inline-block bg-green-100 text-green-800 px-1.5 py-0.5 rounded-full text-[10px]">Verified</span>
                                @else
                                    <span class="inline-block bg-yellow-100 text-yellow-800 px-1.5 py-0.5 rounded-full text-[10px]">Pending</span>
                                @endif
                            </td>
                            <td class="py-1 px-4 text-[11px] text-gray-500">
                                {{ $user->last_login_at ? $user->last_login_at->diffForHumans() : 'Never' }}
                            </td>
                            <td class="py-1 px-4">
                                <div class="flex justify-center space-x-1 items-center">
                                    <a href="{{ route('settings.user.show', $user->id) }}" class="p-0.5 text-blue-600 hover:text-blue-700" title="View">
                                        <span class="material-icons text-base">visibility</span>
                                    </a>
                                    <a href="{{ route('settings.user.edit', $user->id) }}" class="p-0.5 text-yellow-600 hover:text-yellow-700" title="Edit">
                                        <span class="material-icons text-base">edit</span>
                                    </a>
                                    <button onclick="deleteUser({{ $user->id }})" class="p-0.5 text-red-600 hover:text-red-700" title="Delete">
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
        <div class="md:hidden p-4 space-y-4">
            @foreach($users as $user)
            <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                <div class="flex items-center justify-between mb-3">
                    <div class="flex items-center">
                        <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center mr-3">
                            <span class="text-sm font-medium text-blue-600">{{ substr($user->name, 0, 1) }}</span>
                        </div>
                        <div>
                            <div class="text-sm font-medium text-gray-900">{{ $user->name }}</div>
                            <div class="text-xs text-gray-500">{{ $user->username }}</div>
                            <div class="text-xs text-gray-400">{{ $user->email }}</div>
                        </div>
                    </div>
                    <div class="flex space-x-1">
                        <a href="{{ route('settings.user.show', $user->id) }}" class="p-2 bg-blue-50 rounded hover:bg-blue-100">
                            <span class="material-icons text-blue-700 text-sm">visibility</span>
                        </a>
                        <a href="{{ route('settings.user.edit', $user->id) }}" class="p-2 bg-yellow-50 rounded hover:bg-yellow-100">
                            <span class="material-icons text-yellow-700 text-sm">edit</span>
                        </a>
                        <button onclick="deleteUser({{ $user->id }})" class="p-2 bg-red-50 rounded hover:bg-red-100">
                            <span class="material-icons text-red-600 text-sm">delete</span>
                        </button>
                    </div>
                </div>
                
                <div class="grid grid-cols-2 gap-3 text-xs">
                    <div>
                        <span class="text-gray-500">Roles:</span>
                        <div class="flex flex-wrap gap-1 mt-1">
                            @if($user->roles->count() > 0)
                                @foreach($user->roles->take(2) as $role)
                                    <span class="inline-block bg-purple-100 text-purple-800 px-2 py-1 rounded text-xs">{{ $role->name }}</span>
                                @endforeach
                                @if($user->roles->count() > 2)
                                    <span class="inline-block bg-gray-100 text-gray-800 px-2 py-1 rounded text-xs">+{{ $user->roles->count() - 2 }} more</span>
                                @endif
                            @else
                                <span class="inline-block bg-gray-100 text-gray-800 px-2 py-1 rounded text-xs">No roles</span>
                            @endif
                        </div>
                    </div>
                    <div>
                        <span class="text-gray-500">Status:</span>
                        <div class="mt-1">
                            @if($user->email_verified_at)
                                <span class="inline-block bg-green-100 text-green-800 px-2 py-1 rounded-full text-xs">Verified</span>
                            @else
                                <span class="inline-block bg-yellow-100 text-yellow-800 px-2 py-1 rounded-full text-xs">Pending</span>
                            @endif
                        </div>
                    </div>
                </div>
                
                <div class="mt-3 text-xs text-gray-500">
                    Last login: {{ $user->last_login_at ? $user->last_login_at->diffForHumans() : 'Never' }}
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>

<script>
function deleteUser(userId) {
    if (confirm('Are you sure you want to delete this user? This action cannot be undone.')) {
        // Create a form and submit it
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/settings/user/${userId}`;
        
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
let allUsers = [];
let filteredUsers = [];

// Initialize pagination
function initializePagination() {
    const userRows = document.querySelectorAll('tbody tr');
    allUsers = Array.from(userRows).map((row, index) => ({
        id: index,
        element: row,
        searchText: row.textContent.toLowerCase()
    }));

    filteredUsers = [...allUsers];
    displayUsers();
    updatePagination();
}

function displayUsers() {
    const startIndex = (currentPage - 1) * perPage;
    const endIndex = startIndex + perPage;

    allUsers.forEach(user => {
        if (user.element) user.element.style.display = 'none';
    });

    const usersToShow = filteredUsers.slice(startIndex, endIndex);
    usersToShow.forEach(user => {
        if (user.element) user.element.style.display = '';
    });
}

function updatePagination() {
    const totalItems = filteredUsers.length;
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
    const newPerPage = parseInt(document.getElementById('perPage')?.value || 25);

    if (document.getElementById('perPage')) document.getElementById('perPage').value = newPerPage;

    perPage = newPerPage;
    currentPage = 1;
    displayUsers();
    updatePagination();
}

function filterUsers() {
    const searchTerm = (document.getElementById('searchFilter')?.value || '').toLowerCase();
    const statusFilter = (document.getElementById('statusFilter')?.value || '');
    const firmFilter = (document.getElementById('firmFilter')?.value || '');

    filteredUsers = allUsers.filter(user => {
        const matchesSearch = searchTerm === '' || user.searchText.includes(searchTerm);
        const matchesStatus = statusFilter === '' || user.searchText.includes(statusFilter.toLowerCase());
        const matchesFirm = firmFilter === '' || user.searchText.includes(firmFilter.toLowerCase());

        return matchesSearch && matchesStatus && matchesFirm;
    });

    currentPage = 1;
    displayUsers();
    updatePagination();
}

function resetFilters() {
    if (document.getElementById('searchFilter')) document.getElementById('searchFilter').value = '';
    if (document.getElementById('statusFilter')) document.getElementById('statusFilter').value = '';
    if (document.getElementById('firmFilter')) document.getElementById('firmFilter').value = '';

    filteredUsers = [...allUsers];
    currentPage = 1;
    displayUsers();
    updatePagination();
}

function previousPage() {
    if (currentPage > 1) {
        currentPage--;
        displayUsers();
        updatePagination();
    }
}

function nextPage() {
    const totalPages = Math.ceil(filteredUsers.length / perPage);
    if (currentPage < totalPages) {
        currentPage++;
        displayUsers();
        updatePagination();
    }
}

function firstPage() {
    currentPage = 1;
    displayUsers();
    updatePagination();
}

function lastPage() {
    const totalPages = Math.ceil(filteredUsers.length / perPage);
    currentPage = totalPages;
    displayUsers();
    updatePagination();
}

function goToPage(page) {
    currentPage = page;
    displayUsers();
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