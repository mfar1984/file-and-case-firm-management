@extends('layouts.app')

@section('breadcrumb')
    Settings > Role Management
@endsection

@section('content')
<div class="px-6 pt-6 pb-6 max-w-7xl mx-auto">
    <div class="bg-white rounded shadow-md border border-gray-300">
        <div class="p-6 border-b border-gray-200">
            <div class="flex justify-between items-start">
                <div>
                    <div class="flex items-center">
                        <span class="material-icons mr-2 text-purple-600">admin_panel_settings</span>
                        <h1 class="text-xl font-bold text-gray-800 text-[14px]">Role Management</h1>
                    </div>
                    <p class="text-xs text-gray-500 mt-1 ml-8 text-[11px]">Manage user roles and their permissions.</p>
                </div>
                
                <!-- Add Role Button -->
                <button class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded-md text-xs font-medium flex items-center">
                    <span class="material-icons text-xs mr-1">add</span>
                    Add Role
                </button>
            </div>
        </div>
        
        <div class="p-6">
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
    </div>
</div>
@endsection 