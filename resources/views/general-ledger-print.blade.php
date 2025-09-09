<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>General Ledger - {{ $reportTitle }}</title>
    @include('partials.report-print-styles')
</head>
<body>
    @include('partials.report-print-header', [
        'reportTitle' => $reportTitle,
        'dateRange' => $dateRange,
        'firmSettings' => $firmSettings
    ])

    <div class="main-content">
        <table class="report-table">
            <thead>
                <tr>
                    <th style="width: 30%;">Account Name</th>
                    <th style="width: 15%;">Account Type</th>
                    <th style="width: 15%;">Opening Balance</th>
                    <th style="width: 15%;">Debit</th>
                    <th style="width: 15%;">Credit</th>
                    <th style="width: 10%;">Closing Balance</th>
                </tr>
            </thead>
            <tbody>
                @forelse($ledgerData as $account)
                    <tr>
                        <td class="font-bold">{{ $account['name'] ?? 'Unknown Account' }}</td>
                        <td class="text-center">{{ $account['account_type'] ?? 'N/A' }}</td>
                        <td class="text-right">{{ number_format($account['opening_balance'] ?? 0, 2) }}</td>
                        <td class="text-right">{{ number_format($account['debit'] ?? 0, 2) }}</td>
                        <td class="text-right">{{ number_format($account['credit'] ?? 0, 2) }}</td>
                        <td class="text-right font-bold">{{ number_format($account['closing_balance'] ?? 0, 2) }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center">No ledger data found for this period</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div style="margin-top: 20px; text-align: right;">
            <p><strong>Report Generated:</strong> {{ now()->format('d/m/Y H:i:s') }}</p>
            <p><strong>Period:</strong> {{ $dateRange }}</p>
        </div>
    </div>
</body>
</html>
