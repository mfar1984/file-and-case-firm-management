@extends('layouts.app')

@section('breadcrumb')
    Client List
@endsection

@section('content')
<div class="px-4 md:px-6 pt-4 md:pt-6 pb-6 max-w-7xl mx-auto">
    <div class="bg-white rounded shadow-md border border-gray-300">
        <div class="p-4 md:p-6 border-b border-gray-200">
            <div class="flex flex-col md:flex-row md:justify-between md:items-start space-y-4 md:space-y-0">
                <div>
                    <div class="flex items-center">
                        <span class="material-icons mr-2 text-blue-600">people</span>
                        <h1 class="text-lg md:text-xl font-bold text-gray-800">Client Management</h1>
                    </div>
                    <p class="text-xs text-gray-500 mt-1 ml-8">Manage all client information and details.</p>
                </div>
                
                <!-- Add Client Button -->
                <a href="{{ route('client.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 md:px-3 md:py-1 rounded-md text-sm md:text-xs font-medium flex items-center justify-center md:justify-start w-full md:w-auto">
                    <span class="material-icons text-sm md:text-xs mr-1">add</span>
                    Add Client
                </a>
            </div>
        </div>
        
        <!-- Desktop Table View -->
        <div class="hidden md:block p-6">
            <div class="overflow-visible border border-gray-200 rounded">
                <table class="min-w-full border-collapse">
                    <thead>
                        <tr class="bg-primary-light text-white uppercase text-xs">
                            <th class="py-3 px-4 text-left rounded-tl">Client ID</th>
                            <th class="py-3 px-4 text-left">Name</th>
                            <th class="py-3 px-4 text-left">Phone</th>
                            <th class="py-3 px-4 text-left">Email</th>
                            <th class="py-3 px-4 text-left">Address</th>
                            <th class="py-3 px-4 text-left">Status</th>
                            <th class="py-3 px-4 text-center rounded-tr">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($clients as $client)
                        <tr class="hover:bg-gray-50">
                            <td class="py-1 px-4 text-[11px] font-medium">{{ $client->client_code }}</td>
                            <td class="py-1 px-4 text-[11px]">{{ $client->name }}</td>
                            <td class="py-1 px-4 text-[11px]">{{ $client->phone }}</td>
                            <td class="py-1 px-4 text-[11px]">{{ $client->email ?? '-' }}</td>
                            <td class="py-1 px-4 text-[11px]">{{ Str::limit($client->address_current, 40) }}</td>
                            <td class="py-1 px-4 text-[11px]">
                                <span class="inline-block {{ $client->status_badge_color }} px-1.5 py-0.5 rounded-full text-[10px]">{{ $client->is_banned ? 'Banned' : 'Active' }}</span>
                            </td>
                            <td class="py-1 px-4">
                                <div class="flex justify-center space-x-1 items-center">
                                    <form action="{{ route('client.toggle-ban', $client->id) }}" method="POST">
                                        @csrf
                                        <button class="p-0.5 {{ $client->is_banned ? 'text-green-600 hover:text-green-700' : 'text-red-600 hover:text-red-700' }}" title="{{ $client->is_banned ? 'Unban' : 'Ban' }}">
                                            <span class="material-icons text-base">block</span>
                                        </button>
                                    </form>
                                    <a href="{{ route('client.show', $client->id) }}" class="p-0.5 text-blue-600 hover:text-blue-700" title="View">
                                        <span class="material-icons text-base">visibility</span>
                                    </a>
                                    <a href="{{ route('client.edit', $client->id) }}" class="p-0.5 text-yellow-600 hover:text-yellow-700" title="Edit">
                                        <span class="material-icons text-base">edit</span>
                                    </a>
                                    <form action="{{ route('client.destroy', $client->id) }}" method="POST" onsubmit="return confirm('Delete this client?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="p-0.5 text-red-600 hover:text-red-700" title="Delete">
                                            <span class="material-icons text-base">delete</span>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center text-xs text-gray-500 py-6">No clients yet. Click Add Client to create one.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        
        <!-- Mobile Card View -->
        <div class="md:hidden p-4 space-y-4">
            <!-- Client Card 1 -->
            <div class="bg-gray-50 rounded-lg border border-gray-200 p-4">
                <div class="flex justify-between items-start mb-3">
                    <div>
                        <h3 class="font-semibold text-gray-800 text-sm">CL-001</h3>
                        <p class="text-xs text-gray-600">John Doe</p>
                    </div>
                    <span class="inline-block bg-green-100 text-green-800 px-2 py-1 rounded-full text-xs">Active</span>
                </div>
                
                <div class="space-y-2 mb-4">
                    <div class="flex justify-between">
                        <span class="text-xs text-gray-500">Phone:</span>
                        <span class="text-xs font-medium">+60 12-345 6789</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-xs text-gray-500">Email:</span>
                        <span class="text-xs font-medium truncate">john.doe@email.com</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-xs text-gray-500">Address:</span>
                        <span class="text-xs font-medium">Kuala Lumpur, Malaysia</span>
                    </div>
                </div>
                
                <!-- Mobile Action Buttons -->
                <div class="flex space-x-2">
                    <button class="flex-1 bg-red-100 text-red-700 py-2 px-3 rounded-md text-xs font-medium flex items-center justify-center">
                        <span class="material-icons text-sm mr-1">block</span>
                        Ban
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
            
            <!-- Client Card 2 -->
            <div class="bg-gray-50 rounded-lg border border-gray-200 p-4">
                <div class="flex justify-between items-start mb-3">
                    <div>
                        <h3 class="font-semibold text-gray-800 text-sm">CL-002</h3>
                        <p class="text-xs text-gray-600">Jane Smith</p>
                    </div>
                    <span class="inline-block bg-green-100 text-green-800 px-2 py-1 rounded-full text-xs">Active</span>
                </div>
                
                <div class="space-y-2 mb-4">
                    <div class="flex justify-between">
                        <span class="text-xs text-gray-500">Phone:</span>
                        <span class="text-xs font-medium">+60 12-987 6543</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-xs text-gray-500">Email:</span>
                        <span class="text-xs font-medium truncate">jane.smith@email.com</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-xs text-gray-500">Address:</span>
                        <span class="text-xs font-medium">Petaling Jaya, Selangor</span>
                    </div>
                </div>
                
                <!-- Mobile Action Buttons -->
                <div class="flex space-x-2">
                    <button class="flex-1 bg-red-100 text-red-700 py-2 px-3 rounded-md text-xs font-medium flex items-center justify-center">
                        <span class="material-icons text-sm mr-1">block</span>
                        Ban
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
            
            <!-- Client Card 3 -->
            <div class="bg-gray-50 rounded-lg border border-gray-200 p-4">
                <div class="flex justify-between items-start mb-3">
                    <div>
                        <h3 class="font-semibold text-gray-800 text-sm">CL-003</h3>
                        <p class="text-xs text-gray-600">Ahmad Ali</p>
                    </div>
                    <span class="inline-block bg-red-100 text-red-800 px-2 py-1 rounded-full text-xs">Banned</span>
                </div>
                
                <div class="space-y-2 mb-4">
                    <div class="flex justify-between">
                        <span class="text-xs text-gray-500">Phone:</span>
                        <span class="text-xs font-medium">+60 12-456 7890</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-xs text-gray-500">Email:</span>
                        <span class="text-xs font-medium truncate">ahmad.ali@email.com</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-xs text-gray-500">Address:</span>
                        <span class="text-xs font-medium">Shah Alam, Selangor</span>
                    </div>
                </div>
                
                <!-- Mobile Action Buttons -->
                <div class="flex space-x-2">
                    <button class="flex-1 bg-red-100 text-red-700 py-2 px-3 rounded-md text-xs font-medium flex items-center justify-center">
                        <span class="material-icons text-sm mr-1">block</span>
                        Ban
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
            
            <!-- Client Card 4 -->
            <div class="bg-gray-50 rounded-lg border border-gray-200 p-4">
                <div class="flex justify-between items-start mb-3">
                    <div>
                        <h3 class="font-semibold text-gray-800 text-sm">CL-004</h3>
                        <p class="text-xs text-gray-600">Maria Tan</p>
                    </div>
                    <span class="inline-block bg-green-100 text-green-800 px-2 py-1 rounded-full text-xs">Active</span>
                </div>
                
                <div class="space-y-2 mb-4">
                    <div class="flex justify-between">
                        <span class="text-xs text-gray-500">Phone:</span>
                        <span class="text-xs font-medium">+60 12-789 0123</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-xs text-gray-500">Email:</span>
                        <span class="text-xs font-medium truncate">maria.tan@email.com</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-xs text-gray-500">Address:</span>
                        <span class="text-xs font-medium">Subang Jaya, Selangor</span>
                    </div>
                </div>
                
                <!-- Mobile Action Buttons -->
                <div class="flex space-x-2">
                    <button class="flex-1 bg-red-100 text-red-700 py-2 px-3 rounded-md text-xs font-medium flex items-center justify-center">
                        <span class="material-icons text-sm mr-1">block</span>
                        Ban
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
            
            <!-- Client Card 5 -->
            <div class="bg-gray-50 rounded-lg border border-gray-200 p-4">
                <div class="flex justify-between items-start mb-3">
                    <div>
                        <h3 class="font-semibold text-gray-800 text-sm">CL-005</h3>
                        <p class="text-xs text-gray-600">David Wong</p>
                    </div>
                    <span class="inline-block bg-green-100 text-green-800 px-2 py-1 rounded-full text-xs">Active</span>
                </div>
                
                <div class="space-y-2 mb-4">
                    <div class="flex justify-between">
                        <span class="text-xs text-gray-500">Phone:</span>
                        <span class="text-xs font-medium">+60 12-321 6540</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-xs text-gray-500">Email:</span>
                        <span class="text-xs font-medium truncate">david.wong@email.com</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-xs text-gray-500">Address:</span>
                        <span class="text-xs font-medium">Klang, Selangor</span>
                    </div>
                </div>
                
                <!-- Mobile Action Buttons -->
                <div class="flex space-x-2">
                    <button class="flex-1 bg-red-100 text-red-700 py-2 px-3 rounded-md text-xs font-medium flex items-center justify-center">
                        <span class="material-icons text-sm mr-1">block</span>
                        Ban
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