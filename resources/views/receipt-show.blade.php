@extends('layouts.app')

@section('breadcrumb')
    <span class="text-gray-500">Receipt</span>
@endsection

@section('content')
<style>
    .quotation-print-style {
        font-family: 'Poppins', sans-serif;
        font-size: 12px;
        line-height: 1.4;
        color: #333;
    }
    .company-header {
        display: flex;
        align-items: flex-start;
        margin-bottom: 20px;
    }
    .company-logo {
        width: 80px;
        height: 80px;
        margin-right: 20px;
        flex-shrink: 0;
    }
    .company-info {
        flex: 1;
    }
    .company-info h2 {
        font-size: 16px;
        font-weight: bold;
        margin-bottom: 8px;
        color: #333;
    }
    .company-info p {
        font-size: 12px;
        margin-bottom: 4px;
        color: #666;
    }
    .contact-label {
        display: inline-block;
        width: 70px;
        color: #333;
    }
    .contact-separator {
        margin: 0 8px;
        color: #333;
    }
    .contact-value {
        color: #666;
    }
    .document-title {
        text-align: center;
        font-size: 24px;
        font-weight: bold;
        margin: 30px 0;
        color: #333;
        text-transform: uppercase;
        letter-spacing: 2px;
    }
    .customer-info {
        float: left;
        margin-bottom: 4px;
    }
    .customer-info h3 {
        font-size: 16px;
        font-weight: bold;
        margin-bottom: 8px;
        color: #333;
    }
    .customer-info p, .quotation-meta p {
        font-size: 12px;
        margin-bottom: 4px;
        color: #666;
    }
    .quotation-meta {
        float: right;
        text-align: left;
        margin-bottom: 4px;
        margin-right: 0;
        width: auto;
    }
    .quotation-meta p strong {
        color: #333;
    }
    .quotation-label {
        display: inline-block;
        width: 100px;
        color: #333;
    }
    .quotation-separator {
        margin: 0 8px;
        color: #333;
    }
    .quotation-value {
        color: #666;
    }
    .customer-contact-label {
        display: inline-block;
        width: 80px;
        color: #333;
    }
    .customer-contact-separator {
        margin: 0 8px;
        color: #333;
    }
    .customer-contact-value {
        color: #666;
    }
    .summary-section {
        margin-top: 30px;
        overflow: auto;
    }
    .amount-words {
        float: left;
        width: 60%;
        margin-bottom: 20px;
    }
    .summary-table {
        float: right;
        border-collapse: collapse;
        margin-bottom: 20px;
    }
    .summary-label {
        text-align: right;
        padding: 8px 12px 8px 0;
        font-weight: bold;
        font-size: 12px;
        color: #333;
    }
    .summary-value {
        text-align: right;
        padding: 8px 8px;
        border: 1px solid #000;
        min-width: 80px;
    }
    .footer-section {
        margin-top: 40px;
    }
    .reminder-text {
        font-size: 12px;
        color: #666;
        margin-bottom: 30px;
        line-height: 1.4;
    }
    .signature-section {
        margin-top: 60px;
    }
    .signature-line {
        width: 150px;
        border-top: 1px solid #000;
        margin-bottom: 8px;
    }
    .signature-label {
        font-size: 12px;
        font-weight: bold;
        color: #333;
    }
</style>
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
        <div class="p-4 md:p-6 quotation-print-style">
            <!-- Company Header -->
            <div class="company-header">
                <div class="company-logo">
                    <img src="{{ asset('images/logo.png') }}" alt="Company Logo" class="w-full h-full object-contain">
                </div>
                <div class="company-info">
                    @php
                        $firmSettings = \App\Models\FirmSetting::getFirmSettings();
                    @endphp
                    <h2>{{ $firmSettings['firm_name'] ?? 'Naeelah Saleh & Associates' }} ({{ $firmSettings['registration_number'] ?? 'LLP0012345' }})</h2>
                    <p>{{ $firmSettings['address'] ?? 'No. 123, Jalan Tun Razak, 50400 Kuala Lumpur, Malaysia' }}</p>
                    <p><span class="contact-label">Phone No.</span><span class="contact-separator">:</span><span class="contact-value">{{ $firmSettings['phone_number'] ?? '+603-1234-5678' }}</span></p>
                    <p><span class="contact-label">Email</span><span class="contact-separator">:</span><span class="contact-value">{{ $firmSettings['email'] ?? 'admin@naeelahsaleh.com.my' }}</span></p>
                    @if(!empty($firmSettings['website']))
                        <p><span class="contact-label">Website</span><span class="contact-separator">:</span><span class="contact-value">{{ $firmSettings['website'] }}</span></p>
                    @endif
                </div>
            </div>

            <div class="document-title">Receipt</div>

            <div style="overflow: auto; margin-bottom: 30px; clear: both;">
                <div class="customer-info">
                    <h3>Bill To:</h3>
                    <p><strong>{{ $receipt->customer_name ?? 'Customer Name' }}</strong></p>
                    @if($receipt->taxInvoice)
                        <p>{{ $receipt->taxInvoice->customer_address ?? 'Customer Address' }}</p>
                        <p><span class="customer-contact-label">Phone No.</span><span class="customer-contact-separator">:</span><span class="customer-contact-value">{{ $receipt->taxInvoice->customer_phone ?? 'Phone Number' }}</span></p>
                        <p><span class="customer-contact-label">Email</span><span class="customer-contact-separator">:</span><span class="customer-contact-value">{{ $receipt->taxInvoice->customer_email ?? 'Email' }}</span></p>
                    @elseif($receipt->quotation)
                        <p>{{ $receipt->quotation->customer_address ?? 'Customer Address' }}</p>
                        <p><span class="customer-contact-label">Phone No.</span><span class="customer-contact-separator">:</span><span class="customer-contact-value">{{ $receipt->quotation->customer_phone ?? 'Phone Number' }}</span></p>
                        <p><span class="customer-contact-label">Email</span><span class="customer-contact-separator">:</span><span class="customer-contact-value">{{ $receipt->quotation->customer_email ?? 'Email' }}</span></p>
                    @else
                        <p>Customer Address</p>
                        <p><span class="customer-contact-label">Phone No.</span><span class="customer-contact-separator">:</span><span class="customer-contact-value">Phone Number</span></p>
                        <p><span class="customer-contact-label">Email</span><span class="customer-contact-separator">:</span><span class="customer-contact-value">Email</span></p>
                    @endif
                </div>
                <div class="quotation-meta">
                    <p><span class="quotation-label">Receipt No.</span><span class="quotation-separator">:</span><span class="quotation-value">{{ $receipt->receipt_no }}</span></p>
                    <p><span class="quotation-label">Reference</span><span class="quotation-separator">:</span><span class="quotation-value">
                        @if($receipt->quotation)
                            {{ $receipt->quotation->quotation_no }}
                        @elseif($receipt->taxInvoice)
                            {{ $receipt->taxInvoice->invoice_no }}
                        @elseif($receipt->case)
                            {{ $receipt->case->case_number }}
                        @else
                            N/A
                        @endif
                    </span></p>
                    <p><span class="quotation-label">Receipt Date</span><span class="quotation-separator">:</span><span class="quotation-value">{{ $receipt->receipt_date->format('d/m/Y') }}</span></p>
                    <p><span class="quotation-label">Payment Method</span><span class="quotation-separator">:</span><span class="quotation-value">{{ $receipt->payment_method_display }}</span></p>
                    <p><span class="quotation-label">Page</span><span class="quotation-separator">:</span><span class="quotation-value">1 of 1</span></p>
                </div>
            </div>

            <div style="clear: both;"></div>

                <!-- Payment Details Table -->
                <div class="mb-8">
                    <table class="w-full border-collapse">
                        <thead>
                            <tr>
                                <th class="px-3 py-2 text-center text-xs" style="font-weight: 900; border-top: 1px solid #d1d5db; border-bottom: 1px solid #d1d5db; border-left: none; border-right: none;">PAYMENT DETAILS</th>
                                <th class="px-3 py-2 text-center text-xs w-32" style="font-weight: 900; border-top: 1px solid #d1d5db; border-bottom: 1px solid #d1d5db; border-left: none; border-right: none;">AMOUNT (RM)</th>
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
                <div class="summary-section">
                    <div class="amount-words">
                        <p><strong>Ringgit Malaysia:</strong> {{ $receipt->amount_paid_words ?? 'Amount in Words' }}</p>
                        <p style="margin-top: 15px;"><strong>Reference Information:</strong><br>
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
                    <div>
                        <table class="summary-table">
                            <tr>
                                <td class="summary-label">Total Amount:</td>
                                <td class="summary-value">{{ number_format($receipt->total_amount ?? 0, 2) }}</td>
                            </tr>
                            <tr>
                                <td class="summary-label">Amount Paid:</td>
                                <td class="summary-value">{{ number_format($receipt->amount_paid ?? 0, 2) }}</td>
                            </tr>
                            @if($receipt->outstanding_balance > 0)
                            <tr>
                                <td class="summary-label">Outstanding:</td>
                                <td class="summary-value">{{ number_format($receipt->outstanding_balance ?? 0, 2) }}</td>
                            </tr>
                            @endif
                        </table>
                    </div>
                </div>

                <div style="clear: both;"></div>

                <div class="footer-section">
                    @if($receipt->taxInvoice)
                    <div class="reminder-text">
                        <p><strong>Reminder:</strong> Pursuant to the Solicitor's Remuneration Order 2005, interest 8% on the total sum billed will be charges from the expiration of one (1) month of the billing dates</p>
                    </div>
                    @endif

                    <div class="signature-section">
                        <div class="signature-line"></div>
                        <p class="signature-label">Authorised Signature</p>
                    </div>
                </div>
            </div>


        </div>
    </div>
</div>
@endsection
