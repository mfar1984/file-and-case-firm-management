<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Profit & Loss - {{ $reportTitle }}</title>
    @include('partials.report-print-styles')
</head>
<body>
    @include('partials.report-print-header', [
        'reportTitle' => $reportTitle,
        'dateRange' => $dateRange,
        'firmSettings' => $firmSettings
    ])

    <div class="main-content">
        <!-- Revenue Section -->
        <h3 style="margin-top: 0; margin-bottom: 10px; font-size: 12px; text-transform: uppercase;">REVENUE</h3>
        <table class="report-table" style="margin-bottom: 20px;">
            <thead>
                <tr>
                    <th style="width: 70%;">Revenue Category</th>
                    <th style="width: 30%;">Amount (RM)</th>
                </tr>
            </thead>
            <tbody>
                @forelse($revenueBreakdown as $revenue)
                    <tr>
                        <td>{{ $revenue['category'] }}</td>
                        <td class="text-right">{{ number_format($revenue['amount'], 2) }}</td>
                    </tr>
                @empty
                    <tr>
                        <td>No revenue recorded</td>
                        <td class="text-right">0.00</td>
                    </tr>
                @endforelse
                <tr style="border-top: 2px solid #333; font-weight: bold;">
                    <td><strong>TOTAL REVENUE</strong></td>
                    <td class="text-right"><strong>{{ number_format($totalRevenue, 2) }}</strong></td>
                </tr>
            </tbody>
        </table>

        <!-- Expenses Section -->
        <h3 style="margin-bottom: 10px; font-size: 12px; text-transform: uppercase;">EXPENSES</h3>
        <table class="report-table" style="margin-bottom: 20px;">
            <thead>
                <tr>
                    <th style="width: 50%;">Expense Category</th>
                    <th style="width: 20%;">Account</th>
                    <th style="width: 30%;">Amount (RM)</th>
                </tr>
            </thead>
            <tbody>
                @forelse($expenseBreakdown as $expense)
                    <tr>
                        <td>{{ $expense['category'] }}</td>
                        <td>{{ $expense['account'] }}</td>
                        <td class="text-right">{{ number_format($expense['amount'], 2) }}</td>
                    </tr>
                @empty
                    <tr>
                        <td>No expenses recorded</td>
                        <td>-</td>
                        <td class="text-right">0.00</td>
                    </tr>
                @endforelse
                <tr style="border-top: 2px solid #333; font-weight: bold;">
                    <td><strong>TOTAL EXPENSES</strong></td>
                    <td></td>
                    <td class="text-right"><strong>{{ number_format($totalExpenses, 2) }}</strong></td>
                </tr>
            </tbody>
        </table>

        <!-- Summary Section -->
        <div style="margin-top: 30px;">
            <table style="width: 100%; border-collapse: collapse;">
                <tr>
                    <td style="width: 50%;"></td>
                    <td style="width: 50%; border-top: 2px solid #333; padding-top: 15px;">
                        <table style="width: 100%; font-size: 12px;">
                            <tr>
                                <td><strong>Total Revenue:</strong></td>
                                <td class="text-right"><strong>{{ number_format($totalRevenue, 2) }}</strong></td>
                            </tr>
                            <tr>
                                <td><strong>Total Expenses:</strong></td>
                                <td class="text-right"><strong>({{ number_format($totalExpenses, 2) }})</strong></td>
                            </tr>
                            <tr style="border-top: 1px solid #333;">
                                <td><strong>Net {{ $netProfit >= 0 ? 'Profit' : 'Loss' }}:</strong></td>
                                <td class="text-right"><strong>{{ number_format($netProfit, 2) }}</strong></td>
                            </tr>
                            <tr>
                                <td><strong>Profit Margin:</strong></td>
                                <td class="text-right"><strong>{{ number_format($profitMargin, 2) }}%</strong></td>
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
