@extends('layouts.app')

@section('breadcrumb')
    Settings > Role Management > Edit Role
@endsection

@section('content')
<div class="px-4 md:px-6 pt-4 md:pt-6 pb-6 max-w-7xl mx-auto">
    <div class="bg-white rounded shadow-md border border-gray-300">
        <div class="p-4 md:p-6 border-b border-gray-200">
            <div class="flex justify-between items-start">
                <div>
                    <div class="flex items-center">
                        <span class="material-icons mr-2 text-purple-600">edit</span>
                        <h1 class="text-lg md:text-xl font-bold text-gray-800">Edit Role: {{ $role->name }}</h1>
                    </div>
                    <p class="text-xs text-gray-500 mt-1 ml-8">Update role information and permissions.</p>
                </div>
            </div>
        </div>
        
        <div class="p-4 md:p-6">
            <form action="{{ route('settings.role.update', $role->id) }}" method="POST" class="space-y-0">
                @csrf
                @method('PUT')
                
                <!-- Role Information Section -->
                <div class="bg-gray-50 p-4 rounded-lg mb-6">
                    <h3 class="text-sm font-semibold text-gray-700 mb-4">Role Information</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-2">Role Name *</label>
                            <input type="text" name="name" required 
                                   value="{{ $role->name }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500" 
                                   placeholder="e.g., Senior Partner, Junior Associate">
                            @error('name')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-2">Description</label>
                            <textarea name="description" rows="3" 
                                      class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500" 
                                      placeholder="Describe the role's responsibilities and access level">{{ $role->description }}</textarea>
                            @error('description')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Permissions Matrix Section -->
                <div class="bg-gray-50 p-4 rounded-lg mb-6">
                    <h3 class="text-sm font-semibold text-gray-700 mb-4">Permission Matrix</h3>
                    <p class="text-xs text-gray-600 mb-4">Select the permissions this role should have access to:</p>
                    
                    <div class="overflow-x-auto">
                        <table class="min-w-full border border-gray-200 rounded-lg">
                            <thead>
                                <tr class="bg-gray-50">
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b border-gray-200">Category</th>
                                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider border-b border-gray-200">Create</th>
                                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider border-b border-gray-200">Read</th>
                                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider border-b border-gray-200">Edit</th>
                                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider border-b border-gray-200">Delete</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <!-- Overview -->
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-3 text-xs font-medium text-gray-900 border-r border-gray-200">
                                        <div class="flex items-center">
                                            <span class="material-icons text-blue-500 mr-2 text-sm">dashboard</span>
                                            Overview
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 text-center border-r border-gray-200">
                                        <span class="text-gray-400 text-xs">-</span>
                                    </td>
                                    <td class="px-4 py-3 text-center border-r border-gray-200">
                                        <label class="flex items-center justify-center">
                                            <input type="checkbox" name="permissions[]" value="{{ $permissions->where('name', 'view-overview')->first()?->id }}" 
                                                   {{ $role->hasPermissionTo('view-overview') ? 'checked' : '' }}
                                                   class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                        </label>
                                    </td>
                                    <td class="px-4 py-3 text-center border-r border-gray-200">
                                        <span class="text-gray-400 text-xs">-</span>
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        <span class="text-gray-400 text-xs">-</span>
                                    </td>
                                </tr>

                                <!-- Calendar -->
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-3 text-xs font-medium text-gray-900 border-r border-gray-200">
                                        <div class="flex items-center">
                                            <span class="material-icons text-green-500 mr-2 text-sm">calendar_today</span>
                                            Calendar
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 text-center border-r border-gray-200">
                                        <label class="flex items-center justify-center">
                                            <input type="checkbox" name="permissions[]" value="{{ $permissions->where('name', 'create-events')->first()?->id }}" 
                                                   {{ $role->hasPermissionTo('create-events') ? 'checked' : '' }}
                                                   class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                        </label>
                                    </td>
                                    <td class="px-4 py-3 text-center border-r border-gray-200">
                                        <label class="flex items-center justify-center">
                                            <input type="checkbox" name="permissions[]" value="{{ $permissions->where('name', 'view-calendar')->first()?->id }}" 
                                                   {{ $role->hasPermissionTo('view-calendar') ? 'checked' : '' }}
                                                   class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                        </label>
                                    </td>
                                    <td class="px-4 py-3 text-center border-r border-gray-200">
                                        <label class="flex items-center justify-center">
                                            <input type="checkbox" name="permissions[]" value="{{ $permissions->where('name', 'edit-events')->first()?->id }}" 
                                                   {{ $role->hasPermissionTo('edit-events') ? 'checked' : '' }}
                                                   class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                        </label>
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        <label class="flex items-center justify-center">
                                            <input type="checkbox" name="permissions[]" value="{{ $permissions->where('name', 'delete-events')->first()?->id }}" 
                                                   {{ $role->hasPermissionTo('delete-events') ? 'checked' : '' }}
                                                   class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                        </label>
                                    </td>
                                </tr>

                                <!-- Case Management - Main Category -->
                                <tr class="hover:bg-gray-50 bg-gray-100">
                                    <td class="px-4 py-3 text-xs font-semibold text-gray-900 border-r border-gray-200">
                                        <div class="flex items-center">
                                            <span class="material-icons text-purple-500 mr-2 text-sm">folder</span>
                                            Case Management
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 text-center border-r border-gray-200">
                                        <span class="text-gray-400 text-xs">-</span>
                                    </td>
                                    <td class="px-4 py-3 text-center border-r border-gray-200">
                                        <span class="text-gray-400 text-xs">-</span>
                                    </td>
                                    <td class="px-4 py-3 text-center border-r border-gray-200">
                                        <span class="text-gray-400 text-xs">-</span>
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        <span class="text-gray-400 text-xs">-</span>
                                    </td>
                                </tr>

                                <!-- Case -->
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-3 text-xs font-medium text-gray-700 border-r border-gray-200 pl-8">
                                        <div class="flex items-center">
                                            <span class="material-icons text-purple-400 mr-2 text-xs">description</span>
                                            • Case
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 text-center border-r border-gray-200">
                                        <label class="flex items-center justify-center">
                                            <input type="checkbox" name="permissions[]" value="{{ $permissions->where('name', 'create-cases')->first()?->id }}" 
                                                   {{ $role->hasPermissionTo('create-cases') ? 'checked' : '' }}
                                                   class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                        </label>
                                    </td>
                                    <td class="px-4 py-3 text-center border-r border-gray-200">
                                        <label class="flex items-center justify-center">
                                            <input type="checkbox" name="permissions[]" value="{{ $permissions->where('name', 'view-cases')->first()?->id }}" 
                                                   {{ $role->hasPermissionTo('view-cases') ? 'checked' : '' }}
                                                   class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                        </label>
                                    </td>
                                    <td class="px-4 py-3 text-center border-r border-gray-200">
                                        <label class="flex items-center justify-center">
                                            <input type="checkbox" name="permissions[]" value="{{ $permissions->where('name', 'edit-cases')->first()?->id }}" 
                                                   {{ $role->hasPermissionTo('edit-cases') ? 'checked' : '' }}
                                                   class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                        </label>
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        <label class="flex items-center justify-center">
                                            <input type="checkbox" name="permissions[]" value="{{ $permissions->where('name', 'delete-cases')->first()?->id }}" 
                                                   {{ $role->hasPermissionTo('delete-cases') ? 'checked' : '' }}
                                                   class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                        </label>
                                    </td>
                                </tr>

                                <!-- Client -->
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-3 text-xs font-medium text-gray-700 border-r border-gray-200 pl-8">
                                        <div class="flex items-center">
                                            <span class="material-icons text-indigo-400 mr-2 text-xs">people</span>
                                            • Client List
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 text-center border-r border-gray-200">
                                        <label class="flex items-center justify-center">
                                            <input type="checkbox" name="permissions[]" value="{{ $permissions->where('name', 'create-clients')->first()?->id }}" 
                                                   {{ $role->hasPermissionTo('create-clients') ? 'checked' : '' }}
                                                   class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                        </label>
                                    </td>
                                    <td class="px-4 py-3 text-center border-r border-gray-200">
                                        <label class="flex items-center justify-center">
                                            <input type="checkbox" name="permissions[]" value="{{ $permissions->where('name', 'view-clients')->first()?->id }}" 
                                                   {{ $role->hasPermissionTo('view-clients') ? 'checked' : '' }}
                                                   class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                        </label>
                                    </td>
                                    <td class="px-4 py-3 text-center border-r border-gray-200">
                                        <label class="flex items-center justify-center">
                                            <input type="checkbox" name="permissions[]" value="{{ $permissions->where('name', 'edit-clients')->first()?->id }}" 
                                                   {{ $role->hasPermissionTo('edit-clients') ? 'checked' : '' }}
                                                   class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                        </label>
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        <label class="flex items-center justify-center">
                                            <input type="checkbox" name="permissions[]" value="{{ $permissions->where('name', 'delete-clients')->first()?->id }}" 
                                                   {{ $role->hasPermissionTo('delete-clients') ? 'checked' : '' }}
                                                   class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                        </label>
                                    </td>
                                </tr>

                                <!-- Partner -->
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-3 text-xs font-medium text-gray-700 border-r border-gray-200 pl-8">
                                        <div class="flex items-center">
                                            <span class="material-icons text-orange-400 mr-2 text-xs">business</span>
                                            • Partner
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 text-center border-r border-gray-200">
                                        <label class="flex items-center justify-center">
                                            <input type="checkbox" name="permissions[]" value="{{ $permissions->where('name', 'create-partners')->first()?->id }}" 
                                                   {{ $role->hasPermissionTo('create-partners') ? 'checked' : '' }}
                                                   class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                        </label>
                                    </td>
                                    <td class="px-4 py-3 text-center border-r border-gray-200">
                                        <label class="flex items-center justify-center">
                                            <input type="checkbox" name="permissions[]" value="{{ $permissions->where('name', 'view-partners')->first()?->id }}" 
                                                   {{ $role->hasPermissionTo('view-partners') ? 'checked' : '' }}
                                                   class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                        </label>
                                    </td>
                                    <td class="px-4 py-3 text-center border-r border-gray-200">
                                        <label class="flex items-center justify-center">
                                            <input type="checkbox" name="permissions[]" value="{{ $permissions->where('name', 'edit-partners')->first()?->id }}" 
                                                   {{ $role->hasPermissionTo('edit-partners') ? 'checked' : '' }}
                                                   class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                        </label>
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        <label class="flex items-center justify-center">
                                            <input type="checkbox" name="permissions[]" value="{{ $permissions->where('name', 'delete-partners')->first()?->id }}" 
                                                   {{ $role->hasPermissionTo('delete-partners') ? 'checked' : '' }}
                                                   class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                        </label>
                                    </td>
                                </tr>

                                <!-- Accounting - Main Category -->
                                <tr class="hover:bg-gray-50 bg-gray-100">
                                    <td class="px-4 py-3 text-xs font-semibold text-gray-900 border-r border-gray-200">
                                        <div class="flex items-center">
                                            <span class="material-icons text-amber-600 mr-2 text-sm">account_balance</span>
                                            Accounting
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 text-center border-r border-gray-200">
                                        <span class="text-gray-400 text-xs">-</span>
                                    </td>
                                    <td class="px-4 py-3 text-center border-r border-gray-200">
                                        <span class="text-gray-400 text-xs">-</span>
                                    </td>
                                    <td class="px-4 py-3 text-center border-r border-gray-200">
                                        <span class="text-gray-400 text-xs">-</span>
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        <span class="text-gray-400 text-xs">-</span>
                                    </td>
                                </tr>

                                <!-- Quotation -->
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-3 text-xs font-medium text-gray-700 border-r border-gray-200 pl-8">
                                        <div class="flex items-center">
                                            <span class="material-icons text-amber-400 mr-2 text-xs">request_quote</span>
                                            • Quotation
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 text-center border-r border-gray-200">
                                        <label class="flex items-center justify-center">
                                            <input type="checkbox" name="permissions[]" value="{{ $permissions->where('name', 'create-quotations')->first()?->id }}" 
                                                   {{ $role->hasPermissionTo('create-quotations') ? 'checked' : '' }}
                                                   class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                        </label>
                                    </td>
                                    <td class="px-4 py-3 text-center border-r border-gray-200">
                                        <label class="flex items-center justify-center">
                                            <input type="checkbox" name="permissions[]" value="{{ $permissions->where('name', 'view-accounting')->first()?->id }}" 
                                                   {{ $role->hasPermissionTo('view-accounting') ? 'checked' : '' }}
                                                   class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                        </label>
                                    </td>
                                    <td class="px-4 py-3 text-center border-r border-gray-200">
                                        <label class="flex items-center justify-center">
                                            <input type="checkbox" name="permissions[]" value="{{ $permissions->where('name', 'edit-quotations')->first()?->id }}" 
                                                   {{ $role->hasPermissionTo('edit-quotations') ? 'checked' : '' }}
                                                   class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                        </label>
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        <label class="flex items-center justify-center">
                                            <input type="checkbox" name="permissions[]" value="{{ $permissions->where('name', 'delete-quotations')->first()?->id }}" 
                                                   {{ $role->hasPermissionTo('delete-quotations') ? 'checked' : '' }}
                                                   class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                        </label>
                                    </td>
                                </tr>

                                <!-- Tax Invoice -->
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-3 text-xs font-medium text-gray-700 border-r border-gray-200 pl-8">
                                        <div class="flex items-center">
                                            <span class="material-icons text-amber-400 mr-2 text-xs">receipt_long</span>
                                            • Tax Invoice
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 text-center border-r border-gray-200">
                                        <label class="flex items-center justify-center">
                                            <input type="checkbox" name="permissions[]" value="{{ $permissions->where('name', 'create-invoices')->first()?->id }}" 
                                                   {{ $role->hasPermissionTo('create-invoices') ? 'checked' : '' }}
                                                   class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                        </label>
                                    </td>
                                    <td class="px-4 py-3 text-center border-r border-gray-200">
                                        <label class="flex items-center justify-center">
                                            <input type="checkbox" name="permissions[]" value="{{ $permissions->where('name', 'view-accounting')->first()?->id }}" 
                                                   {{ $role->hasPermissionTo('view-accounting') ? 'checked' : '' }}
                                                   class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                        </label>
                                    </td>
                                    <td class="px-4 py-3 text-center border-r border-gray-200">
                                        <label class="flex items-center justify-center">
                                            <input type="checkbox" name="permissions[]" value="{{ $permissions->where('name', 'edit-invoices')->first()?->id }}" 
                                                   {{ $role->hasPermissionTo('edit-invoices') ? 'checked' : '' }}
                                                   class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                        </label>
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        <label class="flex items-center justify-center">
                                            <input type="checkbox" name="permissions[]" value="{{ $permissions->where('name', 'delete-invoices')->first()?->id }}" 
                                                   {{ $role->hasPermissionTo('delete-invoices') ? 'checked' : '' }}
                                                   class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                        </label>
                                    </td>
                                </tr>

                                <!-- Resit -->
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-3 text-xs font-medium text-gray-700 border-r border-gray-200 pl-8">
                                        <div class="flex items-center">
                                            <span class="material-icons text-amber-400 mr-2 text-xs">receipt</span>
                                            • Resit
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 text-center border-r border-gray-200">
                                        <label class="flex items-center justify-center">
                                            <input type="checkbox" name="permissions[]" value="{{ $permissions->where('name', 'create-receipts')->first()?->id }}" 
                                                   {{ $role->hasPermissionTo('create-receipts') ? 'checked' : '' }}
                                                   class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                        </label>
                                    </td>
                                    <td class="px-4 py-3 text-center border-r border-gray-200">
                                        <label class="flex items-center justify-center">
                                            <input type="checkbox" name="permissions[]" value="{{ $permissions->where('name', 'view-accounting')->first()?->id }}" 
                                                   {{ $role->hasPermissionTo('view-accounting') ? 'checked' : '' }}
                                                   class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                        </label>
                                    </td>
                                    <td class="px-4 py-3 text-center border-r border-gray-200">
                                        <label class="flex items-center justify-center">
                                            <input type="checkbox" name="permissions[]" value="{{ $permissions->where('name', 'edit-receipts')->first()?->id }}" 
                                                   {{ $role->hasPermissionTo('edit-receipts') ? 'checked' : '' }}
                                                   class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                        </label>
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        <label class="flex items-center justify-center">
                                            <input type="checkbox" name="permissions[]" value="{{ $permissions->where('name', 'delete-receipts')->first()?->id }}" 
                                                   {{ $role->hasPermissionTo('delete-receipts') ? 'checked' : '' }}
                                                   class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                        </label>
                                    </td>
                                </tr>

                                <!-- Voucher -->
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-3 text-xs font-medium text-gray-700 border-r border-gray-200 pl-8">
                                        <div class="flex items-center">
                                            <span class="material-icons text-amber-400 mr-2 text-xs">confirmation_number</span>
                                            • Voucher
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 text-center border-r border-gray-200">
                                        <label class="flex items-center justify-center">
                                            <input type="checkbox" name="permissions[]" value="{{ $permissions->where('name', 'create-vouchers')->first()?->id }}" 
                                                   {{ $role->hasPermissionTo('create-vouchers') ? 'checked' : '' }}
                                                   class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                        </label>
                                    </td>
                                    <td class="px-4 py-3 text-center border-r border-gray-200">
                                        <label class="flex items-center justify-center">
                                            <input type="checkbox" name="permissions[]" value="{{ $permissions->where('name', 'view-accounting')->first()?->id }}" 
                                                   {{ $role->hasPermissionTo('view-accounting') ? 'checked' : '' }}
                                                   class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                        </label>
                                    </td>
                                    <td class="px-4 py-3 text-center border-r border-gray-200">
                                        <label class="flex items-center justify-center">
                                            <input type="checkbox" name="permissions[]" value="{{ $permissions->where('name', 'edit-vouchers')->first()?->id }}" 
                                                   {{ $role->hasPermissionTo('edit-vouchers') ? 'checked' : '' }}
                                                   class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                        </label>
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        <label class="flex items-center justify-center">
                                            <input type="checkbox" name="permissions[]" value="{{ $permissions->where('name', 'delete-vouchers')->first()?->id }}" 
                                                   {{ $role->hasPermissionTo('delete-vouchers') ? 'checked' : '' }}
                                                   class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                        </label>
                                    </td>
                                </tr>

                                <!-- Bill -->
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-3 text-xs font-medium text-gray-700 border-r border-gray-200 pl-8">
                                        <div class="flex items-center">
                                            <span class="material-icons text-amber-400 mr-2 text-xs">description</span>
                                            • Bill
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 text-center border-r border-gray-200">
                                        <label class="flex items-center justify-center">
                                            <input type="checkbox" name="permissions[]" value="{{ $permissions->where('name', 'create-bills')->first()?->id }}" 
                                                   {{ $role->hasPermissionTo('create-bills') ? 'checked' : '' }}
                                                   class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                        </label>
                                    </td>
                                    <td class="px-4 py-3 text-center border-r border-gray-200">
                                        <label class="flex items-center justify-center">
                                            <input type="checkbox" name="permissions[]" value="{{ $permissions->where('name', 'view-accounting')->first()?->id }}" 
                                                   {{ $role->hasPermissionTo('view-accounting') ? 'checked' : '' }}
                                                   class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                        </label>
                                    </td>
                                    <td class="px-4 py-3 text-center border-r border-gray-200">
                                        <label class="flex items-center justify-center">
                                            <input type="checkbox" name="permissions[]" value="{{ $permissions->where('name', 'edit-bills')->first()?->id }}" 
                                                   {{ $role->hasPermissionTo('edit-bills') ? 'checked' : '' }}
                                                   class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                        </label>
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        <label class="flex items-center justify-center">
                                            <input type="checkbox" name="permissions[]" value="{{ $permissions->where('name', 'delete-bills')->first()?->id }}" 
                                                   {{ $role->hasPermissionTo('delete-bills') ? 'checked' : '' }}
                                                   class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                        </label>
                                    </td>
                                </tr>

                                <!-- File Management -->
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-3 text-xs font-medium text-gray-900 border-r border-gray-200">
                                        <div class="flex items-center">
                                            <span class="material-icons text-teal-500 mr-2 text-sm">folder_open</span>
                                            File Management
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 text-center border-r border-gray-200">
                                        <label class="flex items-center justify-center">
                                            <input type="checkbox" name="permissions[]" value="{{ $permissions->where('name', 'upload-files')->first()?->id }}" 
                                                   {{ $role->hasPermissionTo('upload-files') ? 'checked' : '' }}
                                                   class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                        </label>
                                    </td>
                                    <td class="px-4 py-3 text-center border-r border-gray-200">
                                        <label class="flex items-center justify-center">
                                            <input type="checkbox" name="permissions[]" value="{{ $permissions->where('name', 'view-files')->first()?->id }}" 
                                                   {{ $role->hasPermissionTo('view-files') ? 'checked' : '' }}
                                                   class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                        </label>
                                    </td>
                                    <td class="px-4 py-3 text-center border-r border-gray-200">
                                        <label class="flex items-center justify-center">
                                            <input type="checkbox" name="permissions[]" value="{{ $permissions->where('name', 'checkout-files')->first()?->id }}" 
                                                   {{ $role->hasPermissionTo('checkout-files') ? 'checked' : '' }}
                                                   class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                        </label>
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        <label class="flex items-center justify-center">
                                            <input type="checkbox" name="permissions[]" value="{{ $permissions->where('name', 'delete-files')->first()?->id }}" 
                                                   {{ $role->hasPermissionTo('delete-files') ? 'checked' : '' }}
                                                   class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                        </label>
                                    </td>
                                </tr>

                                <!-- Accounting -->
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-3 text-xs font-medium text-gray-900 border-r border-gray-200">
                                        <div class="flex items-center">
                                            <span class="material-icons text-amber-600 mr-2 text-sm">account_balance</span>
                                            Accounting
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 text-center border-r border-gray-200">
                                        <label class="flex items-center justify-center">
                                            <input type="checkbox" name="permissions[]" value="{{ $permissions->where('name', 'create-quotations')->first()?->id }}" 
                                                   {{ $role->hasPermissionTo('create-quotations') ? 'checked' : '' }}
                                                   class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                        </label>
                                    </td>
                                    <td class="px-4 py-3 text-center border-r border-gray-200">
                                        <label class="flex items-center justify-center">
                                            <input type="checkbox" name="permissions[]" value="{{ $permissions->where('name', 'view-accounting')->first()?->id }}" 
                                                   {{ $role->hasPermissionTo('view-accounting') ? 'checked' : '' }}
                                                   class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                        </label>
                                    </td>
                                    <td class="px-4 py-3 text-center border-r border-gray-200">
                                        <label class="flex items-center justify-center">
                                            <input type="checkbox" name="permissions[]" value="{{ $permissions->where('name', 'edit-quotations')->first()?->id }}" 
                                                   {{ $role->hasPermissionTo('edit-quotations') ? 'checked' : '' }}
                                                   class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                        </label>
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        <label class="flex items-center justify-center">
                                            <input type="checkbox" name="permissions[]" value="{{ $permissions->where('name', 'delete-quotations')->first()?->id }}" 
                                                   {{ $role->hasPermissionTo('delete-quotations') ? 'checked' : '' }}
                                                   class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                        </label>
                                    </td>
                                </tr>

                                <!-- Settings - Main Category -->
                                <tr class="hover:bg-gray-50 bg-gray-100">
                                    <td class="px-4 py-3 text-xs font-semibold text-gray-900 border-r border-gray-200">
                                        <div class="flex items-center">
                                            <span class="material-icons text-yellow-500 mr-2 text-sm">settings</span>
                                            Settings
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 text-center border-r border-gray-200">
                                        <span class="text-gray-400 text-xs">-</span>
                                    </td>
                                    <td class="px-4 py-3 text-center border-r border-gray-200">
                                        <span class="text-gray-400 text-xs">-</span>
                                    </td>
                                    <td class="px-4 py-3 text-center border-r border-gray-200">
                                        <span class="text-gray-400 text-xs">-</span>
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        <span class="text-gray-400 text-xs">-</span>
                                    </td>
                                </tr>

                                <!-- Global Config -->
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-3 text-xs font-medium text-gray-700 border-r border-gray-200 pl-8">
                                        <div class="flex items-center">
                                            <span class="material-icons text-yellow-400 mr-2 text-xs">tune</span>
                                            • Global Config
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 text-center border-r border-gray-200">
                                        <span class="text-gray-400 text-xs">-</span>
                                    </td>
                                    <td class="px-4 py-3 text-center border-r border-gray-200">
                                        <label class="flex items-center justify-center">
                                            <input type="checkbox" name="permissions[]" value="{{ $permissions->where('name', 'view-settings')->first()?->id }}" 
                                                   {{ $role->hasPermissionTo('view-settings') ? 'checked' : '' }}
                                                   class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                        </label>
                                    </td>
                                    <td class="px-4 py-3 text-center border-r border-gray-200">
                                        <label class="flex items-center justify-center">
                                            <input type="checkbox" name="permissions[]" value="{{ $permissions->where('name', 'manage-firm-settings')->first()?->id }}" 
                                                   {{ $role->hasPermissionTo('manage-firm-settings') ? 'checked' : '' }}
                                                   class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                        </label>
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        <span class="text-gray-400 text-xs">-</span>
                                    </td>
                                </tr>

                                <!-- Role Management -->
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-3 text-xs font-medium text-gray-700 border-r border-gray-200 pl-8">
                                        <div class="flex items-center">
                                            <span class="material-icons text-yellow-400 mr-2 text-xs">admin_panel_settings</span>
                                            • Role Management
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 text-center border-r border-gray-200">
                                        <label class="flex items-center justify-center">
                                            <input type="checkbox" name="permissions[]" value="{{ $permissions->where('name', 'manage-roles')->first()?->id }}" 
                                                   {{ $role->hasPermissionTo('manage-roles') ? 'checked' : '' }}
                                                   class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                        </label>
                                    </td>
                                    <td class="px-4 py-3 text-center border-r border-gray-200">
                                        <label class="flex items-center justify-center">
                                            <input type="checkbox" name="permissions[]" value="{{ $permissions->where('name', 'manage-roles')->first()?->id }}" 
                                                   {{ $role->hasPermissionTo('manage-roles') ? 'checked' : '' }}
                                                   class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                        </label>
                                    </td>
                                    <td class="px-4 py-3 text-center border-r border-gray-200">
                                        <label class="flex items-center justify-center">
                                            <input type="checkbox" name="permissions[]" value="{{ $permissions->where('name', 'manage-roles')->first()?->id }}" 
                                                   {{ $role->hasPermissionTo('manage-roles') ? 'checked' : '' }}
                                                   class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                        </label>
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        <label class="flex items-center justify-center">
                                            <input type="checkbox" name="permissions[]" value="{{ $permissions->where('name', 'manage-roles')->first()?->id }}" 
                                                   {{ $role->hasPermissionTo('manage-roles') ? 'checked' : '' }}
                                                   class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                        </label>
                                    </td>
                                </tr>
                                    <td class="px-4 py-3 text-center border-r border-gray-200">
                                        <span class="text-gray-400 text-xs">-</span>
                                    </td>
                                    <td class="px-4 py-3 text-center border-r border-gray-200">
                                        <label class="flex items-center justify-center">
                                            <input type="checkbox" name="permissions[]" value="{{ $permissions->where('name', 'view-settings')->first()?->id }}" 
                                                   {{ $role->hasPermissionTo('view-settings') ? 'checked' : '' }}
                                                   class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                        </label>
                                    </td>
                                    <td class="px-4 py-3 text-center border-r border-gray-200">
                                        <label class="flex items-center justify-center">
                                            <input type="checkbox" name="permissions[]" value="{{ $permissions->where('name', 'manage-firm-settings')->first()?->id }}" 
                                                   {{ $role->hasPermissionTo('manage-firm-settings') ? 'checked' : '' }}
                                                   class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                        </label>
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        <span class="text-gray-400 text-xs">-</span>
                                    </td>
                                </tr>

                                <!-- User Management -->
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-3 text-xs font-medium text-gray-700 border-r border-gray-200 pl-8">
                                        <div class="flex items-center">
                                            <span class="material-icons text-yellow-400 mr-2 text-xs">manage_accounts</span>
                                            • User Management
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 text-center border-r border-gray-200">
                                        <label class="flex items-center justify-center">
                                            <input type="checkbox" name="permissions[]" value="{{ $permissions->where('name', 'create-users')->first()?->id }}" 
                                                   {{ $role->hasPermissionTo('create-users') ? 'checked' : '' }}
                                                   class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                        </label>
                                    </td>
                                    <td class="px-4 py-3 text-center border-r border-gray-200">
                                        <label class="flex items-center justify-center">
                                            <input type="checkbox" name="permissions[]" value="{{ $permissions->where('name', 'view-users')->first()?->id }}" 
                                                   {{ $role->hasPermissionTo('view-users') ? 'checked' : '' }}
                                                   class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                        </label>
                                    </td>
                                    <td class="px-4 py-3 text-center border-r border-gray-200">
                                        <label class="flex items-center justify-center">
                                            <input type="checkbox" name="permissions[]" value="{{ $permissions->where('name', 'edit-users')->first()?->id }}" 
                                                   {{ $role->hasPermissionTo('edit-users') ? 'checked' : '' }}
                                                   class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                        </label>
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        <label class="flex items-center justify-center">
                                            <input type="checkbox" name="permissions[]" value="{{ $permissions->where('name', 'delete-users')->first()?->id }}" 
                                                   {{ $role->hasPermissionTo('delete-users') ? 'checked' : '' }}
                                                   class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                        </label>
                                    </td>
                                </tr>

                                <!-- Case Management -->
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-3 text-xs font-medium text-gray-700 border-r border-gray-200 pl-8">
                                        <div class="flex items-center">
                                            <span class="material-icons text-yellow-400 mr-2 text-xs">folder_open</span>
                                            • Case Management
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 text-center border-r border-gray-200">
                                        <span class="text-gray-400 text-xs">-</span>
                                    </td>
                                    <td class="px-4 py-3 text-center border-r border-gray-200">
                                        <label class="flex items-center justify-center">
                                            <input type="checkbox" name="permissions[]" value="{{ $permissions->where('name', 'view-cases')->first()?->id }}" 
                                                   {{ $role->hasPermissionTo('view-cases') ? 'checked' : '' }}
                                                   class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                        </label>
                                    </td>
                                    <td class="px-4 py-3 text-center border-r border-gray-200">
                                        <label class="flex items-center justify-center">
                                            <input type="checkbox" name="permissions[]" value="{{ $permissions->where('name', 'create-cases')->first()?->id }}" 
                                                   {{ $role->hasPermissionTo('create-cases') ? 'checked' : '' }}
                                                   class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                        </label>
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        <span class="text-gray-400 text-xs">-</span>
                                    </td>
                                </tr>

                                <!-- Category -->
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-3 text-xs font-medium text-gray-700 border-r border-gray-200 pl-8">
                                        <div class="flex items-center">
                                            <span class="material-icons text-yellow-400 mr-2 text-xs">category</span>
                                            • Category
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 text-center border-r border-gray-200">
                                        <label class="flex items-center justify-center">
                                            <input type="checkbox" name="permissions[]" value="{{ $permissions->where('name', 'manage-system-settings')->first()?->id }}" 
                                                   {{ $role->hasPermissionTo('manage-system-settings') ? 'checked' : '' }}
                                                   class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                        </label>
                                    </td>
                                    <td class="px-4 py-3 text-center border-r border-gray-200">
                                        <label class="flex items-center justify-center">
                                            <input type="checkbox" name="permissions[]" value="{{ $permissions->where('name', 'view-settings')->first()?->id }}" 
                                                   {{ $role->hasPermissionTo('view-settings') ? 'checked' : '' }}
                                                   class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                        </label>
                                    </td>
                                    <td class="px-4 py-3 text-center border-r border-gray-200">
                                        <label class="flex items-center justify-center">
                                            <input type="checkbox" name="permissions[]" value="{{ $permissions->where('name', 'manage-system-settings')->first()?->id }}" 
                                                   {{ $role->hasPermissionTo('manage-system-settings') ? 'checked' : '' }}
                                                   class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                        </label>
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        <label class="flex items-center justify-center">
                                            <input type="checkbox" name="permissions[]" value="{{ $permissions->where('name', 'manage-system-settings')->first()?->id }}" 
                                                   {{ $role->hasPermissionTo('manage-system-settings') ? 'checked' : '' }}
                                                   class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                        </label>
                                    </td>
                                </tr>

                                <!-- Log Activity -->
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-3 text-xs font-medium text-gray-700 border-r border-gray-200 pl-8">
                                        <div class="flex items-center">
                                            <span class="material-icons text-yellow-400 mr-2 text-xs">history</span>
                                            • Log Activity
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 text-center border-r border-gray-200">
                                        <span class="text-gray-400 text-xs">-</span>
                                    </td>
                                    <td class="px-4 py-3 text-center border-r border-gray-200">
                                        <label class="flex items-center justify-center">
                                            <input type="checkbox" name="permissions[]" value="{{ $permissions->where('name', 'manage-system-logs')->first()?->id }}" 
                                                   {{ $role->hasPermissionTo('manage-system-logs') ? 'checked' : '' }}
                                                   class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                        </label>
                                    </td>
                                    <td class="px-4 py-3 text-center border-r border-gray-200">
                                        <span class="text-gray-400 text-xs">-</span>
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        <span class="text-gray-400 text-xs">-</span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-col md:flex-row justify-end space-y-2 md:space-y-0 md:space-x-3 pt-4 border-t border-gray-200">
                    <a href="{{ route('settings.role') }}" class="w-full md:w-auto px-4 py-2 text-gray-600 border border-gray-300 rounded-md text-xs font-medium hover:bg-gray-50 text-center">
                        Cancel
                    </a>
                    <button type="submit" class="w-full md:w-auto px-4 py-2 bg-blue-600 text-white rounded-md text-xs font-medium hover:bg-blue-700">
                        Update Role
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection 