@extends('layouts.app')

@section('breadcrumb')
    Bill
@endsection

@section('content')
<div class="px-6 pt-6 max-w-7xl mx-auto">
    <div class="bg-white rounded shadow-md border border-gray-300">
        <div class="p-6 border-b border-gray-200">
            <div class="flex justify-between items-start">
                <div>
                    <div class="flex items-center">
                        <span class="material-icons mr-2 text-orange-600">account_balance_wallet</span>
                        <h1 class="text-xl font-bold text-gray-800 text-[14px]">Bill Management</h1>
                    </div>
                    <p class="text-xs text-gray-500 mt-1 ml-8 text-[11px]">Manage all bills and expense records.</p>
                </div>
                
                <!-- Add Bill Button -->
                <button class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded-md text-xs font-medium flex items-center">
                    <span class="material-icons text-xs mr-1">add</span>
                    Add Bill
                </button>
            </div>
        </div>
        
        <div class="p-6">
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
                            <td class="py-3 px-4 text-[11px] font-medium">BL-2025-001</td>
                            <td class="py-3 px-4 text-[11px]">Office Supplies Co</td>
                            <td class="py-3 px-4 text-[11px]">Office Supplies</td>
                            <td class="py-3 px-4 text-[11px]">Stationery & Paper</td>
                            <td class="py-3 px-4 text-[11px] font-medium">RM 250.00</td>
                            <td class="py-3 px-4 text-[11px]">15/02/2025</td>
                            <td class="py-3 px-4 text-[11px]">10/02/2025</td>
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
    </div>
</div>
@endsection 