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
            <div class="flex items-center space-x-3">
                <a href="{{ route('receipt.print', $receipt->id) }}" target="_blank" class="flex items-center text-xs text-blue-600 hover:text-blue-800">
                    <span class="material-icons mr-1 text-sm">print</span>
                    Print
                </a>
                <a href="{{ route('receipt.index') }}" class="text-xs text-blue-600 hover:underline">Back</a>
            </div>
        </div>
        <div class="p-4 md:p-6">
            <!-- Content -->
            <div>
                <!-- Header Section -->
                <div class="mb-8">
                    <div class="flex items-start">
                        <div class="w-24 h-24 mr-6">
                            <img src="{{ asset('images/logo.png') }}" alt="Company Logo" class="w-full h-full object-contain">
                        </div>
                        <div class="flex-1">
                            <h2 class="text-lg font-bold">Naeelah Saleh & Associates (LLP0012345)</h2>
                            <p class="text-sm text-gray-600">No. 123, Jalan Tun Razak, 50400 Kuala Lumpur, Malaysia</p>
                            <p class="text-sm text-gray-600">Phone No.: +603-1234-5678</p>
                            <p class="text-sm text-gray-600">Email: admin@naeelahsaleh.com.my</p>
                        </div>
                    </div>

                    <div class="text-center mt-6">
                        <h1 class="text-2xl font-bold">Receipt</h1>
                    </div>

                    <div class="grid grid-cols-2 gap-8 mt-6">
                        <div class="customer-info">
                            <p class="font-bold text-lg">{{ $receipt->customer_name ?? 'Customer Name' }}</p>
                            @if($receipt->taxInvoice)
                                <p class="text-sm text-gray-600">{{ $receipt->taxInvoice->customer_address ?? 'Customer Address' }}</p>
                                <p class="text-sm text-gray-600">Phone No.: {{ $receipt->taxInvoice->customer_phone ?? 'Phone Number' }}</p>
                                <p class="text-sm text-gray-600">Email: {{ $receipt->taxInvoice->customer_email ?? 'Email' }}</p>
                            @elseif($receipt->quotation)
                                <p class="text-sm text-gray-600">{{ $receipt->quotation->customer_address ?? 'Customer Address' }}</p>
                                <p class="text-sm text-gray-600">Phone No.: {{ $receipt->quotation->customer_phone ?? 'Phone Number' }}</p>
                                <p class="text-sm text-gray-600">Email: {{ $receipt->quotation->customer_email ?? 'Email' }}</p>
                            @else
                                <p class="text-sm text-gray-600">Customer Address</p>
                                <p class="text-sm text-gray-600">Phone No.: Phone Number</p>
                                <p class="text-sm text-gray-600">Email: Email</p>
                            @endif
                        </div>
                        <div class="quotation-meta text-right">
                            <p class="text-sm"><strong>Receipt No.:</strong> {{ $receipt->receipt_no }}</p>
                            <p class="text-sm"><strong>Reference:</strong>
                                @if($receipt->quotation)
                                    {{ $receipt->quotation->quotation_no }}
                                @elseif($receipt->taxInvoice)
                                    {{ $receipt->taxInvoice->invoice_no }}
                                @elseif($receipt->case)
                                    {{ $receipt->case->case_number }}
                                @else
                                    N/A
                                @endif
                            </p>
                            <p class="text-sm"><strong>Receipt Date:</strong> {{ $receipt->receipt_date->format('d/m/Y') }}</p>
                            <p class="text-sm"><strong>Payment Method:</strong> {{ $receipt->payment_method_display }}</p>
                            <p class="text-sm"><strong>Page:</strong> 1 of 1</p>
                        </div>
                    </div>
                </div>

                <!-- Payment Details Table -->
                <div class="mb-8">
                    <table class="w-full border-collapse">
                        <thead>
                            <tr>
                                <th class="px-3 py-2 text-center text-sm" style="font-weight: 900;">PAYMENT DETAILS</th>
                                <th class="px-3 py-2 text-center text-sm" style="font-weight: 900;">AMOUNT (RM)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="px-3 py-2 text-sm">
                                    <div style="margin-bottom: 2mm;"><strong>Payment Method:</strong> {{ $receipt->payment_method_display }}</div>
                                    @if($receipt->payment_reference)
                                        <div style="margin-bottom: 2mm;"><strong>Reference:</strong> {{ $receipt->payment_reference }}</div>
                                    @endif
                                    @if($receipt->bank_name)
                                        <div style="margin-bottom: 2mm;"><strong>Bank:</strong> {{ $receipt->bank_name }}</div>
                                    @endif
                                    @if($receipt->cheque_number)
                                        <div style="margin-bottom: 2mm;"><strong>Cheque:</strong> {{ $receipt->cheque_number }}</div>
                                    @endif
                                    @if($receipt->transaction_id)
                                        <div style="margin-bottom: 2mm;"><strong>Transaction ID:</strong> {{ $receipt->transaction_id }}</div>
                                    @endif
                                    @if($receipt->payment_notes)
                                        <div><strong>Notes:</strong> {{ $receipt->payment_notes }}</div>
                                    @endif
                                </td>
                                <td class="px-3 py-2 text-sm text-right">
                                    <div style="margin-bottom: 2mm;"><strong>Amount Paid:</strong></div>
                                    <div style="font-size: 12pt; font-weight: bold; color: #059669;">RM {{ number_format($receipt->amount_paid, 2) }}</div>
                                    @if($receipt->outstanding_balance > 0)
                                        <div style="margin-top: 2mm; color: #dc2626;"><strong>Balance:</strong> RM {{ number_format($receipt->outstanding_balance, 2) }}</div>
                                    @endif
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Footer Section -->
                <div>
                    <div class="grid grid-cols-2 gap-8">
                        <div class="amount-in-words">
                            <p class="font-normal" style="font-size: 12px; line-height: 1.2; text-indent: -80px; padding-left: 80px;">Ringgit Malaysia: {{ $receipt->amount_paid_words ?? 'Amount in Words' }}</p>
                            <p class="text-sm mt-4"><strong>Reference Information:</strong><br>
                                @if($receipt->quotation)
                                    <span style="background-color: #dbeafe; color: #1e40af; padding: 1pt 3pt; border-radius: 2pt; font-size: 8pt;">Quotation</span> {{ $receipt->quotation->quotation_no }}
                                @elseif($receipt->taxInvoice)
                                    <span style="background-color: #dcfce7; color: #166534; padding: 1pt 3pt; border-radius: 2pt; font-size: 8pt;">Tax Invoice</span> {{ $receipt->taxInvoice->invoice_no }}
                                @elseif($receipt->case)
                                    <span style="background-color: #f3f4f6; color: #374151; padding: 1pt 3pt; border-radius: 2pt; font-size: 8pt;">Case</span> {{ $receipt->case->case_number }}
                                @else
                                    N/A
                                @endif
                            </p>
                        </div>
                        <div class="summary-table">
                            <table class="w-full border-collapse">
                                <tr>
                                    <td class="py-1 px-2 text-right font-bold text-sm">Total Amount</td>
                                    <td class="py-1 px-2 border border-gray-800 text-right text-sm w-24">{{ number_format($receipt->total_amount ?? 0, 2) }}</td>
                                </tr>
                                <tr>
                                    <td class="py-1 px-2 text-right font-bold text-sm">Amount Paid</td>
                                    <td class="py-1 px-2 border border-gray-800 bg-gray-200 text-right font-bold text-sm w-24">{{ number_format($receipt->amount_paid ?? 0, 2) }}</td>
                                </tr>
                                @if($receipt->outstanding_balance > 0)
                                <tr>
                                    <td class="py-1 px-2 text-right font-bold text-sm">Outstanding</td>
                                    <td class="py-1 px-2 border border-gray-800 text-right text-sm w-24">{{ number_format($receipt->outstanding_balance ?? 0, 2) }}</td>
                                </tr>
                                @endif
                            </table>
                        </div>
                    </div>
                    <div class="mt-8 pt-4 border-t border-gray-800 w-48">
                        <p class="text-sm font-bold">Authorised Signature</p>
                    </div>
                </div>
            </div>

            <!-- Action Buttons (not printed) -->
            <div class="mt-8 flex space-x-2 print:hidden">
                <a href="{{ route('receipt.edit', $receipt->id) }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-sm text-xs font-medium flex items-center">
                    <span class="material-icons text-xs mr-1">edit</span>
                    Edit Receipt
                </a>
                <a href="{{ route('receipt.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-sm text-xs font-medium flex items-center">
                    <span class="material-icons text-xs mr-1">arrow_back</span>
                    Back to Receipts
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
