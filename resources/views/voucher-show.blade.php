@extends('layouts.app')

@section('breadcrumb')
    <span class="text-gray-500">Voucher</span>
@endsection

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex justify-between items-center mb-6">
            <div class="flex items-center">
                <span class="material-icons mr-2 text-blue-600">visibility</span>
                <h1 class="text-lg md:text-xl font-bold text-gray-800 text-[14px]">Voucher {{ $voucher->voucher_no }}</h1>
            </div>
            <div class="flex items-center space-x-3">
                <a href="{{ route('voucher.print', $voucher->id) }}" target="_blank" class="flex items-center text-xs text-blue-600 hover:text-blue-800">
                    <span class="material-icons mr-1 text-sm">print</span>
                    Print
                </a>
                <a href="{{ route('voucher.index') }}" class="text-xs text-blue-600">Back</a>
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
                    <h1 class="text-2xl font-bold">Payment Voucher</h1>
                </div>

                <div class="grid grid-cols-2 gap-8 mt-6">
                    <div class="payee-info">
                        <p class="font-bold text-lg">{{ $voucher->payee_name }}</p>
                        @if($voucher->payee_address)
                        <p class="text-sm text-gray-600">{{ $voucher->payee_address }}</p>
                        @endif
                        @if($voucher->contact_person)
                        <p class="text-sm text-gray-600">Contact Person: {{ $voucher->contact_person }}</p>
                        @endif
                        @if($voucher->phone)
                        <p class="text-sm text-gray-600">Phone No.: {{ $voucher->phone }}</p>
                        @endif
                        @if($voucher->email)
                        <p class="text-sm text-gray-600">Email: {{ $voucher->email }}</p>
                        @endif
                    </div>
                    <div class="voucher-meta text-right">
                        <p class="text-sm"><strong>No.:</strong> {{ $voucher->voucher_no }}</p>
                        <p class="text-sm"><strong>Payment Date:</strong> {{ $voucher->payment_date->format('d/m/Y') }}</p>
                        <p class="text-sm"><strong>Payment Method:</strong> {{ $voucher->payment_method_display }}</p>
                        <p class="text-sm"><strong>Approved By:</strong> {{ $voucher->approved_by }}</p>
                        <p class="text-sm"><strong>Status:</strong>
                            <span class="inline-block {{ $voucher->status_color }} px-2 py-1 rounded-full text-xs">{{ $voucher->status_display }}</span>
                        </p>
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
                            <th class="px-3 py-2 text-center text-sm w-20" style="font-weight: 900;">TAX</th>
                            <th class="px-3 py-2 text-center text-sm w-24" style="font-weight: 900;">AMOUNT<br>(RM)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($voucher->items as $index => $item)
                        <tr>
                            <td class="px-3 py-2 text-center text-sm">{{ $index + 1 }}</td>
                            <td class="px-3 py-2 text-left text-sm">
                                {{ $item->description }}
                                @if($item->category)
                                <br><span class="text-gray-500 text-xs">{{ $item->category }}</span>
                                @endif
                            </td>
                            <td class="px-3 py-2 text-center text-sm">{{ number_format($item->qty, 2) }}</td>
                            <td class="px-3 py-2 text-center text-sm">{{ $item->uom }}</td>
                            <td class="px-3 py-2 text-right text-sm">{{ number_format($item->unit_price, 2) }}</td>
                            <td class="px-3 py-2 text-center text-sm">{{ number_format($item->discount_percent, 1) }}%</td>
                            <td class="px-3 py-2 text-center text-sm">{{ number_format($item->tax_percent, 1) }}%</td>
                            <td class="px-3 py-2 text-right text-sm">{{ number_format($item->amount, 2) }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="px-3 py-2 text-center text-sm text-gray-500">No items found</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Footer Section -->
            <div>
                <div class="grid grid-cols-2 gap-8">
                    <div class="amount-in-words">
                        <p class="font-normal" style="font-size: 12px; line-height: 1.2; text-indent: -80px; padding-left: 80px;">Ringgit Malaysia: {{ $voucher->total_words ?? 'Amount in Words' }}</p>
                        @if($voucher->remark)
                        <p class="text-sm mt-4"><strong>Note:</strong> {{ $voucher->remark }}</p>
                        @endif
                    </div>
                    <div class="summary-table">
                        <table class="w-full border-collapse">
                            <tr>
                                <td class="py-1 px-2 text-right font-bold text-sm">Subtotal</td>
                                <td class="py-1 px-2 border border-gray-800 text-right text-sm w-24">{{ number_format($voucher->subtotal ?? 0, 2) }}</td>
                            </tr>
                            @if($voucher->tax_total > 0)
                            <tr>
                                <td class="py-1 px-2 text-right font-bold text-sm">Tax</td>
                                <td class="py-1 px-2 border border-gray-800 text-right text-sm w-24">{{ number_format($voucher->tax_total ?? 0, 2) }}</td>
                            </tr>
                            @endif
                            <tr>
                                <td class="py-1 px-2 text-right font-bold text-sm">Total</td>
                                <td class="py-1 px-2 border border-gray-800 text-right text-sm w-24 bg-gray-200">{{ number_format($voucher->total_amount ?? 0, 2) }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Signature Section -->
            <div class="mt-12 pt-8 border-t border-gray-300">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <div class="text-center">
                        <div class="text-xs text-gray-700">
                            <div class="mb-8"></div>
                            <div class="border-t border-gray-400 inline-block w-32 pt-2">
                                Prepared By
                            </div>
                        </div>
                    </div>
                    <div class="text-center">
                        <div class="text-xs text-gray-700">
                            <div class="mb-8"></div>
                            <div class="border-t border-gray-400 inline-block w-32 pt-2">
                                Checked By
                            </div>
                        </div>
                    </div>
                    <div class="text-center">
                        <div class="text-xs text-gray-700">
                            <div class="mb-8"></div>
                            <div class="border-t border-gray-400 inline-block w-32 pt-2">
                                Approved By
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
