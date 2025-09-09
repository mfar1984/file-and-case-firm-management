{{-- Quotation Header Partial --}}
<div class="header">
    <div class="header-content">
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
