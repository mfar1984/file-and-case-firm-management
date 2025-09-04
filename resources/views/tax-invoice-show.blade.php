@extends('layouts.app')

@section('breadcrumb')
    <span class="text-gray-500">Tax Invoice</span>
@endsection

@section('content')
<div class="px-4 md:px-6 pt-4 md:pt-6 pb-6 max-w-7xl mx-auto">
    <div class="bg-white rounded shadow-md border border-gray-300">
        <div class="px-4 md:px-6 py-4 border-b border-gray-200 flex items-center justify-between">
            <div class="flex items-center">
                <span class="material-icons mr-2 text-blue-600">receipt</span>
                <h1 class="text-lg md:text-xl font-bold text-gray-800 text-[14px]">Tax Invoice {{ $taxInvoice->invoice_no }}</h1>
            </div>
            <a href="{{ route('tax-invoice.index') }}" class="text-xs text-blue-600 hover:underline">Back</a>
        </div>
        <div class="p-4 md:p-6 space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <div class="text-xs text-gray-500">Customer</div>
                    <div class="text-sm font-semibold">{{ $taxInvoice->customer_name }}</div>
                    <div class="text-xs text-gray-700 whitespace-pre-line">{{ $taxInvoice->customer_address }}</div>
                    <div class="text-xs text-gray-700">{{ $taxInvoice->customer_email }} {{ $taxInvoice->customer_phone ? ' • ' . $taxInvoice->customer_phone : '' }}</div>
                </div>
                <div class="text-right">
                    <div class="text-xs text-gray-500">Case</div>
                    <div class="text-sm font-semibold">{{ $taxInvoice->case->case_number ?? '-' }}</div>
                    <div class="text-xs text-gray-700">Invoice Date: {{ optional($taxInvoice->invoice_date)->format('d/m/Y') }}</div>
                    <div class="text-xs text-gray-700">Due Date: {{ optional($taxInvoice->due_date)->format('d/m/Y') }}</div>
                    <div class="text-xs text-gray-700">Status: {{ ucfirst($taxInvoice->status ?? 'draft') }}</div>
                </div>
            </div>

            <div class="overflow-x-auto border border-gray-200 rounded-sm">
                <table class="min-w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Description</th>
                            <th class="px-1 py-3 text-center text-xs font-medium text-gray-700 uppercase tracking-wider">Qty</th>
                            <th class="px-1 py-3 text-center text-xs font-medium text-gray-700 uppercase tracking-wider">UOM</th>
                            <th class="px-1 py-3 text-center text-xs font-medium text-gray-700 uppercase tracking-wider">Unit Price</th>
                            <th class="px-1 py-3 text-center text-xs font-medium text-gray-700 uppercase tracking-wider">Disc %</th>
                            <th class="px-1 py-3 text-center text-xs font-medium text-gray-700 uppercase tracking-wider">Tax %</th>
                            <th class="px-4 py-3 text-center text-xs font-medium text-gray-700 uppercase tracking-wider">Amount (RM)</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100">
                        @foreach($taxInvoice->items as $it)
                        <tr>
                            <td class="px-4 py-3 text-xs">{{ $it->description }}</td>
                            <td class="px-1 py-3 text-center text-xs">{{ number_format($it->qty, 2) }}</td>
                            <td class="px-1 py-3 text-center text-xs">{{ $it->uom }}</td>
                            <td class="px-1 py-3 text-center text-xs">{{ number_format($it->unit_price, 2) }}</td>
                            <td class="px-1 py-3 text-center text-xs">{{ number_format($it->discount_percent ?? 0, 2) }}</td>
                            <td class="px-1 py-3 text-center text-xs">{{ number_format($it->tax_percent ?? 0, 2) }}</td>
                            <td class="px-4 py-3 text-center text-xs">{{ number_format($it->amount, 2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="flex justify-end">
                <div class="w-full md:w-64 space-y-2">
                    <div class="flex justify-between text-sm">
                        <span class="font-medium text-gray-700">Subtotal:</span>
                        <span class="text-gray-900">RM {{ number_format($taxInvoice->subtotal ?? $taxInvoice->items->sum('amount'), 2) }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="font-medium text-gray-700">Total RM:</span>
                        <span class="text-gray-900 font-semibold">RM {{ number_format($taxInvoice->total ?? $taxInvoice->items->sum('amount'), 2) }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
