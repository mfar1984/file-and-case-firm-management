<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Pre-Quotation - {{ $preQuotation->quotation_no ?? 'PQ-00001' }}</title>
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
            width: 70pt; /* Fixed width untuk alignment */
            text-align: left;
            vertical-align: baseline;
        }
        .contact-separator {
            display: inline-block;
            width: 5pt; /* Fixed width untuk : dan space */
            text-align: left;
            vertical-align: baseline;
        }
        .contact-value {
            display: inline-block;
            vertical-align: baseline;
        }
        .quotation-label {
            display: inline-block;
            width: 100pt; /* Fixed width untuk alignment quotation meta */
            text-align: left;
            vertical-align: baseline;
        }
        .quotation-separator {
            display: inline-block;
            width: 5pt; /* Fixed width untuk : dan space */
            text-align: left;
            vertical-align: baseline;
        }
        .quotation-value {
            display: inline-block;
            vertical-align: baseline;
        }
        .quotation-title {
            text-align: center;
            margin-top: 1mm;
            margin-bottom: 3mm;
            font-size: 12pt;
            font-weight: bold;
            clear: both;
        }
        .quotation-details {
            overflow: auto;
            margin-bottom: 2mm !important;
        }
        .customer-info, .quotation-meta {
            width: 48%;
            float: left;
            margin-bottom: 2mm;
        }
        .quotation-meta {
            float: right;
            text-align: right;
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
            margin-bottom: 10mm;
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
            text-align: center !important;
            vertical-align: middle !important;
            border-top: 1px solid #d1d5db;
            border-bottom: 1px solid #d1d5db;
            border-left: none;
            border-right: none;
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
                Pre-Quotation
            </div>

            <div class="quotation-details">
                <div class="customer-info">
                    <strong>{{ $preQuotation->full_name ?? 'Customer Name' }}</strong><br>
                    {{ $preQuotation->customer_address ?? 'Customer Address' }}<br>
                    <span class="contact-label">Phone No.</span><span class="contact-separator">: </span><span class="contact-value">{{ $preQuotation->customer_phone ?? 'Phone Number' }}</span><br>
                </div>
                <div class="quotation-meta">
                    <span class="quotation-label">No.</span><span class="quotation-separator">: </span><span class="quotation-value">{{ $preQuotation->quotation_no ?? 'PQ-00001' }}</span><br>
                    <span class="quotation-label">Payment Terms</span><span class="quotation-separator">: </span><span class="quotation-value">{{ $preQuotation->payment_terms ?? 'Net 30 days' }}</span><br>
                    <span class="quotation-label">Date</span><span class="quotation-separator">: </span><span class="quotation-value">{{ optional($preQuotation->quotation_date)->format('d/m/Y') ?? '04/09/2025' }}</span><br>
                    <span class="quotation-label">Page</span><span class="quotation-separator">: </span><span class="quotation-value">1 of 1</span>
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
                @forelse($preQuotation->items as $index => $item)
                    <tr>
                        <td>{{ $index + 1 }}.</td>
                        <td>{{ $item->description }}</td>
                        <td>{{ number_format($item->qty, 2) }}</td>
                        <td>{{ $item->uom }}</td>
                        <td class="text-right">{{ number_format($item->unit_price, 2) }}</td>
                        <td class="text-right">{{ number_format($item->discount_percent ?? 0, 2) }}%</td>
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
                        Ringgit Malaysia: {{ $preQuotation->total_words ?? 'Amount in Words' }}
                    </div>
                    <div class="note-section">
                        <strong>Note:</strong> {{ $preQuotation->remark ?? '' }}
                    </div>
                </div>

                <div class="right-section">
                    <table class="summary-table-bordered">
                        <tr>
                            <td class="summary-label-bordered">Subtotal</td>
                            <td class="summary-value-bordered">{{ number_format($preQuotation->subtotal ?? 0, 2) }}</td>
                        </tr>
                        <tr>
                            <td class="summary-label-bordered">Discount</td>
                            <td class="summary-value-bordered">{{ number_format($preQuotation->discount_total ?? 0, 2) }}</td>
                        </tr>
                        <tr>
                            <td class="summary-label-bordered">Tax</td>
                            <td class="summary-value-bordered">{{ number_format($preQuotation->tax_total ?? 0, 2) }}</td>
                        </tr>
                        <tr>
                            <td class="summary-label-bordered">Total</td>
                            <td class="summary-value-bordered total-row">{{ number_format($preQuotation->total ?? 0, 2) }}</td>
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
