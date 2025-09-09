@extends('layouts.app')

@section('breadcrumb')
    Detail Transaction Reports
@endsection

@section('content')
<div class="px-4 md:px-6 pt-4 md:pt-6 pb-6 max-w-7xl mx-auto">
    <div class="bg-white rounded shadow-md border border-gray-300">
        <div class="p-4 md:p-6 border-b border-gray-200">
            <div class="flex items-center">
                <span class="material-icons mr-2 text-green-600">receipt_long</span>
                <h1 class="text-lg md:text-xl font-bold text-gray-800">Detail Transaction Reports</h1>
            </div>
            <p class="text-xs text-gray-500 mt-1 ml-8">Detailed view of individual transactions with running balance.</p>
        </div>
        
        <!-- Filter Section -->
        <div class="p-4 md:p-6 border-b border-gray-200 bg-gray-50">
            <form method="GET" action="{{ route('detail-transaction.index') }}" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-2">From Date</label>
                        <input type="date" name="start_date" value="{{ $startDate }}" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-green-500">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-2">To Date</label>
                        <input type="date" name="end_date" value="{{ $endDate }}" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-green-500">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-2">Account Filter</label>
                        <select name="account_filter" class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-green-500">
                            <option value="all" {{ $accountFilter == 'all' ? 'selected' : '' }}>All Accounts</option>
                            @foreach($accountOptions as $option)
                                @if($option !== 'All Accounts')
                                <option value="{{ $option }}" {{ $accountFilter == $option ? 'selected' : '' }}>{{ $option }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                    <div class="flex items-end space-x-2">
                        <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md text-xs font-medium flex items-center flex-1 justify-center">
                            <span class="material-icons text-xs mr-1">search</span>
                            Filter
                        </button>
                        <a href="{{ route('detail-transaction.print', request()->all()) }}" target="_blank" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-xs font-medium flex items-center justify-center">
                            <span class="material-icons text-xs mr-1">print</span>
                            Print
                        </a>
                    </div>
                </div>
            </form>
        </div>

        <!-- Summary Section -->
        <div class="p-4 md:p-6 border-b border-gray-200 bg-blue-50">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="text-center">
                    <p class="text-xs text-gray-600">Total Debit</p>
                    <p class="text-sm font-bold text-green-600">RM {{ number_format($totalDebit, 2) }}</p>
                </div>
                <div class="text-center">
                    <p class="text-xs text-gray-600">Total Credit</p>
                    <p class="text-sm font-bold text-red-600">RM {{ number_format($totalCredit, 2) }}</p>
                </div>
                <div class="text-center">
                    <p class="text-xs text-gray-600">Net Change</p>
                    <p class="text-sm font-bold {{ $netChange >= 0 ? 'text-green-600' : 'text-red-600' }}">
                        RM {{ number_format($netChange, 2) }}
                    </p>
                </div>
                <div class="text-center">
                    <p class="text-xs text-gray-600">Transactions</p>
                    <p class="text-sm font-bold text-blue-600">{{ $allTransactions->count() }}</p>
                </div>
            </div>
        </div>

        <!-- Transactions Table -->
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Reference</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Account</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Debit</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Credit</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Running Balance</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($allTransactions as $transaction)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 whitespace-nowrap text-xs text-gray-900">
                            {{ $transaction['date']->format('d/m/Y') }}
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap">
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
                                {{ $transaction['type'] == 'Receipt' ? 'bg-green-100 text-green-800' : 
                                   ($transaction['type'] == 'Bill' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                                {{ $transaction['type'] }}
                            </span>
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap text-xs text-blue-600 hover:text-blue-800">
                            <a href="{{ route($transaction['route'], $transaction['id']) }}">
                                {{ $transaction['reference'] }}
                            </a>
                        </td>
                        <td class="px-4 py-3 text-xs text-gray-900">
                            {{ $transaction['description'] }}
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap text-xs text-gray-600">
                            {{ $transaction['account'] }}
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap text-xs text-right text-green-600">
                            {{ $transaction['debit'] > 0 ? 'RM ' . number_format($transaction['debit'], 2) : '-' }}
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap text-xs text-right text-red-600">
                            {{ $transaction['credit'] > 0 ? 'RM ' . number_format($transaction['credit'], 2) : '-' }}
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap text-xs text-right font-medium 
                            {{ $transaction['running_balance'] >= 0 ? 'text-green-600' : 'text-red-600' }}">
                            RM {{ number_format($transaction['running_balance'], 2) }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-4 py-8 text-center text-xs text-gray-500">
                            No transactions found for the selected period.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Summary Section -->
        <div class="p-4 md:p-6 border-t border-gray-200 bg-gray-50">
            <p class="text-xs text-gray-600">
                Showing transactions from {{ Carbon\Carbon::parse($startDate)->format('d M Y') }}
                to {{ Carbon\Carbon::parse($endDate)->format('d M Y') }}
            </p>
        </div>
    </div>
</div>


@endsection
