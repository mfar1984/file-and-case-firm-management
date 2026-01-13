@extends('layouts.app')

@section('breadcrumb')
    Partner
@endsection

@section('content')
<div class="px-4 md:px-6 pt-4 md:pt-6 pb-6 max-w-7xl mx-auto">
    <div class="bg-white rounded shadow-md border border-gray-300">
        <div class="p-4 md:p-6 border-b border-gray-200">
            <div class="flex flex-col md:flex-row md:justify-between md:items-start space-y-4 md:space-y-0">
                <div>
                    <div class="flex items-center">
                        <span class="material-icons mr-2 text-orange-600">business</span>
                        <h1 class="text-lg md:text-xl font-bold text-gray-800">Partner Management</h1>
                    </div>
                    <p class="text-xs text-gray-500 mt-1 ml-8">Manage all partner information and case assignments.</p>
                </div>
                
                <!-- Add Partner Button -->
                <a href="{{ route('partner.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 md:px-3 md:py-1 rounded-md text-sm md:text-xs font-medium flex items-center justify-center md:justify-start w-full md:w-auto">
                    <span class="material-icons text-sm md:text-xs mr-1">add</span>
                    Add Partner
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
                    <input type="text" id="searchFilter" placeholder="Search partners..."
                           onkeyup="filterPartners()"
                           class="border border-gray-300 rounded px-3 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white w-64">

                    <select id="statusFilter" onchange="filterPartners()" class="custom-select border border-gray-300 rounded pl-4 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white">
                        <option value="">All Status</option>
                        <option value="Active">Active</option>
                        <option value="Inactive">Inactive</option>
                    </select>

                    <button onclick="filterPartners()" class="px-3 py-2 bg-blue-500 text-white rounded text-xs hover:bg-blue-600 transition-colors">
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
                            <th class="py-3 px-4 text-left rounded-tl">Partner ID</th>
                            <th class="py-3 px-4 text-left">Name</th>
                            <th class="py-3 px-4 text-left">Phone</th>
                            <th class="py-3 px-4 text-left">Email</th>
                            <th class="py-3 px-4 text-left">Specialization</th>
                            <th class="py-3 px-4 text-left">Active Cases</th>
                            <th class="py-3 px-4 text-left">Operational Status</th>
                            <th class="py-3 px-4 text-center rounded-tr">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($partners as $partner)
                        <tr class="hover:bg-gray-50">
                            <td class="py-1 px-4 text-[11px] font-medium">{{ $partner->partner_code }}</td>
                            <td class="py-1 px-4 text-[11px]">{{ $partner->firm_name }}</td>
                            <td class="py-1 px-4 text-[11px]">{{ $partner->contact_no }}</td>
                            <td class="py-1 px-4 text-[11px]">{{ $partner->email ?? '-' }}</td>
                            <td class="py-1 px-4 text-[11px]">{{ $partner->specialization ? ucfirst($partner->specialization) . ' Law' : '-' }}</td>
                            <td class="py-1 px-4 text-[11px]">0</td>
                            <td class="py-1 px-4 text-[11px]">
                                <span class="inline-block {{ $partner->status_badge_color }} px-1.5 py-0.5 rounded-full text-[10px]">{{ ucfirst($partner->status) }}</span>
                            </td>
                            <td class="py-1 px-4">
                                <div class="flex justify-center space-x-1 items-center">
                                    <form action="{{ route('partner.toggle-ban', $partner->id) }}" method="POST">
                                        @csrf
                                        <button class="p-0.5 {{ $partner->is_banned ? 'text-green-600 hover:text-green-700' : 'text-red-600 hover:text-red-700' }}" title="{{ $partner->is_banned ? 'Unban' : 'Ban' }}">
                                            <span class="material-icons text-base">block</span>
                                        </button>
                                    </form>
                                    <a href="{{ route('partner.show', $partner->id) }}" class="p-0.5 text-blue-600 hover:text-blue-700" title="View">
                                        <span class="material-icons text-base">visibility</span>
                                    </a>
                                    <a href="{{ route('partner.edit', $partner->id) }}" class="p-0.5 text-yellow-600 hover:text-yellow-700" title="Edit">
                                        <span class="material-icons text-base">edit</span>
                                    </a>
                                    <form action="{{ route('partner.destroy', $partner->id) }}" method="POST" onsubmit="return confirm('Delete this partner?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="p-0.5 text-red-600 hover:text-red-700" title="Delete">
                                            <span class="material-icons text-base">delete</span>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="py-8 text-center text-gray-500">
                                <div class="flex flex-col items-center">
                                    <span class="material-icons text-gray-400 text-4xl mb-2">business</span>
                                    <p class="text-sm text-gray-500">No partners available</p>
                                    <p class="text-xs text-gray-400">Add partners to manage legal collaborations</p>
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
                    <input type="text" id="searchFilterMobile" placeholder="Search partners..."
                           onkeyup="filterPartners()"
                           class="w-full border border-gray-300 rounded px-3 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white">

                    <div class="flex gap-2">
                        <select id="statusFilterMobile" onchange="filterPartners()" class="custom-select flex-1 border border-gray-300 rounded pl-4 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white">
                            <option value="">All Status</option>
                            <option value="Active">Active</option>
                            <option value="Inactive">Inactive</option>
                        </select>

                        <button onclick="filterPartners()" class="px-3 py-2 bg-blue-500 text-white rounded text-xs hover:bg-blue-600 transition-colors">
                            🔍
                        </button>

                        <button onclick="resetFilters()" class="px-3 py-2 bg-gray-500 text-white rounded text-xs hover:bg-gray-600 transition-colors">
                            🔄
                        </button>
                    </div>
                </div>
            </div>

            <div class="space-y-4" id="partners-mobile-container">
            <!-- Partner Card 1 -->
            <div class="bg-gray-50 rounded-lg border border-gray-200 p-4">
                <div class="flex justify-between items-start mb-3">
                    <div>
                        <h3 class="font-semibold text-gray-800 text-sm">P-001</h3>
                        <p class="text-xs text-gray-600">A. Rahman</p>
                    </div>
                    <span class="inline-block bg-green-100 text-green-800 px-2 py-1 rounded-full text-xs">Active</span>
                </div>
                
                <div class="space-y-2 mb-4">
                    <div class="flex justify-between">
                        <span class="text-xs text-gray-500">Phone:</span>
                        <span class="text-xs font-medium">+60 12-345 6789</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-xs text-gray-500">Email:</span>
                        <span class="text-xs font-medium truncate">a.rahman@naaelahsaleh.my</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-xs text-gray-500">Specialization:</span>
                        <span class="text-xs font-medium">Civil Law</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-xs text-gray-500">Active Cases:</span>
                        <span class="text-xs font-medium">12</span>
                    </div>
                </div>
                
                <!-- Mobile Action Buttons -->
                <div class="flex space-x-2">
                    <button class="flex-1 bg-red-100 text-red-700 py-2 px-3 rounded-md text-xs font-medium flex items-center justify-center">
                        <span class="material-icons text-sm mr-1">block</span>
                        Ban
                    </button>
                    <a href="#" class="flex-1 bg-blue-100 text-blue-700 py-2 px-3 rounded-md text-xs font-medium flex items-center justify-center">
                        <span class="material-icons text-sm mr-1">visibility</span>
                        View
                    </a>
                    <a href="#" class="flex-1 bg-yellow-100 text-yellow-700 py-2 px-3 rounded-md text-xs font-medium flex items-center justify-center">
                        <span class="material-icons text-sm mr-1">edit</span>
                        Edit
                    </a>
                    <button class="flex-1 bg-red-100 text-red-700 py-2 px-3 rounded-md text-xs font-medium flex items-center justify-center">
                        <span class="material-icons text-sm mr-1">delete</span>
                        Delete
                    </button>
                </div>
            </div>
            
            <!-- Partner Card 2 -->
            <div class="bg-gray-50 rounded-lg border border-gray-200 p-4">
                <div class="flex justify-between items-start mb-3">
                    <div>
                        <h3 class="font-semibold text-gray-800 text-sm">P-002</h3>
                        <p class="text-xs text-gray-600">S. Kumar</p>
                    </div>
                    <span class="inline-block bg-green-100 text-green-800 px-2 py-1 rounded-full text-xs">Active</span>
                </div>
                
                <div class="space-y-2 mb-4">
                    <div class="flex justify-between">
                        <span class="text-xs text-gray-500">Phone:</span>
                        <span class="text-xs font-medium">+60 12-987 6543</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-xs text-gray-500">Email:</span>
                        <span class="text-xs font-medium truncate">s.kumar@naaelahsaleh.my</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-xs text-gray-500">Specialization:</span>
                        <span class="text-xs font-medium">Criminal Law</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-xs text-gray-500">Active Cases:</span>
                        <span class="text-xs font-medium">8</span>
                    </div>
                </div>
                
                <!-- Mobile Action Buttons -->
                <div class="flex space-x-2">
                    <button class="flex-1 bg-red-100 text-red-700 py-2 px-3 rounded-md text-xs font-medium flex items-center justify-center">
                        <span class="material-icons text-sm mr-1">block</span>
                        Ban
                    </button>
                    <a href="#" class="flex-1 bg-blue-100 text-blue-700 py-2 px-3 rounded-md text-xs font-medium flex items-center justify-center">
                        <span class="material-icons text-sm mr-1">visibility</span>
                        View
                    </a>
                    <a href="#" class="flex-1 bg-yellow-100 text-yellow-700 py-2 px-3 rounded-md text-xs font-medium flex items-center justify-center">
                        <span class="material-icons text-sm mr-1">edit</span>
                        Edit
                    </a>
                    <button class="flex-1 bg-red-100 text-red-700 py-2 px-3 rounded-md text-xs font-medium flex items-center justify-center">
                        <span class="material-icons text-sm mr-1">delete</span>
                        Delete
                    </button>
                </div>
            </div>
            
            <!-- Partner Card 3 -->
            <div class="bg-gray-50 rounded-lg border border-gray-200 p-4">
                <div class="flex justify-between items-start mb-3">
                    <div>
                        <h3 class="font-semibold text-gray-800 text-sm">P-003</h3>
                        <p class="text-xs text-gray-600">M. Lim</p>
                    </div>
                    <span class="inline-block bg-green-100 text-green-800 px-2 py-1 rounded-full text-xs">Active</span>
                </div>
                
                <div class="space-y-2 mb-4">
                    <div class="flex justify-between">
                        <span class="text-xs text-gray-500">Phone:</span>
                        <span class="text-xs font-medium">+60 12-456 7890</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-xs text-gray-500">Email:</span>
                        <span class="text-xs font-medium truncate">m.lim@naaelahsaleh.my</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-xs text-gray-500">Specialization:</span>
                        <span class="text-xs font-medium">Corporate Law</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-xs text-gray-500">Active Cases:</span>
                        <span class="text-xs font-medium">15</span>
                    </div>
                </div>
                
                <!-- Mobile Action Buttons -->
                <div class="flex space-x-2">
                    <button class="flex-1 bg-red-100 text-red-700 py-2 px-3 rounded-md text-xs font-medium flex items-center justify-center">
                        <span class="material-icons text-sm mr-1">block</span>
                        Ban
                    </button>
                    <a href="#" class="flex-1 bg-blue-100 text-blue-700 py-2 px-3 rounded-md text-xs font-medium flex items-center justify-center">
                        <span class="material-icons text-sm mr-1">visibility</span>
                        View
                    </a>
                    <a href="#" class="flex-1 bg-yellow-100 text-yellow-700 py-2 px-3 rounded-md text-xs font-medium flex items-center justify-center">
                        <span class="material-icons text-sm mr-1">edit</span>
                        Edit
                    </a>
                    <button class="flex-1 bg-red-100 text-red-700 py-2 px-3 rounded-md text-xs font-medium flex items-center justify-center">
                        <span class="material-icons text-sm mr-1">delete</span>
                        Delete
                    </button>
                </div>
            </div>
            
            <!-- Partner Card 4 -->
            <div class="bg-gray-50 rounded-lg border border-gray-200 p-4">
                <div class="flex justify-between items-start mb-3">
                    <div>
                        <h3 class="font-semibold text-gray-800 text-sm">P-004</h3>
                        <p class="text-xs text-gray-600">N. Tan</p>
                    </div>
                    <span class="inline-block bg-red-100 text-red-800 px-2 py-1 rounded-full text-xs">Inactive</span>
                </div>
                
                <div class="space-y-2 mb-4">
                    <div class="flex justify-between">
                        <span class="text-xs text-gray-500">Phone:</span>
                        <span class="text-xs font-medium">+60 12-789 0123</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-xs text-gray-500">Email:</span>
                        <span class="text-xs font-medium truncate">n.tan@naaelahsaleh.my</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-xs text-gray-500">Specialization:</span>
                        <span class="text-xs font-medium">Family Law</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-xs text-gray-500">Active Cases:</span>
                        <span class="text-xs font-medium">6</span>
                    </div>
                </div>
                
                <!-- Mobile Action Buttons -->
                <div class="flex space-x-2">
                    <button class="flex-1 bg-red-100 text-red-700 py-2 px-3 rounded-md text-xs font-medium flex items-center justify-center">
                        <span class="material-icons text-sm mr-1">block</span>
                        Ban
                    </button>
                    <a href="#" class="flex-1 bg-blue-100 text-blue-700 py-2 px-3 rounded-md text-xs font-medium flex items-center justify-center">
                        <span class="material-icons text-sm mr-1">visibility</span>
                        View
                    </a>
                    <a href="#" class="flex-1 bg-yellow-100 text-yellow-700 py-2 px-3 rounded-md text-xs font-medium flex items-center justify-center">
                        <span class="material-icons text-sm mr-1">edit</span>
                        Edit
                    </a>
                    <button class="flex-1 bg-red-100 text-red-700 py-2 px-3 rounded-md text-xs font-medium flex items-center justify-center">
                        <span class="material-icons text-sm mr-1">delete</span>
                        Delete
                    </button>
                </div>
            </div>
            
            <!-- Partner Card 5 -->
            <div class="bg-gray-50 rounded-lg border border-gray-200 p-4">
                <div class="flex justify-between items-start mb-3">
                    <div>
                        <h3 class="font-semibold text-gray-800 text-sm">P-005</h3>
                        <p class="text-xs text-gray-600">K. Wong</p>
                    </div>
                    <span class="inline-block bg-green-100 text-green-800 px-2 py-1 rounded-full text-xs">Active</span>
                </div>
                
                <div class="space-y-2 mb-4">
                    <div class="flex justify-between">
                        <span class="text-xs text-gray-500">Phone:</span>
                        <span class="text-xs font-medium">+60 12-321 6540</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-xs text-gray-500">Email:</span>
                        <span class="text-xs font-medium truncate">k.wong@naaelahsaleh.my</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-xs text-gray-500">Specialization:</span>
                        <span class="text-xs font-medium">Property Law</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-xs text-gray-500">Active Cases:</span>
                        <span class="text-xs font-medium">10</span>
                    </div>
                </div>
                
                <!-- Mobile Action Buttons -->
                <div class="flex space-x-2">
                    <button class="flex-1 bg-red-100 text-red-700 py-2 px-3 rounded-md text-xs font-medium flex items-center justify-center">
                        <span class="material-icons text-sm mr-1">block</span>
                        Ban
                    </button>
                    <a href="#" class="flex-1 bg-blue-100 text-blue-700 py-2 px-3 rounded-md text-xs font-medium flex items-center justify-center">
                        <span class="material-icons text-sm mr-1">visibility</span>
                        View
                    </a>
                    <a href="#" class="flex-1 bg-yellow-100 text-yellow-700 py-2 px-3 rounded-md text-xs font-medium flex items-center justify-center">
                        <span class="material-icons text-sm mr-1">edit</span>
                        Edit
                    </a>
                    <button class="flex-1 bg-red-100 text-red-700 py-2 px-3 rounded-md text-xs font-medium flex items-center justify-center">
                        <span class="material-icons text-sm mr-1">delete</span>
                        Delete
                    </button>
                </div>
            </div>
            
            <!-- Partner Card 6 -->
            <div class="bg-gray-50 rounded-lg border border-gray-200 p-4">
                <div class="flex justify-between items-start mb-3">
                    <div>
                        <h3 class="font-semibold text-gray-800 text-sm">P-006</h3>
                        <p class="text-xs text-gray-600">R. Singh</p>
                    </div>
                    <span class="inline-block bg-green-100 text-green-800 px-2 py-1 rounded-full text-xs">Active</span>
                </div>
                
                <div class="space-y-2 mb-4">
                    <div class="flex justify-between">
                        <span class="text-xs text-gray-500">Phone:</span>
                        <span class="text-xs font-medium">+60 12-654 3210</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-xs text-gray-500">Email:</span>
                        <span class="text-xs font-medium truncate">r.singh@naaelahsaleh.my</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-xs text-gray-500">Specialization:</span>
                        <span class="text-xs font-medium">Employment Law</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-xs text-gray-500">Active Cases:</span>
                        <span class="text-xs font-medium">7</span>
                    </div>
                </div>
                
                <!-- Mobile Action Buttons -->
                <div class="flex space-x-2">
                    <button class="flex-1 bg-red-100 text-red-700 py-2 px-3 rounded-md text-xs font-medium flex items-center justify-center">
                        <span class="material-icons text-sm mr-1">block</span>
                        Ban
                    </button>
                    <a href="#" class="flex-1 bg-blue-100 text-blue-700 py-2 px-3 rounded-md text-xs font-medium flex items-center justify-center">
                        <span class="material-icons text-sm mr-1">visibility</span>
                        View
                    </a>
                    <a href="#" class="flex-1 bg-yellow-100 text-yellow-700 py-2 px-3 rounded-md text-xs font-medium flex items-center justify-center">
                        <span class="material-icons text-sm mr-1">edit</span>
                        Edit
                    </a>
                    <button class="flex-1 bg-red-100 text-red-700 py-2 px-3 rounded-md text-xs font-medium flex items-center justify-center">
                        <span class="material-icons text-sm mr-1">delete</span>
                        Delete
                    </button>
                </div>
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
let allPartners = [];
let filteredPartners = [];

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    // Get all partner data from the page
    const partnerRows = document.querySelectorAll('tbody tr');
    allPartners = Array.from(partnerRows).map((row, index) => ({
        id: index,
        element: row,
        searchText: row.textContent.toLowerCase()
    }));

    filteredPartners = [...allPartners];
    displayPartners();
    updatePagination();
});

// Display functions
function displayPartners() {
    const startIndex = (currentPage - 1) * perPage;
    const endIndex = startIndex + perPage;

    // Hide all partners first
    allPartners.forEach(partner => {
        if (partner.element) partner.element.style.display = 'none';
    });

    // Show filtered partners for current page
    const partnersToShow = filteredPartners.slice(startIndex, endIndex);
    partnersToShow.forEach(partner => {
        if (partner.element) partner.element.style.display = '';
    });
}

function updatePagination() {
    const totalItems = filteredPartners.length;
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
    displayPartners();
    updatePagination();
}

// Filter Functions
function filterPartners() {
    const searchTerm = (document.getElementById('searchFilter')?.value ||
                       document.getElementById('searchFilterMobile')?.value || '').toLowerCase();
    const statusFilter = (document.getElementById('statusFilter')?.value ||
                        document.getElementById('statusFilterMobile')?.value || '');

    // Sync values between desktop and mobile
    if (document.getElementById('searchFilter')) document.getElementById('searchFilter').value = searchTerm;
    if (document.getElementById('searchFilterMobile')) document.getElementById('searchFilterMobile').value = searchTerm;
    if (document.getElementById('statusFilter')) document.getElementById('statusFilter').value = statusFilter;
    if (document.getElementById('statusFilterMobile')) document.getElementById('statusFilterMobile').value = statusFilter;

    filteredPartners = allPartners.filter(partner => {
        const matchesSearch = searchTerm === '' || partner.searchText.includes(searchTerm);
        const matchesStatus = statusFilter === '' || partner.searchText.includes(statusFilter.toLowerCase());

        return matchesSearch && matchesStatus;
    });

    currentPage = 1;
    displayPartners();
    updatePagination();
}

function resetFilters() {
    if (document.getElementById('searchFilter')) document.getElementById('searchFilter').value = '';
    if (document.getElementById('searchFilterMobile')) document.getElementById('searchFilterMobile').value = '';
    if (document.getElementById('statusFilter')) document.getElementById('statusFilter').value = '';
    if (document.getElementById('statusFilterMobile')) document.getElementById('statusFilterMobile').value = '';

    filteredPartners = [...allPartners];
    currentPage = 1;
    displayPartners();
    updatePagination();
}

function previousPage() {
    if (currentPage > 1) {
        currentPage--;
        displayPartners();
        updatePagination();
    }
}

function nextPage() {
    const totalPages = Math.ceil(filteredPartners.length / perPage);
    if (currentPage < totalPages) {
        currentPage++;
        displayPartners();
        updatePagination();
    }
}

function firstPage() {
    currentPage = 1;
    displayPartners();
    updatePagination();
}

function lastPage() {
    const totalPages = Math.ceil(filteredPartners.length / perPage);
    currentPage = totalPages;
    displayPartners();
    updatePagination();
}

function goToPage(page) {
    currentPage = page;
    displayPartners();
    updatePagination();
}
</script>

@endsection