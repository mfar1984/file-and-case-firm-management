@extends('layouts.app')

@section('breadcrumb')
    Profit and Loss Account
@endsection

@section('content')
<div class="px-4 md:px-6 pt-4 md:pt-6 pb-6 max-w-7xl mx-auto">
    <div class="bg-white rounded shadow-md border border-gray-300">
        <div class="p-4 md:p-6 border-b border-gray-200">
            <div class="flex items-center">
                <span class="material-icons mr-2 text-green-600">trending_up</span>
                <h1 class="text-lg md:text-xl font-bold text-gray-800">Profit and Loss Account</h1>
            </div>
            <p class="text-xs text-gray-500 mt-1 ml-8">Income statement showing revenue, expenses, and net profit/loss.</p>
        </div>
        
        <!-- Filter Section -->
        <div class="p-4 md:p-6 border-b border-gray-200 bg-gray-50">
            <form method="GET" action="{{ route('profit-loss.index') }}" class="space-y-4">
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
                            Generate P&L
                        </button>
                        <a href="{{ route('profit-loss.print', request()->all()) }}" target="_blank" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-xs font-medium flex items-center justify-center">
                            <span class="material-icons text-xs mr-1">print</span>
                            Print
                        </a>
                    </div>
                </div>
            </form>
        </div>

        <!-- P&L Header -->
        <div class="p-4 md:p-6 border-b border-gray-200 bg-blue-50 text-center">
            <h2 class="text-sm font-bold text-gray-800">PROFIT AND LOSS ACCOUNT</h2>
            <p class="text-xs text-gray-600">
                For the period from {{ Carbon\Carbon::parse($startDate)->format('d M Y') }} 
                to {{ Carbon\Carbon::parse($endDate)->format('d M Y') }}
            </p>
        </div>

        <!-- Summary Cards -->
        <div class="p-4 md:p-6 border-b border-gray-200">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="bg-green-50 p-4 rounded-md text-center">
                    <p class="text-xs text-gray-600 mb-1">Total Revenue</p>
                    <p class="text-lg font-bold text-green-600">RM {{ number_format($totalRevenue, 2) }}</p>
                </div>
                <div class="bg-red-50 p-4 rounded-md text-center">
                    <p class="text-xs text-gray-600 mb-1">Total Expenses</p>
                    <p class="text-lg font-bold text-red-600">RM {{ number_format($totalExpenses, 2) }}</p>
                </div>
                <div class="bg-{{ $netProfit >= 0 ? 'blue' : 'red' }}-50 p-4 rounded-md text-center">
                    <p class="text-xs text-gray-600 mb-1">Net {{ $netProfit >= 0 ? 'Profit' : 'Loss' }}</p>
                    <p class="text-lg font-bold text-{{ $netProfit >= 0 ? 'blue' : 'red' }}-600">
                        RM {{ number_format(abs($netProfit), 2) }}
                    </p>
                </div>
                <div class="bg-gray-50 p-4 rounded-md text-center">
                    <p class="text-xs text-gray-600 mb-1">Profit Margin</p>
                    <p class="text-lg font-bold text-gray-600">{{ number_format($profitMargin, 1) }}%</p>
                </div>
            </div>
        </div>

        <!-- P&L Statement -->
        <div class="p-4 md:p-6">
            <div class="max-w-4xl mx-auto">
                
                <!-- REVENUE Section -->
                <div class="mb-8">
                    <h3 class="text-sm font-bold text-gray-800 mb-4 pb-2 border-b border-gray-200">REVENUE</h3>
                    <div class="space-y-2">
                        @foreach($revenue as $revenueItem)
                        <div class="flex justify-between items-center py-2">
                            <span class="text-xs text-gray-700 pl-4">{{ $revenueItem['account'] }}</span>
                            <span class="text-xs font-medium text-green-600">
                                RM {{ number_format($revenueItem['amount'], 2) }}
                            </span>
                        </div>
                        @endforeach
                    </div>
                    <div class="flex justify-between items-center py-3 border-t border-gray-200 mt-4">
                        <span class="text-sm font-bold text-gray-800">TOTAL REVENUE</span>
                        <span class="text-sm font-bold text-green-600">
                            RM {{ number_format($totalRevenue, 2) }}
                        </span>
                    </div>
                </div>

                <!-- EXPENSES Section -->
                <div class="mb-8">
                    <h3 class="text-sm font-bold text-gray-800 mb-4 pb-2 border-b border-gray-200">EXPENSES</h3>
                    
                    @php
                        $operatingExpenses = collect($expenses)->where('category', 'Operating Expenses');
                        $adminExpenses = collect($expenses)->where('category', 'Administrative Expenses');
                    @endphp
                    
                    <!-- Operating Expenses -->
                    @if($operatingExpenses->count() > 0)
                    <div class="mb-6">
                        <h4 class="text-xs font-semibold text-gray-700 mb-2">Operating Expenses</h4>
                        <div class="space-y-1">
                            @foreach($operatingExpenses as $expense)
                            <div class="flex justify-between items-center py-1">
                                <span class="text-xs text-gray-600 pl-4">{{ $expense['account'] }}</span>
                                <span class="text-xs font-medium text-red-600">
                                    RM {{ number_format($expense['amount'], 2) }}
                                </span>
                            </div>
                            @endforeach
                        </div>
                        <div class="flex justify-between items-center py-2 border-t border-gray-100 mt-2">
                            <span class="text-xs font-semibold text-gray-700 pl-2">Total Operating Expenses</span>
                            <span class="text-xs font-bold text-red-600">
                                RM {{ number_format($operatingExpenses->sum('amount'), 2) }}
                            </span>
                        </div>
                    </div>
                    @endif
                    
                    <!-- Administrative Expenses -->
                    @if($adminExpenses->count() > 0)
                    <div class="mb-6">
                        <h4 class="text-xs font-semibold text-gray-700 mb-2">Administrative Expenses</h4>
                        <div class="space-y-1">
                            @foreach($adminExpenses as $expense)
                            <div class="flex justify-between items-center py-1">
                                <span class="text-xs text-gray-600 pl-4">{{ $expense['account'] }}</span>
                                <span class="text-xs font-medium text-red-600">
                                    RM {{ number_format($expense['amount'], 2) }}
                                </span>
                            </div>
                            @endforeach
                        </div>
                        <div class="flex justify-between items-center py-2 border-t border-gray-100 mt-2">
                            <span class="text-xs font-semibold text-gray-700 pl-2">Total Administrative Expenses</span>
                            <span class="text-xs font-bold text-red-600">
                                RM {{ number_format($adminExpenses->sum('amount'), 2) }}
                            </span>
                        </div>
                    </div>
                    @endif
                    
                    <div class="flex justify-between items-center py-3 border-t border-gray-200 mt-4">
                        <span class="text-sm font-bold text-gray-800">TOTAL EXPENSES</span>
                        <span class="text-sm font-bold text-red-600">
                            RM {{ number_format($totalExpenses, 2) }}
                        </span>
                    </div>
                </div>

                <!-- NET PROFIT/LOSS -->
                <div class="border-t-2 border-gray-800 pt-4">
                    <div class="flex justify-between items-center py-4 bg-{{ $netProfit >= 0 ? 'green' : 'red' }}-50 px-4 rounded-md">
                        <span class="text-lg font-bold text-gray-800">
                            NET {{ $netProfit >= 0 ? 'PROFIT' : 'LOSS' }}
                        </span>
                        <span class="text-lg font-bold text-{{ $netProfit >= 0 ? 'green' : 'red' }}-600">
                            RM {{ number_format(abs($netProfit), 2) }}
                        </span>
                    </div>
                    
                    @if($totalRevenue > 0)
                    <div class="mt-2 text-center">
                        <p class="text-xs text-gray-600">
                            Profit Margin: 
                            <span class="font-semibold text-{{ $profitMargin >= 0 ? 'green' : 'red' }}-600">
                                {{ number_format($profitMargin, 1) }}%
                            </span>
                        </p>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Summary Section -->
        <div class="p-4 md:p-6 border-t border-gray-200 bg-gray-50">
            <p class="text-xs text-gray-600">
                P&L for period {{ Carbon\Carbon::parse($startDate)->format('d M Y') }}
                to {{ Carbon\Carbon::parse($endDate)->format('d M Y') }}
            </p>
        </div>
    </div>
</div>

<style>
@media print {
    .no-print { display: none !important; }
    body { font-size: 12px; }
    .grid { display: block !important; }
    .md\\:grid-cols-4 > div { margin-bottom: 1rem; }
}
</style>
@endsection
