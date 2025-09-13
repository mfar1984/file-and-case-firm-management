<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Receipt - {{ $receipt->receipt_no ?? 'R-00001' }}</title>
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
            width: 50%;
            text-align: left;
            font-weight: 500;
            color: #374151;
        }
        
        .item-table th:nth-child(2),
        .item-table td:nth-child(2) {
            width: 50%;
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
                Receipt
            </div>

            <div class="quotation-details">
                <div class="customer-info">
                    <strong>{{ $receipt->customer_name ?? 'Customer Name' }}</strong><br>
                    @if($receipt->taxInvoice)
                        {{ $receipt->taxInvoice->customer_address ?? 'Customer Address' }}<br>
                        <span class="contact-label">Phone No.</span><span class="contact-separator">: </span><span class="contact-value">{{ $receipt->taxInvoice->customer_phone ?? 'Phone Number' }}</span><br>
                    @elseif($receipt->quotation)
                        {{ $receipt->quotation->customer_address ?? 'Customer Address' }}<br>
                        <span class="contact-label">Phone No.</span><span class="contact-separator">: </span><span class="contact-value">{{ $receipt->quotation->customer_phone ?? 'Phone Number' }}</span><br>
                    @else
                        Customer Address<br>
                        <span class="contact-label">Phone No.</span><span class="contact-separator">: </span><span class="contact-value">Phone Number</span><br>
                    @endif
                </div>
                <div class="quotation-meta">
                    <span class="quotation-label">Receipt No.</span><span class="quotation-separator">: </span><span class="quotation-value">{{ $receipt->receipt_no ?? 'R-00001' }}</span><br>
                    <span class="quotation-label">Reference</span><span class="quotation-separator">: </span><span class="quotation-value">
                        @if($receipt->quotation)
                            {{ $receipt->quotation->quotation_no }}
                        @elseif($receipt->taxInvoice)
                            {{ $receipt->taxInvoice->invoice_no }}
                        @elseif($receipt->case)
                            {{ $receipt->case->case_number }}
                        @else
                            N/A
                        @endif
                    </span><br>
                    <span class="quotation-label">Receipt Date</span><span class="quotation-separator">: </span><span class="quotation-value">{{ $receipt->receipt_date->format('d/m/Y') ?? '04/09/2025' }}</span><br>
                    <span class="quotation-label">Payment Method</span><span class="quotation-separator">: </span><span class="quotation-value">{{ $receipt->payment_method_display ?? 'Cash' }}</span><br>
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
                    <th>PAYMENT DETAILS</th>
                    <th>AMOUNT (RM)</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
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
                    <td class="text-right">
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

    <div class="footer">
        <div class="footer-content">
            <!-- Top section with amount in words and summary table -->
            <div class="footer-top">
                <div class="left-section">
                    <div class="amount-in-words">
                        Ringgit Malaysia: {{ $receipt->amount_paid_words ?? 'Amount in Words' }}
                    </div>
                    <div class="note-section">
                        <strong>Reference Information:</strong><br>
                        @if($receipt->quotation)
                            <span style="background-color: #dbeafe; color: #1e40af; padding: 1pt 3pt; border-radius: 2pt; font-size: 8pt;">Quotation</span> {{ $receipt->quotation->quotation_no }}
                        @elseif($receipt->taxInvoice)
                            <span style="background-color: #dcfce7; color: #166534; padding: 1pt 3pt; border-radius: 2pt; font-size: 8pt;">Tax Invoice</span> {{ $receipt->taxInvoice->invoice_no }}
                        @elseif($receipt->case)
                            <span style="background-color: #f3f4f6; color: #374151; padding: 1pt 3pt; border-radius: 2pt; font-size: 8pt;">Case</span> {{ $receipt->case->case_number }}
                        @else
                            N/A
                        @endif
                    </div>
                </div>

                <div class="right-section">
                    <table class="summary-table-bordered">
                        <tr>
                            <td class="summary-label-bordered">Total Amount</td>
                            <td class="summary-value-bordered">{{ number_format($receipt->total_amount ?? 0, 2) }}</td>
                        </tr>
                        <tr>
                            <td class="summary-label-bordered">Amount Paid</td>
                            <td class="summary-value-bordered total-row">{{ number_format($receipt->amount_paid ?? 0, 2) }}</td>
                        </tr>
                        @if($receipt->outstanding_balance > 0)
                        <tr>
                            <td class="summary-label-bordered">Outstanding</td>
                            <td class="summary-value-bordered">{{ number_format($receipt->outstanding_balance ?? 0, 2) }}</td>
                        </tr>
                        @endif
                    </table>
                </div>
            </div>

            <div class="clear"></div>

            <!-- Reminder section after Total (only for Tax Invoice receipts) -->
            @if($receipt->taxInvoice)
            <div class="note-section" style="margin-top: 5mm;">
                <strong>Reminder:</strong> Pursuant to the Solicitor's Remuneration Order 2005, interest 8% on the total sum billed will be charges from the expiration of one (1) month of the billing dates
            </div>
            @endif

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
