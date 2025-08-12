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
                    <p class="text-xs text-gray-500 mt-1 ml-8">Manage all payment receipts and transaction records.</p>
                </div>
                
                <!-- Add Receipt Button -->
                <a href="{{ route('resit.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 md:px-3 md:py-1 rounded-md text-sm md:text-xs font-medium flex items-center justify-center md:justify-start w-full md:w-auto">
                    <span class="material-icons text-sm md:text-xs mr-1">add</span>
                    Add Receipt
                </a>
            </div>
        </div>
        
        <!-- Desktop Table View -->
        <div class="hidden md:block p-6">
            <div class="overflow-visible border border-gray-200 rounded">
                <table class="min-w-full border-collapse">
                    <thead>
                        <tr class="bg-primary-light text-white uppercase text-xs">
                            <th class="py-3 px-4 text-left rounded-tl">Receipt No</th>
                            <th class="py-3 px-4 text-left">Client Name</th>
                            <th class="py-3 px-4 text-left">Invoice No</th>
                            <th class="py-3 px-4 text-left">Amount (RM)</th>
                            <th class="py-3 px-4 text-left">Payment Method</th>
                            <th class="py-3 px-4 text-left">Payment Date</th>
                            <th class="py-3 px-4 text-left">Received By</th>
                            <th class="py-3 px-4 text-left">Status</th>
                            <th class="py-3 px-4 text-center rounded-tr">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <tr class="hover:bg-gray-50">
                            <td class="py-1 px-4 text-[11px] font-medium">RCP-2025-001</td>
                            <td class="py-1 px-4 text-[11px]">Ahmad bin Abdullah</td>
                            <td class="py-1 px-4 text-[11px]">INV-2025-001</td>
                            <td class="py-1 px-4 text-[11px] font-medium">RM 5,000.00</td>
                            <td class="py-1 px-4 text-[11px]">Bank Transfer</td>
                            <td class="py-1 px-4 text-[11px]">15/01/2025</td>
                            <td class="py-1 px-4 text-[11px]">A. Rahman</td>
                            <td class="py-1 px-4 text-[11px]">
                                <span class="inline-block bg-green-100 text-green-800 px-1.5 py-0.5 rounded-full text-[10px]">Confirmed</span>
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
                            <td class="py-3 px-4 text-[11px] font-medium">RCP-2025-002</td>
                            <td class="py-3 px-4 text-[11px]">Sdn Bhd Property</td>
                            <td class="py-3 px-4 text-[11px]">INV-2025-002</td>
                            <td class="py-3 px-4 text-[11px] font-medium">RM 8,500.00</td>
                            <td class="py-3 px-4 text-[11px]">Cash</td>
                            <td class="py-3 px-4 text-[11px]">16/01/2025</td>
                            <td class="py-3 px-4 text-[11px]">S. Kumar</td>
                            <td class="py-3 px-4 text-[11px]">
                                <span class="inline-block bg-green-100 text-green-800 px-2 py-1 rounded-full text-xs">Confirmed</span>
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
                            <td class="py-3 px-4 text-[11px] font-medium">RCP-2025-003</td>
                            <td class="py-3 px-4 text-[11px]">Lim Siew Mei</td>
                            <td class="py-3 px-4 text-[11px]">INV-2025-003</td>
                            <td class="py-3 px-4 text-[11px] font-medium">RM 1,600.00</td>
                            <td class="py-3 px-4 text-[11px]">Cheque</td>
                            <td class="py-3 px-4 text-[11px]">17/01/2025</td>
                            <td class="py-3 px-4 text-[11px]">M. Lim</td>
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
                            <td class="py-3 px-4 text-[11px] font-medium">RCP-2025-004</td>
                            <td class="py-3 px-4 text-[11px]">Tan Family Trust</td>
                            <td class="py-3 px-4 text-[11px]">INV-2025-004</td>
                            <td class="py-3 px-4 text-[11px] font-medium">RM 6,000.00</td>
                            <td class="py-3 px-4 text-[11px]">Bank Transfer</td>
                            <td class="py-3 px-4 text-[11px]">18/01/2025</td>
                            <td class="py-3 px-4 text-[11px]">N. Tan</td>
                            <td class="py-3 px-4 text-[11px]">
                                <span class="inline-block bg-green-100 text-green-800 px-2 py-1 rounded-full text-xs">Confirmed</span>
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
                            <td class="py-3 px-4 text-[11px] font-medium">RCP-2025-005</td>
                            <td class="py-3 px-4 text-[11px]">Wong Corporation</td>
                            <td class="py-3 px-4 text-[11px]">INV-2025-005</td>
                            <td class="py-3 px-4 text-[11px] font-medium">RM 25,000.00</td>
                            <td class="py-3 px-4 text-[11px]">Bank Transfer</td>
                            <td class="py-3 px-4 text-[11px]">19/01/2025</td>
                            <td class="py-3 px-4 text-[11px]">K. Wong</td>
                            <td class="py-3 px-4 text-[11px]">
                                <span class="inline-block bg-green-100 text-green-800 px-2 py-1 rounded-full text-xs">Confirmed</span>
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
                            <td class="py-3 px-4 text-[11px] font-medium">RCP-2025-006</td>
                            <td class="py-3 px-4 text-[11px]">Singh & Associates</td>
                            <td class="py-3 px-4 text-[11px]">INV-2025-006</td>
                            <td class="py-3 px-4 text-[11px] font-medium">RM 3,400.00</td>
                            <td class="py-3 px-4 text-[11px]">Cash</td>
                            <td class="py-3 px-4 text-[11px]">20/01/2025</td>
                            <td class="py-3 px-4 text-[11px]">R. Singh</td>
                            <td class="py-3 px-4 text-[11px]">
                                <span class="inline-block bg-green-100 text-green-800 px-2 py-1 rounded-full text-xs">Confirmed</span>
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
            <!-- Receipt Card 1 -->
            <div class="bg-gray-50 rounded-lg border border-gray-200 p-4">
                <div class="flex justify-between items-start mb-3">
                    <div>
                        <h3 class="font-semibold text-gray-800 text-sm">RCP-2025-001</h3>
                        <p class="text-xs text-gray-600">Ahmad bin Abdullah</p>
                    </div>
                    <span class="inline-block bg-green-100 text-green-800 px-2 py-1 rounded-full text-xs">Confirmed</span>
                </div>
                
                <div class="space-y-2 mb-4">
                    <div class="flex justify-between">
                        <span class="text-xs text-gray-500">Invoice No:</span>
                        <span class="text-xs font-medium">INV-2025-001</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-xs text-gray-500">Amount:</span>
                        <span class="text-xs font-medium">RM 5,000.00</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-xs text-gray-500">Payment Method:</span>
                        <span class="text-xs font-medium">Bank Transfer</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-xs text-gray-500">Payment Date:</span>
                        <span class="text-xs font-medium">15/01/2025</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-xs text-gray-500">Received By:</span>
                        <span class="text-xs font-medium">A. Rahman</span>
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
            
            <!-- Receipt Card 2 -->
            <div class="bg-gray-50 rounded-lg border border-gray-200 p-4">
                <div class="flex justify-between items-start mb-3">
                    <div>
                        <h3 class="font-semibold text-gray-800 text-sm">RCP-2025-002</h3>
                        <p class="text-xs text-gray-600">Sdn Bhd Property</p>
                    </div>
                    <span class="inline-block bg-green-100 text-green-800 px-2 py-1 rounded-full text-xs">Confirmed</span>
                </div>
                
                <div class="space-y-2 mb-4">
                    <div class="flex justify-between">
                        <span class="text-xs text-gray-500">Invoice No:</span>
                        <span class="text-xs font-medium">INV-2025-002</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-xs text-gray-500">Amount:</span>
                        <span class="text-xs font-medium">RM 8,500.00</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-xs text-gray-500">Payment Method:</span>
                        <span class="text-xs font-medium">Cash</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-xs text-gray-500">Payment Date:</span>
                        <span class="text-xs font-medium">16/01/2025</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-xs text-gray-500">Received By:</span>
                        <span class="text-xs font-medium">S. Kumar</span>
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
            
            <!-- Receipt Card 3 -->
            <div class="bg-gray-50 rounded-lg border border-gray-200 p-4">
                <div class="flex justify-between items-start mb-3">
                    <div>
                        <h3 class="font-semibold text-gray-800 text-sm">RCP-2025-003</h3>
                        <p class="text-xs text-gray-600">Lim Siew Mei</p>
                    </div>
                    <span class="inline-block bg-yellow-100 text-yellow-800 px-2 py-1 rounded-full text-xs">Pending</span>
                </div>
                
                <div class="space-y-2 mb-4">
                    <div class="flex justify-between">
                        <span class="text-xs text-gray-500">Invoice No:</span>
                        <span class="text-xs font-medium">INV-2025-003</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-xs text-gray-500">Amount:</span>
                        <span class="text-xs font-medium">RM 1,600.00</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-xs text-gray-500">Payment Method:</span>
                        <span class="text-xs font-medium">Cheque</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-xs text-gray-500">Payment Date:</span>
                        <span class="text-xs font-medium">17/01/2025</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-xs text-gray-500">Received By:</span>
                        <span class="text-xs font-medium">M. Lim</span>
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
            
            <!-- Receipt Card 4 -->
            <div class="bg-gray-50 rounded-lg border border-gray-200 p-4">
                <div class="flex justify-between items-start mb-3">
                    <div>
                        <h3 class="font-semibold text-gray-800 text-sm">RCP-2025-004</h3>
                        <p class="text-xs text-gray-600">Tan Family Trust</p>
                    </div>
                    <span class="inline-block bg-green-100 text-green-800 px-2 py-1 rounded-full text-xs">Confirmed</span>
                </div>
                
                <div class="space-y-2 mb-4">
                    <div class="flex justify-between">
                        <span class="text-xs text-gray-500">Invoice No:</span>
                        <span class="text-xs font-medium">INV-2025-004</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-xs text-gray-500">Amount:</span>
                        <span class="text-xs font-medium">RM 6,000.00</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-xs text-gray-500">Payment Method:</span>
                        <span class="text-xs font-medium">Bank Transfer</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-xs text-gray-500">Payment Date:</span>
                        <span class="text-xs font-medium">18/01/2025</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-xs text-gray-500">Received By:</span>
                        <span class="text-xs font-medium">N. Tan</span>
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
            
            <!-- Receipt Card 5 -->
            <div class="bg-gray-50 rounded-lg border border-gray-200 p-4">
                <div class="flex justify-between items-start mb-3">
                    <div>
                        <h3 class="font-semibold text-gray-800 text-sm">RCP-2025-005</h3>
                        <p class="text-xs text-gray-600">Wong Corporation</p>
                    </div>
                    <span class="inline-block bg-green-100 text-green-800 px-2 py-1 rounded-full text-xs">Confirmed</span>
                </div>
                
                <div class="space-y-2 mb-4">
                    <div class="flex justify-between">
                        <span class="text-xs text-gray-500">Invoice No:</span>
                        <span class="text-xs font-medium">INV-2025-005</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-xs text-gray-500">Amount:</span>
                        <span class="text-xs font-medium">RM 25,000.00</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-xs text-gray-500">Payment Method:</span>
                        <span class="text-xs font-medium">Bank Transfer</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-xs text-gray-500">Payment Date:</span>
                        <span class="text-xs font-medium">19/01/2025</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-xs text-gray-500">Received By:</span>
                        <span class="text-xs font-medium">K. Wong</span>
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
            
            <!-- Receipt Card 6 -->
            <div class="bg-gray-50 rounded-lg border border-gray-200 p-4">
                <div class="flex justify-between items-start mb-3">
                    <div>
                        <h3 class="font-semibold text-gray-800 text-sm">RCP-2025-006</h3>
                        <p class="text-xs text-gray-600">Singh & Associates</p>
                    </div>
                    <span class="inline-block bg-green-100 text-green-800 px-2 py-1 rounded-full text-xs">Confirmed</span>
                </div>
                
                <div class="space-y-2 mb-4">
                    <div class="flex justify-between">
                        <span class="text-xs text-gray-500">Invoice No:</span>
                        <span class="text-xs font-medium">INV-2025-006</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-xs text-gray-500">Amount:</span>
                        <span class="text-xs font-medium">RM 3,400.00</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-xs text-gray-500">Payment Method:</span>
                        <span class="text-xs font-medium">Cash</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-xs text-gray-500">Payment Date:</span>
                        <span class="text-xs font-medium">20/01/2025</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-xs text-gray-500">Received By:</span>
                        <span class="text-xs font-medium">R. Singh</span>
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