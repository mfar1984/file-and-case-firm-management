@extends('layouts.app')

@section('breadcrumb')
    Settings > Role Management
@endsection

@section('content')
<div class="px-4 md:px-6 pt-4 md:pt-6 pb-6 max-w-7xl mx-auto">
    <div class="bg-white rounded shadow-md border border-gray-300">
        <div class="p-4 md:p-6 border-b border-gray-200">
            <div class="flex flex-col md:flex-row md:justify-between md:items-start">
                <div class="mb-4 md:mb-0">
                    <div class="flex items-center">
                        <span class="material-icons mr-2 text-purple-600">admin_panel_settings</span>
                        <h1 class="text-lg md:text-xl font-bold text-gray-800 text-[14px]">Role Management</h1>
                    </div>
                    <p class="text-xs text-gray-500 mt-1 ml-8 text-[11px]">Manage user roles and their permissions.</p>
                </div>
                
                <!-- Add Role Button -->
                <button class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 md:px-3 md:py-1 rounded-md text-sm md:text-xs font-medium flex items-center justify-center md:justify-start w-full md:w-auto">
                    <span class="material-icons text-xs mr-1">add</span>
                    Add Role
                </button>
            </div>
        </div>
        
        <!-- Desktop Table View -->
        <div class="hidden md:block p-6">
            <div class="overflow-visible border border-gray-200 rounded">
                <table class="min-w-full border-collapse">
                    <thead>
                        <tr class="bg-primary-light text-white uppercase text-xs">
                            <th class="py-3 px-4 text-left rounded-tl">Role Name</th>
                            <th class="py-3 px-4 text-left">Description</th>
                            <th class="py-3 px-4 text-left">Permissions</th>
                            <th class="py-3 px-4 text-left">Users Count</th>
                            <th class="py-3 px-4 text-left">Status</th>
                            <th class="py-3 px-4 text-center rounded-tr">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <tr class="hover:bg-gray-50">
                            <td class="py-3 px-4 text-[11px] font-medium">Administrator</td>
                            <td class="py-3 px-4 text-[11px]">Full system access and control</td>
                            <td class="py-3 px-4 text-[11px]">
                                <div class="flex flex-wrap gap-1">
                                    <span class="inline-block bg-blue-100 text-blue-800 px-1 py-0.5 rounded text-xs">All</span>
                                </div>
                            </td>
                            <td class="py-3 px-4 text-[11px]">1</td>
                            <td class="py-3 px-4 text-[11px]">
                                <span class="inline-block bg-green-100 text-green-800 px-2 py-1 rounded-full text-xs">Active</span>
                            </td>
                            <td class="py-3 px-4">
                                <div class="flex justify-center space-x-2 items-center">
                                    <button class="p-1 bg-yellow-50 rounded hover:bg-yellow-100 border border-yellow-100" title="Edit">
                                        <span class="material-icons text-yellow-700 text-xs">edit</span>
                                    </button>
                                    <button class="p-1 bg-red-50 rounded hover:bg-red-100 border border-red-100" title="Delete">
                                        <span class="material-icons text-red-600 text-xs">delete</span>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <tr class="hover:bg-gray-50">
                            <td class="py-3 px-4 text-[11px] font-medium">Firm</td>
                            <td class="py-3 px-4 text-[11px]">Firm staff with comprehensive access</td>
                            <td class="py-3 px-4 text-[11px]">
                                <div class="flex flex-wrap gap-1">
                                    <span class="inline-block bg-green-100 text-green-800 px-1 py-0.5 rounded text-xs">Cases</span>
                                    <span class="inline-block bg-green-100 text-green-800 px-1 py-0.5 rounded text-xs">Clients</span>
                                    <span class="inline-block bg-green-100 text-green-800 px-1 py-0.5 rounded text-xs">Files</span>
                                    <span class="inline-block bg-green-100 text-green-800 px-1 py-0.5 rounded text-xs">Accounting</span>
                                    <span class="inline-block bg-green-100 text-green-800 px-1 py-0.5 rounded text-xs">Partners</span>
                                </div>
                            </td>
                            <td class="py-3 px-4 text-[11px]">5</td>
                            <td class="py-3 px-4 text-[11px]">
                                <span class="inline-block bg-green-100 text-green-800 px-2 py-1 rounded-full text-xs">Active</span>
                            </td>
                            <td class="py-3 px-4">
                                <div class="flex justify-center space-x-2 items-center">
                                    <button class="p-1 bg-yellow-50 rounded hover:bg-yellow-100 border border-yellow-100" title="Edit">
                                        <span class="material-icons text-yellow-700 text-xs">edit</span>
                                    </button>
                                    <button class="p-1 bg-red-50 rounded hover:bg-red-100 border border-red-100" title="Delete">
                                        <span class="material-icons text-red-600 text-xs">delete</span>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <tr class="hover:bg-gray-50">
                            <td class="py-3 px-4 text-[11px] font-medium">Partner</td>
                            <td class="py-3 px-4 text-[11px]">External partner with case access</td>
                            <td class="py-3 px-4 text-[11px]">
                                <div class="flex flex-wrap gap-1">
                                    <span class="inline-block bg-green-100 text-green-800 px-1 py-0.5 rounded text-xs">Cases</span>
                                    <span class="inline-block bg-green-100 text-green-800 px-1 py-0.5 rounded text-xs">Clients</span>
                                    <span class="inline-block bg-green-100 text-green-800 px-1 py-0.5 rounded text-xs">Files</span>
                                    <span class="inline-block bg-yellow-100 text-yellow-800 px-1 py-0.5 rounded text-xs">Limited</span>
                                </div>
                            </td>
                            <td class="py-3 px-4 text-[11px]">3</td>
                            <td class="py-3 px-4 text-[11px]">
                                <span class="inline-block bg-green-100 text-green-800 px-2 py-1 rounded-full text-xs">Active</span>
                            </td>
                            <td class="py-3 px-4">
                                <div class="flex justify-center space-x-2 items-center">
                                    <button class="p-1 bg-yellow-50 rounded hover:bg-yellow-100 border border-yellow-100" title="Edit">
                                        <span class="material-icons text-yellow-700 text-xs">edit</span>
                                    </button>
                                    <button class="p-1 bg-red-50 rounded hover:bg-red-100 border border-red-100" title="Delete">
                                        <span class="material-icons text-red-600 text-xs">delete</span>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <tr class="hover:bg-gray-50">
                            <td class="py-3 px-4 text-[11px] font-medium">Client</td>
                            <td class="py-3 px-4 text-[11px]">Client access to their own cases</td>
                            <td class="py-3 px-4 text-[11px]">
                                <div class="flex flex-wrap gap-1">
                                    <span class="inline-block bg-green-100 text-green-800 px-1 py-0.5 rounded text-xs">Own Cases</span>
                                    <span class="inline-block bg-green-100 text-green-800 px-1 py-0.5 rounded text-xs">Documents</span>
                                    <span class="inline-block bg-yellow-100 text-yellow-800 px-1 py-0.5 rounded text-xs">View Only</span>
                                </div>
                            </td>
                            <td class="py-3 px-4 text-[11px]">25</td>
                            <td class="py-3 px-4 text-[11px]">
                                <span class="inline-block bg-green-100 text-green-800 px-2 py-1 rounded-full text-xs">Active</span>
                            </td>
                            <td class="py-3 px-4">
                                <div class="flex justify-center space-x-2 items-center">
                                    <button class="p-1 bg-yellow-50 rounded hover:bg-yellow-100 border border-yellow-100" title="Edit">
                                        <span class="material-icons text-yellow-700 text-xs">edit</span>
                                    </button>
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
            <!-- Role Card 1 -->
            <div class="bg-white border border-gray-200 rounded-lg p-4 space-y-3">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <span class="text-sm font-medium text-gray-800">Administrator</span>
                        <span class="inline-block bg-green-100 text-green-800 px-2 py-1 rounded-full text-xs">Active</span>
                    </div>
                    <div class="flex space-x-2">
                        <button class="p-2 bg-yellow-50 rounded hover:bg-yellow-100 border border-yellow-100">
                            <span class="material-icons text-yellow-700 text-sm">edit</span>
                        </button>
                        <button class="p-2 bg-red-50 rounded hover:bg-red-100 border border-red-100">
                            <span class="material-icons text-red-600 text-sm">delete</span>
                        </button>
                    </div>
                </div>
                <div class="space-y-2">
                    <div class="flex justify-between">
                        <span class="text-xs font-medium text-gray-600">Description:</span>
                        <span class="text-xs text-gray-800">Full system access and control</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-xs font-medium text-gray-600">Users:</span>
                        <span class="text-xs text-gray-800">1</span>
                    </div>
                    <div class="pt-2 border-t border-gray-100">
                        <div class="flex flex-wrap gap-1">
                            <span class="inline-block bg-blue-100 text-blue-800 px-1 py-0.5 rounded text-xs">All</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Role Card 2 -->
            <div class="bg-white border border-gray-200 rounded-lg p-4 space-y-3">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <span class="text-sm font-medium text-gray-800">Firm</span>
                        <span class="inline-block bg-green-100 text-green-800 px-2 py-1 rounded-full text-xs">Active</span>
                    </div>
                    <div class="flex space-x-2">
                        <button class="p-2 bg-yellow-50 rounded hover:bg-yellow-100 border border-yellow-100">
                            <span class="material-icons text-yellow-700 text-sm">edit</span>
                        </button>
                        <button class="p-2 bg-red-50 rounded hover:bg-red-100 border border-red-100">
                            <span class="material-icons text-red-600 text-sm">delete</span>
                        </button>
                    </div>
                </div>
                <div class="space-y-2">
                    <div class="flex justify-between">
                        <span class="text-xs font-medium text-gray-600">Description:</span>
                        <span class="text-xs text-gray-800">Firm staff with comprehensive access</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-xs font-medium text-gray-600">Users:</span>
                        <span class="text-xs text-gray-800">5</span>
                    </div>
                    <div class="pt-2 border-t border-gray-100">
                        <div class="flex flex-wrap gap-1">
                            <span class="inline-block bg-green-100 text-green-800 px-1 py-0.5 rounded text-xs">Cases</span>
                            <span class="inline-block bg-green-100 text-green-800 px-1 py-0.5 rounded text-xs">Clients</span>
                            <span class="inline-block bg-green-100 text-green-800 px-1 py-0.5 rounded text-xs">Files</span>
                            <span class="inline-block bg-green-100 text-green-800 px-1 py-0.5 rounded text-xs">Accounting</span>
                            <span class="inline-block bg-green-100 text-green-800 px-1 py-0.5 rounded text-xs">Partners</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Role Card 3 -->
            <div class="bg-white border border-gray-200 rounded-lg p-4 space-y-3">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <span class="text-sm font-medium text-gray-800">Partner</span>
                        <span class="inline-block bg-green-100 text-green-800 px-2 py-1 rounded-full text-xs">Active</span>
                    </div>
                    <div class="flex space-x-2">
                        <button class="p-2 bg-yellow-50 rounded hover:bg-yellow-100 border border-yellow-100">
                            <span class="material-icons text-yellow-700 text-sm">edit</span>
                        </button>
                        <button class="p-2 bg-red-50 rounded hover:bg-red-100 border border-red-100">
                            <span class="material-icons text-red-600 text-sm">delete</span>
                        </button>
                    </div>
                </div>
                <div class="space-y-2">
                    <div class="flex justify-between">
                        <span class="text-xs font-medium text-gray-600">Description:</span>
                        <span class="text-xs text-gray-800">External partner with case access</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-xs font-medium text-gray-600">Users:</span>
                        <span class="text-xs text-gray-800">3</span>
                    </div>
                    <div class="pt-2 border-t border-gray-100">
                        <div class="flex flex-wrap gap-1">
                            <span class="inline-block bg-green-100 text-green-800 px-1 py-0.5 rounded text-xs">Cases</span>
                            <span class="inline-block bg-green-100 text-green-800 px-1 py-0.5 rounded text-xs">Clients</span>
                            <span class="inline-block bg-green-100 text-green-800 px-1 py-0.5 rounded text-xs">Files</span>
                            <span class="inline-block bg-yellow-100 text-yellow-800 px-1 py-0.5 rounded text-xs">Limited</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Role Card 4 -->
            <div class="bg-white border border-gray-200 rounded-lg p-4 space-y-3">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <span class="text-sm font-medium text-gray-800">Client</span>
                        <span class="inline-block bg-green-100 text-green-800 px-2 py-1 rounded-full text-xs">Active</span>
                    </div>
                    <div class="flex space-x-2">
                        <button class="p-2 bg-yellow-50 rounded hover:bg-yellow-100 border border-yellow-100">
                            <span class="material-icons text-yellow-700 text-sm">edit</span>
                        </button>
                        <button class="p-2 bg-red-50 rounded hover:bg-red-100 border border-red-100">
                            <span class="material-icons text-red-600 text-sm">delete</span>
                        </button>
                    </div>
                </div>
                <div class="space-y-2">
                    <div class="flex justify-between">
                        <span class="text-xs font-medium text-gray-600">Description:</span>
                        <span class="text-xs text-gray-800">Client access to their own cases</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-xs font-medium text-gray-600">Users:</span>
                        <span class="text-xs text-gray-800">25</span>
                    </div>
                    <div class="pt-2 border-t border-gray-100">
                        <div class="flex flex-wrap gap-1">
                            <span class="inline-block bg-green-100 text-green-800 px-1 py-0.5 rounded text-xs">Own Cases</span>
                            <span class="inline-block bg-green-100 text-green-800 px-1 py-0.5 rounded text-xs">Documents</span>
                            <span class="inline-block bg-yellow-100 text-yellow-800 px-1 py-0.5 rounded text-xs">View Only</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 