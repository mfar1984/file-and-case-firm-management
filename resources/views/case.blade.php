@extends('layouts.app')

@section('breadcrumb')
    Case
@endsection

@section('content')
<div class="px-6 pt-6 max-w-7xl mx-auto">
    <div class="bg-white rounded shadow-md border border-gray-300">
        <div class="p-6 border-b border-gray-200">
            <div class="flex items-center">
                <span class="material-icons mr-2 text-purple-600">folder</span>
                <h1 class="text-xl font-bold text-gray-800 text-[14px]">Case Management</h1>
            </div>
            <p class="text-xs text-gray-500 mt-1 ml-8 text-[11px]">Manage all court cases, clients, and related documents.</p>
        </div>
        <div class="p-6">
            <div class="overflow-visible border border-gray-200 rounded">
                <table class="min-w-full border-collapse">
                    <thead>
                        <tr class="bg-primary-light text-white uppercase text-xs">
                            <th class="py-3 px-4 text-left rounded-tl">Case Ref</th>
                            <th class="py-3 px-4 text-left">File Ref</th>
                            <th class="py-3 px-4 text-left">Court Ref</th>
                            <th class="py-3 px-4 text-left">Client Name</th>
                            <th class="py-3 px-4 text-left">Partner In Charge</th>
                            <th class="py-3 px-4 text-left">Open Date</th>
                            <th class="py-3 px-4 text-left">Status</th>
                            <th class="py-3 px-4 text-center rounded-tr">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <tr class="hover:bg-gray-50">
                            <td class="py-3 px-4 text-[11px] font-medium">2024-010</td>
                            <td class="py-3 px-4 text-[11px]">NF-00123</td>
                            <td class="py-3 px-4 text-[11px]">CR-2024-001</td>
                            <td class="py-3 px-4 text-[11px]">John Doe</td>
                            <td class="py-3 px-4 text-[11px]">A. Rahman</td>
                            <td class="py-3 px-4 text-[11px]">27 Jul 2025</td>
                            <td class="py-3 px-4 text-[11px]">
                                <span class="inline-block bg-green-100 text-green-800 px-2 py-1 rounded-full">Open Case</span>
                            </td>
                            <td class="py-3 px-4">
                                <div class="flex justify-center space-x-2 items-center" x-data="{ open: false }">
                                    <button @click="open = !open" class="p-1 bg-purple-50 rounded hover:bg-purple-100 border border-purple-100" title="Change Status">
                                        <span class="material-icons text-purple-600 text-xs">add</span>
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
                                    <div x-show="open" @click.away="open = false" class="absolute z-50 mt-2 w-64 bg-purple-50 rounded shadow-xl border border-purple-200 p-3 text-[11px]">
                                        <div class="mb-2 font-bold text-purple-700">Change Status</div>
                                        <ul class="space-y-1">
                                            <li><button class="w-full text-left px-2 py-1 hover:bg-purple-100 rounded">Consultation</button></li>
                                            <li><button class="w-full text-left px-2 py-1 hover:bg-purple-100 rounded">Quotation</button></li>
                                            <li><button class="w-full text-left px-2 py-1 hover:bg-purple-100 rounded">Open file</button></li>
                                            <li><button class="w-full text-left px-2 py-1 hover:bg-purple-100 rounded">Proceed</button></li>
                                            <li><button class="w-full text-left px-2 py-1 hover:bg-purple-100 rounded">Closed file</button></li>
                                            <li><button class="w-full text-left px-2 py-1 hover:bg-purple-100 rounded">Cancel</button></li>
                                        </ul>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr class="hover:bg-gray-50">
                            <td class="py-3 px-4 text-[11px] font-medium">2024-009</td>
                            <td class="py-3 px-4 text-[11px]">NF-00124</td>
                            <td class="py-3 px-4 text-[11px]">CR-2024-002</td>
                            <td class="py-3 px-4 text-[11px]">Jane Smith</td>
                            <td class="py-3 px-4 text-[11px]">S. Kumar</td>
                            <td class="py-3 px-4 text-[11px]">25 Jul 2025</td>
                            <td class="py-3 px-4 text-[11px]">
                                <span class="inline-block bg-green-100 text-green-800 px-2 py-1 rounded-full">Open Case</span>
                            </td>
                            <td class="py-3 px-4">
                                <div class="flex justify-center space-x-2 items-center" x-data="{ open: false }">
                                    <button @click="open = !open" class="p-1 bg-purple-50 rounded hover:bg-purple-100 border border-purple-100" title="Change Status">
                                        <span class="material-icons text-purple-600 text-xs">add</span>
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
                                    <div x-show="open" @click.away="open = false" class="absolute z-50 mt-2 w-64 bg-purple-50 rounded shadow-xl border border-purple-200 p-3 text-[11px]">
                                        <div class="mb-2 font-bold text-purple-700">Change Status</div>
                                        <ul class="space-y-1">
                                            <li><button class="w-full text-left px-2 py-1 hover:bg-purple-100 rounded">Consultation</button></li>
                                            <li><button class="w-full text-left px-2 py-1 hover:bg-purple-100 rounded">Quotation</button></li>
                                            <li><button class="w-full text-left px-2 py-1 hover:bg-purple-100 rounded">Open file</button></li>
                                            <li><button class="w-full text-left px-2 py-1 hover:bg-purple-100 rounded">Proceed</button></li>
                                            <li><button class="w-full text-left px-2 py-1 hover:bg-purple-100 rounded">Closed file</button></li>
                                            <li><button class="w-full text-left px-2 py-1 hover:bg-purple-100 rounded">Cancel</button></li>
                                        </ul>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr class="hover:bg-gray-50">
                            <td class="py-3 px-4 text-[11px] font-medium">2024-008</td>
                            <td class="py-3 px-4 text-[11px]">NF-00125</td>
                            <td class="py-3 px-4 text-[11px]">CR-2024-003</td>
                            <td class="py-3 px-4 text-[11px]">Ahmad Ali</td>
                            <td class="py-3 px-4 text-[11px]">M. Lim</td>
                            <td class="py-3 px-4 text-[11px]">20 Jul 2025</td>
                            <td class="py-3 px-4 text-[11px]">
                                <span class="inline-block bg-green-100 text-green-800 px-2 py-1 rounded-full">Open Case</span>
                            </td>
                            <td class="py-3 px-4">
                                <div class="flex justify-center space-x-2 items-center" x-data="{ open: false }">
                                    <button @click="open = !open" class="p-1 bg-purple-50 rounded hover:bg-purple-100 border border-purple-100" title="Change Status">
                                        <span class="material-icons text-purple-600 text-xs">add</span>
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
                                    <div x-show="open" @click.away="open = false" class="absolute z-50 mt-2 w-64 bg-purple-50 rounded shadow-xl border border-purple-200 p-3 text-[11px]">
                                        <div class="mb-2 font-bold text-purple-700">Change Status</div>
                                        <ul class="space-y-1">
                                            <li><button class="w-full text-left px-2 py-1 hover:bg-purple-100 rounded">Consultation</button></li>
                                            <li><button class="w-full text-left px-2 py-1 hover:bg-purple-100 rounded">Quotation</button></li>
                                            <li><button class="w-full text-left px-2 py-1 hover:bg-purple-100 rounded">Open file</button></li>
                                            <li><button class="w-full text-left px-2 py-1 hover:bg-purple-100 rounded">Proceed</button></li>
                                            <li><button class="w-full text-left px-2 py-1 hover:bg-purple-100 rounded">Closed file</button></li>
                                            <li><button class="w-full text-left px-2 py-1 hover:bg-purple-100 rounded">Cancel</button></li>
                                        </ul>
                                    </div>
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