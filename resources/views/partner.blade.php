@extends('layouts.app')

@section('breadcrumb')
    Partner
@endsection

@section('content')
<div class="px-4 md:px-6 pt-4 md:pt-6 pb-6 max-w-7xl mx-auto">
    <div class="bg-white rounded shadow-md border border-gray-300">
        <div class="p-4 md:p-6 border-b border-gray-200">
            <div class="flex flex-col md:flex-row md:justify-between md:items-start space-y-4 md:space-y-0">
                <div>
                    <div class="flex items-center">
                        <span class="material-icons mr-2 text-orange-600">business</span>
                        <h1 class="text-lg md:text-xl font-bold text-gray-800">Partner Management</h1>
                    </div>
                    <p class="text-xs text-gray-500 mt-1 ml-8">Manage all partner information and case assignments.</p>
                </div>
                
                <!-- Add Partner Button -->
                <a href="{{ route('partner.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 md:px-3 md:py-1 rounded-md text-sm md:text-xs font-medium flex items-center justify-center md:justify-start w-full md:w-auto">
                    <span class="material-icons text-sm md:text-xs mr-1">add</span>
                    Add Partner
                </a>
            </div>
        </div>
        
        <!-- Desktop Table View -->
        <div class="hidden md:block p-6">
            <div class="overflow-visible border border-gray-200 rounded">
                <table class="min-w-full border-collapse">
                    <thead>
                        <tr class="bg-primary-light text-white uppercase text-xs">
                            <th class="py-3 px-4 text-left rounded-tl">Partner ID</th>
                            <th class="py-3 px-4 text-left">Name</th>
                            <th class="py-3 px-4 text-left">Phone</th>
                            <th class="py-3 px-4 text-left">Email</th>
                            <th class="py-3 px-4 text-left">Specialization</th>
                            <th class="py-3 px-4 text-left">Active Cases</th>
                            <th class="py-3 px-4 text-left">Operational Status</th>
                            <th class="py-3 px-4 text-center rounded-tr">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($partners as $partner)
                        <tr class="hover:bg-gray-50">
                            <td class="py-1 px-4 text-[11px] font-medium">{{ $partner->partner_code }}</td>
                            <td class="py-1 px-4 text-[11px]">{{ $partner->firm_name }}</td>
                            <td class="py-1 px-4 text-[11px]">{{ $partner->contact_no }}</td>
                            <td class="py-1 px-4 text-[11px]">{{ $partner->email ?? '-' }}</td>
                            <td class="py-1 px-4 text-[11px]">{{ $partner->specialization ? ucfirst($partner->specialization) . ' Law' : '-' }}</td>
                            <td class="py-1 px-4 text-[11px]">0</td>
                            <td class="py-1 px-4 text-[11px]">
                                <span class="inline-block {{ $partner->status_badge_color }} px-1.5 py-0.5 rounded-full text-[10px]">{{ ucfirst($partner->status) }}</span>
                            </td>
                            <td class="py-1 px-4">
                                <div class="flex justify-center space-x-1 items-center">
                                    <form action="{{ route('partner.toggle-ban', $partner->id) }}" method="POST">
                                        @csrf
                                        <button class="p-0.5 {{ $partner->is_banned ? 'text-green-600 hover:text-green-700' : 'text-red-600 hover:text-red-700' }}" title="{{ $partner->is_banned ? 'Unban' : 'Ban' }}">
                                            <span class="material-icons text-base">block</span>
                                        </button>
                                    </form>
                                    <a href="{{ route('partner.show', $partner->id) }}" class="p-0.5 text-blue-600 hover:text-blue-700" title="View">
                                        <span class="material-icons text-base">visibility</span>
                                    </a>
                                    <a href="{{ route('partner.edit', $partner->id) }}" class="p-0.5 text-yellow-600 hover:text-yellow-700" title="Edit">
                                        <span class="material-icons text-base">edit</span>
                                    </a>
                                    <form action="{{ route('partner.destroy', $partner->id) }}" method="POST" onsubmit="return confirm('Delete this partner?')">
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
                            <td colspan="8" class="text-center text-xs text-gray-500 py-6">No partners yet. Click Add Partner to create one.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        
        <!-- Mobile Card View -->
        <div class="md:hidden p-4 space-y-4">
            <!-- Partner Card 1 -->
            <div class="bg-gray-50 rounded-lg border border-gray-200 p-4">
                <div class="flex justify-between items-start mb-3">
                    <div>
                        <h3 class="font-semibold text-gray-800 text-sm">P-001</h3>
                        <p class="text-xs text-gray-600">A. Rahman</p>
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
                        <span class="text-xs font-medium truncate">a.rahman@naaelahsaleh.my</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-xs text-gray-500">Specialization:</span>
                        <span class="text-xs font-medium">Civil Law</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-xs text-gray-500">Active Cases:</span>
                        <span class="text-xs font-medium">12</span>
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
            
            <!-- Partner Card 2 -->
            <div class="bg-gray-50 rounded-lg border border-gray-200 p-4">
                <div class="flex justify-between items-start mb-3">
                    <div>
                        <h3 class="font-semibold text-gray-800 text-sm">P-002</h3>
                        <p class="text-xs text-gray-600">S. Kumar</p>
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
                        <span class="text-xs font-medium truncate">s.kumar@naaelahsaleh.my</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-xs text-gray-500">Specialization:</span>
                        <span class="text-xs font-medium">Criminal Law</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-xs text-gray-500">Active Cases:</span>
                        <span class="text-xs font-medium">8</span>
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
            
            <!-- Partner Card 3 -->
            <div class="bg-gray-50 rounded-lg border border-gray-200 p-4">
                <div class="flex justify-between items-start mb-3">
                    <div>
                        <h3 class="font-semibold text-gray-800 text-sm">P-003</h3>
                        <p class="text-xs text-gray-600">M. Lim</p>
                    </div>
                    <span class="inline-block bg-green-100 text-green-800 px-2 py-1 rounded-full text-xs">Active</span>
                </div>
                
                <div class="space-y-2 mb-4">
                    <div class="flex justify-between">
                        <span class="text-xs text-gray-500">Phone:</span>
                        <span class="text-xs font-medium">+60 12-456 7890</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-xs text-gray-500">Email:</span>
                        <span class="text-xs font-medium truncate">m.lim@naaelahsaleh.my</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-xs text-gray-500">Specialization:</span>
                        <span class="text-xs font-medium">Corporate Law</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-xs text-gray-500">Active Cases:</span>
                        <span class="text-xs font-medium">15</span>
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
            
            <!-- Partner Card 4 -->
            <div class="bg-gray-50 rounded-lg border border-gray-200 p-4">
                <div class="flex justify-between items-start mb-3">
                    <div>
                        <h3 class="font-semibold text-gray-800 text-sm">P-004</h3>
                        <p class="text-xs text-gray-600">N. Tan</p>
                    </div>
                    <span class="inline-block bg-red-100 text-red-800 px-2 py-1 rounded-full text-xs">Inactive</span>
                </div>
                
                <div class="space-y-2 mb-4">
                    <div class="flex justify-between">
                        <span class="text-xs text-gray-500">Phone:</span>
                        <span class="text-xs font-medium">+60 12-789 0123</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-xs text-gray-500">Email:</span>
                        <span class="text-xs font-medium truncate">n.tan@naaelahsaleh.my</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-xs text-gray-500">Specialization:</span>
                        <span class="text-xs font-medium">Family Law</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-xs text-gray-500">Active Cases:</span>
                        <span class="text-xs font-medium">6</span>
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
            
            <!-- Partner Card 5 -->
            <div class="bg-gray-50 rounded-lg border border-gray-200 p-4">
                <div class="flex justify-between items-start mb-3">
                    <div>
                        <h3 class="font-semibold text-gray-800 text-sm">P-005</h3>
                        <p class="text-xs text-gray-600">K. Wong</p>
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
                        <span class="text-xs font-medium truncate">k.wong@naaelahsaleh.my</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-xs text-gray-500">Specialization:</span>
                        <span class="text-xs font-medium">Property Law</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-xs text-gray-500">Active Cases:</span>
                        <span class="text-xs font-medium">10</span>
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
            
            <!-- Partner Card 6 -->
            <div class="bg-gray-50 rounded-lg border border-gray-200 p-4">
                <div class="flex justify-between items-start mb-3">
                    <div>
                        <h3 class="font-semibold text-gray-800 text-sm">P-006</h3>
                        <p class="text-xs text-gray-600">R. Singh</p>
                    </div>
                    <span class="inline-block bg-green-100 text-green-800 px-2 py-1 rounded-full text-xs">Active</span>
                </div>
                
                <div class="space-y-2 mb-4">
                    <div class="flex justify-between">
                        <span class="text-xs text-gray-500">Phone:</span>
                        <span class="text-xs font-medium">+60 12-654 3210</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-xs text-gray-500">Email:</span>
                        <span class="text-xs font-medium truncate">r.singh@naaelahsaleh.my</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-xs text-gray-500">Specialization:</span>
                        <span class="text-xs font-medium">Employment Law</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-xs text-gray-500">Active Cases:</span>
                        <span class="text-xs font-medium">7</span>
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