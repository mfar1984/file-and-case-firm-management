@extends('layouts.app')

@section('breadcrumb')
    <span class="text-gray-500">Bill</span>
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
</style>
<div class="px-4 md:px-6 pt-4 md:pt-6 pb-6 max-w-7xl mx-auto">
    <div class="bg-white rounded shadow-md border border-gray-300">
        <div class="px-4 md:px-6 py-4 border-b border-gray-200 flex items-center justify-between">
            <div class="flex items-center">
                <span class="material-icons mr-2 text-blue-600">receipt</span>
                <h1 class="text-lg md:text-xl font-bold text-gray-800 text-[14px]">Bill {{ $bill->bill_no }}</h1>
            </div>
            <div class="flex items-center space-x-3">
                <a href="{{ route('bill.print', $bill->id) }}" target="_blank" class="flex items-center text-xs text-blue-600 hover:text-blue-800">
                    <span class="material-icons mr-1 text-sm">print</span>
                    Print
                </a>
                <a href="{{ route('bill.index') }}" class="text-xs text-blue-600 hover:underline">Back</a>
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

            <div class="document-title">Bill</div>

            <div style="overflow: auto; margin-bottom: 30px; clear: both;">
                <div class="customer-info">
                    <h3>Bill From:</h3>
                    <p><strong>{{ $bill->vendor_name }}</strong></p>
                    @if($bill->vendor_address)
                    <p>{{ $bill->vendor_address }}</p>
                    @endif
                    @if($bill->vendor_phone)
                    <p><span class="customer-contact-label">Phone No.</span><span class="customer-contact-separator">:</span><span class="customer-contact-value">{{ $bill->vendor_phone }}</span></p>
                    @endif
                    @if($bill->vendor_email)
                    <p><span class="customer-contact-label">Email</span><span class="customer-contact-separator">:</span><span class="customer-contact-value">{{ $bill->vendor_email }}</span></p>
                    @endif
                    @if($bill->category)
                    <p><span class="customer-contact-label">Category</span><span class="customer-contact-separator">:</span><span class="customer-contact-value">{{ $bill->category }}</span></p>
                    @endif
                </div>
                <div class="quotation-meta">
                    <p><span class="quotation-label">Bill No.</span><span class="quotation-separator">:</span><span class="quotation-value">{{ $bill->bill_no }}</span></p>
                    <p><span class="quotation-label">Bill Date</span><span class="quotation-separator">:</span><span class="quotation-value">{{ $bill->bill_date->format('d/m/Y') }}</span></p>
                    <p><span class="quotation-label">Due Date</span><span class="quotation-separator">:</span><span class="quotation-value">{{ $bill->due_date->format('d/m/Y') }}</span></p>
                    @if($bill->payment_date)
                    <p><span class="quotation-label">Payment Date</span><span class="quotation-separator">:</span><span class="quotation-value">{{ $bill->payment_date->format('d/m/Y') }}</span></p>
                    @endif
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
                            <th class="px-3 py-2 text-center text-xs w-20" style="font-weight: 900; border-top: 1px solid #d1d5db; border-bottom: 1px solid #d1d5db; border-left: none; border-right: none;">QTY</th>
                            <th class="px-3 py-2 text-center text-xs w-20" style="font-weight: 900; border-top: 1px solid #d1d5db; border-bottom: 1px solid #d1d5db; border-left: none; border-right: none;">UOM</th>
                            <th class="px-3 py-2 text-center text-xs w-24" style="font-weight: 900; border-top: 1px solid #d1d5db; border-bottom: 1px solid #d1d5db; border-left: none; border-right: none;">PRICE<br>(RM)</th>
                            <th class="px-3 py-2 text-center text-xs w-20" style="font-weight: 900; border-top: 1px solid #d1d5db; border-bottom: 1px solid #d1d5db; border-left: none; border-right: none;">DISC.</th>
                            <th class="px-3 py-2 text-center text-xs w-20" style="font-weight: 900; border-top: 1px solid #d1d5db; border-bottom: 1px solid #d1d5db; border-left: none; border-right: none;">TAX</th>
                            <th class="px-3 py-2 text-center text-xs w-24" style="font-weight: 900; border-top: 1px solid #d1d5db; border-bottom: 1px solid #d1d5db; border-left: none; border-right: none;">AMOUNT<br>(RM)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($bill->items as $index => $item)
                        <tr>
                            <td class="px-3 py-2 text-center text-sm">{{ $index + 1 }}</td>
                            <td class="px-3 py-2 text-sm">
                                {{ $item->description }}
                                @if($item->category)
                                <br><span class="text-gray-500 text-xs">{{ $item->category }}</span>
                                @endif
                            </td>
                            <td class="px-3 py-2 text-center text-sm">{{ number_format($item->qty, 2) }}</td>
                            <td class="px-3 py-2 text-center text-sm">{{ $item->uom }}</td>
                            <td class="px-3 py-2 text-center text-sm">{{ number_format($item->unit_price, 2) }}</td>
                            <td class="px-3 py-2 text-center text-sm">{{ number_format($item->discount_percent, 1) }}%</td>
                            <td class="px-3 py-2 text-center text-sm">{{ number_format($item->tax_percent, 1) }}%</td>
                            <td class="px-3 py-2 text-right text-sm font-medium">{{ number_format($item->amount, 2) }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="px-3 py-2 text-center text-sm text-gray-500">No items found</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="summary-section">
                <table class="summary-table">
                    <tr>
                        <td class="summary-label">Subtotal:</td>
                        <td class="summary-value">{{ number_format($bill->subtotal ?? 0, 2) }}</td>
                    </tr>
                    @if($bill->tax_total > 0)
                    <tr>
                        <td class="summary-label">Tax:</td>
                        <td class="summary-value">{{ number_format($bill->tax_total ?? 0, 2) }}</td>
                    </tr>
                    @endif
                    <tr>
                        <td class="summary-label">Total:</td>
                        <td class="summary-value">{{ number_format($bill->total_amount ?? 0, 2) }}</td>
                    </tr>
                </table>
            </div>

            <div class="footer-section">
                <div class="reminder-text">
                    <p><strong>Ringgit Malaysia:</strong> {{ $bill->total_words ?? 'Amount in Words' }}</p>
                    @if($bill->description)
                        <p style="margin-top: 15px;"><strong>Description:</strong> {{ $bill->description }}</p>
                    @endif
                    @if($bill->remark)
                        <p style="margin-top: 15px;"><strong>Note:</strong> {{ $bill->remark }}</p>
                    @endif
                </div>

                <!-- Signature Section -->
                <div style="margin-top: 60px; display: flex; justify-content: space-around;">
                    <div style="text-align: center;">
                        <div style="width: 150px; border-top: 1px solid #000; margin-bottom: 8px;"></div>
                        <div style="font-size: 12px; font-weight: bold; color: #333;">Prepared By</div>
                    </div>
                    <div style="text-align: center;">
                        <div style="width: 150px; border-top: 1px solid #000; margin-bottom: 8px;"></div>
                        <div style="font-size: 12px; font-weight: bold; color: #333;">Checked By</div>
                    </div>
                    <div style="text-align: center;">
                        <div style="width: 150px; border-top: 1px solid #000; margin-bottom: 8px;"></div>
                        <div style="font-size: 12px; font-weight: bold; color: #333;">Approved By</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
