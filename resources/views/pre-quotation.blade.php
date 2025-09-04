@extends('layouts.app')

@section('breadcrumb')
    Pre-Quotation
@endsection

@section('content')
<div class="px-4 md:px-6 pt-4 md:pt-6 pb-6 max-w-7xl mx-auto">
    <div class="bg-white rounded shadow-md border border-gray-300">
        <div class="p-4 md:p-6 border-b border-gray-200">
            <div class="flex flex-col md:flex-row md:justify-between md:items-start space-y-4 md:space-y-0">
                <div>
                    <div class="flex items-center">
                        <span class="material-icons mr-2 text-blue-600">description</span>
                        <h1 class="text-lg md:text-xl font-bold text-gray-800">Pre-Quotation Management</h1>
                    </div>
                    <p class="text-xs text-gray-500 mt-1 ml-8">Manage all pre-quotations before converting to quotations.</p>
                </div>
                
                <!-- Add Pre-Quotation Button -->
                <a href="{{ route('pre-quotation.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 md:px-3 md:py-1 rounded-md text-sm md:text-xs font-medium flex items-center justify-center md:justify-start w-full md:w-auto">
                    <span class="material-icons text-sm md:text-xs mr-1">add</span>
                    Add Pre-Quotation
                </a>
            </div>
        </div>
        
        <!-- Desktop Table View -->
        <div class="hidden md:block p-6">
            <div class="overflow-visible border border-gray-200 rounded">
                <table class="min-w-full border-collapse">
                    <thead>
                        <tr class="bg-primary-light text-white uppercase text-xs">
                            <th class="py-3 px-4 text-left rounded-tl">Pre-Quotation No</th>
                            <th class="py-3 px-4 text-left">Full Name</th>
                            <th class="py-3 px-4 text-left">Phone</th>
                            <th class="py-3 px-4 text-left">Amount (RM)</th>
                            <th class="py-3 px-4 text-left">Issue Date</th>
                            <th class="py-3 px-4 text-left">Valid Until</th>
                            <th class="py-3 px-4 text-left">Status</th>
                            <th class="py-3 px-4 text-center rounded-tr">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($preQuotations as $pq)
                        <tr class="hover:bg-gray-50">
                            <td class="py-1 px-4 text-[11px] font-medium">{{ $pq->quotation_no }}</td>
                            <td class="py-1 px-4 text-[11px]">{{ $pq->full_name ?? 'N/A' }}</td>
                            <td class="py-1 px-4 text-[11px]">{{ $pq->customer_phone ?? 'N/A' }}</td>
                            <td class="py-1 px-4 text-[11px] font-medium">RM {{ number_format($pq->total, 2) }}</td>
                            <td class="py-1 px-4 text-[11px]">{{ $pq->quotation_date->format('d/m/Y') }}</td>
                            <td class="py-1 px-4 text-[11px]">{{ $pq->valid_until ? $pq->valid_until->format('d/m/Y') : 'N/A' }}</td>
                            <td class="py-1 px-4">
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-[10px] font-medium {{ $pq->status_color }}">
                                    <span class="material-icons text-[10px] mr-1">{{ $pq->status_icon }}</span>
                                    {{ $pq->status_display }}
                                </span>
                            </td>
                            <td class="py-1 px-4 text-center">
                                <div class="flex items-center justify-center space-x-1">
                                    <a href="{{ route('pre-quotation.show', $pq->id) }}" class="text-blue-600 hover:text-blue-800" title="View">
                                        <span class="material-icons text-sm">visibility</span>
                                    </a>
                                    <a href="{{ route('pre-quotation.edit', $pq->id) }}" class="text-green-600 hover:text-green-800" title="Edit">
                                        <span class="material-icons text-sm">edit</span>
                                    </a>
                                    <form action="{{ route('pre-quotation.destroy', $pq->id) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this pre-quotation?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-800" title="Delete">
                                            <span class="material-icons text-sm">delete</span>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="py-8 text-center text-gray-500 text-sm">
                                <div class="flex flex-col items-center">
                                    <span class="material-icons text-4xl text-gray-300 mb-2">description</span>
                                    <p>No pre-quotations found.</p>
                                    <a href="{{ route('pre-quotation.create') }}" class="text-blue-600 hover:text-blue-800 text-xs mt-2">Create your first pre-quotation</a>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Mobile Card View -->
        <div class="md:hidden p-4 space-y-4">
            @forelse($preQuotations as $pq)
            <div class="bg-gray-50 rounded border p-4">
                <div class="flex justify-between items-start mb-2">
                    <div>
                        <h3 class="font-medium text-sm">{{ $pq->quotation_no }}</h3>
                        <p class="text-xs text-gray-600">{{ $pq->full_name ?? 'N/A' }}</p>
                    </div>
                    <span class="inline-flex items-center px-2 py-1 rounded-full text-[10px] font-medium {{ $pq->status_color }}">
                        <span class="material-icons text-[10px] mr-1">{{ $pq->status_icon }}</span>
                        {{ $pq->status_display }}
                    </span>
                </div>
                
                <div class="grid grid-cols-2 gap-2 text-xs mb-3">
                    <div>
                        <span class="text-gray-500">Phone:</span>
                        <span class="block">{{ $pq->customer_phone ?? 'N/A' }}</span>
                    </div>
                    <div>
                        <span class="text-gray-500">Amount:</span>
                        <span class="block font-medium">RM {{ number_format($pq->total, 2) }}</span>
                    </div>
                    <div>
                        <span class="text-gray-500">Issue Date:</span>
                        <span class="block">{{ $pq->quotation_date->format('d/m/Y') }}</span>
                    </div>
                    <div>
                        <span class="text-gray-500">Valid Until:</span>
                        <span class="block">{{ $pq->valid_until ? $pq->valid_until->format('d/m/Y') : 'N/A' }}</span>
                    </div>
                </div>
                
                <div class="flex space-x-2">
                    <a href="{{ route('pre-quotation.show', $pq->id) }}" class="flex-1 bg-blue-600 text-white text-center py-2 rounded text-xs">
                        View
                    </a>
                    <a href="{{ route('pre-quotation.edit', $pq->id) }}" class="flex-1 bg-green-600 text-white text-center py-2 rounded text-xs">
                        Edit
                    </a>
                    <form action="{{ route('pre-quotation.destroy', $pq->id) }}" method="POST" class="flex-1" onsubmit="return confirm('Are you sure?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full bg-red-600 text-white py-2 rounded text-xs">
                            Delete
                        </button>
                    </form>
                </div>
            </div>
            @empty
            <div class="text-center py-8">
                <span class="material-icons text-4xl text-gray-300 mb-2">description</span>
                <p class="text-gray-500 text-sm">No pre-quotations found.</p>
                <a href="{{ route('pre-quotation.create') }}" class="text-blue-600 hover:text-blue-800 text-xs mt-2 block">Create your first pre-quotation</a>
            </div>
            @endforelse
        </div>
    </div>
</div>

@if(session('success'))
<script>
    document.addEventListener('DOMContentLoaded', function() {
        alert('{{ session('success') }}');
    });
</script>
@endif
@endsection
