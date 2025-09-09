@extends('layouts.app')

@section('breadcrumb')
    Journal Report
@endsection

@section('content')
<div class="px-4 md:px-6 pt-4 md:pt-6 pb-6 max-w-7xl mx-auto">
    <div class="bg-white rounded shadow-md border border-gray-300">
        <div class="p-4 md:p-6 border-b border-gray-200">
            <div class="flex items-center">
                <span class="material-icons mr-2 text-green-600">book</span>
                <h1 class="text-lg md:text-xl font-bold text-gray-800">Journal Report</h1>
            </div>
            <p class="text-xs text-gray-500 mt-1 ml-8">Double-entry bookkeeping journal showing debit and credit entries.</p>
        </div>
        
        <!-- Filter Section -->
        <div class="p-4 md:p-6 border-b border-gray-200 bg-gray-50">
            <form method="GET" action="{{ route('journal-report.index') }}" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
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
                    <div class="flex items-end space-x-2">
                        <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md text-xs font-medium flex items-center flex-1 justify-center">
                            <span class="material-icons text-xs mr-1">search</span>
                            Generate Report
                        </button>
                        <a href="{{ route('journal-report.print', request()->all()) }}" target="_blank" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-xs font-medium flex items-center justify-center">
                            <span class="material-icons text-xs mr-1">print</span>
                            Print
                        </a>
                    </div>
                </div>
            </form>
        </div>

        <!-- Summary Section -->
        <div class="p-4 md:p-6 border-b border-gray-200 bg-blue-50">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="text-center">
                    <p class="text-xs text-gray-600">Total Debits</p>
                    <p class="text-sm font-bold text-green-600">RM {{ number_format($totalDebit, 2) }}</p>
                </div>
                <div class="text-center">
                    <p class="text-xs text-gray-600">Total Credits</p>
                    <p class="text-sm font-bold text-red-600">RM {{ number_format($totalCredit, 2) }}</p>
                </div>
                <div class="text-center">
                    <p class="text-xs text-gray-600">Balance Status</p>
                    <p class="text-sm font-bold {{ abs($totalDebit - $totalCredit) < 0.01 ? 'text-green-600' : 'text-red-600' }}">
                        {{ abs($totalDebit - $totalCredit) < 0.01 ? 'BALANCED' : 'UNBALANCED' }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Journal Entries -->
        <div class="p-4 md:p-6">
            @forelse($groupedEntries as $transactionKey => $entries)
                @php
                    $firstEntry = $entries->first();
                    $transactionTotal = $entries->sum('debit');
                @endphp
                
                <!-- Transaction Header -->
                <div class="mb-4 border border-gray-200 rounded-md overflow-hidden">
                    <div class="bg-gray-100 px-4 py-2 border-b border-gray-200">
                        <div class="flex justify-between items-center">
                            <div>
                                <h3 class="text-xs font-semibold text-gray-800">
                                    {{ $firstEntry['date']->format('d M Y') }} - {{ $firstEntry['reference'] }}
                                </h3>
                                <p class="text-xs text-gray-600">{{ $firstEntry['description'] }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-xs text-gray-600">Transaction Total</p>
                                <p class="text-xs font-bold text-blue-600">RM {{ number_format($transactionTotal, 2) }}</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Journal Entries Table -->
                    <div class="overflow-x-auto">
                        <table class="min-w-full">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Account</th>
                                    <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase">Debit</th>
                                    <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase">Credit</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-100">
                                @foreach($entries as $entry)
                                <tr>
                                    <td class="px-4 py-2 text-xs text-gray-900">
                                        {{ $entry['account'] }}
                                    </td>
                                    <td class="px-4 py-2 text-xs text-right {{ $entry['debit'] > 0 ? 'text-green-600 font-medium' : 'text-gray-400' }}">
                                        {{ $entry['debit'] > 0 ? 'RM ' . number_format($entry['debit'], 2) : '-' }}
                                    </td>
                                    <td class="px-4 py-2 text-xs text-right {{ $entry['credit'] > 0 ? 'text-red-600 font-medium' : 'text-gray-400' }}">
                                        {{ $entry['credit'] > 0 ? 'RM ' . number_format($entry['credit'], 2) : '-' }}
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="bg-gray-50">
                                <tr>
                                    <td class="px-4 py-2 text-xs font-bold text-gray-800">TOTAL</td>
                                    <td class="px-4 py-2 text-xs text-right font-bold text-green-600">
                                        RM {{ number_format($entries->sum('debit'), 2) }}
                                    </td>
                                    <td class="px-4 py-2 text-xs text-right font-bold text-red-600">
                                        RM {{ number_format($entries->sum('credit'), 2) }}
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            @empty
                <div class="text-center py-8">
                    <span class="material-icons text-4xl text-gray-400 mb-2">book</span>
                    <p class="text-xs text-gray-500">No journal entries found for the selected period.</p>
                </div>
            @endforelse
        </div>

        <!-- Summary Section -->
        <div class="p-4 md:p-6 border-t border-gray-200 bg-gray-50">
            <p class="text-xs text-gray-600">
                Journal entries from {{ Carbon\Carbon::parse($startDate)->format('d M Y') }}
                to {{ Carbon\Carbon::parse($endDate)->format('d M Y') }}
            </p>
        </div>
    </div>
</div>


@endsection
