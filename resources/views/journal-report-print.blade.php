<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Journal Report - {{ $reportTitle }}</title>
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
                    <th style="width: 12%;">Date</th>
                    <th style="width: 15%;">Reference</th>
                    <th style="width: 30%;">Description</th>
                    <th style="width: 25%;">Account</th>
                    <th style="width: 9%;">Debit</th>
                    <th style="width: 9%;">Credit</th>
                </tr>
            </thead>
            <tbody>
                @forelse($journalEntries as $entry)
                    <tr>
                        <td class="text-center">{{ \Carbon\Carbon::parse($entry['date'])->format('d/m/Y') }}</td>
                        <td class="text-center">{{ $entry['reference'] }}</td>
                        <td>{{ $entry['description'] }}</td>
                        <td>{{ $entry['account'] }}</td>
                        <td class="text-right">{{ $entry['debit'] > 0 ? number_format($entry['debit'], 2) : '-' }}</td>
                        <td class="text-right">{{ $entry['credit'] > 0 ? number_format($entry['credit'], 2) : '-' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center">No journal entries found for this period</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div style="margin-top: 20px;">
            <table style="width: 100%; border-collapse: collapse;">
                <tr>
                    <td style="width: 60%;"></td>
                    <td style="width: 40%; border-top: 1px solid #333; padding-top: 10px;">
                        <table style="width: 100%;">
                            <tr>
                                <td><strong>Total Debit:</strong></td>
                                <td class="text-right"><strong>{{ number_format($totalDebit, 2) }}</strong></td>
                            </tr>
                            <tr>
                                <td><strong>Total Credit:</strong></td>
                                <td class="text-right"><strong>{{ number_format($totalCredit, 2) }}</strong></td>
                            </tr>
                            <tr style="border-top: 1px solid #333;">
                                <td><strong>Difference:</strong></td>
                                <td class="text-right"><strong>{{ number_format($totalDebit - $totalCredit, 2) }}</strong></td>
                            </tr>
                            <tr>
                                <td><strong>Status:</strong></td>
                                <td class="text-right"><strong>{{ abs($totalDebit - $totalCredit) < 0.01 ? 'BALANCED' : 'UNBALANCED' }}</strong></td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </div>

        <div style="margin-top: 20px; text-align: right;">
            <p><strong>Report Generated:</strong> {{ now()->format('d/m/Y H:i:s') }}</p>
            <p><strong>Period:</strong> {{ $dateRange }}</p>
        </div>
    </div>
</body>
</html>
