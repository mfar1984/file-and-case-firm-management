@extends('layouts.app')

@section('breadcrumb')
    General Ledger
@endsection

@section('content')
<div class="px-4 md:px-6 pt-4 md:pt-6 pb-6 max-w-7xl mx-auto">
    <div class="bg-white rounded shadow-md border border-gray-300">
        <div class="p-4 md:p-6 border-b border-gray-200">
            <div class="flex items-center">
                <span class="material-icons mr-2 text-green-600">list_alt</span>
                <h1 class="text-lg md:text-xl font-bold text-gray-800">General Ledger Listing</h1>
            </div>
            <p class="text-xs text-gray-500 mt-1 ml-8">Summary view of all account balances and transaction totals.</p>
        </div>
        
        <!-- Filter Section -->
        <div class="p-4 md:p-6 border-b border-gray-200 bg-gray-50">
            <form method="GET" action="{{ route('general-ledger.index') }}" class="space-y-4" x-data="{
                datePreset: '{{ request('date_preset', 'custom') }}',
                reportDate: '{{ $reportDate }}',
                fromDate: '{{ $fromDate }}',
                toDate: '{{ $toDate }}'
            }" x-init="updateDatesFromPreset()">

                <!-- Date Preset Dropdown -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-2">Date Range Preset</label>
                        <select x-model="datePreset" @change="updateDatesFromPreset()" name="date_preset" class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-green-500">
                            <option value="current_fiscal_ytd" {{ request('date_preset') == 'current_fiscal_ytd' ? 'selected' : '' }}>Current fiscal year to date</option>
                            <option value="current_fiscal_year" {{ request('date_preset') == 'current_fiscal_year' ? 'selected' : '' }}>Current fiscal year</option>
                            <option value="last_fiscal_year" {{ request('date_preset') == 'last_fiscal_year' ? 'selected' : '' }}>Last fiscal year</option>
                            <option value="current_quarter_td" {{ request('date_preset') == 'current_quarter_td' ? 'selected' : '' }}>Current quarter to date</option>
                            <option value="current_quarter" {{ request('date_preset') == 'current_quarter' ? 'selected' : '' }}>Current quarter</option>
                            <option value="last_quarter" {{ request('date_preset') == 'last_quarter' ? 'selected' : '' }}>Last quarter</option>
                            <option value="current_month_td" {{ request('date_preset') == 'current_month_td' || !request('date_preset') ? 'selected' : '' }}>Current month to date</option>
                            <option value="current_month" {{ request('date_preset') == 'current_month' ? 'selected' : '' }}>Current month</option>
                            <option value="last_month" {{ request('date_preset') == 'last_month' ? 'selected' : '' }}>Last month</option>
                            <option value="custom" {{ request('date_preset') == 'custom' ? 'selected' : '' }}>Custom</option>
                        </select>
                    </div>

                    <div class="flex items-end space-x-2">
                        <button type="submit" name="retrieve" value="1" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md text-xs font-medium flex items-center flex-1 justify-center">
                            <span class="material-icons text-xs mr-1">search</span>
                            Retrieve
                        </button>
                        <a href="{{ route('general-ledger.print', request()->all()) }}" target="_blank" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-xs font-medium flex items-center justify-center">
                            <span class="material-icons text-xs mr-1">print</span>
                            Print
                        </a>
                    </div>
                </div>

                <!-- Custom Date Fields (shown when Custom is selected) -->
                <div x-show="datePreset === 'custom'" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-2">From Date</label>
                        <input type="date" x-model="fromDate" name="from_date" class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-green-500">
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-2">To Date</label>
                        <input type="date" x-model="toDate" name="to_date" class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-green-500">
                    </div>
                </div>

                <!-- Hidden fields for preset dates -->
                <input type="hidden" x-model="reportDate" name="report_date">
                <input type="hidden" x-model="fromDate" name="from_date">
                <input type="hidden" x-model="toDate" name="to_date">

                <script>
                    function updateDatesFromPreset() {
                        const today = new Date();
                        const currentYear = today.getFullYear();
                        const currentMonth = today.getMonth();
                        const currentDate = today.getDate();

                        // Helper function to format date as YYYY-MM-DD
                        function formatDate(date) {
                            return date.toISOString().split('T')[0];
                        }

                        // Helper function to get quarter start/end
                        function getQuarter(date) {
                            const quarter = Math.floor(date.getMonth() / 3);
                            const startMonth = quarter * 3;
                            const endMonth = startMonth + 2;

                            return {
                                start: new Date(date.getFullYear(), startMonth, 1),
                                end: new Date(date.getFullYear(), endMonth + 1, 0)
                            };
                        }

                        switch (this.datePreset) {
                            case 'current_fiscal_ytd':
                                // Assuming fiscal year starts April 1st
                                const fiscalYearStart = currentMonth >= 3 ?
                                    new Date(currentYear, 3, 1) :
                                    new Date(currentYear - 1, 3, 1);
                                this.reportDate = formatDate(today);
                                this.fromDate = formatDate(fiscalYearStart);
                                this.toDate = formatDate(today);
                                break;

                            case 'current_fiscal_year':
                                const fiscalStart = currentMonth >= 3 ?
                                    new Date(currentYear, 3, 1) :
                                    new Date(currentYear - 1, 3, 1);
                                const fiscalEnd = currentMonth >= 3 ?
                                    new Date(currentYear + 1, 2, 31) :
                                    new Date(currentYear, 2, 31);
                                this.reportDate = formatDate(fiscalEnd);
                                this.fromDate = formatDate(fiscalStart);
                                this.toDate = formatDate(fiscalEnd);
                                break;

                            case 'last_fiscal_year':
                                const lastFiscalStart = currentMonth >= 3 ?
                                    new Date(currentYear - 1, 3, 1) :
                                    new Date(currentYear - 2, 3, 1);
                                const lastFiscalEnd = currentMonth >= 3 ?
                                    new Date(currentYear, 2, 31) :
                                    new Date(currentYear - 1, 2, 31);
                                this.reportDate = formatDate(lastFiscalEnd);
                                this.fromDate = formatDate(lastFiscalStart);
                                this.toDate = formatDate(lastFiscalEnd);
                                break;

                            case 'current_quarter_td':
                                const currentQuarter = getQuarter(today);
                                this.reportDate = formatDate(today);
                                this.fromDate = formatDate(currentQuarter.start);
                                this.toDate = formatDate(today);
                                break;

                            case 'current_quarter':
                                const currentQ = getQuarter(today);
                                this.reportDate = formatDate(currentQ.end);
                                this.fromDate = formatDate(currentQ.start);
                                this.toDate = formatDate(currentQ.end);
                                break;

                            case 'last_quarter':
                                const lastQuarterDate = new Date(currentYear, currentMonth - 3, 1);
                                const lastQ = getQuarter(lastQuarterDate);
                                this.reportDate = formatDate(lastQ.end);
                                this.fromDate = formatDate(lastQ.start);
                                this.toDate = formatDate(lastQ.end);
                                break;

                            case 'current_month_td':
                                const monthStart = new Date(currentYear, currentMonth, 1);
                                this.reportDate = formatDate(today);
                                this.fromDate = formatDate(monthStart);
                                this.toDate = formatDate(today);
                                break;

                            case 'current_month':
                                const currentMonthStart = new Date(currentYear, currentMonth, 1);
                                const currentMonthEnd = new Date(currentYear, currentMonth + 1, 0);
                                this.reportDate = formatDate(currentMonthEnd);
                                this.fromDate = formatDate(currentMonthStart);
                                this.toDate = formatDate(currentMonthEnd);
                                break;

                            case 'last_month':
                                const lastMonthStart = new Date(currentYear, currentMonth - 1, 1);
                                const lastMonthEnd = new Date(currentYear, currentMonth, 0);
                                this.reportDate = formatDate(lastMonthEnd);
                                this.fromDate = formatDate(lastMonthStart);
                                this.toDate = formatDate(lastMonthEnd);
                                break;

                            case 'custom':
                                // For custom, set report date same as to date
                                this.reportDate = this.toDate;
                                break;
                        }
                    }
                </script>
            </form>
        </div>

        <!-- General Ledger Table -->
        <div class="p-4 md:p-6">
            @if(count($ledgerData) > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Opening Balance</th>
                                <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Debit</th>
                                <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Credit</th>
                                <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Net Change</th>
                                <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Closing Balance</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @php
                                $totalOpeningBalance = 0;
                                $totalDebit = 0;
                                $totalCredit = 0;
                                $totalNetChange = 0;
                                $totalClosingBalance = 0;
                            @endphp
                            
                            @foreach($ledgerData as $ledger)
                                @php
                                    $totalOpeningBalance += $ledger['opening_balance'];
                                    $totalDebit += $ledger['debit'];
                                    $totalCredit += $ledger['credit'];
                                    $totalNetChange += $ledger['net_change'];
                                    $totalClosingBalance += $ledger['closing_balance'];
                                @endphp
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-2 text-xs text-gray-900">{{ $ledger['name'] }}</td>
                                    <td class="px-4 py-2 text-xs text-gray-900 text-right">{{ number_format($ledger['opening_balance'], 2) }}</td>
                                    <td class="px-4 py-2 text-xs text-gray-900 text-right">{{ number_format($ledger['debit'], 2) }}</td>
                                    <td class="px-4 py-2 text-xs text-gray-900 text-right">{{ number_format($ledger['credit'], 2) }}</td>
                                    <td class="px-4 py-2 text-xs text-right {{ $ledger['net_change'] >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                        {{ number_format($ledger['net_change'], 2) }}
                                    </td>
                                    <td class="px-4 py-2 text-xs text-right font-medium {{ $ledger['closing_balance'] >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                        {{ number_format($ledger['closing_balance'], 2) }}
                                    </td>
                                </tr>
                            @endforeach
                            
                            <!-- Total Row -->
                            <tr class="bg-gray-100 font-medium">
                                <td class="px-4 py-2 text-xs text-gray-900 font-bold">TOTAL</td>
                                <td class="px-4 py-2 text-xs text-gray-900 text-right font-bold">{{ number_format($totalOpeningBalance, 2) }}</td>
                                <td class="px-4 py-2 text-xs text-gray-900 text-right font-bold">{{ number_format($totalDebit, 2) }}</td>
                                <td class="px-4 py-2 text-xs text-gray-900 text-right font-bold">{{ number_format($totalCredit, 2) }}</td>
                                <td class="px-4 py-2 text-xs text-right font-bold {{ $totalNetChange >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                    {{ number_format($totalNetChange, 2) }}
                                </td>
                                <td class="px-4 py-2 text-xs text-right font-bold {{ $totalClosingBalance >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                    {{ number_format($totalClosingBalance, 2) }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                
                <!-- Report Info -->
                <div class="mt-6 p-4 bg-blue-50 rounded-md">
                    <div class="flex items-center">
                        <span class="material-icons text-blue-600 text-sm mr-2">info</span>
                        <div class="text-xs text-blue-800">
                            <p class="font-medium">Report Information</p>
                            @php
                                $presetLabels = [
                                    'current_fiscal_ytd' => 'Current fiscal year to date',
                                    'current_fiscal_year' => 'Current fiscal year',
                                    'last_fiscal_year' => 'Last fiscal year',
                                    'current_quarter_td' => 'Current quarter to date',
                                    'current_quarter' => 'Current quarter',
                                    'last_quarter' => 'Last quarter',
                                    'current_month_td' => 'Current month to date',
                                    'current_month' => 'Current month',
                                    'last_month' => 'Last month',
                                    'custom' => 'Custom date range'
                                ];
                                $currentPreset = request('date_preset', 'current_month_td');
                            @endphp
                            <p>Date Range: {{ $presetLabels[$currentPreset] ?? 'Custom date range' }}</p>
                            @if($currentPreset !== 'custom')
                                <p>Report Date: {{ \Carbon\Carbon::parse($reportDate)->format('d M Y') }}</p>
                            @endif
                            <p>Period: {{ \Carbon\Carbon::parse($fromDate)->format('d M Y') }} to {{ \Carbon\Carbon::parse($toDate)->format('d M Y') }}</p>
                            <p class="mt-1 text-blue-600">Generated on {{ now()->format('d M Y H:i:s') }}</p>
                        </div>
                    </div>
                </div>
            @else
                <div class="text-center py-12">
                    <span class="material-icons text-gray-400 text-4xl mb-4">account_balance_wallet</span>
                    <h3 class="text-sm font-medium text-gray-900 mb-2">No Ledger Data</h3>
                    <p class="text-xs text-gray-500 mb-4">Click "Retrieve" to generate general ledger report for the selected date range.</p>
                    <p class="text-xs text-gray-400">Make sure you have set up opening balances in Settings > Global Config first.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
