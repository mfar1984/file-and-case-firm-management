@extends('layouts.app')

@section('breadcrumb')
    <span class="text-gray-500">Quotation</span>
@endsection

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex justify-between items-center mb-6">
            <div class="flex items-center">
                <span class="material-icons mr-2 text-blue-600">visibility</span>
                <h1 class="text-lg md:text-xl font-bold text-gray-800 text-[14px]">Quotation {{ $quotation->quotation_no }}</h1>
            </div>
            <div class="flex items-center space-x-3">
                <a href="{{ route('quotation.print', $quotation->id) }}" target="_blank" class="flex items-center text-xs text-blue-600 hover:text-blue-800">
                    <span class="material-icons mr-1 text-sm">print</span>
                    Print
                </a>
                <a href="{{ route('quotation.index') }}" class="text-xs text-blue-600">Back</a>
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
                    <h1 class="text-2xl font-bold">Sales Quotation</h1>
                </div>
                
                <div class="grid grid-cols-2 gap-8 mt-6">
                    <div class="customer-info">
                        <p class="font-bold text-lg">{{ $quotation->customer_name ?? 'Customer Name' }}</p>
                        <p class="text-sm text-gray-600">{{ $quotation->customer_address ?? 'Customer Address' }}</p>
                        <p class="text-sm text-gray-600">Phone No.: {{ $quotation->customer_phone ?? 'Phone Number' }}</p>
                        <p class="text-sm text-gray-600">Fax No.: {{ $quotation->customer_fax ?? 'Fax Number' }}</p>
                        <p class="text-sm text-gray-600">Attn: {{ $quotation->customer_attn ?? 'Attention' }}</p>
                    </div>
                    <div class="quotation-meta text-right">
                        <p class="text-sm"><strong>No.:</strong> {{ $quotation->quotation_no }}</p>
                        <p class="text-sm"><strong>Payment Terms:</strong> {{ $quotation->payment_terms_display ?? 'CIA' }}</p>
                        <p class="text-sm"><strong>Date:</strong> {{ optional($quotation->quotation_date)->format('d/m/Y') ?? '27/08/2025' }}</p>
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
                            <th class="px-3 py-2 text-center text-sm w-24" style="font-weight: 900;">PRICE<br>(RM)</th>
                            <th class="px-3 py-2 text-center text-sm w-20" style="font-weight: 900;">DISC.</th>
                            <th class="px-3 py-2 text-center text-sm w-24" style="font-weight: 900;">AMOUNT<br>(RM)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $itemCounter = 1; // Counter for regular items only
                        @endphp
                        @forelse($quotation->items as $index => $item)
                            @if($item->item_type === 'title')
                                <!-- Title Row -->
                                <tr class="bg-orange-50">
                                    <td colspan="5" class="px-3 py-2 text-sm">
                                        <span class="text-sm font-medium text-orange-800">{{ $item->title_text }}</span>
                                    </td>
                                </tr>
                            @else
                                <!-- Regular Item Row -->
                                <tr>
                                    <td class="px-3 py-2 text-sm">{{ $itemCounter }}.</td>
                                    <td class="px-3 py-2 text-sm">{{ $item->description }}</td>
                                    <td class="px-3 py-2 text-sm text-right">{{ number_format($item->unit_price, 2) }}</td>
                                    <td class="px-3 py-2 text-sm text-right">{{ number_format($item->discount_amount ?? 0, 2) }}</td>
                                    <td class="px-3 py-2 text-sm text-right">{{ number_format($item->amount, 2) }}</td>
                                </tr>
                                @php
                                    $itemCounter++; // Increment counter only for regular items
                                @endphp
                            @endif
                        @empty
                            <tr>
                                <td class="px-3 py-2 text-sm">1.</td>
                                <td class="px-3 py-2 text-sm">Sample Item</td>
                                <td class="px-3 py-2 text-sm text-right">1.00</td>
                                <td class="px-3 py-2 text-sm">lot</td>
                                <td class="px-3 py-2 text-sm text-right">1,000.00</td>
                                <td class="px-3 py-2 text-sm text-right">0.00</td>
                                <td class="px-3 py-2 text-sm text-right">1,000.00</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Footer Section -->
            <div>
                <div class="grid grid-cols-2 gap-8">
                    <div class="amount-in-words">
                        <p class="font-normal" style="font-size: 12px; line-height: 1.2; text-indent: -80px; padding-left: 80px;">Ringgit Malaysia: {{ $quotation->total_words ?? 'Amount in Words' }}</p>
                        <p class="text-sm mt-4"><strong>Note:</strong> {{ $quotation->remark ?? '' }}</p>
                    </div>
                    <div class="summary-table">
                        <table class="w-full border-collapse">
                            <tr>
                                <td class="py-1 px-2 text-right font-bold text-sm">Subtotal</td>
                                <td class="py-1 px-2 border border-gray-800 text-right text-sm w-24">{{ number_format($quotation->subtotal ?? 0, 2) }}</td>
                            </tr>
                            <tr>
                                <td class="py-1 px-2 text-right font-bold text-sm">Tax</td>
                                <td class="py-1 px-2 border border-gray-800 text-right text-sm w-24">{{ number_format($quotation->tax_total ?? 0, 2) }}</td>
                            </tr>
                            <tr>
                                <td class="py-1 px-2 text-right font-bold text-sm">Total</td>
                                <td class="py-1 px-2 border border-gray-800 bg-gray-200 text-right font-bold text-sm w-24">{{ number_format($quotation->total ?? 0, 2) }}</td>
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


@endsection


