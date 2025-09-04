@extends('layouts.app')

@section('breadcrumb')
    Receipt / Edit / {{ $receipt->receipt_no }}
@endsection

@section('content')
<div class="px-4 md:px-6 pt-4 md:pt-6 pb-6 max-w-7xl mx-auto">
    <div class="bg-white rounded shadow-md border border-gray-300">
        <div class="p-4 md:p-6 border-b border-gray-200">
            <div class="flex flex-col md:flex-row md:justify-between md:items-start space-y-4 md:space-y-0">
                <div>
                    <div class="flex items-center">
                        <span class="material-icons mr-2 text-indigo-600">edit</span>
                        <h1 class="text-lg md:text-xl font-bold text-gray-800">Edit Receipt {{ $receipt->receipt_no }}</h1>
                    </div>
                    <p class="text-xs text-gray-500 mt-1 ml-8">Update receipt information and payment details.</p>
                </div>
                
                <!-- Back Button -->
                <a href="{{ route('receipt.show', $receipt->id) }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 md:px-3 md:py-1 rounded-md text-sm md:text-xs font-medium flex items-center justify-center md:justify-start w-full md:w-auto">
                    <span class="material-icons text-sm md:text-xs mr-1">arrow_back</span>
                    Back to Receipt
                </a>
            </div>
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

        <form method="POST" action="{{ route('receipt.update', $receipt->id) }}" class="p-6">
            @csrf
            @method('PUT')
            
            <!-- Reference Selection -->
            <div class="mb-8">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">Payment Reference</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-2">Reference Type</label>
                        <select name="reference_type" id="reference_type" class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500" onchange="toggleReferenceFields()">
                            <option value="">Select Reference Type</option>
                            <option value="quotation" {{ $receipt->quotation_id ? 'selected' : '' }}>Quotation</option>
                            <option value="tax_invoice" {{ $receipt->tax_invoice_id ? 'selected' : '' }}>Tax Invoice</option>
                            <option value="case" {{ $receipt->case_id && !$receipt->quotation_id && !$receipt->tax_invoice_id ? 'selected' : '' }}>Case Only</option>
                        </select>
                    </div>
                    
                    <div id="quotation_select_div" class="{{ $receipt->quotation_id ? '' : 'hidden' }}">
                        <label class="block text-xs font-medium text-gray-700 mb-2">Select Quotation</label>
                        <select name="quotation_id" id="quotation_select" class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500" onchange="loadQuotationData()">
                            <option value="">Choose quotation</option>
                            @foreach($quotations as $q)
                                <option value="{{ $q->id }}" data-case="{{ $q->case_id }}" data-customer="{{ $q->customer_name }}" data-total="{{ $q->total }}" {{ $receipt->quotation_id == $q->id ? 'selected' : '' }}>
                                    {{ $q->quotation_no }} - RM {{ number_format($q->total, 2) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div id="invoice_select_div" class="{{ $receipt->tax_invoice_id ? '' : 'hidden' }}">
                        <label class="block text-xs font-medium text-gray-700 mb-2">Select Tax Invoice</label>
                        <select name="tax_invoice_id" id="invoice_select" class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500" onchange="loadInvoiceData()">
                            <option value="">Choose tax invoice</option>
                            @foreach($taxInvoices as $inv)
                                <option value="{{ $inv->id }}" data-case="{{ $inv->case_id }}" data-customer="{{ $inv->customer_name }}" data-total="{{ $inv->total }}" {{ $receipt->tax_invoice_id == $inv->id ? 'selected' : '' }}>
                                    {{ $inv->invoice_no }} - RM {{ number_format($inv->total, 2) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div id="case_select_div" class="{{ $receipt->case_id && !$receipt->quotation_id && !$receipt->tax_invoice_id ? '' : 'hidden' }}">
                        <label class="block text-xs font-medium text-gray-700 mb-2">Select Case</label>
                        <select name="case_id" id="case_select" class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">Choose case</option>
                            @foreach($caseOptions as $case)
                                <option value="{{ $case->id }}" {{ $receipt->case_id == $case->id ? 'selected' : '' }}>{{ $case->case_number }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            
            <!-- Payment Information -->
            <div class="mb-8">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">Payment Information</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-2">Receipt Date *</label>
                        <input name="receipt_date" type="date" class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500" value="{{ $receipt->receipt_date->format('Y-m-d') }}" required>
                    </div>
                    
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-2">Payment Reference</label>
                        <input name="payment_reference" type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Customer payment reference" value="{{ $receipt->payment_reference }}">
                    </div>
                    
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-2">Payment Method *</label>
                        <select name="payment_method" class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500" required onchange="togglePaymentFields()">
                            <option value="">Select payment method</option>
                            <option value="cash" {{ $receipt->payment_method == 'cash' ? 'selected' : '' }}>Cash</option>
                            <option value="bank_transfer" {{ $receipt->payment_method == 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
                            <option value="cheque" {{ $receipt->payment_method == 'cheque' ? 'selected' : '' }}>Cheque</option>
                            <option value="credit_card" {{ $receipt->payment_method == 'credit_card' ? 'selected' : '' }}>Credit Card</option>
                            <option value="online_payment" {{ $receipt->payment_method == 'online_payment' ? 'selected' : '' }}>Online Payment</option>
                            <option value="other" {{ $receipt->payment_method == 'other' ? 'selected' : '' }}>Other</option>
                        </select>
                    </div>
                    
                    <div id="bank_name_div" class="{{ $receipt->payment_method == 'bank_transfer' ? '' : 'hidden' }}">
                        <label class="block text-xs font-medium text-gray-700 mb-2">Bank Name</label>
                        <input name="bank_name" type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Bank name" value="{{ $receipt->bank_name }}">
                    </div>
                    
                    <div id="cheque_number_div" class="{{ $receipt->payment_method == 'cheque' ? '' : 'hidden' }}">
                        <label class="block text-xs font-medium text-gray-700 mb-2">Cheque Number</label>
                        <input name="cheque_number" type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Cheque number" value="{{ $receipt->cheque_number }}">
                    </div>
                    
                    <div id="transaction_id_div" class="{{ $receipt->payment_method == 'online_payment' ? '' : 'hidden' }}">
                        <label class="block text-xs font-medium text-gray-700 mb-2">Transaction ID</label>
                        <input name="transaction_id" type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Transaction ID" value="{{ $receipt->transaction_id }}">
                    </div>
                </div>
            </div>
            
            <!-- Amount Information -->
            <div class="mb-8">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">Amount Information</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-2">Total Amount (RM)</label>
                        <input type="text" id="total_amount" class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500 bg-gray-50" readonly value="{{ number_format($receipt->total_amount, 2) }}">
                    </div>
                    
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-2">Amount Paid (RM) *</label>
                        <input name="amount_paid" type="number" step="0.01" min="0.01" class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="0.00" value="{{ $receipt->amount_paid }}" required onchange="calculateOutstanding()">
                    </div>
                    
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-2">Outstanding Balance (RM)</label>
                        <input name="outstanding_balance" type="number" step="0.01" min="0" class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500 bg-gray-50" readonly value="{{ $receipt->outstanding_balance }}">
                    </div>
                    
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-2">Payment Notes</label>
                        <textarea name="payment_notes" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Additional payment notes">{{ $receipt->payment_notes }}</textarea>
                    </div>
                </div>
            </div>
            
            <!-- Submit Button -->
            <div class="flex justify-end space-x-4">
                <a href="{{ route('receipt.show', $receipt->id) }}" class="bg-gray-600 hover:bg-gray-700 text-white px-6 py-2 rounded-md text-sm font-medium">
                    Cancel
                </a>
                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2 rounded-md text-sm font-medium">
                    Update Receipt
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function toggleReferenceFields() {
    const referenceType = document.getElementById('reference_type').value;
    const quotationDiv = document.getElementById('quotation_select_div');
    const invoiceDiv = document.getElementById('invoice_select_div');
    const caseDiv = document.getElementById('case_select_div');
    
    // Hide all
    quotationDiv.classList.add('hidden');
    invoiceDiv.classList.add('hidden');
    caseDiv.classList.add('hidden');
    
    // Show selected
    if (referenceType === 'quotation') {
        quotationDiv.classList.remove('hidden');
    } else if (referenceType === 'tax_invoice') {
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

function loadQuotationData() {
    const select = document.getElementById('quotation_select');
    const option = select.options[select.selectedIndex];
    
    if (option.value) {
        document.getElementById('total_amount').value = option.dataset.total;
        calculateOutstanding();
    } else {
        clearReferenceData();
    }
}

function loadInvoiceData() {
    const select = document.getElementById('invoice_select');
    const option = select.options[select.selectedIndex];
    
    if (option.value) {
        document.getElementById('total_amount').value = option.dataset.total;
        calculateOutstanding();
    } else {
        clearReferenceData();
    }
}

function calculateOutstanding() {
    const totalAmount = parseFloat(document.getElementById('total_amount').value) || 0;
    const amountPaid = parseFloat(document.querySelector('input[name="amount_paid"]').value) || 0;
    const outstanding = Math.max(0, totalAmount - amountPaid);
    
    document.querySelector('input[name="outstanding_balance"]').value = outstanding.toFixed(2);
}

function clearReferenceData() {
    document.getElementById('total_amount').value = '';
    document.querySelector('input[name="amount_paid"]').value = '';
    document.querySelector('input[name="outstanding_balance"]').value = '';
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    // Set initial reference type and show relevant fields
    const referenceType = document.getElementById('reference_type').value;
    if (referenceType) {
        toggleReferenceFields();
    }
    
    // Set initial payment method and show relevant fields
    const paymentMethod = document.querySelector('select[name="payment_method"]').value;
    if (paymentMethod) {
        togglePaymentFields();
    }
});
</script>
@endsection
