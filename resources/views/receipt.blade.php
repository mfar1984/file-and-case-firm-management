@extends('layouts.app')

@section('breadcrumb')
    Receipt
@endsection

@section('content')
<div class="px-4 md:px-6 pt-4 md:pt-6 pb-6 max-w-7xl mx-auto">
    <div class="bg-white rounded shadow-md border border-gray-300">
        <div class="p-4 md:p-6 border-b border-gray-200">
            <div class="flex flex-col md:flex-row md:justify-between md:items-start space-y-4 md:space-y-0">
                <div>
                    <div class="flex items-center">
                        <span class="material-icons mr-2 text-green-600">receipt</span>
                        <h1 class="text-lg md:text-xl font-bold text-gray-800">Receipt Management</h1>
                    </div>
                    <p class="text-xs text-gray-500 mt-1 ml-8">Track all customer payments and receipts.</p>
                </div>
                
                <!-- Add Receipt Button -->
                <a href="{{ route('receipt.create') }}" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 md:px-3 md:py-1 rounded-md text-sm md:text-xs font-medium flex items-center justify-center md:justify-start w-full md:w-auto">
                    <span class="material-icons text-sm md:text-xs mr-1">add</span>
                    Add Receipt
                </a>
            </div>
        </div>
        
        <!-- Desktop Table View -->
        <div class="hidden md:block p-6">
            <div class="overflow-visible border border-gray-200 rounded">
                <table class="min-w-full border-collapse">
                    <thead>
                        <tr class="bg-primary-light text-white uppercase text-xs">
                            <th class="py-3 px-4 text-left rounded-tl">Receipt No</th>
                            <th class="py-3 px-4 text-left">Date</th>
                            <th class="py-3 px-4 text-left">Reference</th>
                            <th class="py-3 px-4 text-left">Customer</th>
                            <th class="py-3 px-4 text-left">Amount Paid (RM)</th>
                            <th class="py-3 px-4 text-left">Payment Method</th>
                            <th class="py-3 px-4 text-left">Status</th>
                            <th class="py-3 px-4 text-center rounded-tr">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($receipts as $receipt)
                        <tr class="hover:bg-gray-50">
                            <td class="py-1 px-4 text-[11px] font-medium">{{ $receipt->receipt_no }}</td>
                            <td class="py-1 px-4 text-[11px]">{{ $receipt->receipt_date->format('d/m/Y') }}</td>
                            <td class="py-1 px-4 text-[11px]">
                                @if($receipt->quotation)
                                    <span class="inline-block px-1.5 py-0.5 rounded-full text-[10px] bg-blue-100 text-blue-800">
                                        {{ $receipt->quotation->quotation_no }}
                                    </span>
                                @elseif($receipt->taxInvoice)
                                    <span class="inline-block px-1.5 py-0.5 rounded-full text-[10px] bg-green-100 text-green-800">
                                        {{ $receipt->taxInvoice->invoice_no }}
                                    </span>
                                @elseif($receipt->case)
                                    <span class="inline-block px-1.5 py-0.5 rounded-full text-[10px] bg-gray-100 text-gray-800">
                                        {{ $receipt->case->case_number }}
                                    </span>
                                @else
                                    <span class="text-gray-500">-</span>
                                @endif
                            </td>
                            <td class="py-1 px-4 text-[11px]">{{ $receipt->customer_name }}</td>
                            <td class="py-1 px-4 text-[11px] font-medium">RM {{ number_format($receipt->amount_paid, 2) }}</td>
                            <td class="py-1 px-4 text-[11px]">{{ $receipt->payment_method_display }}</td>
                            <td class="py-1 px-4 text-[11px]">
                                <span class="inline-block px-1.5 py-0.5 rounded-full text-[10px] {{ $receipt->status_color }}">
                                    {{ ucfirst($receipt->status) }}
                                </span>
                            </td>
                            <td class="py-1 px-4">
                                <div class="flex justify-center space-x-1 items-center">
                                    <a href="{{ route('receipt.show', $receipt->id) }}" class="p-0.5 text-blue-600 hover:text-blue-700" title="View">
                                        <span class="material-icons text-base">visibility</span>
                                    </a>
                                    <a href="{{ route('receipt.edit', $receipt->id) }}" class="p-0.5 text-yellow-600 hover:text-yellow-700" title="Edit">
                                        <span class="material-icons text-base">edit</span>
                                    </a>
                                    <button class="p-0.5 text-red-600 hover:text-red-700" title="Delete" onclick="return window.__deleteReceipt({{ $receipt->id }}, this)">
                                        <span class="material-icons text-base">delete</span>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="py-8 px-4 text-center text-gray-500">
                                <div class="flex flex-col items-center">
                                    <span class="material-icons text-4xl text-gray-300 mb-2">receipt</span>
                                    <p class="text-sm">No receipts found</p>
                                    <p class="text-xs text-gray-400 mt-1">Create your first receipt to get started</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Mobile View -->
        <div class="md:hidden">
            @forelse($receipts as $receipt)
            <div class="p-4 border-b border-gray-200 last:border-b-0">
                <div class="flex justify-between items-start mb-3">
                    <div>
                        <div class="font-medium text-gray-900">{{ $receipt->receipt_no }}</div>
                        <div class="text-sm text-gray-500">{{ $receipt->receipt_date->format('d/m/Y') }}</div>
                    </div>
                    <span class="inline-flex items-center px-1.5 py-0.5 rounded-full text-[10px] font-medium {{ $receipt->status_color }}">
                        <span class="material-icons text-xs mr-1">{{ $receipt->status_icon }}</span>
                        {{ ucfirst($receipt->status) }}
                    </span>
                </div>
                
                <div class="space-y-2 mb-3">
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-500">Reference:</span>
                        <span class="text-sm text-gray-900">
                            @if($receipt->quotation)
                                {{ $receipt->quotation->quotation_no }}
                            @elseif($receipt->taxInvoice)
                                {{ $receipt->taxInvoice->invoice_no }}
                            @elseif($receipt->case)
                                {{ $receipt->case->case_number }}
                            @else
                                -
                            @endif
                        </span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-500">Customer:</span>
                        <span class="text-sm text-gray-900">{{ $receipt->customer_name }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-500">Amount:</span>
                        <span class="text-sm font-medium text-gray-900">RM {{ number_format($receipt->amount_paid, 2) }}</span>
                    </div>
                    @if($receipt->outstanding_balance > 0)
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-500">Balance:</span>
                        <span class="text-sm text-gray-500">RM {{ number_format($receipt->outstanding_balance, 2) }}</span>
                    </div>
                    @endif
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-500">Method:</span>
                        <span class="text-sm text-gray-900">{{ $receipt->payment_method_display }}</span>
                    </div>
                </div>
                
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-2">
                        <a href="{{ route('receipt.show', $receipt->id) }}" class="text-blue-600 hover:text-blue-900 text-sm">
                            <span class="material-icons text-sm mr-1">visibility</span>
                            View
                        </a>
                        <a href="{{ route('receipt.edit', $receipt->id) }}" class="text-indigo-600 hover:text-indigo-900 text-sm">
                            <span class="material-icons text-sm mr-1">edit</span>
                            Edit
                        </a>
                    </div>
                    <button class="text-red-600 hover:text-red-900 text-sm" onclick="return window.__deleteReceipt({{ $receipt->id }}, this)">
                        <span class="material-icons text-sm mr-1">delete</span>
                        Delete
                    </button>
                </div>
            </div>
            @empty
            <div class="p-8 text-center text-gray-500">
                <span class="material-icons text-4xl text-gray-300 mb-2">receipt</span>
                <p class="text-sm">No receipts found</p>
                <p class="text-xs text-gray-400 mt-1">Create your first receipt to get started</p>
            </div>
            @endforelse
        </div>
    </div>
</div>

<script>
window.__deleteReceipt = function(id, element) {
    if (confirm('Are you sure you want to delete this receipt? This action cannot be undone.')) {
        fetch(`/receipt/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Remove the row from the table
                if (element.closest('tr')) {
                    element.closest('tr').remove();
                } else if (element.closest('.border-b')) {
                    element.closest('.border-b').remove();
                }
                
                // Show success message
                alert('Receipt deleted successfully');
                
                // Reload page if no more receipts
                if (document.querySelectorAll('tbody tr, .border-b').length === 0) {
                    location.reload();
                }
            } else {
                alert('Failed to delete receipt: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while deleting the receipt');
        });
    }
    return false;
};
</script>
@endsection
