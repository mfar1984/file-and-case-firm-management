@extends('layouts.app')

@section('breadcrumb')
    Receipt / Add
@endsection

@section('content')
<div class="px-4 md:px-6 pt-4 md:pt-6 pb-6 max-w-7xl mx-auto">
    <div class="bg-white rounded shadow-md border border-gray-300">
        <div class="px-4 md:px-6 py-4 border-b border-gray-200">
            <div class="flex items-center">
                <span class="material-icons mr-2 text-green-600">receipt</span>
                <h1 class="text-lg md:text-xl font-bold text-gray-800 text-[14px]">Add Receipt</h1>
            </div>
            <p class="text-xs text-gray-500 mt-1 ml-8 text-[11px]">Record customer payment for quotations or tax invoices.</p>
        </div>
        
        <!-- Success/Error Messages -->
        @if(session('success'))
            <div class="p-4 bg-green-100 border border-green-400 text-green-700 rounded">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="p-4 bg-red-100 border border-red-400 text-red-700 rounded">
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('receipt.store') }}" class="p-4 md:p-6">
            @csrf
            
            <!-- Reference Selection -->
            <div class="space-y-4 mb-8">
                <h3 class="text-sm font-semibold text-gray-800 mb-4">Payment Reference</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-2">Reference Type</label>
                        <select name="reference_type" id="reference_type" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs focus:outline-none focus:ring-2 focus:ring-blue-500" onchange="toggleReferenceFields()">
                            <option value="">Select Reference Type</option>
                            <option value="tax_invoice" {{ $prefillData && $prefillData['type'] === 'invoice' ? 'selected' : '' }}>Tax Invoice</option>
                            <option value="case">Case Only</option>
                        </select>
                    </div>
                    

                    
                    <div id="invoice_select_div" class="hidden">
                        <label class="block text-xs font-medium text-gray-700 mb-2">Select Tax Invoice</label>
                        <select name="tax_invoice_id" id="invoice_select" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs focus:outline-none focus:ring-2 focus:ring-blue-500" onchange="loadInvoiceData()">
                            <option value="">Choose tax invoice</option>
                            @foreach($taxInvoices as $inv)
                                @php
                                    $previousPayments = \App\Models\Receipt::where('tax_invoice_id', $inv->id)->sum('amount_paid');
                                    $outstandingBalance = max($inv->total - $previousPayments, 0);
                                @endphp
                                <option value="{{ $inv->id }}" 
                                        data-case="{{ $inv->case_id }}" 
                                        data-customer="{{ $inv->customer_name }}" 
                                        data-total="{{ $inv->total }}"
                                        data-previous-payments="{{ $previousPayments }}"
                                        data-outstanding-balance="{{ $outstandingBalance }}">
                                    {{ $inv->invoice_no }} - RM {{ number_format($inv->total, 2) }}
                                    @if($previousPayments > 0)
                                        (Paid: RM {{ number_format($previousPayments, 2) }})
                                    @endif
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div id="case_select_div" class="hidden">
                        <label class="block text-xs font-medium text-gray-700 mb-2">Select Case</label>
                        <select name="case_id" id="case_select" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">Choose case</option>
                            @foreach($caseOptions as $case)
                                <option value="{{ $case->id }}">{{ $case->case_number }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            
            <!-- Payment Information -->
            <div class="space-y-4 mb-8">
                <h3 class="text-sm font-semibold text-gray-800 mb-4">Payment Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-2">Receipt Date *</label>
                        <input name="receipt_date" type="date" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs focus:outline-none focus:ring-2 focus:ring-blue-500" value="{{ date('Y-m-d') }}" required>
                    </div>
                    
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-2">Payment Reference</label>
                        <input name="payment_reference" type="text" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Customer payment reference">
                    </div>
                    
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-2">Payment Method *</label>
                        <select name="payment_method" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs focus:outline-none focus:ring-2 focus:ring-blue-500" required onchange="togglePaymentFields()">
                            <option value="">Select payment method</option>
                            <option value="cash">Cash</option>
                            <option value="bank_transfer">Bank Transfer</option>
                            <option value="cheque">Cheque</option>
                            <option value="credit_card">Credit Card</option>
                            <option value="online_payment">Online Payment</option>
                            <option value="other">Other</option>
                        </select>
                    </div>
                    
                    <div id="bank_name_div" class="hidden">
                        <label class="block text-xs font-medium text-gray-700 mb-2">Bank Name</label>
                        <input name="bank_name" type="text" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Bank name">
                    </div>
                    
                    <div id="cheque_number_div" class="hidden">
                        <label class="block text-xs font-medium text-gray-700 mb-2">Cheque Number</label>
                        <input name="cheque_number" type="text" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Cheque number">
                    </div>
                    
                    <div id="transaction_id_div" class="hidden">
                        <label class="block text-xs font-medium text-gray-700 mb-2">Transaction ID</label>
                        <input name="transaction_id" type="text" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Transaction ID">
                    </div>
                </div>
            </div>
            
            <!-- Amount Information -->
            <div class="space-y-4 mb-8">
                <h3 class="text-sm font-semibold text-gray-800 mb-4">Amount Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-2">Total Amount (RM)</label>
                        <input type="text" id="total_amount" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs focus:outline-none focus:ring-2 focus:ring-blue-500 bg-gray-50" readonly>
                    </div>
                    
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-2">Previous Payments (RM)</label>
                        <input type="text" id="previous_payments" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs focus:outline-none focus:ring-2 focus:ring-blue-500 bg-gray-50" readonly>
                    </div>
                    
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-2">Amount Paid (RM) *</label>
                        <input name="amount_paid" type="number" step="0.01" min="0.01" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="0.00" required onchange="calculateOutstanding()" max="0">
                    </div>
                    
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-2">Outstanding Balance (RM)</label>
                        <input name="outstanding_balance" type="number" step="0.01" min="0" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs focus:outline-none focus:ring-2 focus:ring-blue-500 bg-gray-50" readonly>
                    </div>
                    
                    <div class="md:col-span-2">
                        <label class="block text-xs font-medium text-gray-700 mb-2">Payment Notes</label>
                        <textarea name="payment_notes" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Additional payment notes"></textarea>
                    </div>
                </div>
            </div>
            
            <!-- Form Actions -->
            <div class="flex flex-col md:flex-row justify-end space-y-2 md:space-y-0 md:space-x-3 pt-6">
                <a href="{{ route('receipt.index') }}" class="w-full md:w-auto px-4 py-2 bg-gray-500 text-white text-xs rounded-lg hover:bg-gray-600 transition-colors text-center">
                    Cancel
                </a>
                <button type="submit" class="w-full md:w-auto px-4 py-2 bg-green-600 text-white text-xs rounded-lg hover:bg-green-700 transition-colors flex items-center justify-center">
                    <span class="material-icons text-xs mr-1">save</span>
                    Add Receipt
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function toggleReferenceFields() {
    const referenceType = document.getElementById('reference_type').value;
    const invoiceDiv = document.getElementById('invoice_select_div');
    const caseDiv = document.getElementById('case_select_div');
    
    // Hide all
    invoiceDiv.classList.add('hidden');
    caseDiv.classList.add('hidden');
    
    // Show selected
    if (referenceType === 'tax_invoice') {
        invoiceDiv.classList.remove('hidden');
    } else if (referenceType === 'case') {
        caseDiv.classList.remove('hidden');
    }
    
    // Clear data
    clearReferenceData();
}

function togglePaymentFields() {
    const paymentMethod = document.querySelector('select[name="payment_method"]').value;
    const bankDiv = document.getElementById('bank_name_div');
    const chequeDiv = document.getElementById('cheque_number_div');
    const transactionDiv = document.getElementById('transaction_id_div');
    
    // Hide all
    bankDiv.classList.add('hidden');
    chequeDiv.classList.add('hidden');
    transactionDiv.classList.add('hidden');
    
    // Show relevant fields
    if (paymentMethod === 'bank_transfer') {
        bankDiv.classList.remove('hidden');
    } else if (paymentMethod === 'cheque') {
        chequeDiv.classList.remove('hidden');
    } else if (paymentMethod === 'online_payment') {
        transactionDiv.classList.remove('hidden');
    }
}



function loadInvoiceData() {
    const select = document.getElementById('invoice_select');
    const option = select.options[select.selectedIndex];
    
    if (option.value) {
        document.getElementById('total_amount').value = option.dataset.total;
        
        // Get previous payments from data attributes
        const previousPayments = parseFloat(option.dataset.previousPayments) || 0;
        document.getElementById('previous_payments').value = previousPayments.toFixed(2);
        
        // Set max amount for new payment
        const maxAmount = parseFloat(option.dataset.outstandingBalance) || 0;
        document.querySelector('input[name="amount_paid"]').max = maxAmount;
        
        calculateOutstanding();
    } else {
        clearReferenceData();
    }
}

function calculateOutstanding() {
    const totalAmount = parseFloat(document.getElementById('total_amount').value) || 0;
    const previousPayments = parseFloat(document.getElementById('previous_payments').value) || 0;
    const newAmountPaid = parseFloat(document.querySelector('input[name="amount_paid"]').value) || 0;
    
    // Calculate outstanding after new payment
    const outstanding = Math.max(0, totalAmount - previousPayments - newAmountPaid);
    
    document.querySelector('input[name="outstanding_balance"]').value = outstanding.toFixed(2);
}

function clearReferenceData() {
    document.getElementById('total_amount').value = '';
    document.getElementById('previous_payments').value = '';
    document.querySelector('input[name="amount_paid"]').value = '';
    document.querySelector('input[name="amount_paid"]').max = '';
    document.querySelector('input[name="outstanding_balance"]').value = '';
}

// Initialize with prefill data if available
document.addEventListener('DOMContentLoaded', function() {
    @if($prefillData)
        // Set reference type
        document.getElementById('reference_type').value = '{{ $prefillData["type"] }}';
        toggleReferenceFields();
        
        // Set reference data
        if ('{{ $prefillData["type"] }}' === 'invoice') {
            document.getElementById('invoice_select').value = '{{ $prefillData["tax_invoice_id"] }}';
            document.getElementById('total_amount').value = '{{ $prefillData["total_amount"] }}';
            document.getElementById('previous_payments').value = '{{ $prefillData["previous_payments"] ?? 0 }}';
            document.querySelector('input[name="amount_paid"]').max = '{{ $prefillData["outstanding_balance"] }}';
            document.querySelector('input[name="outstanding_balance"]').value = '{{ $prefillData["outstanding_balance"] }}';
        }
    @endif
});
</script>
@endsection
