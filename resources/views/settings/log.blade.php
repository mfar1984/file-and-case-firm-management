@extends('layouts.app')

@section('breadcrumb')
    Settings > Log Activity
@endsection

@section('content')
<div class="px-6 pt-6 pb-6 max-w-7xl mx-auto">
    <div class="bg-white rounded shadow-md border border-gray-300">
        <div class="p-6 border-b border-gray-200 flex items-center justify-between">
            <div class="flex items-center">
                <span class="material-icons mr-2 text-red-600">history</span>
                <h1 class="text-xl font-bold text-gray-800 text-[14px]">Log Activity</h1>
            </div>
            <div class="flex items-center space-x-2">
                <input type="text" class="border border-gray-300 rounded px-2 py-1 text-xs" placeholder="Search logs...">
                <button class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded-md text-xs font-medium flex items-center">
                    <span class="material-icons text-xs mr-1">search</span>Search
                </button>
            </div>
        </div>
        <div class="p-6">
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
    </div>
</div>
@endsection 