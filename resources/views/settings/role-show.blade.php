@extends('layouts.app')

@section('breadcrumb')
    Settings > Role Management > View Role
@endsection

@section('content')
<div class="px-4 md:px-6 pt-4 md:pt-6 pb-6 max-w-7xl mx-auto">
    <div class="bg-white rounded shadow-md border border-gray-300">
        <div class="p-4 md:p-6 border-b border-gray-200">
            <div class="flex justify-between items-start">
                <div>
                    <div class="flex items-center">
                        <span class="material-icons mr-2 text-purple-600">visibility</span>
                        <h1 class="text-lg md:text-xl font-bold text-gray-800">View Role: {{ $role->name }}</h1>
                    </div>
                    <p class="text-xs text-gray-500 mt-1 ml-8">View role details, permissions, and assigned users.</p>
                </div>
                <div class="flex space-x-2">
                    <a href="{{ route('settings.role.edit', $role->id) }}" class="bg-yellow-600 hover:bg-yellow-700 text-white px-4 py-1.5 rounded-md text-xs font-medium flex items-center">
                        <span class="material-icons text-xs mr-1">edit</span>
                        Edit Role
                    </a>
                    <a href="{{ route('settings.role') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-1.5 rounded-md text-xs font-medium flex items-center">
                        <span class="material-icons text-xs mr-1">arrow_back</span>
                        Back to Roles
                    </a>
                </div>
            </div>
        </div>
        
        <div class="p-4 md:p-6">
            <!-- Role Information Section -->
            <div class="bg-gray-50 p-4 rounded-lg mb-6">
                <h3 class="text-sm font-semibold text-gray-700 mb-4">Role Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-2">Role Name</label>
                        <p class="text-xs text-gray-900 font-medium">{{ $role->name }}</p>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-2">Description</label>
                        <p class="text-xs text-gray-900">{{ $role->description ?? 'No description provided' }}</p>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-2">Users Count</label>
                        <p class="text-xs text-gray-900 font-medium">{{ $roleUserCount }} users</p>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-2">Created Date</label>
                        <p class="text-xs text-gray-900">{{ $role->created_at->format('M d, Y H:i') }}</p>
                    </div>
                </div>
            </div>

            <!-- Permissions Section -->
            <div class="bg-gray-50 p-4 rounded-lg mb-6">
                <h3 class="text-sm font-semibold text-gray-700 mb-4">Assigned Permissions ({{ $role->permissions->count() }})</h3>
                <p class="text-xs text-gray-600 mb-4">Permissions currently assigned to this role:</p>
                
                @if($role->permissions->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full border border-gray-200 rounded-lg">
                            <thead>
                                <tr class="bg-gray-50">
                                    <th class="px-4 py-1.5 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b border-gray-200">Category</th>
                                    <th class="px-4 py-1.5 text-center text-xs font-medium text-gray-500 uppercase tracking-wider border-b border-gray-200">Create</th>
                                    <th class="px-4 py-1.5 text-center text-xs font-medium text-gray-500 uppercase tracking-wider border-b border-gray-200">Read</th>
                                    <th class="px-4 py-1.5 text-center text-xs font-medium text-gray-500 uppercase tracking-wider border-b border-gray-200">Edit</th>
                                    <th class="px-4 py-1.5 text-center text-xs font-medium text-gray-500 uppercase tracking-wider border-b border-gray-200">Delete</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <!-- Overview -->
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-1.5 text-xs font-medium text-gray-900 border-r border-gray-200">
                                        <div class="flex items-center">
                                            <span class="material-icons text-blue-500 mr-2 text-sm">dashboard</span>
                                            Overview
                                        </div>
                                    </td>
                                    <td class="px-4 py-1.5 text-center border-r border-gray-200">
                                        <span class="text-gray-400 text-xs">-</span>
                                    </td>
                                    <td class="px-4 py-1.5 text-center border-r border-gray-200">
                                        @if($role->hasPermissionTo('view-overview'))
                                            <span class="text-green-600 text-lg">✓</span>
                                        @else
                                            <span class="text-gray-400 text-xs">-</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-1.5 text-center border-r border-gray-200">
                                        <span class="text-gray-400 text-xs">-</span>
                                    </td>
                                    <td class="px-4 py-1.5 text-center">
                                        <span class="text-gray-400 text-xs">-</span>
                                    </td>
                                </tr>

                                <!-- Calendar -->
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-1.5 text-xs font-medium text-gray-900 border-r border-gray-200">
                                        <div class="flex items-center">
                                            <span class="material-icons text-green-500 mr-2 text-sm">calendar_today</span>
                                            Calendar
                                        </div>
                                    </td>
                                    <td class="px-4 py-1.5 text-center border-r border-gray-200">
                                        @if($role->hasPermissionTo('create-events'))
                                            <span class="text-green-600 text-lg">✓</span>
                                        @else
                                            <span class="text-gray-400 text-xs">-</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-1.5 text-center border-r border-gray-200">
                                        @if($role->hasPermissionTo('view-calendar'))
                                            <span class="text-green-600 text-lg">✓</span>
                                        @else
                                            <span class="text-gray-400 text-xs">-</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-1.5 text-center border-r border-gray-200">
                                        @if($role->hasPermissionTo('edit-events'))
                                            <span class="text-green-600 text-lg">✓</span>
                                        @else
                                            <span class="text-gray-400 text-xs">-</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-1.5 text-center">
                                        @if($role->hasPermissionTo('delete-events'))
                                            <span class="text-green-600 text-lg">✓</span>
                                        @else
                                            <span class="text-gray-400 text-xs">-</span>
                                        @endif
                                    </td>
                                </tr>

                                <!-- Case Management - Main Category -->
                                <tr class="hover:bg-gray-50 bg-gray-100">
                                    <td class="px-4 py-1.5 text-xs font-semibold text-gray-900 border-r border-gray-200">
                                        <div class="flex items-center">
                                            <span class="material-icons text-purple-500 mr-2 text-sm">folder</span>
                                            Case Management
                                        </div>
                                    </td>
                                    <td class="px-4 py-1.5 text-center border-r border-gray-200">
                                        <span class="text-gray-400 text-xs">-</span>
                                    </td>
                                    <td class="px-4 py-1.5 text-center border-r border-gray-200">
                                        <span class="text-gray-400 text-xs">-</span>
                                    </td>
                                    <td class="px-4 py-1.5 text-center border-r border-gray-200">
                                        <span class="text-gray-400 text-xs">-</span>
                                    </td>
                                    <td class="px-4 py-1.5 text-center">
                                        <span class="text-gray-400 text-xs">-</span>
                                    </td>
                                </tr>

                                <!-- Case -->
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-1.5 text-xs font-medium text-gray-700 border-r border-gray-200 pl-8">
                                        <div class="flex items-center">
                                            <span class="material-icons text-purple-400 mr-2 text-xs">description</span>
                                            • Case
                                        </div>
                                    </td>
                                    <td class="px-4 py-1.5 text-center border-r border-gray-200">
                                        @if($role->hasPermissionTo('create-cases'))
                                            <span class="text-green-600 text-lg">✓</span>
                                        @else
                                            <span class="text-gray-400 text-xs">-</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-1.5 text-center border-r border-gray-200">
                                        @if($role->hasPermissionTo('view-cases'))
                                            <span class="text-green-600 text-lg">✓</span>
                                        @else
                                            <span class="text-gray-400 text-xs">-</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-1.5 text-center border-r border-gray-200">
                                        @if($role->hasPermissionTo('edit-cases'))
                                            <span class="text-green-600 text-lg">✓</span>
                                        @else
                                            <span class="text-gray-400 text-xs">-</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-1.5 text-center">
                                        @if($role->hasPermissionTo('delete-cases'))
                                            <span class="text-green-600 text-lg">✓</span>
                                        @else
                                            <span class="text-gray-400 text-xs">-</span>
                                        @endif
                                    </td>
                                </tr>

                                <!-- Client Management -->
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-1.5 text-xs font-medium text-gray-700 border-r border-gray-200 pl-8">
                                        <div class="flex items-center">
                                            <span class="material-icons text-blue-400 mr-2 text-xs">people</span>
                                            • Client Management
                                        </div>
                                    </td>
                                    <td class="px-4 py-1.5 text-center border-r border-gray-200">
                                        @if($role->hasPermissionTo('create-clients'))
                                            <span class="text-green-600 text-lg">✓</span>
                                        @else
                                            <span class="text-gray-400 text-xs">-</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-1.5 text-center border-r border-gray-200">
                                        @if($role->hasPermissionTo('view-clients'))
                                            <span class="text-green-600 text-lg">✓</span>
                                        @else
                                            <span class="text-gray-400 text-xs">-</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-1.5 text-center border-r border-gray-200">
                                        @if($role->hasPermissionTo('edit-clients'))
                                            <span class="text-green-600 text-lg">✓</span>
                                        @else
                                            <span class="text-gray-400 text-xs">-</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-1.5 text-center">
                                        @if($role->hasPermissionTo('delete-clients'))
                                            <span class="text-green-600 text-lg">✓</span>
                                        @else
                                            <span class="text-gray-400 text-xs">-</span>
                                        @endif
                                    </td>
                                </tr>

                                <!-- Partner Management -->
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-1.5 text-xs font-medium text-gray-700 border-r border-gray-200 pl-8">
                                        <div class="flex items-center">
                                            <span class="material-icons text-indigo-400 mr-2 text-xs">business</span>
                                            • Partner Management
                                        </div>
                                    </td>
                                    <td class="px-4 py-1.5 text-center border-r border-gray-200">
                                        @if($role->hasPermissionTo('create-partners'))
                                            <span class="text-green-600 text-lg">✓</span>
                                        @else
                                            <span class="text-gray-400 text-xs">-</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-1.5 text-center border-r border-gray-200">
                                        @if($role->hasPermissionTo('view-partners'))
                                            <span class="text-green-600 text-lg">✓</span>
                                        @else
                                            <span class="text-gray-400 text-xs">-</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-1.5 text-center border-r border-gray-200">
                                        @if($role->hasPermissionTo('edit-partners'))
                                            <span class="text-green-600 text-lg">✓</span>
                                        @else
                                            <span class="text-gray-400 text-xs">-</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-1.5 text-center">
                                        @if($role->hasPermissionTo('delete-partners'))
                                            <span class="text-green-600 text-lg">✓</span>
                                        @else
                                            <span class="text-gray-400 text-xs">-</span>
                                        @endif
                                    </td>
                                </tr>

                                <!-- File Management -->
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-1.5 text-xs font-medium text-gray-900 border-r border-gray-200">
                                        <div class="flex items-center">
                                            <span class="material-icons text-orange-500 mr-2 text-sm">folder_open</span>
                                            File Management
                                        </div>
                                    </td>
                                    <td class="px-4 py-1.5 text-center border-r border-gray-200">
                                        @if($role->hasPermissionTo('upload-files'))
                                            <span class="text-green-600 text-lg">✓</span>
                                        @else
                                            <span class="text-gray-400 text-xs">-</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-1.5 text-center border-r border-gray-200">
                                        @if($role->hasPermissionTo('view-files'))
                                            <span class="text-green-600 text-lg">✓</span>
                                        @else
                                            <span class="text-gray-400 text-xs">-</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-1.5 text-center border-r border-gray-200">
                                        <span class="text-gray-400 text-xs">-</span>
                                    </td>
                                    <td class="px-4 py-1.5 text-center">
                                        @if($role->hasPermissionTo('delete-files'))
                                            <span class="text-green-600 text-lg">✓</span>
                                        @else
                                            <span class="text-gray-400 text-xs">-</span>
                                        @endif
                                    </td>
                                </tr>

                                <!-- Accounting - Main Category -->
                                <tr class="hover:bg-gray-50 bg-gray-100">
                                    <td class="px-4 py-1.5 text-xs font-semibold text-gray-900 border-r border-gray-200">
                                        <div class="flex items-center">
                                            <span class="material-icons text-amber-500 mr-2 text-sm">account_balance</span>
                                            Accounting
                                        </div>
                                    </td>
                                    <td class="px-4 py-1.5 text-center border-r border-gray-200">
                                        <span class="text-gray-400 text-xs">-</span>
                                    </td>
                                    <td class="px-4 py-1.5 text-center border-r border-gray-200">
                                        <span class="text-gray-400 text-xs">-</span>
                                    </td>
                                    <td class="px-4 py-1.5 text-center border-r border-gray-200">
                                        <span class="text-gray-400 text-xs">-</span>
                                    </td>
                                    <td class="px-4 py-1.5 text-center">
                                        <span class="text-gray-400 text-xs">-</span>
                                    </td>
                                </tr>

                                <!-- Quotation -->
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-1.5 text-xs font-medium text-gray-700 border-r border-gray-200 pl-8">
                                        <div class="flex items-center">
                                            <span class="material-icons text-amber-400 mr-2 text-xs">description</span>
                                            • Quotation
                                        </div>
                                    </td>
                                    <td class="px-4 py-1.5 text-center border-r border-gray-200">
                                        @if($role->hasPermissionTo('create-quotations'))
                                            <span class="text-green-600 text-lg">✓</span>
                                        @else
                                            <span class="text-gray-400 text-xs">-</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-1.5 text-center border-r border-gray-200">
                                        @if($role->hasPermissionTo('view-accounting'))
                                            <span class="text-green-600 text-lg">✓</span>
                                        @else
                                            <span class="text-gray-400 text-xs">-</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-1.5 text-center border-r border-gray-200">
                                        @if($role->hasPermissionTo('edit-quotations'))
                                            <span class="text-green-600 text-lg">✓</span>
                                        @else
                                            <span class="text-gray-400 text-xs">-</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-1.5 text-center">
                                        @if($role->hasPermissionTo('delete-quotations'))
                                            <span class="text-green-600 text-lg">✓</span>
                                        @else
                                            <span class="text-gray-400 text-xs">-</span>
                                        @endif
                                    </td>
                                </tr>

                                <!-- Tax Invoice -->
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-1.5 text-xs font-medium text-gray-700 border-r border-gray-200 pl-8">
                                        <div class="flex items-center">
                                            <span class="material-icons text-amber-400 mr-2 text-xs">receipt_long</span>
                                            • Tax Invoice
                                        </div>
                                    </td>
                                    <td class="px-4 py-1.5 text-center border-r border-gray-200">
                                        @if($role->hasPermissionTo('create-invoices'))
                                            <span class="text-green-600 text-lg">✓</span>
                                        @else
                                            <span class="text-gray-400 text-xs">-</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-1.5 text-center border-r border-gray-200">
                                        @if($role->hasPermissionTo('view-accounting'))
                                            <span class="text-green-600 text-lg">✓</span>
                                        @else
                                            <span class="text-gray-400 text-xs">-</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-1.5 text-center border-r border-gray-200">
                                        @if($role->hasPermissionTo('edit-invoices'))
                                            <span class="text-green-600 text-lg">✓</span>
                                        @else
                                            <span class="text-gray-400 text-xs">-</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-1.5 text-center">
                                        @if($role->hasPermissionTo('delete-invoices'))
                                            <span class="text-green-600 text-lg">✓</span>
                                        @else
                                            <span class="text-gray-400 text-xs">-</span>
                                        @endif
                                    </td>
                                </tr>

                                <!-- Resit -->
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-1.5 text-xs font-medium text-gray-700 border-r border-gray-200 pl-8">
                                        <div class="flex items-center">
                                            <span class="material-icons text-amber-400 mr-2 text-xs">receipt</span>
                                            • Resit
                                        </div>
                                    </td>
                                    <td class="px-4 py-1.5 text-center border-r border-gray-200">
                                        @if($role->hasPermissionTo('create-receipts'))
                                            <span class="text-green-600 text-lg">✓</span>
                                        @else
                                            <span class="text-gray-400 text-xs">-</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-1.5 text-center border-r border-gray-200">
                                        @if($role->hasPermissionTo('view-accounting'))
                                            <span class="text-green-600 text-lg">✓</span>
                                        @else
                                            <span class="text-gray-400 text-xs">-</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-1.5 text-center border-r border-gray-200">
                                        @if($role->hasPermissionTo('edit-receipts'))
                                            <span class="text-green-600 text-lg">✓</span>
                                        @else
                                            <span class="text-gray-400 text-xs">-</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-1.5 text-center">
                                        @if($role->hasPermissionTo('delete-receipts'))
                                            <span class="text-green-600 text-lg">✓</span>
                                        @else
                                            <span class="text-gray-400 text-xs">-</span>
                                        @endif
                                    </td>
                                </tr>

                                <!-- Voucher -->
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-1.5 text-xs font-medium text-gray-700 border-r border-gray-200 pl-8">
                                        <div class="flex items-center">
                                            <span class="material-icons text-amber-400 mr-2 text-xs">confirmation_number</span>
                                            • Voucher
                                        </div>
                                    </td>
                                    <td class="px-4 py-1.5 text-center border-r border-gray-200">
                                        @if($role->hasPermissionTo('create-vouchers'))
                                            <span class="text-green-600 text-lg">✓</span>
                                        @else
                                            <span class="text-gray-400 text-xs">-</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-1.5 text-center border-r border-gray-200">
                                        @if($role->hasPermissionTo('view-accounting'))
                                            <span class="text-green-600 text-lg">✓</span>
                                        @else
                                            <span class="text-gray-400 text-xs">-</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-1.5 text-center border-r border-gray-200">
                                        @if($role->hasPermissionTo('edit-vouchers'))
                                            <span class="text-green-600 text-lg">✓</span>
                                        @else
                                            <span class="text-gray-400 text-xs">-</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-1.5 text-center">
                                        @if($role->hasPermissionTo('delete-vouchers'))
                                            <span class="text-green-600 text-lg">✓</span>
                                        @else
                                            <span class="text-gray-400 text-xs">-</span>
                                        @endif
                                    </td>
                                </tr>

                                <!-- Bills -->
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-1.5 text-xs font-medium text-gray-700 border-r border-gray-200 pl-8">
                                        <div class="flex items-center">
                                            <span class="material-icons text-amber-400 mr-2 text-xs">receipt_long</span>
                                            • Bills
                                        </div>
                                    </td>
                                    <td class="px-4 py-1.5 text-center border-r border-gray-200">
                                        @if($role->hasPermissionTo('create-bills'))
                                            <span class="text-green-600 text-lg">✓</span>
                                        @else
                                            <span class="text-gray-400 text-xs">-</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-1.5 text-center border-r border-gray-200">
                                        @if($role->hasPermissionTo('view-accounting'))
                                            <span class="text-green-600 text-lg">✓</span>
                                        @else
                                            <span class="text-gray-400 text-xs">-</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-1.5 text-center border-r border-gray-200">
                                        @if($role->hasPermissionTo('edit-bills'))
                                            <span class="text-green-600 text-lg">✓</span>
                                        @else
                                            <span class="text-gray-400 text-xs">-</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-1.5 text-center">
                                        @if($role->hasPermissionTo('delete-bills'))
                                            <span class="text-green-600 text-lg">✓</span>
                                        @else
                                            <span class="text-gray-400 text-xs">-</span>
                                        @endif
                                    </td>
                                </tr>

                                <!-- Reports -->
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-1.5 text-xs font-medium text-gray-900 border-r border-gray-200">
                                        <div class="flex items-center">
                                            <span class="material-icons text-red-500 mr-2 text-sm">assessment</span>
                                            Reports
                                        </div>
                                    </td>
                                    <td class="px-4 py-1.5 text-center border-r border-gray-200">
                                        <span class="text-gray-400 text-xs">-</span>
                                    </td>
                                    <td class="px-4 py-1.5 text-center border-r border-gray-200">
                                        @if($role->hasPermissionTo('view-reports'))
                                            <span class="text-green-600 text-lg">✓</span>
                                        @else
                                            <span class="text-gray-400 text-xs">-</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-1.5 text-center border-r border-gray-200">
                                        <span class="text-gray-400 text-xs">-</span>
                                    </td>
                                    <td class="px-4 py-1.5 text-center">
                                        @if($role->hasPermissionTo('export-reports'))
                                            <span class="text-green-600 text-lg">✓</span>
                                        @else
                                            <span class="text-gray-400 text-xs">-</span>
                                        @endif
                                    </td>
                                </tr>

                                <!-- Settings - Main Category -->
                                <tr class="hover:bg-gray-50 bg-gray-100">
                                    <td class="px-4 py-1.5 text-xs font-semibold text-gray-900 border-r border-gray-200">
                                        <div class="flex items-center">
                                            <span class="material-icons text-gray-500 mr-2 text-sm">settings</span>
                                            Settings
                                        </div>
                                    </td>
                                    <td class="px-4 py-1.5 text-center border-r border-gray-200">
                                        <span class="text-gray-400 text-xs">-</span>
                                    </td>
                                    <td class="px-4 py-1.5 text-center border-r border-gray-200">
                                        <span class="text-gray-400 text-xs">-</span>
                                    </td>
                                    <td class="px-4 py-1.5 text-center border-r border-gray-200">
                                        <span class="text-gray-400 text-xs">-</span>
                                    </td>
                                    <td class="px-4 py-1.5 text-center">
                                        <span class="text-gray-400 text-xs">-</span>
                                    </td>
                                </tr>

                                <!-- User Management -->
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-1.5 text-xs font-medium text-gray-700 border-r border-gray-200 pl-8">
                                        <div class="flex items-center">
                                            <span class="material-icons text-gray-400 mr-2 text-xs">person</span>
                                            • User Management
                                        </div>
                                    </td>
                                    <td class="px-4 py-1.5 text-center border-r border-gray-200">
                                        @if($role->hasPermissionTo('create-users'))
                                            <span class="text-green-600 text-lg">✓</span>
                                        @else
                                            <span class="text-gray-400 text-xs">-</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-1.5 text-center border-r border-gray-200">
                                        @if($role->hasPermissionTo('view-users'))
                                            <span class="text-green-600 text-lg">✓</span>
                                        @else
                                            <span class="text-gray-400 text-xs">-</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-1.5 text-center border-r border-gray-200">
                                        @if($role->hasPermissionTo('edit-users'))
                                            <span class="text-green-600 text-lg">✓</span>
                                        @else
                                            <span class="text-gray-400 text-xs">-</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-1.5 text-center">
                                        @if($role->hasPermissionTo('delete-users'))
                                            <span class="text-green-600 text-lg">✓</span>
                                        @else
                                            <span class="text-gray-400 text-xs">-</span>
                                        @endif
                                    </td>
                                </tr>

                                <!-- Role Management -->
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-1.5 text-xs font-medium text-gray-700 border-r border-gray-200 pl-8">
                                        <div class="flex items-center">
                                            <span class="material-icons text-gray-400 mr-2 text-xs">admin_panel_settings</span>
                                            • Role Management
                                        </div>
                                    </td>
                                    <td class="px-4 py-1.5 text-center border-r border-gray-200">
                                        @if($role->hasPermissionTo('manage-roles'))
                                            <span class="text-green-600 text-lg">✓</span>
                                        @else
                                            <span class="text-gray-400 text-xs">-</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-1.5 text-center border-r border-gray-200">
                                        @if($role->hasPermissionTo('manage-roles'))
                                            <span class="text-green-600 text-lg">✓</span>
                                        @else
                                            <span class="text-gray-400 text-xs">-</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-1.5 text-center border-r border-gray-200">
                                        @if($role->hasPermissionTo('manage-roles'))
                                            <span class="text-green-600 text-lg">✓</span>
                                        @else
                                            <span class="text-gray-400 text-xs">-</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-1.5 text-center">
                                        @if($role->hasPermissionTo('manage-roles'))
                                            <span class="text-green-600 text-lg">✓</span>
                                        @else
                                            <span class="text-gray-400 text-xs">-</span>
                                        @endif
                                    </td>
                                </tr>

                                <!-- Categories -->
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-1.5 text-xs font-medium text-gray-700 border-r border-gray-200 pl-8">
                                        <div class="flex items-center">
                                            <span class="material-icons text-gray-400 mr-2 text-xs">category</span>
                                            • Categories
                                        </div>
                                    </td>
                                    <td class="px-4 py-1.5 text-center border-r border-gray-200">
                                        @if($role->hasPermissionTo('manage-system-settings'))
                                            <span class="text-green-600 text-lg">✓</span>
                                        @else
                                            <span class="text-gray-400 text-xs">-</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-1.5 text-center border-r border-gray-200">
                                        @if($role->hasPermissionTo('view-settings'))
                                            <span class="text-green-600 text-lg">✓</span>
                                        @else
                                            <span class="text-gray-400 text-xs">-</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-1.5 text-center border-r border-gray-200">
                                        @if($role->hasPermissionTo('manage-system-settings'))
                                            <span class="text-green-600 text-lg">✓</span>
                                        @else
                                            <span class="text-gray-400 text-xs">-</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-1.5 text-center">
                                        @if($role->hasPermissionTo('manage-system-settings'))
                                            <span class="text-green-600 text-lg">✓</span>
                                        @else
                                            <span class="text-gray-400 text-xs">-</span>
                                        @endif
                                    </td>
                                </tr>

                                <!-- System Logs -->
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-1.5 text-xs font-medium text-gray-700 border-r border-gray-200 pl-8">
                                        <div class="flex items-center">
                                            <span class="material-icons text-gray-400 mr-2 text-xs">history</span>
                                            • System Logs
                                        </div>
                                    </td>
                                    <td class="px-4 py-1.5 text-center border-r border-gray-200">
                                        <span class="text-gray-400 text-xs">-</span>
                                    </td>
                                    <td class="px-4 py-1.5 text-center border-r border-gray-200">
                                        @if($role->hasPermissionTo('manage-system-logs'))
                                            <span class="text-green-600 text-lg">✓</span>
                                        @else
                                            <span class="text-gray-400 text-xs">-</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-1.5 text-center border-r border-gray-200">
                                        <span class="text-gray-400 text-xs">-</span>
                                    </td>
                                    <td class="px-4 py-1.5 text-center">
                                        <span class="text-gray-400 text-xs">-</span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-4">
                        <span class="material-icons text-gray-400 text-2xl mb-2">info</span>
                        <p class="text-sm text-gray-500">No permissions assigned to this role.</p>
                    </div>
                @endif
            </div>

            <!-- Users Section -->
            @if($roleUserCount > 0)
            <div class="bg-gray-50 p-4 rounded-lg mb-6">
                <h3 class="text-sm font-semibold text-gray-700 mb-4">Users with this Role ({{ $roleUserCount }})</h3>
                <p class="text-xs text-gray-600 mb-4">Users currently assigned to this role:</p>
                
                <div class="overflow-x-auto">
                    <table class="min-w-full border border-gray-200 rounded-lg">
                        <thead>
                            <tr class="bg-gray-50">
                                <th class="px-4 py-1.5 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b border-gray-200">Name</th>
                                <th class="px-4 py-1.5 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b border-gray-200">Email</th>
                                <th class="px-4 py-1.5 text-center text-xs font-medium text-gray-500 uppercase tracking-wider border-b border-gray-200">Status</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($users as $user)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-1.5 text-xs font-medium text-gray-900 border-r border-gray-200">{{ $user->name }}</td>
                                <td class="px-4 py-1.5 text-xs text-gray-600 border-r border-gray-200">{{ $user->email }}</td>
                                <td class="px-4 py-1.5 text-center">
                                    <span class="inline-block bg-green-100 text-green-800 px-2 py-1 rounded-full text-xs font-medium">Active</span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    
                    @if($roleUserCount > 10)
                        <div class="text-center py-4">
                            <p class="text-xs text-gray-500">Showing first 10 users. Total: {{ $roleUserCount }} users</p>
                        </div>
                    @endif
                </div>
            </div>
            @else
            <div class="bg-gray-50 p-4 rounded-lg mb-6">
                <h3 class="text-sm font-semibold text-gray-700 mb-4">Users with this Role (0)</h3>
                <div class="text-center py-4">
                    <span class="material-icons text-gray-400 text-2xl mb-2">group</span>
                    <p class="text-sm text-gray-500">No users are currently assigned to this role.</p>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection 