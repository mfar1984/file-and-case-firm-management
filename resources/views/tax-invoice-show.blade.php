@extends('layouts.app')

@section('breadcrumb')
    <span class="text-gray-500">Tax Invoice</span>
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
        display: flex;
        justify-content: flex-end;
    }
    .summary-table {
        border-collapse: collapse;
        margin-left: auto;
    }
    .summary-table td {
        padding: 8px 12px;
        font-size: 12px;
        font-weight: bold;
        color: #333;
    }
    .summary-label {
        text-align: right;
        font-weight: bold;
        border: none;
    }
    .summary-value {
        text-align: right;
        border: 1px solid #000;
        min-width: 80px;
    }
    .footer-section {
        margin-top: 40px;
    }
    .reminder-text {
        font-size: 12px;
        color: #333;
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
                <span class="material-icons mr-2 text-blue-600">receipt</span>
                <h1 class="text-lg md:text-xl font-bold text-gray-800 text-[14px]">Tax Invoice {{ $taxInvoice->invoice_no }}</h1>
            </div>
            <div class="flex items-center space-x-3">
                <a href="{{ route('tax-invoice.print', $taxInvoice->id) }}" target="_blank" class="flex items-center text-xs text-blue-600 hover:text-blue-800">
                    <span class="material-icons mr-1 text-sm">print</span>
                    Print
                </a>
                <a href="{{ route('tax-invoice.index') }}" class="text-xs text-blue-600 hover:underline">Back</a>
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

            <div class="document-title">Tax Invoice</div>

            <div style="overflow: auto; margin-bottom: 30px; clear: both;">
                <div class="customer-info">
                    <h3>Bill To:</h3>
                    <p><strong>{{ $taxInvoice->customer_name ?? 'Customer Name' }}</strong></p>
                    <p>{{ $taxInvoice->customer_address ?? 'Customer Address' }}</p>
                    <p><span class="customer-contact-label">Phone No.</span><span class="customer-contact-separator">:</span><span class="customer-contact-value">{{ $taxInvoice->customer_phone ?? 'Phone Number' }}</span></p>
                    <p><span class="customer-contact-label">Email</span><span class="customer-contact-separator">:</span><span class="customer-contact-value">{{ $taxInvoice->customer_email ?? 'Email' }}</span></p>
                </div>
                <div class="quotation-meta">
                    <p><span class="quotation-label">Invoice No.</span><span class="quotation-separator">:</span><span class="quotation-value">{{ $taxInvoice->invoice_no }}</span></p>
                    <p><span class="quotation-label">Case Ref</span><span class="quotation-separator">:</span><span class="quotation-value">{{ $taxInvoice->case->case_number ?? 'N/A' }}</span></p>
                    <p><span class="quotation-label">Issue Date</span><span class="quotation-separator">:</span><span class="quotation-value">{{ optional($taxInvoice->invoice_date)->format('d/m/Y') ?? '04/09/2025' }}</span></p>
                    <p><span class="quotation-label">Due Date</span><span class="quotation-separator">:</span><span class="quotation-value">{{ optional($taxInvoice->due_date)->format('d/m/Y') ?? '04/10/2025' }}</span></p>
                    <p><span class="quotation-label">Page</span><span class="quotation-separator">:</span><span class="quotation-value">1 of 1</span></p>
                </div>
            </div>

            <div style="clear: both;"></div>

            <!-- Items Table -->
            <div class="mb-8">
                <table class="w-full border-collapse">
                    <thead>
                        <tr>
                            <th class="px-3 py-2 text-center text-xs w-12" style="font-weight: 900; border-top: 1px solid #d1d5db; border-bottom: 1px solid #d1d5db; border-left: none; border-right: none;">ITEM.</th>
                            <th class="px-3 py-2 text-center text-xs" style="font-weight: 900; border-top: 1px solid #d1d5db; border-bottom: 1px solid #d1d5db; border-left: none; border-right: none;">DESCRIPTION</th>
                            <th class="px-3 py-2 text-center text-xs w-24" style="font-weight: 900; border-top: 1px solid #d1d5db; border-bottom: 1px solid #d1d5db; border-left: none; border-right: none;">PRICE<br>(RM)</th>
                            <th class="px-3 py-2 text-center text-xs w-20" style="font-weight: 900; border-top: 1px solid #d1d5db; border-bottom: 1px solid #d1d5db; border-left: none; border-right: none;">DISC.</th>
                            <th class="px-3 py-2 text-center text-xs w-24" style="font-weight: 900; border-top: 1px solid #d1d5db; border-bottom: 1px solid #d1d5db; border-left: none; border-right: none;">AMOUNT<br>(RM)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $itemCounter = 1; // Counter for regular items only
                        @endphp
                        @forelse($taxInvoice->items as $index => $item)
                            @if($item->item_type === 'title')
                                <!-- Title Row -->
                                <tr style="background-color: #fff7ed;">
                                    <td colspan="5" style="padding: 8px; font-weight: bold; color: #c2410c; text-align: left;">
                                        {{ $item->title_text }}
                                    </td>
                                </tr>
                            @else
                                <!-- Regular Item Row -->
                                <tr>
                                    <td class="px-3 py-2 text-center text-sm">{{ $itemCounter++ }}</td>
                                    <td class="px-3 py-2 text-sm">{{ $item->description }}</td>
                                    <td class="px-3 py-2 text-center text-sm">{{ number_format($item->unit_price, 2) }}</td>
                                    <td class="px-3 py-2 text-center text-sm">{{ number_format($item->discount_percent ?? 0, 0) }}%</td>
                                    <td class="px-3 py-2 text-right text-sm font-medium">{{ number_format($item->amount, 2) }}</td>
                                </tr>
                            @endif
                        @empty
                            <tr>
                                <td colspan="5" class="px-3 py-4 text-center text-gray-500">No items found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="summary-section">
                <table class="summary-table">
                    <tr>
                        <td class="summary-label">Subtotal:</td>
                        <td class="summary-value">{{ number_format($taxInvoice->subtotal ?? 0, 2) }}</td>
                    </tr>
                    <tr>
                        <td class="summary-label">Tax:</td>
                        <td class="summary-value">{{ number_format($taxInvoice->tax_total ?? 0, 2) }}</td>
                    </tr>
                    <tr>
                        <td class="summary-label">Total:</td>
                        <td class="summary-value">{{ number_format($taxInvoice->total ?? 0, 2) }}</td>
                    </tr>
                </table>
            </div>

            <div class="footer-section">
                <div class="reminder-text">
                    <p><strong>Ringgit Malaysia:</strong> {{ $taxInvoice->total_words ?? 'Amount in Words' }}</p>
                    @if($taxInvoice->remark)
                        <p style="margin-top: 15px;"><strong>Note:</strong> {{ $taxInvoice->remark }}</p>
                    @endif
                </div>

                <div class="signature-section">
                    <div class="signature-line"></div>
                    <div class="signature-label">Authorised Signature</div>
                </div>
            </div>
            </div>
        </div>
    </div>
</div>
@endsection
