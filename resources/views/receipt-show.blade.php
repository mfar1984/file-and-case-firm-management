@extends('layouts.app')

@section('breadcrumb')
    <span class="text-gray-500">Receipt</span>
@endsection

@section('content')
<div class="px-4 md:px-6 pt-4 md:pt-6 pb-6 max-w-7xl mx-auto">
    <div class="bg-white rounded shadow-md border border-gray-300">
        <div class="px-4 md:px-6 py-4 border-b border-gray-200 flex items-center justify-between">
            <div class="flex items-center">
                <span class="material-icons mr-2 text-green-600">receipt</span>
                <h1 class="text-lg md:text-xl font-bold text-gray-800 text-[14px]">Receipt {{ $receipt->receipt_no }}</h1>
            </div>
            <a href="{{ route('receipt.index') }}" class="text-xs text-blue-600 hover:underline">Back</a>
        </div>
        <div class="p-4 md:p-6 space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <div class="text-xs text-gray-500">Customer</div>
                    <div class="text-sm font-semibold">{{ $receipt->customer_name }}</div>
                    <div class="text-xs text-gray-700">
                        @if($receipt->taxInvoice)
                            {{ $receipt->taxInvoice->customer_email ?? 'N/A' }} {{ $receipt->taxInvoice->customer_phone ? ' • ' . $receipt->taxInvoice->customer_phone : '' }}
                        @elseif($receipt->quotation)
                            {{ $receipt->quotation->customer_email ?? 'N/A' }} {{ $receipt->quotation->customer_phone ? ' • ' . $receipt->quotation->customer_phone : '' }}
                        @else
                            N/A
                        @endif
                    </div>
                    <div class="text-xs text-gray-700">
                        @if($receipt->taxInvoice)
                            {{ $receipt->taxInvoice->customer_address ?? 'N/A' }}
                        @elseif($receipt->quotation)
                            {{ $receipt->quotation->customer_address ?? 'N/A' }}
                        @else
                            N/A
                        @endif
                    </div>
                </div>
                <div class="text-right">
                    <div class="text-xs text-gray-500">Reference</div>
                    <div class="text-sm font-semibold">
                        @if($receipt->quotation)
                            {{ $receipt->quotation->quotation_no }}
                        @elseif($receipt->taxInvoice)
                            {{ $receipt->taxInvoice->invoice_no }}
                        @elseif($receipt->case)
                            {{ $receipt->case->case_number }}
                        @else
                            N/A
                        @endif
                    </div>
                    <div class="text-xs text-gray-700">Receipt Date: {{ $receipt->receipt_date->format('d/m/Y') }}</div>
                    <div class="text-xs text-gray-700">Payment Method: {{ $receipt->payment_method_display }}</div>
                    <div class="text-xs text-gray-700">Status: {{ ucfirst($receipt->status) }}</div>
                </div>
            </div>

            <!-- Payment Details Table -->
            <div class="overflow-x-auto border border-gray-200 rounded-sm">
                <table class="min-w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Payment Details</th>
                            <th class="px-4 py-3 text-center text-xs font-medium text-gray-700 uppercase tracking-wider">Amount (RM)</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100">
                        <tr>
                            <td class="px-4 py-3 text-xs">
                                <div class="space-y-1">
                                    <div><strong>Payment Method:</strong> {{ $receipt->payment_method_display }}</div>
                                    @if($receipt->payment_reference)
                                        <div><strong>Reference:</strong> {{ $receipt->payment_reference }}</div>
                                    @endif
                                    @if($receipt->bank_name)
                                        <div><strong>Bank:</strong> {{ $receipt->bank_name }}</div>
                                    @endif
                                    @if($receipt->cheque_number)
                                        <div><strong>Cheque:</strong> {{ $receipt->cheque_number }}</div>
                                    @endif
                                    @if($receipt->transaction_id)
                                        <div><strong>Transaction ID:</strong> {{ $receipt->transaction_id }}</div>
                                    @endif
                                    @if($receipt->payment_notes)
                                        <div><strong>Notes:</strong> {{ $receipt->payment_notes }}</div>
                                    @endif
                                </div>
                            </td>
                            <td class="px-4 py-3 text-right text-xs">
                                <div class="space-y-1">
                                    <div><strong>Amount Paid:</strong></div>
                                    <div class="text-lg font-semibold text-green-600">RM {{ number_format($receipt->amount_paid, 2) }}</div>
                                    @if($receipt->outstanding_balance > 0)
                                        <div class="text-sm text-red-600">Balance: RM {{ number_format($receipt->outstanding_balance, 2) }}</div>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Reference Information -->
            <div class="bg-gray-50 rounded-sm p-4">
                <h3 class="text-sm font-semibold text-gray-800 mb-3">Reference Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-xs">
                    <div>
                        <span class="text-gray-500">Reference Type:</span>
                        <div class="font-medium">
                            @if($receipt->quotation)
                                <span class="inline-block px-1.5 py-0.5 rounded-full text-[10px] bg-blue-100 text-blue-800">Quotation</span>
                            @elseif($receipt->taxInvoice)
                                <span class="inline-block px-1.5 py-0.5 rounded-full text-[10px] bg-green-100 text-green-800">Tax Invoice</span>
                            @elseif($receipt->case)
                                <span class="inline-block px-1.5 py-0.5 rounded-full text-[10px] bg-gray-100 text-gray-800">Case</span>
                            @else
                                <span class="text-gray-500">N/A</span>
                            @endif
                        </div>
                    </div>
                    <div>
                        <span class="text-gray-500">Reference Number:</span>
                        <div class="font-medium">
                            @if($receipt->quotation)
                                {{ $receipt->quotation->quotation_no }}
                            @elseif($receipt->taxInvoice)
                                {{ $receipt->taxInvoice->invoice_no }}
                            @elseif($receipt->case)
                                {{ $receipt->case->case_number }}
                            @else
                                N/A
                            @endif
                        </div>
                    </div>
                                         <div class="text-right">
                         <span class="text-gray-500">Total Amount:</span>
                         <div class="font-medium">RM {{ number_format($receipt->total_amount, 2) }}</div>
                     </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex justify-end space-x-3">
                <a href="{{ route('receipt.edit', $receipt->id) }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-xs font-medium flex items-center">
                    <span class="material-icons text-xs mr-1">edit</span>
                    Edit Receipt
                </a>
                <a href="{{ route('receipt.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-md text-xs font-medium flex items-center">
                    <span class="material-icons text-xs mr-1">arrow_back</span>
                    Back to Receipts
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
