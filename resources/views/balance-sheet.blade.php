@extends('layouts.app')

@section('breadcrumb')
    Balance Sheet
@endsection

@section('content')
<div class="px-4 md:px-6 pt-4 md:pt-6 pb-6 max-w-7xl mx-auto">
    <div class="bg-white rounded shadow-md border border-gray-300">
        <div class="p-4 md:p-6 border-b border-gray-200">
            <div class="flex items-center">
                <span class="material-icons mr-2 text-green-600">balance</span>
                <h1 class="text-lg md:text-xl font-bold text-gray-800">Balance Sheet</h1>
            </div>
            <p class="text-xs text-gray-500 mt-1 ml-8">Financial position showing assets, liabilities, and equity.</p>
        </div>
        
        <!-- Filter Section -->
        <div class="p-4 md:p-6 border-b border-gray-200 bg-gray-50">
            <form method="GET" action="{{ route('balance-sheet.index') }}" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-2">As of Date</label>
                        <input type="date" name="as_of_date" value="{{ $asOfDate }}" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-green-500">
                    </div>
                    <div class="flex items-end">
                        <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md text-xs font-medium flex items-center w-full justify-center">
                            <span class="material-icons text-xs mr-1">search</span>
                            Generate Balance Sheet
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Balance Sheet Header -->
        <div class="p-4 md:p-6 border-b border-gray-200 bg-blue-50 text-center">
            <h2 class="text-sm font-bold text-gray-800">BALANCE SHEET</h2>
            <p class="text-xs text-gray-600">As of {{ Carbon\Carbon::parse($asOfDate)->format('d F Y') }}</p>
            <div class="mt-2">
                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
                    {{ $isBalanced ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                    {{ $isBalanced ? '✓ BALANCED' : '⚠ UNBALANCED' }}
                </span>
            </div>
        </div>

        <!-- Balance Sheet Content -->
        <div class="p-4 md:p-6">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                
                <!-- ASSETS Column -->
                <div>
                    <h3 class="text-sm font-bold text-gray-800 mb-4 pb-2 border-b border-gray-200">ASSETS</h3>
                    
                    @php
                        $currentAssets = collect($assets)->where('category', 'Current Assets');
                        $fixedAssets = collect($assets)->where('category', 'Fixed Assets');
                    @endphp
                    
                    <!-- Current Assets -->
                    @if($currentAssets->count() > 0)
                    <div class="mb-6">
                        <h4 class="text-xs font-semibold text-gray-700 mb-2">Current Assets</h4>
                        <div class="space-y-1">
                            @foreach($currentAssets as $asset)
                            <div class="flex justify-between items-center py-1">
                                <span class="text-xs text-gray-600 pl-4">{{ $asset['account'] }}</span>
                                <span class="text-xs font-medium text-gray-800">
                                    RM {{ number_format($asset['amount'], 2) }}
                                </span>
                            </div>
                            @endforeach
                        </div>
                        <div class="flex justify-between items-center py-2 border-t border-gray-200 mt-2">
                            <span class="text-xs font-semibold text-gray-700">Total Current Assets</span>
                            <span class="text-xs font-bold text-blue-600">
                                RM {{ number_format($currentAssets->sum('amount'), 2) }}
                            </span>
                        </div>
                    </div>
                    @endif
                    
                    <!-- Fixed Assets -->
                    @if($fixedAssets->count() > 0)
                    <div class="mb-6">
                        <h4 class="text-xs font-semibold text-gray-700 mb-2">Fixed Assets</h4>
                        <div class="space-y-1">
                            @foreach($fixedAssets as $asset)
                            <div class="flex justify-between items-center py-1">
                                <span class="text-xs text-gray-600 pl-4">{{ $asset['account'] }}</span>
                                <span class="text-xs font-medium text-gray-800">
                                    RM {{ number_format($asset['amount'], 2) }}
                                </span>
                            </div>
                            @endforeach
                        </div>
                        <div class="flex justify-between items-center py-2 border-t border-gray-200 mt-2">
                            <span class="text-xs font-semibold text-gray-700">Total Fixed Assets</span>
                            <span class="text-xs font-bold text-blue-600">
                                RM {{ number_format($fixedAssets->sum('amount'), 2) }}
                            </span>
                        </div>
                    </div>
                    @endif
                    
                    <!-- Total Assets -->
                    <div class="border-t-2 border-gray-800 pt-2">
                        <div class="flex justify-between items-center">
                            <span class="text-sm font-bold text-gray-800">TOTAL ASSETS</span>
                            <span class="text-sm font-bold text-green-600">
                                RM {{ number_format($totalAssets, 2) }}
                            </span>
                        </div>
                    </div>
                </div>
                
                <!-- LIABILITIES & EQUITY Column -->
                <div>
                    <h3 class="text-sm font-bold text-gray-800 mb-4 pb-2 border-b border-gray-200">LIABILITIES & EQUITY</h3>
                    
                    @php
                        $currentLiabilities = collect($liabilities)->where('category', 'Current Liabilities');
                        $longTermLiabilities = collect($liabilities)->where('category', 'Long-term Liabilities');
                        $ownerEquity = collect($equity)->where('category', 'Owner\'s Equity');
                    @endphp
                    
                    <!-- Current Liabilities -->
                    @if($currentLiabilities->count() > 0)
                    <div class="mb-6">
                        <h4 class="text-xs font-semibold text-gray-700 mb-2">Current Liabilities</h4>
                        <div class="space-y-1">
                            @foreach($currentLiabilities as $liability)
                            <div class="flex justify-between items-center py-1">
                                <span class="text-xs text-gray-600 pl-4">{{ $liability['account'] }}</span>
                                <span class="text-xs font-medium text-gray-800">
                                    RM {{ number_format($liability['amount'], 2) }}
                                </span>
                            </div>
                            @endforeach
                        </div>
                        <div class="flex justify-between items-center py-2 border-t border-gray-200 mt-2">
                            <span class="text-xs font-semibold text-gray-700">Total Current Liabilities</span>
                            <span class="text-xs font-bold text-red-600">
                                RM {{ number_format($currentLiabilities->sum('amount'), 2) }}
                            </span>
                        </div>
                    </div>
                    @endif
                    
                    <!-- Long-term Liabilities -->
                    @if($longTermLiabilities->count() > 0)
                    <div class="mb-6">
                        <h4 class="text-xs font-semibold text-gray-700 mb-2">Long-term Liabilities</h4>
                        <div class="space-y-1">
                            @foreach($longTermLiabilities as $liability)
                            <div class="flex justify-between items-center py-1">
                                <span class="text-xs text-gray-600 pl-4">{{ $liability['account'] }}</span>
                                <span class="text-xs font-medium text-gray-800">
                                    RM {{ number_format($liability['amount'], 2) }}
                                </span>
                            </div>
                            @endforeach
                        </div>
                        <div class="flex justify-between items-center py-2 border-t border-gray-200 mt-2">
                            <span class="text-xs font-semibold text-gray-700">Total Long-term Liabilities</span>
                            <span class="text-xs font-bold text-red-600">
                                RM {{ number_format($longTermLiabilities->sum('amount'), 2) }}
                            </span>
                        </div>
                    </div>
                    @endif
                    
                    <!-- Total Liabilities -->
                    <div class="mb-6 border-t border-gray-200 pt-2">
                        <div class="flex justify-between items-center">
                            <span class="text-xs font-bold text-gray-700">TOTAL LIABILITIES</span>
                            <span class="text-xs font-bold text-red-600">
                                RM {{ number_format($totalLiabilities, 2) }}
                            </span>
                        </div>
                    </div>
                    
                    <!-- Owner's Equity -->
                    @if($ownerEquity->count() > 0)
                    <div class="mb-6">
                        <h4 class="text-xs font-semibold text-gray-700 mb-2">Owner's Equity</h4>
                        <div class="space-y-1">
                            @foreach($ownerEquity as $equityItem)
                            <div class="flex justify-between items-center py-1">
                                <span class="text-xs text-gray-600 pl-4">{{ $equityItem['account'] }}</span>
                                <span class="text-xs font-medium text-gray-800">
                                    RM {{ number_format($equityItem['amount'], 2) }}
                                </span>
                            </div>
                            @endforeach
                        </div>
                        <div class="flex justify-between items-center py-2 border-t border-gray-200 mt-2">
                            <span class="text-xs font-semibold text-gray-700">Total Owner's Equity</span>
                            <span class="text-xs font-bold text-blue-600">
                                RM {{ number_format($totalEquity, 2) }}
                            </span>
                        </div>
                    </div>
                    @endif
                    
                    <!-- Total Liabilities & Equity -->
                    <div class="border-t-2 border-gray-800 pt-2">
                        <div class="flex justify-between items-center">
                            <span class="text-sm font-bold text-gray-800">TOTAL LIABILITIES & EQUITY</span>
                            <span class="text-sm font-bold text-green-600">
                                RM {{ number_format($totalLiabilities + $totalEquity, 2) }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Export Section -->
        <div class="p-4 md:p-6 border-t border-gray-200 bg-gray-50">
            <div class="flex justify-between items-center">
                <p class="text-xs text-gray-600">
                    Balance Sheet as of {{ Carbon\Carbon::parse($asOfDate)->format('d M Y') }}
                </p>
                <div class="flex space-x-2">
                    <button onclick="window.print()" class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded text-xs">
                        <span class="material-icons text-xs mr-1">print</span>
                        Print
                    </button>
                    <button class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded text-xs">
                        <span class="material-icons text-xs mr-1">download</span>
                        Export PDF
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
@media print {
    .no-print { display: none !important; }
    body { font-size: 12px; }
    .grid { display: block !important; }
    .lg\\:grid-cols-2 > div { margin-bottom: 2rem; }
}
</style>
@endsection
