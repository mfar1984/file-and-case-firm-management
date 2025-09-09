@extends('layouts.app')

@section('breadcrumb')
    <span class="text-gray-500">Pre-Quotation</span>
@endsection

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex justify-between items-center mb-6">
            <div class="flex items-center">
                <span class="material-icons mr-2 text-blue-600">visibility</span>
                <h1 class="text-lg md:text-xl font-bold text-gray-800 text-[14px]">Pre-Quotation {{ $preQuotation->quotation_no }}</h1>
            </div>
            <div class="flex items-center space-x-3">
                <a href="{{ route('pre-quotation.print', $preQuotation->id) }}" target="_blank" class="flex items-center text-xs text-blue-600 hover:text-blue-800">
                    <span class="material-icons mr-1 text-sm">print</span>
                    Print
                </a>
                <a href="{{ route('pre-quotation.edit', $preQuotation->id) }}" class="flex items-center text-xs text-green-600 hover:text-green-800">
                    <span class="material-icons mr-1 text-sm">edit</span>
                    Edit
                </a>

                <a href="{{ route('pre-quotation.index') }}" class="text-xs text-blue-600">Back</a>
            </div>
        </div>

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
                    <h1 class="text-2xl font-bold">Pre-Quotation</h1>
                </div>

                <div class="grid grid-cols-2 gap-8 mt-6">
                    <div class="customer-info">
                        <p class="font-bold text-lg">{{ $preQuotation->full_name ?? 'Customer Name' }}</p>
                        <p class="text-sm text-gray-600">{{ $preQuotation->customer_address ?? 'Customer Address' }}</p>
                        <p class="text-sm text-gray-600">Phone No.: {{ $preQuotation->customer_phone ?? 'Phone Number' }}</p>
                        <p class="text-sm text-gray-600">Email: {{ $preQuotation->customer_email ?? 'Email' }}</p>
                    </div>
                    <div class="quotation-meta text-right">
                        <p class="text-sm"><strong>No.:</strong> {{ $preQuotation->quotation_no }}</p>
                        <p class="text-sm"><strong>Payment Terms:</strong> {{ $preQuotation->payment_terms ?? 'Net 30 days' }}</p>
                        <p class="text-sm"><strong>Date:</strong> {{ optional($preQuotation->quotation_date)->format('d/m/Y') ?? '04/09/2025' }}</p>
                        @if($preQuotation->valid_until)
                        <p class="text-sm"><strong>Valid Until:</strong> {{ $preQuotation->valid_until->format('d/m/Y') }}</p>
                        @endif
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
                        @forelse($preQuotation->items as $index => $item)
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
                        <p class="font-normal" style="font-size: 12px; line-height: 1.2; text-indent: -80px; padding-left: 80px;">Ringgit Malaysia: {{ $preQuotation->total_words ?? 'Amount in Words' }}</p>
                        <p class="text-sm mt-4"><strong>Note:</strong> {{ $preQuotation->remark ?? '' }}</p>
                    </div>
                    <div class="summary-table">
                        <table class="w-full border-collapse">
                            <tr>
                                <td class="py-1 px-2 text-right font-bold text-sm">Subtotal</td>
                                <td class="py-1 px-2 border border-gray-800 text-right text-sm w-24">{{ number_format($preQuotation->subtotal ?? 0, 2) }}</td>
                            </tr>
                            <tr>
                                <td class="py-1 px-2 text-right font-bold text-sm">Discount</td>
                                <td class="py-1 px-2 border border-gray-800 text-right text-sm w-24">{{ number_format($preQuotation->discount_total ?? 0, 2) }}</td>
                            </tr>
                            <tr>
                                <td class="py-1 px-2 text-right font-bold text-sm">Tax</td>
                                <td class="py-1 px-2 border border-gray-800 text-right text-sm w-24">{{ number_format($preQuotation->tax_total ?? 0, 2) }}</td>
                            </tr>
                            <tr>
                                <td class="py-1 px-2 text-right font-bold text-sm">Total</td>
                                <td class="py-1 px-2 border border-gray-800 bg-gray-200 text-right font-bold text-sm w-24">{{ number_format($preQuotation->total ?? 0, 2) }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
                <div class="mt-8 pt-4 border-t border-gray-800 w-48">
                    <p class="text-sm font-bold">Authorised Signature</p>
                </div>
            </div>
        </div>

        <!-- Action Buttons (not printed) -->
        <div class="mt-8 flex space-x-2 print:hidden">
            <a href="{{ route('pre-quotation.edit', $preQuotation->id) }}" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-sm text-xs font-medium flex items-center">
                <span class="material-icons text-xs mr-1">edit</span>
                Edit Pre-Quotation
            </a>
            <form action="{{ route('pre-quotation.destroy', $preQuotation->id) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this pre-quotation?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-sm text-xs font-medium flex items-center">
                    <span class="material-icons text-xs mr-1">delete</span>
                    Delete
                </button>
            </form>
        </div>
    </div>
</div>


@endsection
