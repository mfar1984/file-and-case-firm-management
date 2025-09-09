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
                page-break-before: auto; /* Allow page break when space < 5mm */
            }
            .item-table {
                page-break-after: auto; /* Allow page break after table */
            }
            /* Force table repetition for DomPDF */
            .item-table {
                page-break-inside: auto !important;
            }
            .item-table thead {
                display: table-header-group !important;
            }
            .item-table tbody {
                display: table-row-group !important;
            }
            /* Automatic page break when space is limited */
            .item-table tbody tr:last-child {
                page-break-after: auto;
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
            width: 70pt; /* Fixed width untuk alignment */
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
        
        /* 3-Section Layout System - Table Section */
        .item-table {
            /* Calculate available space: A4(297mm) - Header(75mm) - Footer(30mm) = ~192mm */
            max-height: 180mm !important; /* Safe limit untuk table section */
            page-break-before: auto;
            page-break-after: auto;
            margin-bottom: 15mm !important; /* Add bottom margin untuk gap */
        }
        
        .item-table tbody {
            /* Limit tbody to trigger page break at item ~16 */
            max-height: 150mm !important; /* ~16 rows at 9-10mm per row */
            overflow: visible !important; /* Allow page break flow */
            padding-bottom: 15mm !important; /* Padding bottom untuk tbody supaya data tidak sampai edge */
        }
        
        /* Force page break when tbody reaches limit - REMOVE aggressive break */
        /* .item-table tbody tr:nth-child(16) ~ tr {
            page-break-before: always; 
        } */
        
        /* Better approach - use natural page break with height limits */
        .item-table tbody tr {
            page-break-inside: avoid; /* Don't break individual rows */
        }
        
        /* Add padding untuk tbody rows supaya tidak sampai edge */
        .item-table tbody tr:last-child {
            padding-bottom: 20mm !important; /* Extra padding untuk last row */
        }
        
        .item-table tbody tr td {
            padding-bottom: 3mm !important; /* Individual cell padding bottom */
        }
        
        @media print {
            .item-table {
                max-height: 180mm !important;
                page-break-inside: auto !important;
                margin-bottom: 20mm !important; /* Increase bottom margin untuk print */
            }
            
            .item-table tbody {
                max-height: 150mm !important;
                page-break-inside: auto !important;
                padding-bottom: 25mm !important; /* Increase padding bottom untuk tbody supaya data tidak sampai edge */
            }
            
            /* Extra padding untuk last row dalam print */
            .item-table tbody tr:last-child td {
                padding-bottom: 15mm !important; /* Extra padding untuk last row cells */
            }
            
            /* Ensure thead repeats on new pages */
            .item-table thead {
                display: table-header-group !important;
                page-break-after: avoid !important;
            }
        }
        .item-table tbody tr {
            page-break-inside: avoid; /* Avoid breaking rows */
        }
        
        /* Table repetition on page breaks */
        .item-table {
            page-break-before: auto;
            page-break-after: auto;
            max-height: 180mm !important; /* Limit table height untuk page break */
        }
        
        /* Table tbody limit untuk gap dengan footer */
        .item-table tbody {
            max-height: 150mm !important; /* Limit tbody height */
            overflow: hidden !important; /* Hide overflow untuk page break */
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
            margin-top: 5mm;
            padding: 0 !important;
            font-size: 9pt;
            page-break-inside: avoid;
            break-inside: avoid;
            page-break-before: auto;
        }
        
        /* Auto page break logic - when table gets too close to footer */
        .main-content + .footer {
            /* If less than 10mm space available, force page break */
            page-break-before: auto;
        }
        
        /* Footer positioning for all scenarios */
        @media screen {
            .footer {
                position: relative; /* Normal flow for screen */
            }
        }
        
        
        @media print {
            /* Override any global CSS that might interfere */
            * {
                -webkit-print-color-adjust: exact !important;
                color-adjust: exact !important;
            }
            
            @page {
                margin: 20mm 15mm 15mm 15mm !important; /* Consistent margins */
                size: A4 !important;
            }
            
            html {
                height: 100% !important;
            }
            
            body {
                margin: 0 !important;
                padding: 0 !important;
                padding-top: 75mm !important; /* Space untuk fixed header */
                box-sizing: border-box !important;
            }
            
            .header {
                flex-shrink: 0 !important; /* Header won't shrink */
                /* Header repeats on every page */
                position: fixed !important;
                top: 0 !important;
                left: 0 !important;
                right: 0 !important;
                height: 75mm !important; /* Fixed header height */
                background: white !important;
                z-index: 1000 !important;
                padding: 5mm 15mm 2mm 15mm !important;
                box-sizing: border-box !important;
            }
            
            .main-content {
                margin-bottom: 5mm !important;
                padding-bottom: 10mm !important;
            }
            
            .footer {
                position: relative !important;
                width: 100% !important;
                background: white !important;
                margin: 0 !important;
                padding: 0 !important;
                page-break-inside: avoid !important;
            }
            
            .footer * {
                margin: 0 !important;
                padding: 0 !important;
            }
            
            .footer-top {
                margin-bottom: 2mm !important;
            }
            
            .footer-bottom {
                margin-top: 2mm !important;
                margin-bottom: 0 !important;
            }
            
        }
        .footer-content {
            padding: 0 !important; /* Remove ALL padding */
            margin: 0 !important; /* Remove ALL margin */
        }
        .footer-top {
            display: flex;
            justify-content: space-between;
            margin: 0 !important;
            margin-bottom: 2mm !important;
        }
        .left-section {
            width: 55%;
            float: left;
        }
        .right-section {
            width: 30%;
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
            padding: 4pt 2pt !important;
            border: none !important;
            background-color: transparent !important;
        }
        .summary-value-bordered {
            text-align: right !important;
            padding: 1pt 3pt !important;
            border: 1px solid #000 !important;
            background-color: transparent !important;
            min-width: 25pt !important;
            width: 25pt !important;
            max-width: 25pt !important;
        }
        .summary-value-bordered.total-row {
            font-weight: bold !important;
            background-color: #e0e0e0 !important;
            border: 1px solid #000 !important;
            padding: 1pt 3pt !important;
            width: 30pt !important;
            max-width: 30pt !important;
        }
        .footer-bottom {
            margin-top: 2mm !important;
            margin-bottom: 0 !important;
            text-align: left !important;
            float: none !important;
        }
        .signature-line {
            width: 120pt;
            border-top: 1px solid #000;
            margin-bottom: 1pt;
            margin-top: 60pt !important;
            height: 1pt;
            margin-left: 0;
        }
        .authorised-signature {
            margin: 0;
            margin-bottom: 0 !important;
            width: 120pt;
            text-align: left;
            display: block;
        }
        
        .authorised-signature strong {
            text-align: left; /* Ensure text is left aligned */
            display: block; /* Block display for proper alignment */
        }
        
        .amount-in-words {
            margin-bottom: 8mm;
            font-size: 9pt;
            line-height: 1.2;
            text-indent: -80pt;
            padding-left: 80pt;
        }

        .note-section {
            margin-bottom: 2mm;
            font-size: 8pt;
            line-height: 1.2;
            text-align: justify;
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
            
            <!-- Reminder section after Total -->
            <div class="note-section" style="margin-top: 5mm;">
                <strong>Reminder:</strong> Pursuant to the Solicitor's Remuneration Order 2005, interest 8% on the total sum billed will be charges from the expiration of one (1) month of the billing dates
            </div>

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
