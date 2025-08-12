@extends('layouts.app')

@section('breadcrumb')
    Bill
@endsection

@section('content')
<div class="px-4 md:px-6 pt-4 md:pt-6 pb-6 max-w-7xl mx-auto">
    <div class="bg-white rounded shadow-md border border-gray-300">
        <div class="p-4 md:p-6 border-b border-gray-200">
            <div class="flex flex-col md:flex-row md:justify-between md:items-start space-y-4 md:space-y-0">
                <div>
                    <div class="flex items-center">
                        <span class="material-icons mr-2 text-orange-600">account_balance_wallet</span>
                        <h1 class="text-lg md:text-xl font-bold text-gray-800">Bill Management</h1>
                    </div>
                    <p class="text-xs text-gray-500 mt-1 ml-8">Manage all bills and expense records.</p>
                </div>
                
                <!-- Add Bill Button -->
                <a href="{{ route('bill.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 md:px-3 md:py-1 rounded-md text-sm md:text-xs font-medium flex items-center justify-center md:justify-start w-full md:w-auto">
                    <span class="material-icons text-sm md:text-xs mr-1">add</span>
                    Add Bill
                </a>
            </div>
        </div>
        
        <!-- Desktop Table View -->
        <div class="hidden md:block p-6">
            <div class="overflow-visible border border-gray-200 rounded">
                <table class="min-w-full border-collapse">
                    <thead>
                        <tr class="bg-primary-light text-white uppercase text-xs">
                            <th class="py-3 px-4 text-left rounded-tl">Bill No</th>
                            <th class="py-3 px-4 text-left">Vendor/Supplier</th>
                            <th class="py-3 px-4 text-left">Category</th>
                            <th class="py-3 px-4 text-left">Description</th>
                            <th class="py-3 px-4 text-left">Amount (RM)</th>
                            <th class="py-3 px-4 text-left">Due Date</th>
                            <th class="py-3 px-4 text-left">Payment Date</th>
                            <th class="py-3 px-4 text-left">Status</th>
                            <th class="py-3 px-4 text-center rounded-tr">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <tr class="hover:bg-gray-50">
                            <td class="py-1 px-4 text-[11px] font-medium">BL-2025-001</td>
                            <td class="py-1 px-4 text-[11px]">Office Supplies Co</td>
                            <td class="py-1 px-4 text-[11px]">Office Supplies</td>
                            <td class="py-1 px-4 text-[11px]">Stationery & Paper</td>
                            <td class="py-1 px-4 text-[11px] font-medium">RM 250.00</td>
                            <td class="py-1 px-4 text-[11px]">15/02/2025</td>
                            <td class="py-1 px-4 text-[11px]">10/02/2025</td>
                            <td class="py-1 px-4 text-[11px]">
                                <span class="inline-block bg-green-100 text-green-800 px-1.5 py-0.5 rounded-full text-[10px]">Paid</span>
                            </td>
                            <td class="py-1 px-4">
                                <div class="flex justify-center space-x-1 items-center">
                                    <button class="p-0.5 text-red-600 hover:text-red-700" title="Cancel">
                                        <span class="material-icons text-base">block</span>
                                    </button>
                                    <a href="#" class="p-0.5 text-blue-600 hover:text-blue-700" title="View">
                                        <span class="material-icons text-base">visibility</span>
                                    </a>
                                    <a href="#" class="p-0.5 text-yellow-600 hover:text-yellow-700" title="Edit">
                                        <span class="material-icons text-base">edit</span>
                                    </a>
                                    <button class="p-0.5 text-red-600 hover:text-red-700" title="Delete">
                                        <span class="material-icons text-base">delete</span>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <tr class="hover:bg-gray-50">
                            <td class="py-3 px-4 text-[11px] font-medium">BL-2025-002</td>
                            <td class="py-3 px-4 text-[11px]">Internet Provider</td>
                            <td class="py-3 px-4 text-[11px]">Utilities</td>
                            <td class="py-3 px-4 text-[11px]">Monthly Internet</td>
                            <td class="py-3 px-4 text-[11px] font-medium">RM 180.00</td>
                            <td class="py-3 px-4 text-[11px]">20/02/2025</td>
                            <td class="py-3 px-4 text-[11px]">-</td>
                            <td class="py-3 px-4 text-[11px]">
                                <span class="inline-block bg-yellow-100 text-yellow-800 px-2 py-1 rounded-full text-xs">Pending</span>
                            </td>
                            <td class="py-3 px-4">
                                <div class="flex justify-center space-x-2 items-center">
                                    <button class="p-1 bg-red-50 rounded hover:bg-red-100 border border-red-100" title="Banned">
                                        <span class="material-icons text-red-600 text-xs">block</span>
                                    </button>
                                    <a href="#" class="p-1 bg-blue-50 rounded hover:bg-blue-100 border border-blue-100" title="View">
                                        <span class="material-icons text-blue-600 text-xs">visibility</span>
                                    </a>
                                    <a href="#" class="p-1 bg-yellow-50 rounded hover:bg-yellow-100 border border-yellow-100" title="Edit">
                                        <span class="material-icons text-yellow-700 text-xs">edit</span>
                                    </a>
                                    <button class="p-1 bg-red-50 rounded hover:bg-red-100 border border-red-100" title="Delete">
                                        <span class="material-icons text-red-600 text-xs">delete</span>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <tr class="hover:bg-gray-50">
                            <td class="py-3 px-4 text-[11px] font-medium">BL-2025-003</td>
                            <td class="py-3 px-4 text-[11px]">Electricity Board</td>
                            <td class="py-3 px-4 text-[11px]">Utilities</td>
                            <td class="py-3 px-4 text-[11px]">Monthly Electricity</td>
                            <td class="py-3 px-4 text-[11px] font-medium">RM 320.00</td>
                            <td class="py-3 px-4 text-[11px]">25/02/2025</td>
                            <td class="py-3 px-4 text-[11px]">-</td>
                            <td class="py-3 px-4 text-[11px]">
                                <span class="inline-block bg-yellow-100 text-yellow-800 px-2 py-1 rounded-full text-xs">Pending</span>
                            </td>
                            <td class="py-3 px-4">
                                <div class="flex justify-center space-x-2 items-center">
                                    <button class="p-1 bg-red-50 rounded hover:bg-red-100 border border-red-100" title="Banned">
                                        <span class="material-icons text-red-600 text-xs">block</span>
                                    </button>
                                    <a href="#" class="p-1 bg-blue-50 rounded hover:bg-blue-100 border border-blue-100" title="View">
                                        <span class="material-icons text-blue-600 text-xs">visibility</span>
                                    </a>
                                    <a href="#" class="p-1 bg-yellow-50 rounded hover:bg-yellow-100 border border-yellow-100" title="Edit">
                                        <span class="material-icons text-yellow-700 text-xs">edit</span>
                                    </a>
                                    <button class="p-1 bg-red-50 rounded hover:bg-red-100 border border-red-100" title="Delete">
                                        <span class="material-icons text-red-600 text-xs">delete</span>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <tr class="hover:bg-gray-50">
                            <td class="py-3 px-4 text-[11px] font-medium">BL-2025-004</td>
                            <td class="py-3 px-4 text-[11px]">Cleaning Services</td>
                            <td class="py-3 px-4 text-[11px]">Services</td>
                            <td class="py-3 px-4 text-[11px]">Monthly Cleaning</td>
                            <td class="py-3 px-4 text-[11px] font-medium">RM 450.00</td>
                            <td class="py-3 px-4 text-[11px]">28/02/2025</td>
                            <td class="py-3 px-4 text-[11px]">-</td>
                            <td class="py-3 px-4 text-[11px]">
                                <span class="inline-block bg-yellow-100 text-yellow-800 px-2 py-1 rounded-full text-xs">Pending</span>
                            </td>
                            <td class="py-3 px-4">
                                <div class="flex justify-center space-x-2 items-center">
                                    <button class="p-1 bg-red-50 rounded hover:bg-red-100 border border-red-100" title="Banned">
                                        <span class="material-icons text-red-600 text-xs">block</span>
                                    </button>
                                    <a href="#" class="p-1 bg-blue-50 rounded hover:bg-blue-100 border border-blue-100" title="View">
                                        <span class="material-icons text-blue-600 text-xs">visibility</span>
                                    </a>
                                    <a href="#" class="p-1 bg-yellow-50 rounded hover:bg-yellow-100 border border-yellow-100" title="Edit">
                                        <span class="material-icons text-yellow-700 text-xs">edit</span>
                                    </a>
                                    <button class="p-1 bg-red-50 rounded hover:bg-red-100 border border-red-100" title="Delete">
                                        <span class="material-icons text-red-600 text-xs">delete</span>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <tr class="hover:bg-gray-50">
                            <td class="py-3 px-4 text-[11px] font-medium">BL-2025-005</td>
                            <td class="py-3 px-4 text-[11px]">Legal Database</td>
                            <td class="py-3 px-4 text-[11px]">Subscriptions</td>
                            <td class="py-3 px-4 text-[11px]">Annual Subscription</td>
                            <td class="py-3 px-4 text-[11px] font-medium">RM 2,500.00</td>
                            <td class="py-3 px-4 text-[11px]">15/03/2025</td>
                            <td class="py-3 px-4 text-[11px]">-</td>
                            <td class="py-3 px-4 text-[11px]">
                                <span class="inline-block bg-yellow-100 text-yellow-800 px-2 py-1 rounded-full text-xs">Pending</span>
                            </td>
                            <td class="py-3 px-4">
                                <div class="flex justify-center space-x-2 items-center">
                                    <button class="p-1 bg-red-50 rounded hover:bg-red-100 border border-red-100" title="Banned">
                                        <span class="material-icons text-red-600 text-xs">block</span>
                                    </button>
                                    <a href="#" class="p-1 bg-blue-50 rounded hover:bg-blue-100 border border-blue-100" title="View">
                                        <span class="material-icons text-blue-600 text-xs">visibility</span>
                                    </a>
                                    <a href="#" class="p-1 bg-yellow-50 rounded hover:bg-yellow-100 border border-yellow-100" title="Edit">
                                        <span class="material-icons text-yellow-700 text-xs">edit</span>
                                    </a>
                                    <button class="p-1 bg-red-50 rounded hover:bg-red-100 border border-red-100" title="Delete">
                                        <span class="material-icons text-red-600 text-xs">delete</span>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <tr class="hover:bg-gray-50">
                            <td class="py-3 px-4 text-[11px] font-medium">BL-2025-006</td>
                            <td class="py-3 px-4 text-[11px]">Insurance Co</td>
                            <td class="py-3 px-4 text-[11px]">Insurance</td>
                            <td class="py-3 px-4 text-[11px]">Professional Liability</td>
                            <td class="py-3 px-4 text-[11px] font-medium">RM 1,800.00</td>
                            <td class="py-3 px-4 text-[11px]">30/01/2025</td>
                            <td class="py-3 px-4 text-[11px]">25/01/2025</td>
                            <td class="py-3 px-4 text-[11px]">
                                <span class="inline-block bg-green-100 text-green-800 px-2 py-1 rounded-full text-xs">Paid</span>
                            </td>
                            <td class="py-3 px-4">
                                <div class="flex justify-center space-x-2 items-center">
                                    <button class="p-1 bg-red-50 rounded hover:bg-red-100 border border-red-100" title="Banned">
                                        <span class="material-icons text-red-600 text-xs">block</span>
                                    </button>
                                    <a href="#" class="p-1 bg-blue-50 rounded hover:bg-blue-100 border border-blue-100" title="View">
                                        <span class="material-icons text-blue-600 text-xs">visibility</span>
                                    </a>
                                    <a href="#" class="p-1 bg-yellow-50 rounded hover:bg-yellow-100 border border-yellow-100" title="Edit">
                                        <span class="material-icons text-yellow-700 text-xs">edit</span>
                                    </a>
                                    <button class="p-1 bg-red-50 rounded hover:bg-red-100 border border-red-100" title="Delete">
                                        <span class="material-icons text-red-600 text-xs">delete</span>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        
        <!-- Mobile Card View -->
        <div class="md:hidden p-4 space-y-4">
            <!-- Bill Card 1 -->
            <div class="bg-gray-50 rounded-lg border border-gray-200 p-4">
                <div class="flex justify-between items-start mb-3">
                    <div>
                        <h3 class="font-semibold text-gray-800 text-sm">BL-2025-001</h3>
                        <p class="text-xs text-gray-600">Office Supplies Co</p>
                    </div>
                    <span class="inline-block bg-green-100 text-green-800 px-2 py-1 rounded-full text-xs">Paid</span>
                </div>
                
                <div class="space-y-2 mb-4">
                    <div class="flex justify-between">
                        <span class="text-xs text-gray-500">Category:</span>
                        <span class="text-xs font-medium">Office Supplies</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-xs text-gray-500">Description:</span>
                        <span class="text-xs font-medium">Stationery & Paper</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-xs text-gray-500">Amount:</span>
                        <span class="text-xs font-medium">RM 250.00</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-xs text-gray-500">Due Date:</span>
                        <span class="text-xs font-medium">15/02/2025</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-xs text-gray-500">Payment Date:</span>
                        <span class="text-xs font-medium">10/02/2025</span>
                    </div>
                </div>
                
                <!-- Mobile Action Buttons -->
                <div class="flex space-x-2">
                    <button class="flex-1 bg-red-100 text-red-700 py-2 px-3 rounded-md text-xs font-medium flex items-center justify-center">
                        <span class="material-icons text-sm mr-1">block</span>
                        Cancel
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
            
            <!-- Bill Card 2 -->
            <div class="bg-gray-50 rounded-lg border border-gray-200 p-4">
                <div class="flex justify-between items-start mb-3">
                    <div>
                        <h3 class="font-semibold text-gray-800 text-sm">BL-2025-002</h3>
                        <p class="text-xs text-gray-600">Internet Provider</p>
                    </div>
                    <span class="inline-block bg-yellow-100 text-yellow-800 px-2 py-1 rounded-full text-xs">Pending</span>
                </div>
                
                <div class="space-y-2 mb-4">
                    <div class="flex justify-between">
                        <span class="text-xs text-gray-500">Category:</span>
                        <span class="text-xs font-medium">Utilities</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-xs text-gray-500">Description:</span>
                        <span class="text-xs font-medium">Monthly Internet</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-xs text-gray-500">Amount:</span>
                        <span class="text-xs font-medium">RM 180.00</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-xs text-gray-500">Due Date:</span>
                        <span class="text-xs font-medium">20/02/2025</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-xs text-gray-500">Payment Date:</span>
                        <span class="text-xs font-medium">-</span>
                    </div>
                </div>
                
                <!-- Mobile Action Buttons -->
                <div class="flex space-x-2">
                    <button class="flex-1 bg-red-100 text-red-700 py-2 px-3 rounded-md text-xs font-medium flex items-center justify-center">
                        <span class="material-icons text-sm mr-1">block</span>
                        Cancel
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
            
            <!-- Bill Card 3 -->
            <div class="bg-gray-50 rounded-lg border border-gray-200 p-4">
                <div class="flex justify-between items-start mb-3">
                    <div>
                        <h3 class="font-semibold text-gray-800 text-sm">BL-2025-003</h3>
                        <p class="text-xs text-gray-600">Electricity Board</p>
                    </div>
                    <span class="inline-block bg-yellow-100 text-yellow-800 px-2 py-1 rounded-full text-xs">Pending</span>
                </div>
                
                <div class="space-y-2 mb-4">
                    <div class="flex justify-between">
                        <span class="text-xs text-gray-500">Category:</span>
                        <span class="text-xs font-medium">Utilities</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-xs text-gray-500">Description:</span>
                        <span class="text-xs font-medium">Monthly Electricity</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-xs text-gray-500">Amount:</span>
                        <span class="text-xs font-medium">RM 320.00</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-xs text-gray-500">Due Date:</span>
                        <span class="text-xs font-medium">25/02/2025</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-xs text-gray-500">Payment Date:</span>
                        <span class="text-xs font-medium">-</span>
                    </div>
                </div>
                
                <!-- Mobile Action Buttons -->
                <div class="flex space-x-2">
                    <button class="flex-1 bg-red-100 text-red-700 py-2 px-3 rounded-md text-xs font-medium flex items-center justify-center">
                        <span class="material-icons text-sm mr-1">block</span>
                        Cancel
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
            
            <!-- Bill Card 4 -->
            <div class="bg-gray-50 rounded-lg border border-gray-200 p-4">
                <div class="flex justify-between items-start mb-3">
                    <div>
                        <h3 class="font-semibold text-gray-800 text-sm">BL-2025-004</h3>
                        <p class="text-xs text-gray-600">Cleaning Services</p>
                    </div>
                    <span class="inline-block bg-yellow-100 text-yellow-800 px-2 py-1 rounded-full text-xs">Pending</span>
                </div>
                
                <div class="space-y-2 mb-4">
                    <div class="flex justify-between">
                        <span class="text-xs text-gray-500">Category:</span>
                        <span class="text-xs font-medium">Services</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-xs text-gray-500">Description:</span>
                        <span class="text-xs font-medium">Monthly Cleaning</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-xs text-gray-500">Amount:</span>
                        <span class="text-xs font-medium">RM 450.00</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-xs text-gray-500">Due Date:</span>
                        <span class="text-xs font-medium">28/02/2025</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-xs text-gray-500">Payment Date:</span>
                        <span class="text-xs font-medium">-</span>
                    </div>
                </div>
                
                <!-- Mobile Action Buttons -->
                <div class="flex space-x-2">
                    <button class="flex-1 bg-red-100 text-red-700 py-2 px-3 rounded-md text-xs font-medium flex items-center justify-center">
                        <span class="material-icons text-sm mr-1">block</span>
                        Cancel
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
            
            <!-- Bill Card 5 -->
            <div class="bg-gray-50 rounded-lg border border-gray-200 p-4">
                <div class="flex justify-between items-start mb-3">
                    <div>
                        <h3 class="font-semibold text-gray-800 text-sm">BL-2025-005</h3>
                        <p class="text-xs text-gray-600">Legal Database</p>
                    </div>
                    <span class="inline-block bg-yellow-100 text-yellow-800 px-2 py-1 rounded-full text-xs">Pending</span>
                </div>
                
                <div class="space-y-2 mb-4">
                    <div class="flex justify-between">
                        <span class="text-xs text-gray-500">Category:</span>
                        <span class="text-xs font-medium">Subscriptions</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-xs text-gray-500">Description:</span>
                        <span class="text-xs font-medium">Annual Subscription</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-xs text-gray-500">Amount:</span>
                        <span class="text-xs font-medium">RM 2,500.00</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-xs text-gray-500">Due Date:</span>
                        <span class="text-xs font-medium">15/03/2025</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-xs text-gray-500">Payment Date:</span>
                        <span class="text-xs font-medium">-</span>
                    </div>
                </div>
                
                <!-- Mobile Action Buttons -->
                <div class="flex space-x-2">
                    <button class="flex-1 bg-red-100 text-red-700 py-2 px-3 rounded-md text-xs font-medium flex items-center justify-center">
                        <span class="material-icons text-sm mr-1">block</span>
                        Cancel
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
            
            <!-- Bill Card 6 -->
            <div class="bg-gray-50 rounded-lg border border-gray-200 p-4">
                <div class="flex justify-between items-start mb-3">
                    <div>
                        <h3 class="font-semibold text-gray-800 text-sm">BL-2025-006</h3>
                        <p class="text-xs text-gray-600">Insurance Co</p>
                    </div>
                    <span class="inline-block bg-green-100 text-green-800 px-2 py-1 rounded-full text-xs">Paid</span>
                </div>
                
                <div class="space-y-2 mb-4">
                    <div class="flex justify-between">
                        <span class="text-xs text-gray-500">Category:</span>
                        <span class="text-xs font-medium">Insurance</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-xs text-gray-500">Description:</span>
                        <span class="text-xs font-medium">Professional Liability</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-xs text-gray-500">Amount:</span>
                        <span class="text-xs font-medium">RM 1,800.00</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-xs text-gray-500">Due Date:</span>
                        <span class="text-xs font-medium">30/01/2025</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-xs text-gray-500">Payment Date:</span>
                        <span class="text-xs font-medium">25/01/2025</span>
                    </div>
                </div>
                
                <!-- Mobile Action Buttons -->
                <div class="flex space-x-2">
                    <button class="flex-1 bg-red-100 text-red-700 py-2 px-3 rounded-md text-xs font-medium flex items-center justify-center">
                        <span class="material-icons text-sm mr-1">block</span>
                        Cancel
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
@endsection 