<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Trial Balance - {{ $reportTitle }}</title>
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
                    <th style="width: 40%;">Account Name</th>
                    <th style="width: 15%;">Account Type</th>
                    <th style="width: 22.5%;">Debit (RM)</th>
                    <th style="width: 22.5%;">Credit (RM)</th>
                </tr>
            </thead>
            <tbody>
                @forelse($accounts as $account)
                    <tr>
                        <td class="font-bold">{{ $account['account_name'] }}</td>
                        <td class="text-center">{{ $account['account_type'] }}</td>
                        <td class="text-right">{{ $account['debit'] > 0 ? number_format($account['debit'], 2) : '-' }}</td>
                        <td class="text-right">{{ $account['credit'] > 0 ? number_format($account['credit'], 2) : '-' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center">No accounts found for this period</td>
                    </tr>
                @endforelse
                
                <!-- Totals Row -->
                <tr style="border-top: 2px solid #333; font-weight: bold; background-color: #f9fafb;">
                    <td><strong>TOTAL</strong></td>
                    <td></td>
                    <td class="text-right"><strong>{{ number_format($totalDebits, 2) }}</strong></td>
                    <td class="text-right"><strong>{{ number_format($totalCredits, 2) }}</strong></td>
                </tr>
                
                <!-- Difference Row -->
                <tr style="border-top: 1px solid #333;">
                    <td><strong>DIFFERENCE</strong></td>
                    <td></td>
                    <td class="text-right" colspan="2">
                        <strong>{{ number_format(abs($difference), 2) }}</strong>
                    </td>
                </tr>
                
                <!-- Status Row -->
                <tr>
                    <td><strong>STATUS</strong></td>
                    <td></td>
                    <td class="text-right" colspan="2">
                        <strong style="color: {{ $isBalanced ? 'green' : 'red' }};">
                            {{ $isBalanced ? '✓ BALANCED' : '✗ UNBALANCED' }}
                        </strong>
                    </td>
                </tr>
            </tbody>
        </table>

        <!-- Balance Verification -->
        <div style="margin-top: 30px; padding: 15px; border: 1px solid #d1d5db; background-color: #f9fafb;">
            <h4 style="margin-top: 0; font-size: 11px;">BALANCE VERIFICATION:</h4>
            <table style="width: 100%; font-size: 10px;">
                <tr>
                    <td style="width: 50%;">
                        <strong>Assets & Expenses (Debit):</strong><br>
                        @foreach($accounts as $account)
                            @if(in_array($account['account_type'], ['Asset', 'Expense']) && $account['debit'] > 0)
                                • {{ $account['account_name'] }}: RM {{ number_format($account['debit'], 2) }}<br>
                            @endif
                        @endforeach
                    </td>
                    <td style="width: 50%;">
                        <strong>Liabilities, Equity & Revenue (Credit):</strong><br>
                        @foreach($accounts as $account)
                            @if(in_array($account['account_type'], ['Liability', 'Equity', 'Revenue']) && $account['credit'] > 0)
                                • {{ $account['account_name'] }}: RM {{ number_format($account['credit'], 2) }}<br>
                            @endif
                        @endforeach
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
