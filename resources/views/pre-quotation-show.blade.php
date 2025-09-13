@extends('layouts.app')

@section('breadcrumb')
    <span class="text-gray-500">Pre-Quotation</span>
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
        font-size: 18px;
        font-weight: bold;
        text-transform: uppercase;
        letter-spacing: 2px;
        margin: 20px 0;
        color: #333;
    }
    .customer-info {
        float: left;
        width: 50%;
        margin-bottom: 20px;
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
    .clear {
        clear: both;
    }
    .summary-section {
        margin-top: 30px;
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
    }
    .amount-words {
        flex: 1;
        margin-right: 40px;
    }
    .amount-words p {
        font-size: 12px;
        color: #333;
        margin-bottom: 8px;
    }
    .summary-table {
        border-collapse: collapse;
        min-width: 200px;
    }
    .summary-table td {
        padding: 8px 12px;
        font-size: 12px;
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
        <div class="p-4 md:p-6 border-b border-gray-200">
            <div class="flex justify-between items-center">
            <div class="flex items-center">
                <span class="material-icons mr-2 text-blue-600">visibility</span>
                <h1 class="text-lg md:text-xl font-bold text-gray-800 text-[14px]">Pre-Quotation {{ $preQuotation->quotation_no }}</h1>
            </div>
            <div class="flex items-center space-x-3">
                <a href="{{ route('pre-quotation.print', $preQuotation->id) }}" target="_blank" class="flex items-center text-xs text-blue-600 hover:text-blue-800">
                    <span class="material-icons mr-1 text-sm">print</span>
                    Print
                </a>
                <a href="{{ route('pre-quotation.index') }}" class="text-xs text-blue-600">Back</a>
            </div>
            </div>
        </div>

        <!-- Content -->
        <div class="p-4 md:p-6">
        <div class="quotation-print-style">
            <!-- Header Section -->
            <div class="company-header">
                <div class="company-logo">
                    <img src="{{ asset('images/logo.png') }}" alt="Company Logo" style="width: 100%; height: 100%; object-fit: contain;">
                </div>
                <div class="company-info">
                    <h2>{{ $firmSettings->firm_name ?? 'Naeelah Saleh & Associates' }} ({{ $firmSettings->registration_number ?? 'LLP0012345' }})</h2>
                    <p>{{ $firmSettings->address ?? 'No. 123, Jalan Tun Razak, 50400 Kuala Lumpur, Malaysia' }}</p>
                    <p><span class="contact-label">Phone No.</span><span class="contact-separator">:</span><span class="contact-value">{{ $firmSettings->phone_number ?? '+6019-3186436' }}</span></p>
                    <p><span class="contact-label">Email</span><span class="contact-separator">:</span><span class="contact-value">{{ $firmSettings->email ?? 'info@naaelahsaleh.my' }}</span></p>
                    <p><span class="contact-label">SST No.</span><span class="contact-separator">:</span><span class="contact-value">{{ $firmSettings->tax_registration_number ?? 'W24-2507-32000179' }}</span></p>
                </div>
            </div>

            <div class="document-title">
                Legal Quotation
            </div>

            <div class="customer-info">
                <h3>{{ $preQuotation->full_name ?? 'Customer Name' }}</h3>
                <p>{{ $preQuotation->customer_address ?? 'Customer Address' }}</p>
                @if($preQuotation->customer_phone)
                    <p><span class="customer-contact-label">Phone No.</span><span class="customer-contact-separator">:</span><span class="customer-contact-value">{{ $preQuotation->customer_phone }}</span></p>
                @endif
                @if($preQuotation->customer_email)
                    <p><span class="customer-contact-label">Email</span><span class="customer-contact-separator">:</span><span class="customer-contact-value">{{ $preQuotation->customer_email }}</span></p>
                @endif
                <p><span class="customer-contact-label">Attn</span><span class="customer-contact-separator">:</span><span class="customer-contact-value">{{ $preQuotation->customer_attn ?? 'Attention' }}</span></p>
            </div>
            <div class="quotation-meta">
                <p><span class="quotation-label">No.</span><span class="quotation-separator">:</span><span class="quotation-value">{{ $preQuotation->quotation_no }}</span></p>
                <p><span class="quotation-label">Payment Terms</span><span class="quotation-separator">:</span><span class="quotation-value">{{ $preQuotation->payment_terms_display ?? 'Net 30 days' }}</span></p>
                <p><span class="quotation-label">Date</span><span class="quotation-separator">:</span><span class="quotation-value">{{ optional($preQuotation->quotation_date)->format('d/m/Y') ?? date('d/m/Y') }}</span></p>
                <p><span class="quotation-label">Page</span><span class="quotation-separator">:</span><span class="quotation-value">1 of 1</span></p>
            </div>
            <div class="clear"></div>

            <!-- Items Table -->
            <div style="margin-bottom: 20px;">
                <table class="w-full" style="border-collapse: collapse; font-size: 12px;">
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
                        @forelse($preQuotation->items as $index => $item)
                            @if(($item->item_type ?? 'item') === 'title')
                                <!-- Title Row -->
                                <tr style="background-color: #fef3c7;">
                                    <td colspan="5" class="px-3 py-2 text-sm font-semibold text-gray-800">
                                        {{ $item->title_text }}
                                    </td>
                                </tr>
                            @else
                                <!-- Regular Item Row -->
                                <tr>
                                    <td class="px-3 py-2 text-sm text-center">{{ $itemCounter++ }}</td>
                                    <td class="px-3 py-2 text-sm">{{ $item->description }}</td>
                                    <td class="px-3 py-2 text-sm text-right">{{ number_format($item->unit_price, 2) }}</td>
                                    <td class="px-3 py-2 text-sm text-right">{{ number_format($item->discount_percent ?? 0, 0) }}%</td>
                                    <td class="px-3 py-2 text-sm text-right">{{ number_format($item->amount, 2) }}</td>
                                </tr>
                            @endif
                        @empty
                            <tr>
                                <td colspan="5" class="px-3 py-2 text-sm text-center">No items found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Footer Section -->
            <div class="summary-section">
                <div class="amount-words">
                    <p><strong>Ringgit Malaysia:</strong> {{ $preQuotation->total_words ?? 'Amount in Words' }}</p>
                </div>
                <div>
                    <table class="summary-table">
                        <tr>
                            <td class="summary-label">Subtotal</td>
                            <td class="summary-value">{{ number_format($preQuotation->subtotal ?? 0, 2) }}</td>
                        </tr>
                        <tr>
                            <td class="summary-label">SST</td>
                            <td class="summary-value">{{ number_format($preQuotation->tax_total ?? 0, 2) }}</td>
                        </tr>
                        <tr>
                            <td class="summary-label">Total</td>
                            <td class="summary-value" style="font-weight: bold;">{{ number_format($preQuotation->total ?? 0, 2) }}</td>
                        </tr>
                    </table>
                </div>
            </div>

            <div class="footer-section">
                <div class="reminder-text">
                    <p><strong>Reminder:</strong> Pursuant to the Solicitor's Remuneration Order 2005, interest 8% on the total sum billed will be charges from the expiration of one (1) month of the billing dates</p>
                </div>

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
