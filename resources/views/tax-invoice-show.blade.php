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
            <div class="flex items-center space-x-3">
                <a href="{{ route('tax-invoice.print', $taxInvoice->id) }}" target="_blank" class="flex items-center text-xs text-blue-600 hover:text-blue-800">
                    <span class="material-icons mr-1 text-sm">print</span>
                    Print
                </a>
                <a href="{{ route('tax-invoice.index') }}" class="text-xs text-blue-600 hover:underline">Back</a>
            </div>
        </div>
        <div class="p-4 md:p-6">
            <!-- Content -->
            <div>
                <!-- Header Section -->
                <div class="mb-8">
                    <div class="flex items-start">
                        <div class="w-24 h-24 mr-6">
                            <img src="{{ asset('images/logo.png') }}" alt="Company Logo" class="w-full h-full object-contain">
                        </div>
                        <div class="flex-1">
                            <h2 class="text-lg font-bold">Naeelah Saleh & Associates (LLP0012345)</h2>
                            <p class="text-sm text-gray-600">No. 123, Jalan Tun Razak, 50400 Kuala Lumpur, Malaysia</p>
                            <p class="text-sm text-gray-600">Phone No.: +603-1234-5678</p>
                            <p class="text-sm text-gray-600">Email: admin@naeelahsaleh.com.my</p>
                        </div>
                    </div>

                    <div class="text-center mt-6">
                        <h1 class="text-2xl font-bold">Tax Invoice</h1>
                    </div>

                    <div class="grid grid-cols-2 gap-8 mt-6">
                        <div class="customer-info">
                            <p class="font-bold text-lg">{{ $taxInvoice->customer_name ?? 'Customer Name' }}</p>
                            <p class="text-sm text-gray-600">{{ $taxInvoice->customer_address ?? 'Customer Address' }}</p>
                            <p class="text-sm text-gray-600">Phone No.: {{ $taxInvoice->customer_phone ?? 'Phone Number' }}</p>
                            <p class="text-sm text-gray-600">Email: {{ $taxInvoice->customer_email ?? 'Email' }}</p>
                        </div>
                        <div class="quotation-meta text-right">
                            <p class="text-sm"><strong>Invoice No.:</strong> {{ $taxInvoice->invoice_no }}</p>
                            <p class="text-sm"><strong>Case Ref:</strong> {{ $taxInvoice->case->case_number ?? 'N/A' }}</p>
                            <p class="text-sm"><strong>Issue Date:</strong> {{ optional($taxInvoice->invoice_date)->format('d/m/Y') ?? '04/09/2025' }}</p>
                            <p class="text-sm"><strong>Due Date:</strong> {{ optional($taxInvoice->due_date)->format('d/m/Y') ?? '04/10/2025' }}</p>
                            <p class="text-sm"><strong>Page:</strong> 1 of 1</p>
                        </div>
                    </div>
                </div>

                <!-- Items Table -->
                <div class="mb-8">
                    <table class="w-full border-collapse">
                        <thead>
                            <tr>
                                <th class="px-3 py-2 text-center text-sm w-12" style="font-weight: 900;">ITEM.</th>
                                <th class="px-3 py-2 text-center text-sm" style="font-weight: 900;">DESCRIPTION</th>
                                <th class="px-3 py-2 text-center text-sm w-20" style="font-weight: 900;">QTY</th>
                                <th class="px-3 py-2 text-center text-sm w-20" style="font-weight: 900;">UOM</th>
                                <th class="px-3 py-2 text-center text-sm w-24" style="font-weight: 900;">PRICE<br>(RM)</th>
                                <th class="px-3 py-2 text-center text-sm w-20" style="font-weight: 900;">DISC.</th>
                                <th class="px-3 py-2 text-center text-sm w-24" style="font-weight: 900;">AMOUNT<br>(RM)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($taxInvoice->items as $index => $item)
                                <tr>
                                    <td class="px-3 py-2 text-sm">{{ $index + 1 }}.</td>
                                    <td class="px-3 py-2 text-sm">{{ $item->description }}</td>
                                    <td class="px-3 py-2 text-sm text-right">{{ number_format($item->qty, 2) }}</td>
                                    <td class="px-3 py-2 text-sm">{{ $item->uom }}</td>
                                    <td class="px-3 py-2 text-sm text-right">{{ number_format($item->unit_price, 2) }}</td>
                                    <td class="px-3 py-2 text-sm text-right">{{ number_format($item->discount_percent ?? 0, 2) }}%</td>
                                    <td class="px-3 py-2 text-sm text-right">{{ number_format($item->amount, 2) }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-3 py-2 text-sm text-center">No items found</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Footer Section -->
                <div>
                    <div class="grid grid-cols-2 gap-8">
                        <div class="amount-in-words">
                            <p class="font-normal" style="font-size: 12px; line-height: 1.2; text-indent: -80px; padding-left: 80px;">Ringgit Malaysia: {{ $taxInvoice->total_words ?? 'Amount in Words' }}</p>
                            <p class="text-sm mt-4"><strong>Note:</strong> {{ $taxInvoice->remark ?? '' }}</p>
                        </div>
                        <div class="summary-table">
                            <table class="w-full border-collapse">
                                <tr>
                                    <td class="py-1 px-2 text-right font-bold text-sm">Subtotal</td>
                                    <td class="py-1 px-2 border border-gray-800 text-right text-sm w-24">{{ number_format($taxInvoice->subtotal ?? 0, 2) }}</td>
                                </tr>
                                <tr>
                                    <td class="py-1 px-2 text-right font-bold text-sm">Tax</td>
                                    <td class="py-1 px-2 border border-gray-800 text-right text-sm w-24">{{ number_format($taxInvoice->tax_total ?? 0, 2) }}</td>
                                </tr>
                                <tr>
                                    <td class="py-1 px-2 text-right font-bold text-sm">Total</td>
                                    <td class="py-1 px-2 border border-gray-800 bg-gray-200 text-right font-bold text-sm w-24">{{ number_format($taxInvoice->total ?? 0, 2) }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <div class="mt-8 pt-4 border-t border-gray-800 w-48">
                        <p class="text-sm font-bold">Authorised Signature</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
