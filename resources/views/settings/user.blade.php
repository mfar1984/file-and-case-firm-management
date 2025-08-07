@extends('layouts.app')

@section('breadcrumb')
    Settings > User Management
@endsection

@section('content')
<div class="px-4 md:px-6 pt-4 md:pt-6 pb-6 max-w-7xl mx-auto">
    <div class="bg-white rounded shadow-md border border-gray-300">
        <div class="p-4 md:p-6 border-b border-gray-200">
            <div class="flex flex-col md:flex-row md:justify-between md:items-start">
                <div class="mb-4 md:mb-0">
                    <div class="flex items-center">
                        <span class="material-icons mr-2 text-blue-600">people</span>
                        <h1 class="text-lg md:text-xl font-bold text-gray-800 text-[14px]">User Management</h1>
                    </div>
                    <p class="text-xs text-gray-500 mt-1 ml-8 text-[11px]">Manage system users, roles, and permissions.</p>
                </div>
                
                <!-- Add User Button -->
                <button class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 md:px-3 md:py-1 rounded-md text-sm md:text-xs font-medium flex items-center justify-center md:justify-start w-full md:w-auto">
                    <span class="material-icons text-xs mr-1">add</span>
                    Add User
                </button>
            </div>
        </div>
        
        <!-- Desktop Table View -->
        <div class="hidden md:block p-6">
            <div class="overflow-visible border border-gray-200 rounded">
                <table class="min-w-full border-collapse">
                    <thead>
                        <tr class="bg-primary-light text-white uppercase text-xs">
                            <th class="py-3 px-4 text-left rounded-tl">Name</th>
                            <th class="py-3 px-4 text-left">Email</th>
                            <th class="py-3 px-4 text-left">Role</th>
                            <th class="py-3 px-4 text-left">Status</th>
                            <th class="py-3 px-4 text-left">Last Login</th>
                            <th class="py-3 px-4 text-left">Created Date</th>
                            <th class="py-3 px-4 text-center rounded-tr">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <tr class="hover:bg-gray-50">
                            <td class="py-3 px-4 text-[11px] font-medium">Admin User</td>
                            <td class="py-3 px-4 text-[11px]">admin@naaelahsaleh.my</td>
                            <td class="py-3 px-4 text-[11px]">
                                <span class="inline-block bg-red-100 text-red-800 px-2 py-1 rounded-full text-xs">Administrator</span>
                            </td>
                            <td class="py-3 px-4 text-[11px]">
                                <span class="inline-block bg-green-100 text-green-800 px-2 py-1 rounded-full text-xs">Active</span>
                            </td>
                            <td class="py-3 px-4 text-[11px]">2025-07-10 09:15:23</td>
                            <td class="py-3 px-4 text-[11px]">2025-01-01</td>
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
                            <td class="py-3 px-4 text-[11px] font-medium">Partner One</td>
                            <td class="py-3 px-4 text-[11px]">partner1@firm.com</td>
                            <td class="py-3 px-4 text-[11px]">
                                <span class="inline-block bg-purple-100 text-purple-800 px-2 py-1 rounded-full text-xs">Partner</span>
                            </td>
                            <td class="py-3 px-4 text-[11px]">
                                <span class="inline-block bg-green-100 text-green-800 px-2 py-1 rounded-full text-xs">Active</span>
                            </td>
                            <td class="py-3 px-4 text-[11px]">2025-07-10 08:30:15</td>
                            <td class="py-3 px-4 text-[11px]">2025-01-15</td>
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
                            <td class="py-3 px-4 text-[11px] font-medium">Clerk One</td>
                            <td class="py-3 px-4 text-[11px]">clerk@firm.com</td>
                            <td class="py-3 px-4 text-[11px]">
                                <span class="inline-block bg-blue-100 text-blue-800 px-2 py-1 rounded-full text-xs">Clerk</span>
                            </td>
                            <td class="py-3 px-4 text-[11px]">
                                <span class="inline-block bg-green-100 text-green-800 px-2 py-1 rounded-full text-xs">Active</span>
                            </td>
                            <td class="py-3 px-4 text-[11px]">2025-07-10 07:45:30</td>
                            <td class="py-3 px-4 text-[11px]">2025-02-01</td>
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
                            <td class="py-3 px-4 text-[11px] font-medium">Client One</td>
                            <td class="py-3 px-4 text-[11px]">client1@gmail.com</td>
                            <td class="py-3 px-4 text-[11px]">
                                <span class="inline-block bg-green-100 text-green-800 px-2 py-1 rounded-full text-xs">Client</span>
                            </td>
                            <td class="py-3 px-4 text-[11px]">
                                <span class="inline-block bg-green-100 text-green-800 px-2 py-1 rounded-full text-xs">Active</span>
                            </td>
                            <td class="py-3 px-4 text-[11px]">2025-07-09 16:20:45</td>
                            <td class="py-3 px-4 text-[11px]">2025-03-15</td>
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
                            <td class="py-3 px-4 text-[11px] font-medium">Client Two</td>
                            <td class="py-3 px-4 text-[11px]">client2@yahoo.com</td>
                            <td class="py-3 px-4 text-[11px]">
                                <span class="inline-block bg-green-100 text-green-800 px-2 py-1 rounded-full text-xs">Client</span>
                            </td>
                            <td class="py-3 px-4 text-[11px]">
                                <span class="inline-block bg-gray-100 text-gray-800 px-2 py-1 rounded-full text-xs">Inactive</span>
                            </td>
                            <td class="py-3 px-4 text-[11px]">2025-07-05 10:15:20</td>
                            <td class="py-3 px-4 text-[11px]">2025-04-01</td>
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
            <!-- User Card 1 -->
            <div class="bg-white border border-gray-200 rounded-lg p-4 space-y-3">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <span class="text-sm font-medium text-gray-800">Admin User</span>
                        <span class="inline-block bg-red-100 text-red-800 px-2 py-1 rounded-full text-xs">Administrator</span>
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
                        <span class="text-xs font-medium text-gray-600">Email:</span>
                        <span class="text-xs text-gray-800">admin@naaelahsaleh.my</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-xs font-medium text-gray-600">Status:</span>
                        <span class="inline-block bg-green-100 text-green-800 px-2 py-1 rounded-full text-xs">Active</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-xs font-medium text-gray-600">Last Login:</span>
                        <span class="text-xs text-gray-800">2025-07-10 09:15:23</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-xs font-medium text-gray-600">Created:</span>
                        <span class="text-xs text-gray-800">2025-01-01</span>
                    </div>
                </div>
            </div>

            <!-- User Card 2 -->
            <div class="bg-white border border-gray-200 rounded-lg p-4 space-y-3">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <span class="text-sm font-medium text-gray-800">Partner One</span>
                        <span class="inline-block bg-purple-100 text-purple-800 px-2 py-1 rounded-full text-xs">Partner</span>
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
                        <span class="text-xs font-medium text-gray-600">Email:</span>
                        <span class="text-xs text-gray-800">partner1@firm.com</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-xs font-medium text-gray-600">Status:</span>
                        <span class="inline-block bg-green-100 text-green-800 px-2 py-1 rounded-full text-xs">Active</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-xs font-medium text-gray-600">Last Login:</span>
                        <span class="text-xs text-gray-800">2025-07-10 08:30:15</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-xs font-medium text-gray-600">Created:</span>
                        <span class="text-xs text-gray-800">2025-01-15</span>
                    </div>
                </div>
            </div>

            <!-- User Card 3 -->
            <div class="bg-white border border-gray-200 rounded-lg p-4 space-y-3">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <span class="text-sm font-medium text-gray-800">Clerk One</span>
                        <span class="inline-block bg-blue-100 text-blue-800 px-2 py-1 rounded-full text-xs">Clerk</span>
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
                        <span class="text-xs font-medium text-gray-600">Email:</span>
                        <span class="text-xs text-gray-800">clerk@firm.com</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-xs font-medium text-gray-600">Status:</span>
                        <span class="inline-block bg-green-100 text-green-800 px-2 py-1 rounded-full text-xs">Active</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-xs font-medium text-gray-600">Last Login:</span>
                        <span class="text-xs text-gray-800">2025-07-10 07:45:30</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-xs font-medium text-gray-600">Created:</span>
                        <span class="text-xs text-gray-800">2025-02-01</span>
                    </div>
                </div>
            </div>

            <!-- User Card 4 -->
            <div class="bg-white border border-gray-200 rounded-lg p-4 space-y-3">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <span class="text-sm font-medium text-gray-800">Client One</span>
                        <span class="inline-block bg-green-100 text-green-800 px-2 py-1 rounded-full text-xs">Client</span>
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
                        <span class="text-xs font-medium text-gray-600">Email:</span>
                        <span class="text-xs text-gray-800">client1@gmail.com</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-xs font-medium text-gray-600">Status:</span>
                        <span class="inline-block bg-green-100 text-green-800 px-2 py-1 rounded-full text-xs">Active</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-xs font-medium text-gray-600">Last Login:</span>
                        <span class="text-xs text-gray-800">2025-07-09 16:20:45</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-xs font-medium text-gray-600">Created:</span>
                        <span class="text-xs text-gray-800">2025-03-15</span>
                    </div>
                </div>
            </div>

            <!-- User Card 5 -->
            <div class="bg-white border border-gray-200 rounded-lg p-4 space-y-3">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <span class="text-sm font-medium text-gray-800">Client Two</span>
                        <span class="inline-block bg-green-100 text-green-800 px-2 py-1 rounded-full text-xs">Client</span>
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
                        <span class="text-xs font-medium text-gray-600">Email:</span>
                        <span class="text-xs text-gray-800">client2@yahoo.com</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-xs font-medium text-gray-600">Status:</span>
                        <span class="inline-block bg-gray-100 text-gray-800 px-2 py-1 rounded-full text-xs">Inactive</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-xs font-medium text-gray-600">Last Login:</span>
                        <span class="text-xs text-gray-800">2025-07-05 10:15:20</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-xs font-medium text-gray-600">Created:</span>
                        <span class="text-xs text-gray-800">2025-04-01</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 