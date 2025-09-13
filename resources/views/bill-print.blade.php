<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Bill - {{ $bill->bill_no ?? 'BL-00001' }}</title>
    <style>
        /*
         * GLOBAL STYLES
         */
        @page {
            margin: 20mm 15mm 20mm 15mm; /* Top, Right, Bottom, Left */
        }
        body {
            font-family: 'DejaVu Sans', sans-serif; /* Fallback for special characters */
            font-size: 9pt;
            line-height: 1.4;
            color: #333;
            margin: 0;
            padding: 0;
        }

        /*
         * HEADER
         */
        .header {
            height: auto;
            padding-bottom: 1mm;
            margin-bottom: 1mm;
        }
        .header-content {
            padding: 0;
        }
        .company-info {
            float: left;
            width: 70%;
        }
        .company-logo {
            float: left;
            margin-right: 10mm;
            width: 30mm;
        }
        .company-logo img {
            width: 100%;
            height: auto;
        }
        .contact-label {
            display: inline-block;
            width: 20mm;
            vertical-align: baseline;
        }
        .contact-separator {
            display: inline-block;
            width: 5pt;
            text-align: left;
            vertical-align: baseline;
        }
        .contact-value {
            display: inline-block;
            vertical-align: baseline;
        }
        .bill-title {
            text-align: center;
            margin-top: 0.5mm;
            margin-bottom: 1.5mm;
            font-size: 12pt;
            font-weight: bold;
            clear: both;
        }
        .bill-details {
            overflow: auto;
            margin-bottom: 1mm !important;
        }
        .vendor-info {
            width: 48%;
            float: left;
            margin-bottom: 1mm;
        }
        .bill-meta {
            float: right;
            text-align: left;
            margin-bottom: 1mm;
            margin-right: 0;
            width: auto;
        }
        .bill-label {
            display: inline-block;
            width: 25mm;
            vertical-align: baseline;
        }
        .bill-separator {
            display: inline-block;
            width: 5pt;
            text-align: left;
            vertical-align: baseline;
        }
        .bill-value {
            display: inline-block;
            vertical-align: baseline;
        }
        .clear {
            clear: both;
        }

        /*
         * CONTENT TABLE
         */
        .item-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 0mm !important;
            margin-bottom: 5mm;
            page-break-inside: auto;
        }
        .item-table th, .item-table td {
            padding: 4pt 6pt;
            text-align: left;
            vertical-align: top;
            font-size: 9pt;
        }
        .item-table th {
            font-weight: bold;
            text-align: center;
            border-top: 1pt solid #333;
            border-bottom: 1pt solid #333;
        }
        .item-table .text-right {
            text-align: right;
        }
        .item-table .text-center {
            text-align: center;
        }

        /*
         * FOOTER
         */
        .footer {
            margin-top: 5mm;
            page-break-inside: avoid;
        }
        .footer-content {
            width: 100%;
        }
        .footer-top {
            display: table;
            width: 100%;
            margin-bottom: 5mm;
        }
        .left-section {
            display: table-cell;
            width: 60%;
            vertical-align: top;
            padding-right: 10mm;
        }
        .right-section {
            display: table-cell;
            width: 40%;
            vertical-align: top;
        }
        .amount-in-words {
            font-size: 9pt;
            line-height: 1.2;
            text-indent: -20mm;
            padding-left: 20mm;
            margin-bottom: 3mm;
        }
        .summary-table {
            width: 100%;
            border-collapse: collapse;
        }
        .summary-table td {
            padding: 2pt 4pt;
            font-size: 9pt;
            border: 1pt solid #333;
        }
        .summary-table .total-row {
            background-color: #f0f0f0;
            font-weight: bold;
        }
        .signature-section {
            margin-top: 15mm;
            border-top: 1pt solid #333;
            padding-top: 5mm;
        }
        .signature-grid {
            display: table;
            width: 100%;
        }
        .signature-cell {
            display: table-cell;
            width: 33.33%;
            text-align: center;
            vertical-align: bottom;
        }
        .signature-line {
            border-top: 1pt solid #333;
            margin-top: 15mm;
            padding-top: 2mm;
            font-size: 8pt;
            text-align: center;
            width: 25mm;
            margin-left: auto;
            margin-right: auto;
        }

        @media print {
            * {
                -webkit-print-color-adjust: exact !important;
                color-adjust: exact !important;
            }
            @page {
                margin: 20mm 15mm 15mm 15mm !important;
                size: A4 !important;
            }
            body {
                margin: 0 !important;
                padding: 0 !important;
            }
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="header-content">
            <div class="company-logo">
                <img src="{{ public_path('images/logo.png') }}" alt="Company Logo">
            </div>
            <div class="company-info">
                <strong>{{ $firmSettings->firm_name ?? 'Naeelah Saleh & Associates' }}</strong> ({{ $firmSettings->registration_number ?? 'LLP0012345' }})<br>
                {{ $firmSettings->address ?? 'No. 123, Jalan Tun Razak, 50400 Kuala Lumpur, Malaysia' }}<br>
                <span class="contact-label">Phone No.</span><span class="contact-separator">: </span><span class="contact-value">{{ $firmSettings->phone_number ?? '+6019-3186436' }}</span><br>
                <span class="contact-label">Email</span><span class="contact-separator">: </span><span class="contact-value">{{ $firmSettings->email ?? 'admin@naeelahsaleh.com.my' }}</span>
            </div>
        </div>
        <div class="bill-title">BILL</div>

        <div class="bill-details">
            <div class="vendor-info">
                <strong>{{ $bill->vendor_name ?? 'Vendor Name' }}</strong><br>
                {{ $bill->vendor_address ?? 'Vendor Address' }}<br>
                @if($bill->vendor_phone)
                <span class="contact-label">Phone No.</span><span class="contact-separator">: </span><span class="contact-value">{{ $bill->vendor_phone }}</span><br>
                @endif
                @if($bill->vendor_email)
                <span class="contact-label">Email</span><span class="contact-separator">: </span><span class="contact-value">{{ $bill->vendor_email }}</span><br>
                @endif
                @if($bill->category)
                <span class="contact-label">Category</span><span class="contact-separator">: </span><span class="contact-value">{{ $bill->category }}</span><br>
                @endif
            </div>
            <div class="bill-meta">
                <span class="bill-label">No.</span><span class="bill-separator">: </span><span class="bill-value">{{ $bill->bill_no ?? 'BL-00001' }}</span><br>
                <span class="bill-label">Bill Date</span><span class="bill-separator">: </span><span class="bill-value">{{ optional($bill->bill_date)->format('d/m/Y') ?? date('d/m/Y') }}</span><br>
                <span class="bill-label">Due Date</span><span class="bill-separator">: </span><span class="bill-value">{{ optional($bill->due_date)->format('d/m/Y') ?? date('d/m/Y') }}</span><br>
                @if($bill->payment_date)
                <span class="bill-label">Payment Date</span><span class="bill-separator">: </span><span class="bill-value">{{ $bill->payment_date->format('d/m/Y') }}</span><br>
                @endif
                @if($bill->payment_method)
                <span class="bill-label">Payment Method</span><span class="bill-separator">: </span><span class="bill-value">{{ $bill->payment_method_display }}</span><br>
                @endif
                @if($bill->payment_reference)
                <span class="bill-label">Payment Ref</span><span class="bill-separator">: </span><span class="bill-value">{{ $bill->payment_reference }}</span><br>
                @endif
                <span class="bill-label">Page</span><span class="bill-separator">: </span><span class="bill-value">1 of 1</span>
            </div>
        </div>
        <div class="clear"></div>
    </div>

    <div class="main-content">
        <table class="item-table">
            <thead>
                <tr>
                    <th>ITEM.</th>
                    <th>DESCRIPTION</th>
                    <th>QTY</th>
                    <th>UOM</th>
                    <th>PRICE<br>(RM)</th>
                    <th>SST</th>
                    <th>AMOUNT<br>(RM)</th>
                </tr>
            </thead>
            <tbody>
                @forelse($bill->items as $index => $item)
                    <tr>
                        <td>{{ $index + 1 }}.</td>
                        <td>{{ $item->description }}@if($item->category)<br><small>{{ $item->category }}</small>@endif</td>
                        <td class="text-center">{{ number_format($item->qty, 2) }}</td>
                        <td class="text-center">{{ $item->uom }}</td>
                        <td class="text-right">{{ number_format($item->unit_price, 2) }}</td>
                        <td class="text-right">{{ number_format($item->tax_percent ?? 0, 1) }}%</td>
                        <td class="text-right">{{ number_format($item->amount, 2) }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center">No items found</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="footer">
        <div class="footer-content">
            <div class="footer-top">
                <div class="left-section">
                    <div class="amount-in-words">
                        Ringgit Malaysia: {{ $bill->total_words ?? 'Amount in Words' }}
                    </div>
                    @if($bill->description)
                    <div style="margin-top: 3mm;">
                        <strong>Description:</strong> {{ $bill->description }}
                    </div>
                    @endif
                    @if($bill->remark)
                    <div style="margin-top: 3mm;">
                        <strong>Note:</strong> {{ $bill->remark }}
                    </div>
                    @endif
                </div>
                <div class="right-section">
                    <table class="summary-table">
                        <tr>
                            <td style="text-align: right; font-weight: bold;">Subtotal</td>
                            <td style="text-align: right; width: 25mm;">{{ number_format($bill->subtotal ?? 0, 2) }}</td>
                        </tr>
                        @if($bill->tax_total > 0)
                        <tr>
                            <td style="text-align: right; font-weight: bold;">SST</td>
                            <td style="text-align: right;">{{ number_format($bill->tax_total ?? 0, 2) }}</td>
                        </tr>
                        @endif
                        <tr class="total-row">
                            <td style="text-align: right; font-weight: bold;">Total</td>
                            <td style="text-align: right; font-weight: bold;">{{ number_format($bill->total_amount ?? 0, 2) }}</td>
                        </tr>
                    </table>
                </div>
            </div>

            <div class="signature-section">
                <div class="signature-grid">
                    <div class="signature-cell">
                        <div class="signature-line">Prepared By</div>
                    </div>
                    <div class="signature-cell">
                        <div class="signature-line">Checked By</div>
                    </div>
                    <div class="signature-cell">
                        <div class="signature-line">Approved By</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
