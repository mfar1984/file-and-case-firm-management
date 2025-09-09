@extends('layouts.app')

@section('breadcrumb')
    Trial Balance
@endsection

@section('content')
<div class="px-4 md:px-6 pt-4 md:pt-6 pb-6 max-w-7xl mx-auto">
    <div class="bg-white rounded shadow-md border border-gray-300">
        <div class="p-4 md:p-6 border-b border-gray-200">
            <div class="flex items-center">
                <span class="material-icons mr-2 text-green-600">account_balance</span>
                <h1 class="text-lg md:text-xl font-bold text-gray-800">Trial Balance</h1>
            </div>
            <p class="text-xs text-gray-500 mt-1 ml-8">Verify that total debits equal total credits across all accounts.</p>
        </div>
        
        <!-- Filter Section -->
        <div class="p-4 md:p-6 border-b border-gray-200 bg-gray-50">
            <form method="GET" action="{{ route('trial-balance.index') }}" class="space-y-4">
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
                            Generate Trial Balance
                        </button>
                        <a href="{{ route('trial-balance.print', request()->all()) }}" target="_blank" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-xs font-medium flex items-center justify-center">
                            <span class="material-icons text-xs mr-1">print</span>
                            Print
                        </a>
                    </div>
                </div>
            </form>
        </div>

        <!-- Trial Balance Header -->
        <div class="p-4 md:p-6 border-b border-gray-200 bg-blue-50 text-center">
            <h2 class="text-sm font-bold text-gray-800">TRIAL BALANCE</h2>
            <p class="text-xs text-gray-600">
                For the period from {{ Carbon\Carbon::parse($startDate)->format('d M Y') }} 
                to {{ Carbon\Carbon::parse($endDate)->format('d M Y') }}
            </p>
            <div class="mt-2">
                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
                    {{ $isBalanced ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                    {{ $isBalanced ? '✓ BALANCED' : '⚠ UNBALANCED' }}
                </span>
            </div>
        </div>

        <!-- Summary Cards -->
        <div class="p-4 md:p-6 border-b border-gray-200">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="bg-green-50 p-4 rounded-md text-center">
                    <p class="text-xs text-gray-600 mb-1">Total Debits</p>
                    <p class="text-lg font-bold text-green-600">RM {{ number_format($totalDebit, 2) }}</p>
                </div>
                <div class="bg-red-50 p-4 rounded-md text-center">
                    <p class="text-xs text-gray-600 mb-1">Total Credits</p>
                    <p class="text-lg font-bold text-red-600">RM {{ number_format($totalCredit, 2) }}</p>
                </div>
                <div class="bg-{{ $isBalanced ? 'blue' : 'red' }}-50 p-4 rounded-md text-center">
                    <p class="text-xs text-gray-600 mb-1">Difference</p>
                    <p class="text-lg font-bold text-{{ $isBalanced ? 'blue' : 'red' }}-600">
                        RM {{ number_format(abs($totalDebit - $totalCredit), 2) }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Trial Balance Table -->
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Account Name</th>
                        <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Account Type</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Debit</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Credit</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @php
                        $accountTypes = ['Asset', 'Liability', 'Equity', 'Revenue', 'Expense'];
                    @endphp
                    
                    @foreach($accountTypes as $type)
                        @php
                            $typeAccounts = collect($accounts)->where('account_type', $type);
                        @endphp
                        
                        @if($typeAccounts->count() > 0)
                            <!-- Account Type Header -->
                            <tr class="bg-gray-100">
                                <td colspan="4" class="px-4 py-2 text-xs font-bold text-gray-800 uppercase">
                                    {{ $type }} ACCOUNTS
                                </td>
                            </tr>
                            
                            <!-- Accounts in this type -->
                            @foreach($typeAccounts as $account)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-3 text-xs text-gray-900 pl-8">
                                    {{ $account['account_name'] }}
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
                                        {{ $account['account_type'] == 'Asset' ? 'bg-blue-100 text-blue-800' : 
                                           ($account['account_type'] == 'Liability' ? 'bg-red-100 text-red-800' : 
                                           ($account['account_type'] == 'Equity' ? 'bg-purple-100 text-purple-800' :
                                           ($account['account_type'] == 'Revenue' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800'))) }}">
                                        {{ $account['account_type'] }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap text-xs text-right {{ $account['debit'] > 0 ? 'text-green-600 font-medium' : 'text-gray-400' }}">
                                    {{ $account['debit'] > 0 ? 'RM ' . number_format($account['debit'], 2) : '-' }}
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap text-xs text-right {{ $account['credit'] > 0 ? 'text-red-600 font-medium' : 'text-gray-400' }}">
                                    {{ $account['credit'] > 0 ? 'RM ' . number_format($account['credit'], 2) : '-' }}
                                </td>
                            </tr>
                            @endforeach
                            
                            <!-- Subtotal for this account type -->
                            <tr class="bg-gray-50">
                                <td class="px-4 py-2 text-xs font-semibold text-gray-700 pl-6">
                                    Total {{ $type }} Accounts
                                </td>
                                <td class="px-4 py-2"></td>
                                <td class="px-4 py-2 text-xs text-right font-semibold text-green-600">
                                    RM {{ number_format($typeAccounts->sum('debit'), 2) }}
                                </td>
                                <td class="px-4 py-2 text-xs text-right font-semibold text-red-600">
                                    RM {{ number_format($typeAccounts->sum('credit'), 2) }}
                                </td>
                            </tr>
                        @endif
                    @endforeach
                </tbody>
                <tfoot class="bg-gray-800 text-white">
                    <tr>
                        <td class="px-4 py-3 text-sm font-bold">GRAND TOTAL</td>
                        <td class="px-4 py-3"></td>
                        <td class="px-4 py-3 text-sm text-right font-bold">
                            RM {{ number_format($totalDebit, 2) }}
                        </td>
                        <td class="px-4 py-3 text-sm text-right font-bold">
                            RM {{ number_format($totalCredit, 2) }}
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <!-- Balance Verification -->
        <div class="p-4 md:p-6 border-t border-gray-200 bg-{{ $isBalanced ? 'green' : 'red' }}-50">
            <div class="text-center">
                @if($isBalanced)
                    <div class="flex items-center justify-center mb-2">
                        <span class="material-icons text-green-600 mr-2">check_circle</span>
                        <h3 class="text-sm font-bold text-green-800">TRIAL BALANCE IS BALANCED</h3>
                    </div>
                    <p class="text-xs text-green-700">
                        Total Debits (RM {{ number_format($totalDebit, 2) }}) equals Total Credits (RM {{ number_format($totalCredit, 2) }})
                    </p>
                @else
                    <div class="flex items-center justify-center mb-2">
                        <span class="material-icons text-red-600 mr-2">error</span>
                        <h3 class="text-sm font-bold text-red-800">TRIAL BALANCE IS UNBALANCED</h3>
                    </div>
                    <p class="text-xs text-red-700">
                        Difference of RM {{ number_format(abs($totalDebit - $totalCredit), 2) }} needs to be investigated
                    </p>
                @endif
            </div>
        </div>

        <!-- Summary Section -->
        <div class="p-4 md:p-6 border-t border-gray-200 bg-gray-50">
            <p class="text-xs text-gray-600">
                Trial Balance for period {{ Carbon\Carbon::parse($startDate)->format('d M Y') }}
                to {{ Carbon\Carbon::parse($endDate)->format('d M Y') }}
            </p>
        </div>
    </div>
</div>

<style>
@media print {
    .no-print { display: none !important; }
    body { font-size: 12px; }
    table { font-size: 10px; }
    .grid { display: block !important; }
    .md\\:grid-cols-3 > div { margin-bottom: 1rem; }
}
</style>
@endsection
