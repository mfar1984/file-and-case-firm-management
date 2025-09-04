<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Sales Quotation - {{ $quotation->quotation_no ?? 'Q-00005' }}</title>
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
        
        /* Header repetition using position fixed */
        .header {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            height: 70mm; /* Kurangkan height untuk compact */
            background: white;
            z-index: 1000;
            padding-bottom: 0.25mm; /* Kurangkan padding */
            margin-bottom: 0.25mm; /* Kurangkan margin */
            margin-top: -3mm; /* Naikkan lagi ke atas */
        }
        
        body {
            margin-top: 70mm; /* Space for fixed header - adjust to match header height */
        }
        
        .header-content {
            padding: 0;
            margin-top: 0;
        }
        
        /* CSS Counter for page numbering - DomPDF compatible */
        @page {
            margin: 20mm 15mm 20mm 15mm;
        }
        
        body {
            counter-reset: page;
        }
        
        @media print {
            .main-content {
                page-break-before: auto;
            }
            .footer {
                page-break-inside: avoid; /* Don't break footer content */
                break-inside: avoid; /* Modern CSS */
            }
            .item-table {
                page-break-after: avoid;
            }
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
            width: 60pt; /* Fixed width untuk alignment */
            text-align: left;
            vertical-align: baseline; /* Ensure consistent vertical alignment */
        }
        .contact-separator {
            display: inline-block;
            width: 5pt; /* Fixed width untuk : dan space */
            text-align: left;
            vertical-align: baseline; /* Ensure consistent vertical alignment */
        }
        .contact-value {
            display: inline-block;
            vertical-align: baseline; /* Ensure consistent vertical alignment */
        }
        .quotation-label {
            display: inline-block;
            width: 100pt; /* Fixed width untuk alignment quotation meta */
            text-align: left;
            vertical-align: baseline; /* Ensure consistent vertical alignment */
        }
        .quotation-separator {
            display: inline-block;
            width: 5pt; /* Fixed width untuk : dan space */
            text-align: left;
            vertical-align: baseline; /* Ensure consistent vertical alignment */
        }
        .quotation-value {
            display: inline-block;
            vertical-align: baseline; /* Ensure consistent vertical alignment */
        }
        
        /* Dynamic page numbering using CSS counter */
        .page-counter::before {
            content: counter(page) " of {{ $totalPages ?? '1' }}";
        }
        .quotation-title {
            text-align: center;
            margin-top: 0.5mm;
            margin-bottom: 1.5mm;
            font-size: 12pt;
            font-weight: bold;
            clear: both; /* Clear float for title */
        }
        .quotation-details {
            overflow: auto; /* Clear floats */
            margin-bottom: 1mm !important;
        }
        .customer-info {
            width: 48%;
            float: left;
            margin-bottom: 1mm;
        }
        .quotation-meta {
            float: right;
            text-align: left;
            margin-bottom: 1mm;
            margin-right: 0; /* Paling hujung kanan */
            width: auto; /* Auto width untuk content */
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
            margin-top: 0mm !important; /* Jarak dari detail quotation */
            margin-bottom: 5mm; /* Kurangkan spacing */
            page-break-inside: auto; /* Allow page breaks within table */
        }
        
        /* Main content wrapper */
        .main-content {
            page-break-after: avoid; /* Prevent page break after table */
        }
        .item-table th, .item-table td {
            padding: 4pt 6pt;
            text-align: left;
            vertical-align: top;
            font-size: 9pt;
        }
        .item-table th {
            background-color: transparent;
            font-weight: 900;
            color: #374151;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            font-size: 9pt;
            text-align: center !important; /* Center align header text */
            vertical-align: middle !important; /* Middle align header text */
            border-top: 1px solid #d1d5db;
            border-bottom: 1px solid #d1d5db;
            border-left: none;
            border-right: none;
        }
        .item-table thead {
            display: table-header-group; /* Repeat header on each page */
        }
        .item-table tbody {
            display: table-row-group;
        }
        .item-table tbody tr {
            page-break-inside: avoid; /* Avoid breaking rows */
        }
        
        /* For DomPDF compatibility - ensure content flows properly */
        .header {
            page-break-after: avoid; /* Keep header with content */
        }
        
        .main-content {
            page-break-before: avoid; /* Keep content with header */
        }
        .item-table .text-right {
            text-align: right;
        }
        
        /* Column specific styling */
        .item-table th:first-child,
        .item-table td:first-child {
            width: 8%;
            text-align: center;
            font-weight: 600;
            color: #374151;
        }
        
        .item-table th:nth-child(2),
        .item-table td:nth-child(2) {
            width: 40%;
        }
        
        .item-table th:nth-child(3),
        .item-table td:nth-child(3) {
            width: 10%;
            text-align: center;
        }
        
        .item-table th:nth-child(4),
        .item-table td:nth-child(4) {
            width: 10%;
            text-align: center;
        }
        
        .item-table th:nth-child(5),
        .item-table td:nth-child(5) {
            width: 12%;
            text-align: right;
            font-weight: 500;
            color: #1f2937;
        }
        
        .item-table th:nth-child(6),
        .item-table td:nth-child(6) {
            width: 10%;
            text-align: right;
        }
        
        .item-table th:nth-child(7),
        .item-table td:nth-child(7) {
            width: 10%;
            text-align: right;
            font-weight: 500;
            color: #1f2937;
        }

        /*
         * FOOTER
         */
        .footer {
            margin-top: 20mm;
            padding-top: 10mm;
            border-top: 1px solid #eee;
            font-size: 9pt;
            page-break-inside: avoid; /* Don't break footer */
            break-inside: avoid; /* Modern CSS property */
        }
        .footer-content {
            padding: 0;
        }
        .footer-top {
            display: flex;
            justify-content: space-between;
            margin-top: 15mm;
            margin-bottom: 15mm;
        }
        .left-section {
            width: 55%;
            float: left;
        }
        .right-section {
            width: 40%;
            float: right;
        }
        .summary-table-bordered {
            width: 100% !important;
            border-collapse: collapse !important;
            table-layout: fixed !important;
        }
        .summary-label-bordered {
            text-align: right !important;
            font-weight: bold !important;
            padding: 6pt 2pt !important;
            border: none !important;
            background-color: transparent !important;
        }
        .summary-value-bordered {
            text-align: right !important;
            padding: 1pt 3pt !important;
            border: 1px solid #000 !important;
            background-color: transparent !important;
            min-width: 40pt !important;
            width: 40pt !important;
            max-width: 40pt !important;
        }
        .summary-value-bordered.total-row {
            font-weight: bold !important;
            background-color: #e0e0e0 !important;
            border: 1px solid #000 !important;
            padding: 1pt 3pt !important;
            width: 40pt !important;
            max-width: 40pt !important;
        }
        .footer-bottom {
            margin-top: 20mm;
        }
        .signature-line {
            width: 200pt;
            border-top: 1px solid #000;
            margin-bottom: 5pt;
        }
        .authorised-signature {
            margin-top: 10mm;
            width: 50%;
            float: left;
            border-top: 1px solid #333;
            padding-top: 5mm;
        }
        
        .amount-in-words {
            margin-bottom: 8mm;
            font-size: 9pt;
            line-height: 1.2;
            text-indent: -80pt;
            padding-left: 80pt;
        }

        .note-section {
            margin-bottom: 5mm;
            font-size: 9pt;
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
                <span class="contact-label">Email</span><span class="contact-separator">: </span><span class="contact-value">{{ $firmSettings->email ?? 'info@naaelahsaleh.my' }}</span><br>
                <span class="contact-label">SST No.</span><span class="contact-separator">: </span><span class="contact-value">{{ $firmSettings->tax_registration_number ?? 'W24-2507-32000179' }}</span>
            </div>
            <div class="clear"></div>

            <div class="quotation-title">
                Legal Quotation
            </div>

            <div class="quotation-details">
                <div class="customer-info">
                    <strong>{{ $quotation->customer_name ?? 'Customer Name' }}</strong><br>
                    {{ $quotation->customer_address ?? 'Customer Address' }}<br>
                    <span class="contact-label">Phone No.</span><span class="contact-separator">: </span><span class="contact-value">{{ $quotation->customer_phone ?? 'Phone Number' }}</span><br>
                </div>
                <div class="quotation-meta">
                    <span class="quotation-label">No.</span><span class="quotation-separator">: </span><span class="quotation-value">{{ $quotation->quotation_no ?? 'Q-00007' }}</span><br>
                    <span class="quotation-label">Payment Terms</span><span class="quotation-separator">: </span><span class="quotation-value">{{ $quotation->payment_terms_display ?? 'Net 30 days' }}</span><br>
                    <span class="quotation-label">Date</span><span class="quotation-separator">: </span><span class="quotation-value">{{ optional($quotation->quotation_date)->format('d/m/Y') ?? '04/09/2025' }}</span><br>
                    <span class="quotation-label">Page</span><span class="quotation-separator">: </span><span class="quotation-value page-counter"></span>
                </div>
            </div>
            <div class="clear"></div>
        </div>
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
                    <th>DISC.</th>
                    <th>AMOUNT<br>(RM)</th>
                </tr>
            </thead>
            <tbody>
                @forelse($quotation->items as $index => $item)
                    <tr>
                        <td>{{ $index + 1 }}.</td>
                        <td>{{ $item->description }}</td>
                        <td>{{ number_format($item->qty, 2) }}</td>
                        <td>{{ $item->uom }}</td>
                        <td class="text-right">{{ number_format($item->unit_price, 2) }}</td>
                        <td class="text-right">{{ number_format($item->discount_amount ?? 0, 2) }}</td>
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
            <!-- Top section with amount in words and summary table -->
            <div class="footer-top">
                <div class="left-section">
                    <div class="amount-in-words">
                        Ringgit Malaysia: {{ $quotation->total_words ?? 'Amount in Words' }}
                    </div>
                    <div class="note-section">
                        <strong>Note:</strong> {{ $quotation->remark ?? '' }}
                    </div>
                </div>

                <div class="right-section">
                    <table class="summary-table-bordered">
                        <tr>
                            <td class="summary-label-bordered">Subtotal</td>
                            <td class="summary-value-bordered">{{ number_format($quotation->subtotal ?? 0, 2) }}</td>
                        </tr>
                        <tr>
                            <td class="summary-label-bordered">Tax</td>
                            <td class="summary-value-bordered">{{ number_format($quotation->tax_total ?? 0, 2) }}</td>
                        </tr>
                        <tr>
                            <td class="summary-label-bordered">Total</td>
                            <td class="summary-value-bordered total-row">{{ number_format($quotation->total ?? 0, 2) }}</td>
                        </tr>
                    </table>
                </div>
            </div>

            <div class="clear"></div>

            <!-- Bottom section with signature -->
            <div class="footer-bottom">
                <div class="authorised-signature">
                    <div class="signature-line"></div>
                    <strong>Authorised Signature</strong>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
