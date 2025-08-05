@extends('layouts.app')

@section('breadcrumb')
    Voucher
@endsection

@section('content')
<div class="px-6 pt-6 max-w-7xl mx-auto">
    <div class="bg-white rounded shadow-md border border-gray-300">
        <div class="p-6 border-b border-gray-200">
            <div class="flex justify-between items-start">
                <div>
                    <div class="flex items-center">
                        <span class="material-icons mr-2 text-purple-600">card_giftcard</span>
                        <h1 class="text-xl font-bold text-gray-800 text-[14px]">Voucher Management</h1>
                    </div>
                    <p class="text-xs text-gray-500 mt-1 ml-8 text-[11px]">Manage all vouchers and discount certificates.</p>
                </div>
                
                <!-- Add Voucher Button -->
                <button class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded-md text-xs font-medium flex items-center">
                    <span class="material-icons text-xs mr-1">add</span>
                    Add Voucher
                </button>
            </div>
        </div>
        
        <div class="p-6">
            <div class="overflow-visible border border-gray-200 rounded">
                <table class="min-w-full border-collapse">
                    <thead>
                        <tr class="bg-primary-light text-white uppercase text-xs">
                            <th class="py-3 px-4 text-left rounded-tl">Voucher No</th>
                            <th class="py-3 px-4 text-left">Client Name</th>
                            <th class="py-3 px-4 text-left">Voucher Type</th>
                            <th class="py-3 px-4 text-left">Value (RM)</th>
                            <th class="py-3 px-4 text-left">Issue Date</th>
                            <th class="py-3 px-4 text-left">Expiry Date</th>
                            <th class="py-3 px-4 text-left">Used Date</th>
                            <th class="py-3 px-4 text-left">Status</th>
                            <th class="py-3 px-4 text-center rounded-tr">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <tr class="hover:bg-gray-50">
                            <td class="py-3 px-4 text-[11px] font-medium">VC-2025-001</td>
                            <td class="py-3 px-4 text-[11px]">Ahmad bin Abdullah</td>
                            <td class="py-3 px-4 text-[11px]">Discount</td>
                            <td class="py-3 px-4 text-[11px] font-medium">RM 500.00</td>
                            <td class="py-3 px-4 text-[11px]">15/01/2025</td>
                            <td class="py-3 px-4 text-[11px]">15/03/2025</td>
                            <td class="py-3 px-4 text-[11px]">-</td>
                            <td class="py-3 px-4 text-[11px]">
                                <span class="inline-block bg-green-100 text-green-800 px-2 py-1 rounded-full text-xs">Active</span>
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
                            <td class="py-3 px-4 text-[11px] font-medium">VC-2025-002</td>
                            <td class="py-3 px-4 text-[11px]">Sdn Bhd Property</td>
                            <td class="py-3 px-4 text-[11px]">Credit</td>
                            <td class="py-3 px-4 text-[11px] font-medium">RM 1,000.00</td>
                            <td class="py-3 px-4 text-[11px]">16/01/2025</td>
                            <td class="py-3 px-4 text-[11px]">16/04/2025</td>
                            <td class="py-3 px-4 text-[11px]">18/01/2025</td>
                            <td class="py-3 px-4 text-[11px]">
                                <span class="inline-block bg-blue-100 text-blue-800 px-2 py-1 rounded-full text-xs">Used</span>
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
                            <td class="py-3 px-4 text-[11px] font-medium">VC-2025-003</td>
                            <td class="py-3 px-4 text-[11px]">Lim Siew Mei</td>
                            <td class="py-3 px-4 text-[11px]">Discount</td>
                            <td class="py-3 px-4 text-[11px] font-medium">RM 300.00</td>
                            <td class="py-3 px-4 text-[11px]">17/01/2025</td>
                            <td class="py-3 px-4 text-[11px]">17/02/2025</td>
                            <td class="py-3 px-4 text-[11px]">-</td>
                            <td class="py-3 px-4 text-[11px]">
                                <span class="inline-block bg-red-100 text-red-800 px-2 py-1 rounded-full text-xs">Expired</span>
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
                            <td class="py-3 px-4 text-[11px] font-medium">VC-2025-004</td>
                            <td class="py-3 px-4 text-[11px]">Tan Family Trust</td>
                            <td class="py-3 px-4 text-[11px]">Credit</td>
                            <td class="py-3 px-4 text-[11px] font-medium">RM 2,000.00</td>
                            <td class="py-3 px-4 text-[11px]">18/01/2025</td>
                            <td class="py-3 px-4 text-[11px]">18/06/2025</td>
                            <td class="py-3 px-4 text-[11px]">-</td>
                            <td class="py-3 px-4 text-[11px]">
                                <span class="inline-block bg-green-100 text-green-800 px-2 py-1 rounded-full text-xs">Active</span>
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
                            <td class="py-3 px-4 text-[11px] font-medium">VC-2025-005</td>
                            <td class="py-3 px-4 text-[11px]">Wong Corporation</td>
                            <td class="py-3 px-4 text-[11px]">Discount</td>
                            <td class="py-3 px-4 text-[11px] font-medium">RM 5,000.00</td>
                            <td class="py-3 px-4 text-[11px]">19/01/2025</td>
                            <td class="py-3 px-4 text-[11px]">19/12/2025</td>
                            <td class="py-3 px-4 text-[11px]">-</td>
                            <td class="py-3 px-4 text-[11px]">
                                <span class="inline-block bg-green-100 text-green-800 px-2 py-1 rounded-full text-xs">Active</span>
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
                            <td class="py-3 px-4 text-[11px] font-medium">VC-2025-006</td>
                            <td class="py-3 px-4 text-[11px]">Singh & Associates</td>
                            <td class="py-3 px-4 text-[11px]">Credit</td>
                            <td class="py-3 px-4 text-[11px] font-medium">RM 800.00</td>
                            <td class="py-3 px-4 text-[11px]">20/01/2025</td>
                            <td class="py-3 px-4 text-[11px]">20/05/2025</td>
                            <td class="py-3 px-4 text-[11px]">22/01/2025</td>
                            <td class="py-3 px-4 text-[11px]">
                                <span class="inline-block bg-blue-100 text-blue-800 px-2 py-1 rounded-full text-xs">Used</span>
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