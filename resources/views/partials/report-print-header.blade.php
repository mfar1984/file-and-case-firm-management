{{-- Report Print Header Component --}}
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

        <div class="report-title">
            {{ $reportTitle ?? 'Financial Report' }}
        </div>

        <div class="report-details">
            <div class="report-info">
                @if(isset($reportPeriod))
                    <strong>Period:</strong> {{ $reportPeriod }}<br>
                @endif
                @if(isset($asOfDate))
                    <strong>As of:</strong> {{ $asOfDate }}<br>
                @endif
                @if(isset($dateRange))
                    <strong>Date Range:</strong> {{ $dateRange }}<br>
                @endif
            </div>
            <div class="report-meta">
                <span class="report-label">Generated</span><span class="report-separator">: </span><span class="report-value">{{ now()->format('d/m/Y H:i') }}</span><br>
                <span class="report-label">Page</span><span class="report-separator">: </span><span class="report-value page-counter"></span>
            </div>
        </div>
        <div class="clear"></div>
    </div>
</div>
