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
                        @if($preQuotation->customer_address)
                        <div class="mt-2">
                            <p class="text-sm text-gray-600 whitespace-pre-line">{{ $preQuotation->customer_address }}</p>
                        </div>
                        @endif
                        @if($preQuotation->customer_phone)
                        <p class="text-sm text-gray-600 mt-1">Phone: {{ $preQuotation->customer_phone }}</p>
                        @endif
                        @if($preQuotation->customer_email)
                        <p class="text-sm text-gray-600">Email: {{ $preQuotation->customer_email }}</p>
                        @endif
                    </div>
                    
                    <div class="quotation-info text-right">
                        <div class="mb-2">
                            <span class="text-sm text-gray-600">Pre-Quotation No:</span>
                            <span class="font-bold ml-2">{{ $preQuotation->quotation_no }}</span>
                        </div>
                        <div class="mb-2">
                            <span class="text-sm text-gray-600">Date:</span>
                            <span class="ml-2">{{ $preQuotation->quotation_date->format('d/m/Y') }}</span>
                        </div>
                        @if($preQuotation->valid_until)
                        <div class="mb-2">
                            <span class="text-sm text-gray-600">Valid Until:</span>
                            <span class="ml-2">{{ $preQuotation->valid_until->format('d/m/Y') }}</span>
                        </div>
                        @endif
                        @if($preQuotation->payment_terms)
                        <div class="mb-2">
                            <span class="text-sm text-gray-600">Payment Terms:</span>
                            <span class="ml-2">{{ $preQuotation->payment_terms }}</span>
                        </div>
                        @endif
                        <div class="mt-4">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium {{ $preQuotation->status_color }}">
                                <span class="material-icons text-xs mr-1">{{ $preQuotation->status_icon }}</span>
                                {{ $preQuotation->status_display }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Items Table -->
            <div class="mb-8">
                <table class="w-full border-collapse border border-gray-300">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="border border-gray-300 px-4 py-2 text-left text-sm font-bold">Description</th>
                            <th class="border border-gray-300 px-4 py-2 text-center text-sm font-bold w-16">Qty</th>
                            <th class="border border-gray-300 px-4 py-2 text-center text-sm font-bold w-16">UOM</th>
                            <th class="border border-gray-300 px-4 py-2 text-center text-sm font-bold w-24">Unit Price (RM)</th>
                            <th class="border border-gray-300 px-4 py-2 text-center text-sm font-bold w-20">Disc %</th>
                            <th class="border border-gray-300 px-4 py-2 text-center text-sm font-bold w-20">Tax %</th>
                            <th class="border border-gray-300 px-4 py-2 text-center text-sm font-bold w-24">Amount (RM)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($preQuotation->items as $item)
                        <tr>
                            <td class="border border-gray-300 px-4 py-2 text-sm">{{ $item->description ?: 'N/A' }}</td>
                            <td class="border border-gray-300 px-4 py-2 text-center text-sm">{{ number_format($item->qty, 2) }}</td>
                            <td class="border border-gray-300 px-4 py-2 text-center text-sm">{{ $item->uom }}</td>
                            <td class="border border-gray-300 px-4 py-2 text-center text-sm">{{ number_format($item->unit_price, 2) }}</td>
                            <td class="border border-gray-300 px-4 py-2 text-center text-sm">{{ number_format($item->discount_percent, 2) }}%</td>
                            <td class="border border-gray-300 px-4 py-2 text-center text-sm">{{ number_format($item->tax_percent, 2) }}%</td>
                            <td class="border border-gray-300 px-4 py-2 text-center text-sm">{{ number_format($item->amount, 2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Summary Section -->
            <div class="flex justify-end mb-8">
                <div class="w-1/3">
                    <table class="w-full">
                        <tr>
                            <td class="text-right text-sm py-1">Subtotal:</td>
                            <td class="text-right text-sm py-1 font-bold">RM {{ number_format($preQuotation->subtotal, 2) }}</td>
                        </tr>
                        <tr>
                            <td class="text-right text-sm py-1">Discount:</td>
                            <td class="text-right text-sm py-1 font-bold">RM {{ number_format($preQuotation->discount_total, 2) }}</td>
                        </tr>
                        <tr>
                            <td class="text-right text-sm py-1">Tax:</td>
                            <td class="text-right text-sm py-1 font-bold">RM {{ number_format($preQuotation->tax_total, 2) }}</td>
                        </tr>
                        <tr class="border-t border-gray-300">
                            <td class="text-right text-lg py-2 font-bold">Total:</td>
                            <td class="text-right text-lg py-2 font-bold">RM {{ number_format($preQuotation->total, 2) }}</td>
                        </tr>
                    </table>
                </div>
            </div>

            <!-- Remarks Section -->
            @if($preQuotation->remark)
            <div class="mb-8">
                <h3 class="text-sm font-bold mb-2">Remarks:</h3>
                <p class="text-sm text-gray-700 whitespace-pre-line">{{ $preQuotation->remark }}</p>
            </div>
            @endif

            <!-- Footer Section -->
            <div class="mt-12 text-center">
                <p class="text-sm text-gray-600">Thank you for your business!</p>
                <p class="text-xs text-gray-500 mt-2">This is a computer-generated pre-quotation and does not require a signature.</p>
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
