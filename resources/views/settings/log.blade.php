@extends('layouts.app')

@section('breadcrumb')
    Settings > Log Activity
@endsection

@section('content')
<div class="px-4 md:px-6 pt-4 md:pt-6 pb-6 max-w-7xl mx-auto">
    <div class="bg-white rounded shadow-md border border-gray-300">
        <div class="p-4 md:p-6 border-b border-gray-200 flex flex-col md:flex-row md:items-center md:justify-between">
            <div class="flex items-center mb-4 md:mb-0">
                <span class="material-icons mr-2 text-red-600">history</span>
                <h1 class="text-lg md:text-xl font-bold text-gray-800 text-[14px]">Log Activity</h1>
            </div>
            <div class="flex items-center space-x-2">
                <input type="text" class="border border-gray-300 rounded px-2 py-1 text-xs flex-1 md:flex-none" placeholder="Search logs...">
                <button class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 md:px-3 md:py-1 rounded-md text-sm md:text-xs font-medium flex items-center justify-center md:justify-start w-full md:w-auto">
                    <span class="material-icons text-xs mr-1">search</span>Search
                </button>
            </div>
        </div>
        
        <!-- Desktop Table View -->
        <div class="hidden md:block p-6">
            <div class="overflow-x-auto border border-gray-200 rounded">
                <table class="min-w-full border-collapse text-[12px]">
                    <thead>
                        <tr class="bg-primary-light text-white uppercase text-xs">
                            <th class="py-3 px-4 text-left rounded-tl">Date/Time</th>
                            <th class="py-3 px-4 text-left">User</th>
                            <th class="py-3 px-4 text-left">Role</th>
                            <th class="py-3 px-4 text-left">Action</th>
                            <th class="py-3 px-4 text-left">Description</th>
                            <th class="py-3 px-4 text-left rounded-tr">IP Address</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <tr class="hover:bg-gray-50">
                            <td class="py-3 px-4">2025-07-10 09:15:23</td>
                            <td class="py-3 px-4">admin@naaelahsaleh.my</td>
                            <td class="py-3 px-4">Administrator</td>
                            <td class="py-3 px-4"><span class="inline-block bg-green-100 text-green-800 px-2 py-1 rounded-full text-xs">Login</span></td>
                            <td class="py-3 px-4">User logged in</td>
                            <td class="py-3 px-4">192.168.1.10</td>
                        </tr>
                        <tr class="hover:bg-gray-50">
                            <td class="py-3 px-4">2025-07-10 09:17:02</td>
                            <td class="py-3 px-4">admin@naaelahsaleh.my</td>
                            <td class="py-3 px-4">Administrator</td>
                            <td class="py-3 px-4"><span class="inline-block bg-yellow-100 text-yellow-800 px-2 py-1 rounded-full text-xs">Update</span></td>
                            <td class="py-3 px-4">Updated client information for Ahmad Ali</td>
                            <td class="py-3 px-4">192.168.1.10</td>
                        </tr>
                        <tr class="hover:bg-gray-50">
                            <td class="py-3 px-4">2025-07-10 09:18:45</td>
                            <td class="py-3 px-4">partner1@firm.com</td>
                            <td class="py-3 px-4">Partner</td>
                            <td class="py-3 px-4"><span class="inline-block bg-blue-100 text-blue-800 px-2 py-1 rounded-full text-xs">Create</span></td>
                            <td class="py-3 px-4">Created new case: CVY-2025-001</td>
                            <td class="py-3 px-4">192.168.1.15</td>
                        </tr>
                        <tr class="hover:bg-gray-50">
                            <td class="py-3 px-4">2025-07-10 09:20:10</td>
                            <td class="py-3 px-4">clerk@firm.com</td>
                            <td class="py-3 px-4">Clerk</td>
                            <td class="py-3 px-4"><span class="inline-block bg-red-100 text-red-800 px-2 py-1 rounded-full text-xs">Delete</span></td>
                            <td class="py-3 px-4">Deleted document: agreement.pdf</td>
                            <td class="py-3 px-4">192.168.1.20</td>
                        </tr>
                        <tr class="hover:bg-gray-50">
                            <td class="py-3 px-4">2025-07-10 09:22:33</td>
                            <td class="py-3 px-4">client1@gmail.com</td>
                            <td class="py-3 px-4">Client</td>
                            <td class="py-3 px-4"><span class="inline-block bg-gray-100 text-gray-800 px-2 py-1 rounded-full text-xs">Logout</span></td>
                            <td class="py-3 px-4">User logged out</td>
                            <td class="py-3 px-4">192.168.1.30</td>
                        </tr>
                        <tr class="hover:bg-gray-50">
                            <td class="py-3 px-4">2025-07-10 09:25:11</td>
                            <td class="py-3 px-4">admin@naaelahsaleh.my</td>
                            <td class="py-3 px-4">Administrator</td>
                            <td class="py-3 px-4"><span class="inline-block bg-green-100 text-green-800 px-2 py-1 rounded-full text-xs">Login</span></td>
                            <td class="py-3 px-4">User logged in</td>
                            <td class="py-3 px-4">192.168.1.10</td>
                        </tr>
                        <!-- More dummy rows as needed -->
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Mobile Card View -->
        <div class="md:hidden p-4 space-y-4">
            <!-- Log Entry 1 -->
            <div class="bg-white border border-gray-200 rounded-lg p-4 space-y-3">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-2">
                        <span class="inline-block bg-green-100 text-green-800 px-2 py-1 rounded-full text-xs">Login</span>
                        <span class="text-xs text-gray-500">2025-07-10 09:15:23</span>
                    </div>
                </div>
                <div class="space-y-2">
                    <div class="flex justify-between">
                        <span class="text-xs font-medium text-gray-600">User:</span>
                        <span class="text-xs text-gray-800">admin@naaelahsaleh.my</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-xs font-medium text-gray-600">Role:</span>
                        <span class="text-xs text-gray-800">Administrator</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-xs font-medium text-gray-600">IP Address:</span>
                        <span class="text-xs text-gray-800">192.168.1.10</span>
                    </div>
                </div>
                <div class="pt-2 border-t border-gray-100">
                    <span class="text-xs text-gray-700">User logged in</span>
                </div>
            </div>

            <!-- Log Entry 2 -->
            <div class="bg-white border border-gray-200 rounded-lg p-4 space-y-3">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-2">
                        <span class="inline-block bg-yellow-100 text-yellow-800 px-2 py-1 rounded-full text-xs">Update</span>
                        <span class="text-xs text-gray-500">2025-07-10 09:17:02</span>
                    </div>
                </div>
                <div class="space-y-2">
                    <div class="flex justify-between">
                        <span class="text-xs font-medium text-gray-600">User:</span>
                        <span class="text-xs text-gray-800">admin@naaelahsaleh.my</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-xs font-medium text-gray-600">Role:</span>
                        <span class="text-xs text-gray-800">Administrator</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-xs font-medium text-gray-600">IP Address:</span>
                        <span class="text-xs text-gray-800">192.168.1.10</span>
                    </div>
                </div>
                <div class="pt-2 border-t border-gray-100">
                    <span class="text-xs text-gray-700">Updated client information for Ahmad Ali</span>
                </div>
            </div>

            <!-- Log Entry 3 -->
            <div class="bg-white border border-gray-200 rounded-lg p-4 space-y-3">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-2">
                        <span class="inline-block bg-blue-100 text-blue-800 px-2 py-1 rounded-full text-xs">Create</span>
                        <span class="text-xs text-gray-500">2025-07-10 09:18:45</span>
                    </div>
                </div>
                <div class="space-y-2">
                    <div class="flex justify-between">
                        <span class="text-xs font-medium text-gray-600">User:</span>
                        <span class="text-xs text-gray-800">partner1@firm.com</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-xs font-medium text-gray-600">Role:</span>
                        <span class="text-xs text-gray-800">Partner</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-xs font-medium text-gray-600">IP Address:</span>
                        <span class="text-xs text-gray-800">192.168.1.15</span>
                    </div>
                </div>
                <div class="pt-2 border-t border-gray-100">
                    <span class="text-xs text-gray-700">Created new case: CVY-2025-001</span>
                </div>
            </div>

            <!-- Log Entry 4 -->
            <div class="bg-white border border-gray-200 rounded-lg p-4 space-y-3">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-2">
                        <span class="inline-block bg-red-100 text-red-800 px-2 py-1 rounded-full text-xs">Delete</span>
                        <span class="text-xs text-gray-500">2025-07-10 09:20:10</span>
                    </div>
                </div>
                <div class="space-y-2">
                    <div class="flex justify-between">
                        <span class="text-xs font-medium text-gray-600">User:</span>
                        <span class="text-xs text-gray-800">clerk@firm.com</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-xs font-medium text-gray-600">Role:</span>
                        <span class="text-xs text-gray-800">Clerk</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-xs font-medium text-gray-600">IP Address:</span>
                        <span class="text-xs text-gray-800">192.168.1.20</span>
                    </div>
                </div>
                <div class="pt-2 border-t border-gray-100">
                    <span class="text-xs text-gray-700">Deleted document: agreement.pdf</span>
                </div>
            </div>

            <!-- Log Entry 5 -->
            <div class="bg-white border border-gray-200 rounded-lg p-4 space-y-3">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-2">
                        <span class="inline-block bg-gray-100 text-gray-800 px-2 py-1 rounded-full text-xs">Logout</span>
                        <span class="text-xs text-gray-500">2025-07-10 09:22:33</span>
                    </div>
                </div>
                <div class="space-y-2">
                    <div class="flex justify-between">
                        <span class="text-xs font-medium text-gray-600">User:</span>
                        <span class="text-xs text-gray-800">client1@gmail.com</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-xs font-medium text-gray-600">Role:</span>
                        <span class="text-xs text-gray-800">Client</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-xs font-medium text-gray-600">IP Address:</span>
                        <span class="text-xs text-gray-800">192.168.1.30</span>
                    </div>
                </div>
                <div class="pt-2 border-t border-gray-100">
                    <span class="text-xs text-gray-700">User logged out</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 