@extends('layouts.app')

@section('breadcrumb')
    Tax Invoice
@endsection

@section('content')
<div class="px-4 md:px-6 pt-4 md:pt-6 pb-6 max-w-7xl mx-auto">
    <div class="bg-white rounded shadow-md border border-gray-300">
        <div class="p-4 md:p-6 border-b border-gray-200">
            <div class="flex flex-col md:flex-row md:justify-between md:items-start space-y-4 md:space-y-0">
                <div>
                    <div class="flex items-center">
                        <span class="material-icons mr-2 text-blue-600">receipt</span>
                        <h1 class="text-lg md:text-xl font-bold text-gray-800">Tax Invoice Management</h1>
                    </div>
                    <p class="text-xs text-gray-500 mt-1 ml-8">Manage all tax invoices and billing.</p>
                </div>
                
                <!-- Add Tax Invoice Button -->
                <a href="{{ route('tax-invoice.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 md:px-3 md:py-1 rounded-md text-sm md:text-xs font-medium flex items-center justify-center md:justify-start w-full md:w-auto">
                    <span class="material-icons text-sm md:text-xs mr-1">add</span>
                    Add Tax Invoice
                </a>
            </div>
        </div>
        
        <!-- Desktop Table View -->
        <div class="hidden md:block p-6">
            <div class="overflow-visible border border-gray-200 rounded">
                <table class="min-w-full border-collapse">
                    <thead>
                        <tr class="bg-primary-light text-white uppercase text-xs">
                            <th class="py-3 px-4 text-left rounded-tl">Invoice No</th>
                            <th class="py-3 px-4 text-left">Client Name</th>
                            <th class="py-3 px-4 text-left">Case Ref</th>
                            <th class="py-3 px-4 text-left">Amount (RM)</th>
                            <th class="py-3 px-4 text-left">Issue Date</th>
                            <th class="py-3 px-4 text-left">Due Date</th>
                            <th class="py-3 px-4 text-left">Status</th>
                            <th class="py-3 px-4 text-center rounded-tr">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($taxInvoices as $inv)
                        <tr class="hover:bg-gray-50">
                            <td class="py-1 px-4 text-[11px] font-medium">{{ $inv->invoice_no }}</td>
                            <td class="py-1 px-4 text-[11px]">{{ $inv->customer_name ?? 'N/A' }}</td>
                            <td class="py-1 px-4 text-[11px]">{{ $inv->case->case_number ?? 'N/A' }}</td>
                            <td class="py-1 px-4 text-[11px] font-medium">RM {{ number_format($inv->total, 2) }}</td>
                            <td class="py-1 px-4 text-[11px]">{{ optional($inv->invoice_date)->format('d/m/Y') }}</td>
                            <td class="py-1 px-4 text-[11px]">{{ optional($inv->due_date)->format('d/m/Y') }}</td>
                            <td class="py-1 px-4 text-[11px]">
                                <span class="inline-block px-1.5 py-0.5 rounded-full text-[10px] font-medium {{ $inv->status_color }}">
                                    {{ $inv->status_display }}
                                </span>
                            </td>
                            <td class="py-1 px-4">
                                <div class="flex justify-center space-x-1 items-center">
                                    <a href="{{ route('tax-invoice.show', $inv->id) }}" class="p-0.5 text-blue-600 hover:text-blue-700" title="View">
                                        <span class="material-icons text-base">visibility</span>
                                    </a>
                                    <a href="{{ route('tax-invoice.edit', $inv->id) }}" class="p-0.5 text-yellow-600 hover:text-yellow-700" title="Edit">
                                        <span class="material-icons text-base">edit</span>
                                    </a>
                                    
                                    <!-- Status Change Icons -->
                                    @if($inv->status === 'draft')
                                        <button onclick="window.__sendTaxInvoice({{ $inv->id }})" class="p-0.5 text-blue-600 hover:text-blue-700" title="Send Invoice">
                                            <span class="material-icons text-base">send</span>
                                        </button>
                                    @endif
                                    
                                    @if(in_array($inv->status, ['sent', 'partially_paid', 'overdue']))
                                        <button onclick="window.__markAsPaid({{ $inv->id }})" class="p-0.5 text-green-600 hover:text-green-700" title="Mark as Paid">
                                            <span class="material-icons text-base">check_circle</span>
                                        </button>
                                    @endif
                                    
                                    @if(in_array($inv->status, ['sent', 'overdue']))
                                        <button onclick="window.__markAsPartiallyPaid({{ $inv->id }})" class="p-0.5 text-yellow-600 hover:text-yellow-700" title="Mark as Partially Paid">
                                            <span class="material-icons text-base">schedule</span>
                                        </button>
                                    @endif
                                    
                                    @if(in_array($inv->status, ['sent', 'partially_paid']))
                                        <button onclick="window.__markAsOverdue({{ $inv->id }})" class="p-0.5 text-orange-600 hover:text-orange-700" title="Mark as Overdue">
                                            <span class="material-icons text-base">warning</span>
                                        </button>
                                    @endif
                                    
                                    @if(in_array($inv->status, ['draft', 'sent', 'partially_paid', 'overdue']))
                                        <button onclick="window.__cancelTaxInvoice({{ $inv->id }})" class="p-0.5 text-red-600 hover:text-red-700" title="Cancel">
                                            <span class="material-icons text-base">cancel</span>
                                        </button>
                                    @endif
                                    
                                    <button class="p-0.5 text-red-600 hover:text-red-700" title="Delete" onclick="return window.__deleteTaxInvoice({{ $inv->id }}, this)">
                                        <span class="material-icons text-base">delete</span>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="py-8 px-4 text-center text-gray-500">
                                <div class="flex flex-col items-center">
                                    <span class="material-icons text-4xl text-gray-300 mb-2">receipt_long</span>
                                    <p class="text-sm">No tax invoices found</p>
                                    <p class="text-xs text-gray-400 mt-1">Create your first tax invoice to get started</p>
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
            @forelse($taxInvoices as $inv)
            <div class="bg-gray-50 rounded-lg border border-gray-200 p-4">
                <div class="flex justify-between items-start mb-3">
                    <div>
                        <h3 class="font-semibold text-gray-800 text-sm">{{ $inv->invoice_no }}</h3>
                        <p class="text-xs text-gray-600">{{ $inv->customer_name ?? 'N/A' }}</p>
                    </div>
                    <span class="inline-block px-1.5 py-0.5 rounded-full text-[10px] font-medium {{ $inv->status_color }}">
                        {{ $inv->status_display }}
                    </span>
                </div>
                
                <div class="space-y-2 mb-4">
                    <div class="flex justify-between">
                        <span class="text-xs text-gray-500">Case Ref:</span>
                        <span class="text-xs font-medium">{{ $inv->case->case_number ?? 'N/A' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-xs text-gray-500">Amount:</span>
                        <span class="text-xs font-medium">RM {{ number_format($inv->total, 2) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-xs text-gray-500">Issue Date:</span>
                        <span class="text-xs font-medium">{{ optional($inv->invoice_date)->format('d/m/Y') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-xs text-gray-500">Due Date:</span>
                        <span class="text-xs font-medium">{{ optional($inv->due_date)->format('d/m/Y') }}</span>
                    </div>
                </div>
                
                <!-- Mobile Action Buttons -->
                <div class="flex flex-wrap gap-2">
                    <a href="{{ route('tax-invoice.show', $inv->id) }}" class="p-2 bg-blue-100 text-blue-700 rounded-md hover:bg-blue-200" title="View">
                        <span class="material-icons text-sm">visibility</span>
                    </a>
                    <a href="{{ route('tax-invoice.edit', $inv->id) }}" class="p-2 bg-yellow-100 text-yellow-700 rounded-md hover:bg-yellow-200" title="Edit">
                        <span class="material-icons text-sm">edit</span>
                    </a>
                    
                    <!-- Status Change Icons -->
                    @if($inv->status === 'draft')
                        <button onclick="window.__sendTaxInvoice({{ $inv->id }})" class="p-2 bg-blue-100 text-blue-700 rounded-md hover:bg-blue-200" title="Send Invoice">
                            <span class="material-icons text-sm">send</span>
                        </button>
                    @endif
                    
                    @if(in_array($inv->status, ['sent', 'partially_paid', 'overdue']))
                        <button onclick="window.__markAsPaid({{ $inv->id }})" class="p-2 bg-green-100 text-green-700 rounded-md hover:bg-green-200" title="Mark as Paid">
                            <span class="material-icons text-sm">check_circle</span>
                        </button>
                    @endif
                    
                    @if(in_array($inv->status, ['sent', 'overdue']))
                        <button onclick="window.__markAsPartiallyPaid({{ $inv->id }})" class="p-2 bg-yellow-100 text-yellow-700 rounded-md hover:bg-yellow-200" title="Mark as Partially Paid">
                            <span class="material-icons text-sm">schedule</span>
                        </button>
                    @endif
                    
                    @if(in_array($inv->status, ['sent', 'partially_paid']))
                        <button onclick="window.__markAsOverdue({{ $inv->id }})" class="p-2 bg-orange-100 text-orange-700 rounded-md hover:bg-orange-200" title="Mark as Overdue">
                            <span class="material-icons text-sm">warning</span>
                        </button>
                    @endif
                    
                    @if(in_array($inv->status, ['draft', 'sent', 'partially_paid', 'overdue']))
                        <button onclick="window.__cancelTaxInvoice({{ $inv->id }})" class="p-2 bg-red-100 text-red-700 rounded-md hover:bg-red-200" title="Cancel">
                            <span class="material-icons text-sm">cancel</span>
                        </button>
                    @endif
                    
                    <button class="p-2 bg-red-100 text-red-700 rounded-md hover:bg-red-200" title="Delete" onclick="return window.__deleteTaxInvoice({{ $inv->id }}, this)">
                        <span class="material-icons text-sm">delete</span>
                    </button>
                </div>
            </div>
            @empty
            <div class="text-center text-gray-500 py-8">
                <div class="flex flex-col items-center">
                    <span class="material-icons text-4xl text-gray-300 mb-2">receipt_long</span>
                    <p class="text-sm">No tax invoices found</p>
                    <p class="text-xs text-gray-400 mt-1">Create your first tax invoice to get started</p>
                </div>
            </div>
            @endforelse
        </div>
    </div>
</div>

<script>
window.__deleteTaxInvoice = function(id, el) {
    if (confirm('Are you sure you want to delete this tax invoice? This action cannot be undone.')) {
        fetch(`/tax-invoice/${id}`, {
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
                alert('Failed to delete tax invoice: ' + (data.message || 'Unknown error'));
            }
        })
        .catch(error => {
            console.error('Error deleting tax invoice:', error);
            alert('An error occurred while deleting the tax invoice.');
        });
    }
};

// Status Change Functions
window.__sendTaxInvoice = function(id) {
    if (confirm('Are you sure you want to send this tax invoice? This will change the status to "sent".')) {
        fetch(`/tax-invoice/${id}/send`, {
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
                alert(data.message);
                location.reload(); // Reload to show updated status
            } else {
                alert('Failed to send tax invoice: ' + (data.message || 'Unknown error'));
            }
        })
        .catch(error => {
            console.error('Error sending tax invoice:', error);
            alert('An error occurred while sending the tax invoice.');
        });
    }
};

window.__markAsPaid = function(id) {
    if (confirm('Are you sure you want to mark this tax invoice as paid?')) {
        fetch(`/tax-invoice/${id}/mark-as-paid`, {
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
                alert(data.message);
                location.reload(); // Reload to show updated status
            } else {
                alert('Failed to mark as paid: ' + (data.message || 'Unknown error'));
            }
        })
        .catch(error => {
            console.error('Error marking as paid:', error);
            alert('An error occurred while updating the status.');
        });
    }
};

window.__markAsPartiallyPaid = function(id) {
    if (confirm('Are you sure you want to mark this tax invoice as partially paid?')) {
        fetch(`/tax-invoice/${id}/mark-as-partially-paid`, {
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
                alert(data.message);
                location.reload(); // Reload to show updated status
            } else {
                alert('Failed to mark as partially paid: ' + (data.message || 'Unknown error'));
            }
        })
        .catch(error => {
            console.error('Error marking as partially paid:', error);
            alert('An error occurred while updating the status.');
        });
    }
};

window.__markAsOverdue = function(id) {
    if (confirm('Are you sure you want to mark this tax invoice as overdue?')) {
        fetch(`/tax-invoice/${id}/mark-as-overdue`, {
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
                alert(data.message);
                location.reload(); // Reload to show updated status
            } else {
                alert('Failed to mark as overdue: ' + (data.message || 'Unknown error'));
            }
        })
        .catch(error => {
            console.error('Error marking as overdue:', error);
            alert('An error occurred while updating the status.');
        });
    }
};

window.__cancelTaxInvoice = function(id) {
    if (confirm('Are you sure you want to cancel this tax invoice? This action cannot be undone.')) {
        fetch(`/tax-invoice/${id}/cancel`, {
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
                alert(data.message);
                location.reload(); // Reload to show updated status
            } else {
                alert('Failed to cancel tax invoice: ' + (data.message || 'Unknown error'));
            }
        })
        .catch(error => {
            console.error('Error cancelling tax invoice:', error);
            alert('An error occurred while cancelling the tax invoice.');
        });
    }
};
</script>
@endsection 