@extends('layouts.app')

@section('breadcrumb')
    Case
@endsection

@section('content')
<div class="px-4 md:px-6 pt-4 md:pt-6 pb-6 max-w-7xl mx-auto">
    <div class="bg-white rounded shadow-md border border-gray-300">
        <div class="p-4 md:p-6 border-b border-gray-200">
            <div class="flex flex-col md:flex-row md:justify-between md:items-start space-y-4 md:space-y-0">
                <div>
                    <div class="flex items-center">
                        <span class="material-icons mr-2 text-purple-600">folder</span>
                        <h1 class="text-lg md:text-xl font-bold text-gray-800">Case Management</h1>
                    </div>
                    <p class="text-xs text-gray-500 mt-1 ml-8">Manage all court cases, clients, and related documents.</p>
                </div>
                
                <!-- Add Case Button -->
                <a href="{{ route('case.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 md:px-3 md:py-1 rounded-md text-sm md:text-xs font-medium flex items-center justify-center md:justify-start w-full md:w-auto">
                    <span class="material-icons text-sm md:text-xs mr-1">add</span>
                    Add Case
                </a>
            </div>
        </div>
        
        <!-- Desktop Table View -->
        <div class="hidden md:block p-6">
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
                            <td class="py-1 px-4 text-[11px] font-medium">2024-010</td>
                            <td class="py-1 px-4 text-[11px]">NF-00123</td>
                            <td class="py-1 px-4 text-[11px]">CR-2024-001</td>
                            <td class="py-1 px-4 text-[11px]">John Doe</td>
                            <td class="py-1 px-4 text-[11px]">A. Rahman</td>
                            <td class="py-1 px-4 text-[11px]">27 Jul 2025</td>
                            <td class="py-1 px-4 text-[11px]">
                                <span class="inline-block bg-green-100 text-green-800 px-1.5 py-0.5 rounded-full text-[10px]">Open Case</span>
                            </td>
                            <td class="py-1 px-4">
                                <div class="flex justify-center space-x-1 items-center" x-data="{ open: false }">
                                    <button @click="open = !open" class="p-0.5 text-purple-600 hover:text-purple-700" title="Change Status">
                                        <span class="material-icons text-base">add</span>
                                    </button>
                                    <a href="{{ route('case.view') }}" class="p-0.5 text-blue-600 hover:text-blue-700" title="View">
                                        <span class="material-icons text-base">visibility</span>
                                    </a>
                                    <a href="#" class="p-0.5 text-yellow-600 hover:text-yellow-700" title="Edit">
                                        <span class="material-icons text-base">edit</span>
                                    </a>
                                    <button class="p-0.5 text-red-600 hover:text-red-700" title="Delete">
                                        <span class="material-icons text-base">delete</span>
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
                            <td class="py-1 px-4 text-[11px] font-medium">2024-009</td>
                            <td class="py-1 px-4 text-[11px]">NF-00124</td>
                            <td class="py-1 px-4 text-[11px]">CR-2024-002</td>
                            <td class="py-1 px-4 text-[11px]">Jane Smith</td>
                            <td class="py-1 px-4 text-[11px]">S. Kumar</td>
                            <td class="py-1 px-4 text-[11px]">25 Jul 2025</td>
                            <td class="py-1 px-4 text-[11px]">
                                <span class="inline-block bg-green-100 text-green-800 px-2 py-1 rounded-full">Open Case</span>
                            </td>
                            <td class="py-1 px-4">
                                <div class="flex justify-center space-x-2 items-center" x-data="{ open: false }">
                                    <button @click="open = !open" class="p-1 bg-purple-50 rounded hover:bg-purple-100 border border-purple-100" title="Change Status">
                                        <span class="material-icons text-purple-600 text-xs">add</span>
                                    </button>
                                    <a href="{{ route('case.view') }}" class="p-1 bg-blue-50 rounded hover:bg-blue-100 border border-blue-100" title="View">
                                        <span class="material-icons text-blue-600 text-xs">visibility</span>
                                    </a>
                                    <a href="{{ route('case.edit') }}" class="p-0.5 text-yellow-600 hover:text-yellow-700" title="Edit">
                                        <span class="material-icons text-base">edit</span>
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
                            <td class="py-1 px-4 text-[11px] font-medium">2024-008</td>
                            <td class="py-1 px-4 text-[11px]">NF-00125</td>
                            <td class="py-1 px-4 text-[11px]">CR-2024-003</td>
                            <td class="py-1 px-4 text-[11px]">Ahmad Ali</td>
                            <td class="py-1 px-4 text-[11px]">M. Lim</td>
                            <td class="py-1 px-4 text-[11px]">20 Jul 2025</td>
                            <td class="py-1 px-4 text-[11px]">
                                <span class="inline-block bg-green-100 text-green-800 px-2 py-1 rounded-full">Open Case</span>
                            </td>
                            <td class="py-1 px-4">
                                <div class="flex justify-center space-x-2 items-center" x-data="{ open: false }">
                                    <button @click="open = !open" class="p-1 bg-purple-50 rounded hover:bg-purple-100 border border-purple-100" title="Change Status">
                                        <span class="material-icons text-purple-600 text-xs">add</span>
                                    </button>
                                    <a href="{{ route('case.view') }}" class="p-1 bg-blue-50 rounded hover:bg-blue-100 border border-blue-100" title="View">
                                        <span class="material-icons text-blue-600 text-xs">visibility</span>
                                    </a>
                                    <a href="{{ route('case.edit') }}" class="p-0.5 text-yellow-600 hover:text-yellow-700" title="Edit">
                                        <span class="material-icons text-base">edit</span>
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
        
        <!-- Mobile Card View -->
        <div class="md:hidden p-4 space-y-4">
            <!-- Case Card 1 -->
            <div class="bg-gray-50 rounded-lg border border-gray-200 p-4" x-data="{ showStatusMenu: false }">
                <div class="flex justify-between items-start mb-3">
                    <div>
                        <h3 class="font-semibold text-gray-800 text-sm">2024-010</h3>
                        <p class="text-xs text-gray-600">NF-00123</p>
                    </div>
                    <span class="inline-block bg-green-100 text-green-800 px-2 py-1 rounded-full text-xs">Open Case</span>
                </div>
                
                <div class="space-y-2 mb-4">
                    <div class="flex justify-between">
                        <span class="text-xs text-gray-500">Client:</span>
                        <span class="text-xs font-medium">John Doe</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-xs text-gray-500">Partner:</span>
                        <span class="text-xs font-medium">A. Rahman</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-xs text-gray-500">Court Ref:</span>
                        <span class="text-xs font-medium">CR-2024-001</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-xs text-gray-500">Open Date:</span>
                        <span class="text-xs font-medium">27 Jul 2025</span>
                    </div>
                </div>
                
                <!-- Mobile Action Buttons -->
                <div class="flex space-x-2">
                    <button @click="showStatusMenu = !showStatusMenu" class="flex-1 bg-purple-100 text-purple-700 py-2 px-3 rounded-md text-xs font-medium flex items-center justify-center">
                        <span class="material-icons text-sm mr-1">add</span>
                        Status
                    </button>
                    <a href="{{ route('case.view') }}" class="flex-1 bg-blue-100 text-blue-700 py-2 px-3 rounded-md text-xs font-medium flex items-center justify-center">
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
                
                <!-- Mobile Status Dropdown -->
                <div x-show="showStatusMenu" @click.away="showStatusMenu = false" class="mt-3 bg-white rounded-md shadow-lg border border-gray-200 p-3">
                    <div class="mb-2 font-bold text-purple-700 text-xs">Change Status</div>
                    <div class="grid grid-cols-2 gap-2">
                        <button class="w-full text-left px-3 py-2 text-xs hover:bg-purple-50 rounded">Consultation</button>
                        <button class="w-full text-left px-3 py-2 text-xs hover:bg-purple-50 rounded">Quotation</button>
                        <button class="w-full text-left px-3 py-2 text-xs hover:bg-purple-50 rounded">Open file</button>
                        <button class="w-full text-left px-3 py-2 text-xs hover:bg-purple-50 rounded">Proceed</button>
                        <button class="w-full text-left px-3 py-2 text-xs hover:bg-purple-50 rounded">Closed file</button>
                        <button class="w-full text-left px-3 py-2 text-xs hover:bg-purple-50 rounded">Cancel</button>
                    </div>
                </div>
            </div>
            
            <!-- Case Card 2 -->
            <div class="bg-gray-50 rounded-lg border border-gray-200 p-4" x-data="{ showStatusMenu: false }">
                <div class="flex justify-between items-start mb-3">
                    <div>
                        <h3 class="font-semibold text-gray-800 text-sm">2024-009</h3>
                        <p class="text-xs text-gray-600">NF-00124</p>
                    </div>
                    <span class="inline-block bg-green-100 text-green-800 px-2 py-1 rounded-full text-xs">Open Case</span>
                </div>
                
                <div class="space-y-2 mb-4">
                    <div class="flex justify-between">
                        <span class="text-xs text-gray-500">Client:</span>
                        <span class="text-xs font-medium">Jane Smith</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-xs text-gray-500">Partner:</span>
                        <span class="text-xs font-medium">S. Kumar</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-xs text-gray-500">Court Ref:</span>
                        <span class="text-xs font-medium">CR-2024-002</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-xs text-gray-500">Open Date:</span>
                        <span class="text-xs font-medium">25 Jul 2025</span>
                    </div>
                </div>
                
                <!-- Mobile Action Buttons -->
                <div class="flex space-x-2">
                    <button @click="showStatusMenu = !showStatusMenu" class="flex-1 bg-purple-100 text-purple-700 py-2 px-3 rounded-md text-xs font-medium flex items-center justify-center">
                        <span class="material-icons text-sm mr-1">add</span>
                        Status
                    </button>
                    <a href="{{ route('case.view') }}" class="flex-1 bg-blue-100 text-blue-700 py-2 px-3 rounded-md text-xs font-medium flex items-center justify-center">
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
                
                <!-- Mobile Status Dropdown -->
                <div x-show="showStatusMenu" @click.away="showStatusMenu = false" class="mt-3 bg-white rounded-md shadow-lg border border-gray-200 p-3">
                    <div class="mb-2 font-bold text-purple-700 text-xs">Change Status</div>
                    <div class="grid grid-cols-2 gap-2">
                        <button class="w-full text-left px-3 py-2 text-xs hover:bg-purple-50 rounded">Consultation</button>
                        <button class="w-full text-left px-3 py-2 text-xs hover:bg-purple-50 rounded">Quotation</button>
                        <button class="w-full text-left px-3 py-2 text-xs hover:bg-purple-50 rounded">Open file</button>
                        <button class="w-full text-left px-3 py-2 text-xs hover:bg-purple-50 rounded">Proceed</button>
                        <button class="w-full text-left px-3 py-2 text-xs hover:bg-purple-50 rounded">Closed file</button>
                        <button class="w-full text-left px-3 py-2 text-xs hover:bg-purple-50 rounded">Cancel</button>
                    </div>
                </div>
            </div>
            
            <!-- Case Card 3 -->
            <div class="bg-gray-50 rounded-lg border border-gray-200 p-4" x-data="{ showStatusMenu: false }">
                <div class="flex justify-between items-start mb-3">
                    <div>
                        <h3 class="font-semibold text-gray-800 text-sm">2024-008</h3>
                        <p class="text-xs text-gray-600">NF-00125</p>
                    </div>
                    <span class="inline-block bg-green-100 text-green-800 px-2 py-1 rounded-full text-xs">Open Case</span>
                </div>
                
                <div class="space-y-2 mb-4">
                    <div class="flex justify-between">
                        <span class="text-xs text-gray-500">Client:</span>
                        <span class="text-xs font-medium">Ahmad Ali</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-xs text-gray-500">Partner:</span>
                        <span class="text-xs font-medium">M. Lim</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-xs text-gray-500">Court Ref:</span>
                        <span class="text-xs font-medium">CR-2024-003</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-xs text-gray-500">Open Date:</span>
                        <span class="text-xs font-medium">20 Jul 2025</span>
                    </div>
                </div>
                
                <!-- Mobile Action Buttons -->
                <div class="flex space-x-2">
                    <button @click="showStatusMenu = !showStatusMenu" class="flex-1 bg-purple-100 text-purple-700 py-2 px-3 rounded-md text-xs font-medium flex items-center justify-center">
                        <span class="material-icons text-sm mr-1">add</span>
                        Status
                    </button>
                    <a href="{{ route('case.view') }}" class="flex-1 bg-blue-100 text-blue-700 py-2 px-3 rounded-md text-xs font-medium flex items-center justify-center">
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
                
                <!-- Mobile Status Dropdown -->
                <div x-show="showStatusMenu" @click.away="showStatusMenu = false" class="mt-3 bg-white rounded-md shadow-lg border border-gray-200 p-3">
                    <div class="mb-2 font-bold text-purple-700 text-xs">Change Status</div>
                    <div class="grid grid-cols-2 gap-2">
                        <button class="w-full text-left px-3 py-2 text-xs hover:bg-purple-50 rounded">Consultation</button>
                        <button class="w-full text-left px-3 py-2 text-xs hover:bg-purple-50 rounded">Quotation</button>
                        <button class="w-full text-left px-3 py-2 text-xs hover:bg-purple-50 rounded">Open file</button>
                        <button class="w-full text-left px-3 py-2 text-xs hover:bg-purple-50 rounded">Proceed</button>
                        <button class="w-full text-left px-3 py-2 text-xs hover:bg-purple-50 rounded">Closed file</button>
                        <button class="w-full text-left px-3 py-2 text-xs hover:bg-purple-50 rounded">Cancel</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 