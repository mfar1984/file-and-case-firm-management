@extends('layouts.app')

@section('breadcrumb')
    Quotation
@endsection

@section('content')
<div class="px-4 md:px-6 pt-4 md:pt-6 pb-6 max-w-7xl mx-auto">
    <div class="bg-white rounded shadow-md border border-gray-300">
        <div class="p-4 md:p-6 border-b border-gray-200">
            <div class="flex flex-col md:flex-row md:justify-between md:items-start space-y-4 md:space-y-0">
                <div>
                    <div class="flex items-center">
                        <span class="material-icons mr-2 text-blue-600">description</span>
                        <h1 class="text-lg md:text-xl font-bold text-gray-800">Quotation Management</h1>
                    </div>
                    <p class="text-xs text-gray-500 mt-1 ml-8">Manage all quotations and convert them to invoices.</p>
                </div>
                
                <!-- Add Quotation Button -->
                <a href="{{ route('quotation.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 md:px-3 md:py-1 rounded-md text-sm md:text-xs font-medium flex items-center justify-center md:justify-start w-full md:w-auto">
                    <span class="material-icons text-sm md:text-xs mr-1">add</span>
                    Add Quotation
                </a>
            </div>
        </div>
        
        <!-- Desktop Table View -->
        <div class="hidden md:block p-6">
            <div class="overflow-visible border border-gray-200 rounded">
                <table class="min-w-full border-collapse">
                    <thead>
                        <tr class="bg-primary-light text-white uppercase text-xs">
                            <th class="py-3 px-4 text-left rounded-tl">Quotation No</th>
                            <th class="py-3 px-4 text-left">Client Name</th>
                            <th class="py-3 px-4 text-left">Case Ref</th>
                            <th class="py-3 px-4 text-left">Amount (RM)</th>
                            <th class="py-3 px-4 text-left">Issue Date</th>
                            <th class="py-3 px-4 text-left">Valid Until</th>
                            <th class="py-3 px-4 text-left">Status</th>
                            <th class="py-3 px-4 text-center rounded-tr">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($quotations as $q)
                        <tr class="hover:bg-gray-50">
                            <td class="py-1 px-4 text-[11px] font-medium">{{ $q->quotation_no }}</td>
                            <td class="py-1 px-4 text-[11px]">{{ $q->customer_name ?? 'N/A' }}</td>
                            <td class="py-1 px-4 text-[11px]">{{ $q->case->case_number ?? 'N/A' }}</td>
                            <td class="py-1 px-4 text-[11px] font-medium">RM {{ number_format($q->total, 2) }}</td>
                            <td class="py-1 px-4 text-[11px]">{{ optional($q->quotation_date)->format('d/m/Y') }}</td>
                            <td class="py-1 px-4 text-[11px]">{{ optional($q->valid_until)->format('d/m/Y') }}</td>
                            <td class="py-1 px-4 text-[11px]">
                                <span class="inline-block px-1.5 py-0.5 rounded-full text-[10px] {{ $q->status_color }}">
                                    {{ $q->status_display }}
                                </span>
                            </td>
                            <td class="py-1 px-4">
                                <div class="flex justify-center space-x-1 items-center">
                                    <a href="{{ route('quotation.show', $q->id) }}" class="p-0.5 text-blue-600 hover:text-blue-700" title="View">
                                        <span class="material-icons text-base">visibility</span>
                                    </a>
                                    <a href="{{ route('quotation.create', ['case_id' => $q->case_id, 'case_number' => $q->case->case_number ?? null, 'from_quotation' => $q->id]) }}" class="p-0.5 text-yellow-600 hover:text-yellow-700" title="Prefill/Edit">
                                        <span class="material-icons text-base">edit</span>
                                    </a>
                                    
                                    <!-- Status Change Buttons -->
                                    @if($q->status === 'pending')
                                        <button class="p-0.5 text-green-600 hover:text-green-700" title="Accept" onclick="return window.__acceptQuotation({{ $q->id }}, this)">
                                            <span class="material-icons text-base">check_circle</span>
                                        </button>
                                        <button class="p-0.5 text-red-600 hover:text-red-700" title="Reject" onclick="return window.__rejectQuotation({{ $q->id }}, this)">
                                            <span class="material-icons text-base">cancel</span>
                                        </button>
                                    @endif
                                    
                                    @if(in_array($q->status, ['pending', 'accepted', 'rejected']))
                                        <button class="p-0.5 text-gray-600 hover:text-gray-700" title="Cancel" onclick="return window.__cancelQuotation({{ $q->id }}, this)">
                                            <span class="material-icons text-base">block</span>
                                        </button>
                                    @endif
                                    
                                    @if(in_array($q->status, ['cancelled', 'rejected']))
                                        <button class="p-0.5 text-blue-600 hover:text-blue-700" title="Reactivate" onclick="return window.__reactivateQuotation({{ $q->id }}, this)">
                                            <span class="material-icons text-base">refresh</span>
                                        </button>
                                    @endif
                                    
                                    @if($q->status === 'accepted')
                                        <a href="{{ route('tax-invoice.create', ['from_quotation' => $q->id]) }}" class="p-0.5 text-purple-600 hover:text-purple-700" title="Convert to Invoice">
                                            <span class="material-icons text-base">receipt_long</span>
                                        </a>
                                    @endif
                                    
                                    <button class="p-0.5 text-red-600 hover:text-red-700" title="Delete" onclick="return window.__deleteQuotation({{ $q->id }}, this)">
                                        <span class="material-icons text-base">delete</span>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="py-8 px-4 text-center text-gray-500">No quotations found</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        
        <!-- Mobile Card View -->
        <div class="md:hidden p-4 space-y-4">
            @forelse($quotations as $q)
            <div class="bg-gray-50 rounded-lg border border-gray-200 p-4">
                <div class="flex justify-between items-start mb-3">
                    <div>
                        <h3 class="font-semibold text-gray-800 text-sm">{{ $q->quotation_no }}</h3>
                        <p class="text-xs text-gray-600">{{ $q->customer_name ?? 'N/A' }}</p>
                    </div>
                    <span class="inline-block px-1.5 py-0.5 rounded-full text-[10px] {{ $q->status_color }}">
                        {{ $q->status_display }}
                    </span>
                </div>
                
                <div class="space-y-2 mb-4">
                    <div class="flex justify-between">
                        <span class="text-xs text-gray-500">Case Ref:</span>
                        <span class="text-xs font-medium">{{ $q->case->case_number ?? 'N/A' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-xs text-gray-500">Amount:</span>
                        <span class="text-xs font-medium">RM {{ number_format($q->total, 2) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-xs text-gray-500">Issue Date:</span>
                        <span class="text-xs font-medium">{{ optional($q->quotation_date)->format('d/m/Y') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-xs text-gray-500">Valid Until:</span>
                        <span class="text-xs font-medium">{{ optional($q->valid_until)->format('d/m/Y') }}</span>
                    </div>
                </div>
                
                <!-- Mobile Action Buttons -->
                <div class="flex flex-wrap gap-2">
                    <a href="{{ route('quotation.show', $q->id) }}" class="flex-1 bg-blue-100 text-blue-700 py-2 px-3 rounded-md text-xs font-medium flex items-center justify-center">
                        <span class="material-icons text-sm mr-1">visibility</span>
                        View
                    </a>
                    <a href="{{ route('quotation.create', ['case_id' => $q->case_id, 'case_number' => $q->case->case_number ?? null, 'from_quotation' => $q->id]) }}" class="flex-1 bg-yellow-100 text-yellow-700 py-2 px-3 rounded-md text-xs font-medium flex items-center justify-center">
                        <span class="material-icons text-sm mr-1">edit</span>
                        Edit
                    </a>
                    
                    <!-- Status Change Buttons -->
                    @if($q->status === 'pending')
                        <button class="flex-1 bg-green-100 text-green-700 py-2 px-3 rounded-md text-xs font-medium flex items-center justify-center" onclick="return window.__acceptQuotation({{ $q->id }}, this)">
                            <span class="material-icons text-sm mr-1">check_circle</span>
                            Accept
                        </button>
                        <button class="flex-1 bg-red-100 text-red-700 py-2 px-3 rounded-md text-xs font-medium flex items-center justify-center" onclick="return window.__rejectQuotation({{ $q->id }}, this)">
                            <span class="material-icons text-sm mr-1">cancel</span>
                            Reject
                        </button>
                    @endif
                    
                    @if(in_array($q->status, ['pending', 'accepted', 'rejected']))
                        <button class="flex-1 bg-gray-100 text-gray-700 py-2 px-3 rounded-md text-xs font-medium flex items-center justify-center" onclick="return window.__cancelQuotation({{ $q->id }}, this)">
                            <span class="material-icons text-sm mr-1">block</span>
                            Cancel
                        </button>
                    @endif
                    
                    @if(in_array($q->status, ['cancelled', 'rejected']))
                        <button class="flex-1 bg-blue-100 text-blue-700 py-2 px-3 rounded-md text-xs font-medium flex items-center justify-center" onclick="return window.__reactivateQuotation({{ $q->id }}, this)">
                            <span class="material-icons text-sm mr-1">refresh</span>
                            Reactivate
                        </button>
                    @endif
                    
                    @if($q->status === 'accepted')
                        <a href="{{ route('tax-invoice.create', ['from_quotation' => $q->id]) }}" class="flex-1 bg-purple-100 text-purple-700 py-2 px-3 rounded-md text-xs font-medium flex items-center justify-center">
                            <span class="material-icons text-sm mr-1">receipt_long</span>
                            Invoice
                        </a>
                    @endif
                    
                    <button class="flex-1 bg-red-100 text-red-700 py-2 px-3 rounded-md text-xs font-medium flex items-center justify-center" onclick="return window.__deleteQuotation({{ $q->id }}, this)">
                        <span class="material-icons text-sm mr-1">delete</span>
                        Delete
                    </button>
                </div>
            </div>
            @empty
            <div class="text-center text-gray-500 py-8">No quotations found</div>
            @endforelse
        </div>
    </div>
</div>

<script>
window.__deleteQuotation = function(id, el) {
    if (confirm('Are you sure you want to delete this quotation? This action cannot be undone.')) {
        fetch(`/quotation/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                el.closest('tr, div').remove(); // Remove the row/card from the DOM
                alert(data.message);
            } else {
                alert('Failed to delete quotation: ' + (data.message || 'Unknown error'));
            }
        })
        .catch(error => {
            console.error('Error deleting quotation:', error);
            alert('An error occurred while deleting the quotation.');
        });
    }
};

window.__acceptQuotation = function(id, el) {
    if (confirm('Are you sure you want to accept this quotation?')) {
        fetch(`/quotation/${id}/accept`, {
            method: 'PATCH',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload(); // Reload to update status and buttons
                alert(data.message);
            } else {
                alert('Failed to accept quotation: ' + (data.message || 'Unknown error'));
            }
        })
        .catch(error => {
            console.error('Error accepting quotation:', error);
            alert('An error occurred while accepting the quotation.');
        });
    }
};

window.__rejectQuotation = function(id, el) {
    if (confirm('Are you sure you want to reject this quotation?')) {
        fetch(`/quotation/${id}/reject`, {
            method: 'PATCH',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload(); // Reload to update status and buttons
                alert(data.message);
            } else {
                alert('Failed to reject quotation: ' + (data.message || 'Unknown error'));
            }
        })
        .catch(error => {
            console.error('Error rejecting quotation:', error);
            alert('An error occurred while rejecting the quotation.');
        });
    }
};

window.__cancelQuotation = function(id, el) {
    if (confirm('Are you sure you want to cancel this quotation?')) {
        fetch(`/quotation/${id}/cancel`, {
            method: 'PATCH',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload(); // Reload to update status and buttons
                alert(data.message);
            } else {
                alert('Failed to cancel quotation: ' + (data.message || 'Unknown error'));
            }
        })
        .catch(error => {
            console.error('Error cancelling quotation:', error);
            alert('An error occurred while cancelling the quotation.');
        });
    }
};

window.__reactivateQuotation = function(id, el) {
    if (confirm('Are you sure you want to reactivate this quotation?')) {
        fetch(`/quotation/${id}/reactivate`, {
            method: 'PATCH',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload(); // Reload to update status and buttons
                alert(data.message);
            } else {
                alert('Failed to reactivate quotation: ' + (data.message || 'Unknown error'));
            }
        })
        .catch(error => {
            console.error('Error reactivating quotation:', error);
            alert('An error occurred while reactivating the quotation.');
        });
    }
};
</script>
@endsection
