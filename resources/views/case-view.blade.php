@extends('layouts.app')

@section('breadcrumb')
    <span class="text-gray-500">Case Details</span>
@endsection

@section('content')
<div class="px-4 md:px-6 pt-4 md:pt-6 pb-6 max-w-7xl mx-auto">
    <div class="bg-white rounded shadow-md border border-gray-300">
        <!-- Header -->
        <div class="p-4 md:p-6 border-b border-gray-200">
            <div class="flex justify-between items-start">
                <div>
                    <div class="flex items-center">
                        <span class="material-icons mr-2 text-purple-600">folder</span>
                        <h1 class="text-lg md:text-xl font-bold text-gray-800">Case Details</h1>
                    </div>
                    <p class="text-xs text-gray-500 mt-1 ml-8">Case Reference: {{ $case->case_number ?? 'N/A' }}</p>
                </div>
                <div class="flex space-x-2">
                    <a href="{{ route('case.edit', $case->id) }}" class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded-sm text-xs font-medium flex items-center" style="border-radius: 2px !important;">
                        <span class="material-icons text-xs mr-1">edit</span>
                        Edit Case
                    </a>
                    <button class="bg-gray-600 hover:bg-gray-700 text-white px-3 py-1 rounded-sm text-xs font-medium flex items-center" style="border-radius: 2px !important;">
                        <span class="material-icons text-xs mr-1">print</span>
                        Print
                    </button>
                    <a href="{{ route('case.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-3 py-1 rounded-sm text-xs font-medium flex items-center" style="border-radius: 2px !important;">
                        <span class="material-icons text-xs mr-1">arrow_back</span>
                        Back to List
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Body -->
        <div class="p-4 md:p-6">
            <!-- Case Information -->
            <div class="bg-gray-50 p-4 rounded-sm mb-6" style="border-radius: 2px !important;">
                <div class="p-2 border-b border-gray-200 bg-gradient-to-r from-blue-600 to-blue-700 text-white -mx-4 -mt-4">
                    <div class="flex items-center">
                        <span class="material-icons mr-2 text-xs">description</span>
                        <h2 class="text-sm font-semibold">Case Information</h2>
                    </div>
                </div>
                <div class="p-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Case Reference</label>
                            <p class="text-[11px] text-gray-900 font-medium">{{ $case->case_number ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Case Title</label>
                            <p class="text-[11px] text-gray-900">{{ $case->title ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Case Type</label>
                            <p class="text-[11px] text-gray-900">{{ $case->caseType->description ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Status</label>
                            @if($case->caseStatus)
                                <span class="inline-block bg-green-100 text-green-800 px-2 py-1 rounded-sm text-[11px] font-medium" style="border-radius: 2px !important;">
                                    {{ $case->caseStatus->name }}
                                </span>
                            @else
                                <span class="inline-block bg-gray-100 text-gray-800 px-2 py-1 rounded-sm text-[11px] font-medium" style="border-radius: 2px !important;">
                                    Unknown
                                </span>
                            @endif
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Priority Level</label>
                            <p class="text-[11px] text-gray-900 capitalize">{{ $case->priority_level ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Judge Name</label>
                            <p class="text-[11px] text-gray-900">{{ $case->judge_name ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Court</label>
                            <p class="text-[11px] text-gray-900">{{ $case->court_location ?? 'N/A' }}</p>
                        </div>
                        @php
                            $caseTypeDescription = strtolower($case->caseType->description ?? '');
                            $isConveyancing = strtolower($case->section ?? '') === 'conveyancing';
                            $isProbate = strtolower($case->section ?? '') === 'probate';
                        @endphp
                        @if($isConveyancing && $case->name_of_property)
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Name of Property</label>
                            <p class="text-[11px] text-gray-900 font-medium text-blue-600">{{ $case->name_of_property }}</p>
                        </div>
                        @endif
                        @if($caseTypeDescription !== 'criminal')
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">{{ $isConveyancing ? 'Purchase Price' : 'Claim Amount' }}</label>
                            <p class="text-[11px] text-gray-900 font-medium text-green-600">
                                {{ $case->claim_amount ? 'RM ' . number_format($case->claim_amount, 2) : 'N/A' }}
                            </p>
                        </div>
                        @endif
                        @if($isProbate && $case->others_document)
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Others Document</label>
                            <p class="text-[11px] text-gray-900">{{ $case->others_document }}</p>
                        </div>
                        @endif
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Person In Charge</label>
                            <p class="text-[11px] text-gray-900">{{ $case->person_in_charge ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Created By</label>
                            <p class="text-[11px] text-gray-900">{{ $case->createdBy->name ?? 'N/A' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Applicant Information -->
            <div class="bg-blue-50 p-4 rounded-sm mb-6" style="border-radius: 2px !important;">
                <div class="p-2 border-b border-gray-200 bg-gradient-to-r from-blue-600 to-blue-700 text-white -mx-4 -mt-4">
                    <div class="flex items-center">
                        <span class="material-icons mr-2 text-xs">person</span>
                        <h2 class="text-sm font-semibold">Applicant Information</h2>
                    </div>
                </div>
                <div class="px-0 py-4">
                    @php
                        $applicants = $case->parties->where('party_type', 'plaintiff');
                    @endphp
                    @if($applicants->count() > 0)
                        <div class="-mx-4 overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-blue-50">
                                    <tr>
                                        <th class="pl-7 pr-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Full Name</th>
                                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">IC/Passport</th>
                                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Phone</th>
                                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Gender</th>
                                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nationality</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($applicants as $applicant)
                                        <tr class="hover:bg-grey-50">
                                            <td class="pl-7 pr-3 py-2 text-[11px] text-gray-900 font-medium">{{ $applicant->name }}</td>
                                            <td class="px-3 py-2 text-[11px] text-gray-900">{{ $applicant->ic_passport ?? 'N/A' }}</td>
                                            <td class="px-3 py-2 text-[11px] text-gray-900">{{ $applicant->phone ?? 'N/A' }}</td>
                                            <td class="px-3 py-2 text-[11px] text-gray-900">{{ $applicant->email ?? 'N/A' }}</td>
                                            <td class="px-3 py-2 text-[11px] text-gray-900">{{ ucfirst($applicant->gender ?? 'N/A') }}</td>
                                            <td class="px-3 py-2 text-[11px] text-gray-900">{{ $applicant->nationality ?? 'N/A' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-6">
                            <span class="material-icons text-gray-400 text-3xl mb-2">person_outline</span>
                            <p class="text-xs text-gray-500">No applicant information available.</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Respondent Information -->
            <div class="bg-blue-50 p-4 rounded-sm mb-6" style="border-radius: 2px !important;">
                <div class="p-2 border-b border-gray-200 bg-gradient-to-r from-blue-600 to-blue-700 text-white -mx-4 -mt-4">
                    <div class="flex items-center">
                        <span class="material-icons mr-2 text-xs">person_remove</span>
                        <h2 class="text-sm font-semibold">Respondent Information</h2>
                    </div>
                </div>
                <div class="px-0 py-4">
                    @php
                        $respondents = $case->parties->where('party_type', 'defendant');
                    @endphp
                    @if($respondents->count() > 0)
                        <div class="-mx-4 overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-blue-50">
                                    <tr>
                                        <th class="pl-7 pr-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Full Name</th>
                                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">IC/Passport</th>
                                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Phone</th>
                                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Gender</th>
                                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nationality</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($respondents as $respondent)
                                        <tr class="hover:bg-grey-50">
                                            <td class="pl-7 pr-3 py-2 text-[11px] text-gray-900 font-medium">{{ $respondent->name }}</td>
                                            <td class="px-3 py-2 text-[11px] text-gray-900">{{ $respondent->ic_passport ?? 'N/A' }}</td>
                                            <td class="px-3 py-2 text-[11px] text-gray-900">{{ $respondent->phone ?? 'N/A' }}</td>
                                            <td class="px-3 py-2 text-[11px] text-gray-900">{{ $respondent->email ?? 'N/A' }}</td>
                                            <td class="px-3 py-2 text-[11px] text-gray-900">{{ ucfirst($respondent->gender ?? 'N/A') }}</td>
                                            <td class="px-3 py-2 text-[11px] text-gray-900">{{ $respondent->nationality ?? 'N/A' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-6">
                            <span class="material-icons text-gray-400 text-3xl mb-2">person_remove_outlined</span>
                            <p class="text-xs text-gray-500">No respondents assigned to this case.</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Financial Information -->
            <div class="bg-blue-50 p-4 rounded-sm mb-6">
                <div class="p-2 border-b border-gray-200 bg-gradient-to-r from-blue-600 to-blue-700 text-white -mx-4 -mt-4">
                    <div class="flex justify-between items-center">
                        <div class="flex items-center">
                            <span class="material-icons mr-2 text-xs">account_balance_wallet</span>
                            <h2 class="text-sm font-semibold">Financial Information</h2>
                        </div>
                        <div class="flex items-center space-x-2">
                            <a href="{{ route('quotation.create', ['case_id' => $case->id, 'case_number' => $case->case_number]) }}" class="bg-white text-blue-700 px-3 py-1 rounded-sm text-[11px] font-medium hover:bg-gray-50" style="border-radius: 2px !important;">
                                Create Quotation
                            </a>
                            <a href="{{ route('tax-invoice.create', ['case_id' => $case->id, 'case_number' => $case->case_number]) }}" class="bg-white text-blue-700 px-3 py-1 rounded-sm text-[11px] font-medium hover:bg-gray-50" style="border-radius: 2px !important;">
                                Create Invoice
                            </a>
                            <a href="{{ route('receipt.create', ['case_id' => $case->id, 'case_number' => $case->case_number]) }}" class="bg-white text-emerald-700 px-3 py-1 rounded-sm text-[11px] font-medium hover:bg-gray-50" style="border-radius: 2px !important;">
                                Create Receipt
                            </a>
                        </div>
                    </div>
                </div>
                @php
                    // Real data: sum tax invoices for this case
                    $totalInvoiced = \App\Models\TaxInvoice::where('case_id', $case->id)->sum('total');
                    
                    // Sum receipts for this case
                    $amountPaid = \App\Models\Receipt::where('case_id', $case->id)->sum('amount_paid');
                    
                    // Calculate balance and payment status
                    $balance = max($totalInvoiced - $amountPaid, 0);
                    $percent = $totalInvoiced > 0 ? round(($amountPaid / $totalInvoiced) * 100) : 0;
                    
                    // Payment status logic
                    if ($totalInvoiced == 0) {
                        $status = 'No Invoice';
                        $statusClassBg = 'bg-gray-100 text-gray-800';
                    } elseif ($percent === 0) {
                        $status = 'Unpaid';
                        $statusClassBg = 'bg-red-100 text-red-800';
                    } elseif ($percent < 100) {
                        $status = 'Partially Paid';
                        $statusClassBg = 'bg-yellow-100 text-yellow-800';
                    } else {
                        $status = 'Fully Paid';
                        $statusClassBg = 'bg-green-100 text-green-800';
                    }
                    
                    $balanceTextClass = $balance > 0 ? 'text-red-600' : 'text-green-600';
                @endphp
                <div class="p-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Total Amount Invoice</label>
                            <p class="text-[11px] text-gray-900 font-medium">{{ $totalInvoiced > 0 ? 'RM ' . number_format($totalInvoiced, 2) : 'N/A' }}</p>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Amount Paid</label>
                            <p class="text-[11px] text-gray-900">{{ 'RM ' . number_format($amountPaid, 2) }}</p>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Balance</label>
                            <p class="text-[11px] text-gray-900 font-medium {{ $balanceTextClass }}">{{ 'RM ' . number_format($balance, 2) }}</p>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Payment Status</label>
                            <span class="inline-block px-2 py-1 rounded-full text-[11px] font-medium {{ $statusClassBg }}">{{ $status }}</span>
                        </div>
                    </div>
                    <div class="mt-6 pt-4 border-t border-gray-200">
                        <div class="flex justify-between items-center mb-2">
                            <span class="text-xs font-medium text-gray-700">Payment Progress</span>
                            <span class="text-xs text-gray-500">{{ $percent }}%</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-emerald-600 h-2 rounded-full" style="width: {{ $percent }}%"></div>
                        </div>
                    </div>

                    <!-- Related Financial Records -->
                    @php
                        $quotations = \App\Models\Quotation::where('case_id', $case->id)->orderBy('created_at', 'desc')->get();
                        $taxInvoices = \App\Models\TaxInvoice::where('case_id', $case->id)->orderBy('created_at', 'desc')->get();
                        $receipts = \App\Models\Receipt::where('case_id', $case->id)->orderBy('created_at', 'desc')->get();
                    @endphp
                    <div class="mt-6 pt-4 border-t border-gray-200">
                        <h3 class="text-xs font-semibold text-gray-800 mb-3 flex items-center">
                            <span class="material-icons text-emerald-600 text-base mr-2">list_alt</span>
                            Related Financial Records
                        </h3>
                        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
                            <!-- Quotations -->
                            <div class="bg-white border border-gray-200 rounded-sm">
                                <div class="px-3 py-2 bg-gray-50 border-b border-gray-200 flex items-center justify-between">
                                    <span class="text-[11px] font-medium text-gray-700">Quotations ({{ $quotations->count() }})</span>
                                    <a href="{{ route('quotation.index') }}" class="text-[10px] text-blue-600">View all</a>
                                </div>
                                <div class="overflow-x-auto">
                                    <table class="min-w-full">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th class="px-3 py-2 text-left text-[10px] text-gray-600">Ref</th>
                                                <th class="px-3 py-2 text-left text-[10px] text-gray-600">Date</th>
                                                <th class="px-3 py-2 text-left text-[10px] text-gray-600">Status</th>
                                                <th class="px-3 py-2 text-right text-[10px] text-gray-600">Total (RM)</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-gray-100">
                                            @forelse($quotations as $q)
                                            <tr>
                                                <td class="px-3 py-2 text-[11px]"><a href="{{ route('quotation.show', $q->id) }}" class="text-blue-600 hover:underline">{{ $q->quotation_no }}</a></td>
                                                <td class="px-3 py-2 text-[11px]">{{ optional($q->quotation_date)->format('d/m/Y') }}</td>
                                                <td class="px-3 py-2 text-[11px]">{{ $q->status_display ?? ucfirst($q->status) }}</td>
                                                <td class="px-3 py-2 text-[11px] text-right">{{ number_format($q->total ?? 0, 2) }}</td>
                                            </tr>
                                            @empty
                                            <tr>
                                                <td class="px-3 py-3 text-[11px] text-gray-500" colspan="4">No quotations</td>
                                            </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- Tax Invoices -->
                            <div class="bg-white border border-gray-200 rounded-sm">
                                <div class="px-3 py-2 bg-gray-50 border-b border-gray-200 flex items-center justify-between">
                                    <span class="text-[11px] font-medium text-gray-700">Tax Invoices ({{ $taxInvoices->count() }})</span>
                                    <a href="{{ route('tax-invoice.index') }}" class="text-[10px] text-blue-600">View all</a>
                                </div>
                                <div class="overflow-x-auto">
                                    <table class="min-w-full">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th class="px-3 py-2 text-left text-[10px] text-gray-600">Ref</th>
                                                <th class="px-3 py-2 text-left text-[10px] text-gray-600">Date</th>
                                                <th class="px-3 py-2 text-left text-[10px] text-gray-600">Status</th>
                                                <th class="px-3 py-2 text-right text-[10px] text-gray-600">Total (RM)</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-gray-100">
                                            @forelse($taxInvoices as $inv)
                                            <tr>
                                                <td class="px-3 py-2 text-[11px]"><a href="{{ route('tax-invoice.show', $inv->id) }}" class="text-blue-600 hover:underline">{{ $inv->invoice_no }}</a></td>
                                                <td class="px-3 py-2 text-[11px]">{{ optional($inv->invoice_date)->format('d/m/Y') }}</td>
                                                <td class="px-3 py-2 text-[11px]">{{ $inv->status_display ?? ucfirst($inv->status) }}</td>
                                                <td class="px-3 py-2 text-[11px] text-right">{{ number_format($inv->total ?? 0, 2) }}</td>
                                            </tr>
                                            @empty
                                            <tr>
                                                <td class="px-3 py-3 text-[11px] text-gray-500" colspan="4">No tax invoices</td>
                                            </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- Receipts -->
                            <div class="bg-white border border-gray-200 rounded-sm">
                                <div class="px-3 py-2 bg-gray-50 border-b border-gray-200 flex items-center justify-between">
                                    <span class="text-[11px] font-medium text-gray-700">Receipts ({{ $receipts->count() }})</span>
                                    <a href="{{ route('receipt.index') }}" class="text-[10px] text-blue-600">View all</a>
                                </div>
                                <div class="overflow-x-auto">
                                    <table class="min-w-full">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th class="px-3 py-2 text-left text-[10px] text-gray-600">Ref</th>
                                                <th class="px-3 py-2 text-left text-[10px] text-gray-600">Date</th>
                                                <th class="px-3 py-2 text-left text-[10px] text-gray-600">Method</th>
                                                <th class="px-3 py-2 text-right text-[10px] text-gray-600">Amount (RM)</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-gray-100">
                                            @forelse($receipts as $r)
                                            <tr>
                                                <td class="px-3 py-2 text-[11px]"><a href="{{ route('receipt.show', $r->id) }}" class="text-blue-600 hover:underline">{{ $r->receipt_no }}</a></td>
                                                <td class="px-3 py-2 text-[11px]">{{ optional($r->receipt_date)->format('d/m/Y') }}</td>
                                                <td class="px-3 py-2 text-[11px]">{{ $r->payment_method_display }}</td>
                                                <td class="px-3 py-2 text-[11px] text-right">{{ number_format($r->amount_paid ?? 0, 2) }}</td>
                                            </tr>
                                            @empty
                                            <tr>
                                                <td class="px-3 py-3 text-[11px] text-gray-500" colspan="4">No receipts</td>
                                            </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Case Timeline -->
            <div class="bg-blue-50 p-4 rounded-sm mb-6">
                <div class="p-2 border-b border-gray-200 bg-gradient-to-r from-blue-600 to-blue-700 text-white -mx-4 -mt-4">
                    <div class="flex justify-between items-center">
                        <div class="flex items-center">
                            <span class="material-icons mr-2 text-xs">timeline</span>
                            <h2 class="text-sm font-semibold">Case Timeline</h2>
                        </div>
                        <button @click="$dispatch('open-modal', 'add-timeline')" class="bg-white text-blue-600 px-3 py-1 rounded-md text-xs font-medium flex items-center hover:bg-gray-50 transition-colors">
                            <span class="material-icons text-xs mr-1">add</span>
                            Add Event
                        </button>
                    </div>
                </div>
                <div class="p-4">
                    @php
                        $systemSettings = \App\Models\SystemSetting::getSystemSettings();
                    @endphp
                    @if($case->timeline->count() > 0)
                        <!-- Vertical Timeline -->
                        <div class="relative">
                            <!-- Timeline Line -->
                            <div class="absolute left-56 top-0 bottom-0 w-0.5 bg-gray-300"></div>
                            
                            <!-- Timeline Events -->
                            <div class="space-y-6">
                                @foreach($case->timeline as $event)
                                    <div class="relative flex items-start">
                                        <!-- Timeline Marker (Left) -->
                                        <div class="absolute left-56 transform -translate-x-1/2">
                                            <div class="w-5 h-5 {{ $event->getStatusColor() }} rounded-sm border-2 border-white shadow-sm flex items-center justify-center" style="border-radius: 2px !important;">
                                                <span class="material-icons text-white text-sm">{{ $event->getStatusIcon() }}</span>
                                            </div>
                                        </div>
                                        
                                        <!-- Date & Time (Left of Marker with Better Spacing) -->
                                        <div class="w-48 pr-30 text-left">
                                            <div class="text-xs text-gray-700 font-medium leading-tight">
                                                {{ $event->event_date->format($systemSettings->date_format ?? 'l, j F Y') }}
                                            </div>
                                            <div class="text-xs text-gray-500 mt-1 leading-tight">
                                                {{ $event->event_date->format($systemSettings->time_format ?? 'g:i:s A') }}
                                            </div>
                                        </div>
                                        
                                        <!-- Event Details (Right with Better Spacing) -->
                                        <div class="ml-8 flex-1 pl-4">
                                            <div class="flex justify-between items-start mb-2">
                                                <h3 class="text-xs font-medium text-gray-900">{{ $event->title }}</h3>
                                                <div class="flex space-x-1">
                                                    <button @click="console.log('Edit button clicked for event {{ $event->id }}'); $dispatch('open-modal', 'edit-timeline-{{ $event->id }}')" class="text-gray-400 hover:text-blue-600 transition-colors" title="Edit Event">
                                                        <span class="material-icons text-sm">edit</span>
                                                    </button>
                                                    <button @click="deleteTimelineEvent({{ $event->id }})" class="text-gray-400 hover:text-red-600 transition-colors" title="Delete Event">
                                                        <span class="material-icons text-sm">delete</span>
                                                    </button>
                                                </div>
                                            </div>
                                            <p class="text-xs text-gray-600 leading-relaxed mb-3">
                                                {{ $event->description }}
                                            </p>
                                            
                                            <!-- Interactive Elements for Active Events - Removed as not needed -->
                                            
                                            <!-- Event Metadata -->
                                            @if($event->metadata && count($event->metadata) > 0)
                                                <div class="mt-3 text-xs text-gray-500">
                                                    @foreach($event->metadata as $key => $value)
                                                        @if(is_string($value) || is_numeric($value))
                                                            <span class="inline-block bg-gray-100 px-2 py-1 rounded mr-2 mb-1">
                                                                {{ ucfirst(str_replace('_', ' ', $key)) }}: {{ $value }}
                                                            </span>
                                                        @endif
                                                    @endforeach
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @else
                        <!-- No Timeline Events -->
                        <div class="text-center py-8">
                            <span class="material-icons text-gray-400 text-4xl mb-2">timeline</span>
                            <p class="text-xs text-gray-500">No timeline events found for this case.</p>
                            <p class="text-xs text-gray-400 mt-1">Timeline events will appear here as the case progresses.</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Documents -->
            <div class="bg-blue-50 p-4 rounded-sm mb-6">
                <div class="p-2 border-b border-gray-200 bg-gradient-to-r from-blue-600 to-blue-700 text-white -mx-4 -mt-4">
                    <div class="flex justify-between items-center">
                        <div class="flex items-center">
                            <span class="material-icons mr-2 text-xs">folder</span>
                            <h2 class="text-sm font-semibold">Documents</h2>
                        </div>
                        <button @click="$dispatch('open-modal', 'file-management')" class="bg-white text-blue-600 px-3 py-1 rounded-md text-xs font-medium flex items-center hover:bg-gray-50 transition-colors">
                            <span class="material-icons text-xs mr-1">cloud_upload</span>
                            Upload File
                        </button>
                    </div>
                </div>
                <div class="p-4">
                    @if($case->files->count() > 0)
                        <div class="space-y-3">
                            @foreach($case->files as $file)
                            <div class="flex items-center justify-between p-3 bg-white rounded-lg border border-gray-200">
                                <div class="flex items-center space-x-3">
                                    <span class="material-icons text-red-500 text-xs">picture_as_pdf</span>
                                    <div>
                                        <p class="text-[11px] font-medium text-gray-900">{{ $file->file_name ?? 'Document' }}</p>
                                        <p class="text-[11px] text-gray-500">{{ $file->fileType->description ?? 'Document' }} • Uploaded {{ $file->created_at->format('d M Y') }}</p>
                                    </div>
                                </div>
                                <div class="flex space-x-2">
                                    <a href="{{ route('file-management.view', hash('sha256', $file->id . '|' . $file->file_path . '|' . config('app.key'))) }}" target="_blank" class="p-1 bg-blue-50 rounded-sm hover:bg-blue-100 border border-blue-100" title="View" style="border-radius: 2px !important;">
                                        <span class="material-icons text-blue-600 text-xs">visibility</span>
                                    </a>
                                    <a href="{{ route('file-management.download', $file->id) }}" class="p-1 bg-green-50 rounded-sm hover:bg-green-100 border border-green-100" title="Download" style="border-radius: 2px !important;">
                                        <span class="material-icons text-green-600 text-xs">download</span>
                                    </a>
                                    <form action="{{ route('file-management.destroy', $file->id) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this file?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1 bg-red-50 rounded-sm hover:bg-red-100 border border-red-100" title="Delete" style="border-radius: 2px !important;">
                                            <span class="material-icons text-red-600 text-xs">delete</span>
                                        </button>
                                    </form>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-6">
                            <span class="material-icons text-gray-400 text-3xl mb-2">folder_open</span>
                            <p class="text-xs text-gray-500">No documents uploaded for this case.</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-blue-50 p-4 rounded-sm mb-6">
                <div class="p-2 border-b border-gray-200 bg-gradient-to-r from-blue-600 to-blue-700 text-white -mx-4 -mt-4">
                    <div class="flex items-center">
                        <span class="material-icons mr-2 text-xs">flash_on</span>
                        <h2 class="text-sm font-semibold">Quick Actions</h2>
                    </div>
                </div>
                <div class="p-4">
                    <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
                        <a href="{{ route('tax-invoice.create') }}?case_id={{ $case->id }}" class="flex flex-col items-center p-4 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors border border-blue-100">
                            <span class="material-icons text-blue-600 text-lg mb-2">receipt</span>
                            <span class="text-[11px] font-medium text-blue-700">Create Invoice</span>
                        </a>

                        <a href="{{ route('calendar') }}" class="flex flex-col items-center p-4 bg-green-50 rounded-sm hover:bg-green-100 transition-colors border border-green-100" style="border-radius: 2px !important;">
                            <span class="material-icons text-green-600 text-lg mb-2">event</span>
                            <span class="text-[11px] font-medium text-green-700">Schedule Hearing</span>
                        </a>

                        <a href="mailto:{{ $case->client->email ?? '' }}?subject=Regarding Case: {{ $case->case_number }}" class="flex flex-col items-center p-4 bg-purple-50 rounded-sm hover:bg-purple-100 transition-colors border border-purple-100" style="border-radius: 2px !important;">
                            <span class="material-icons text-purple-600 text-lg mb-2">email</span>
                            <span class="text-[11px] font-medium text-purple-700">Send Email</span>
                        </a>

                        <a href="tel:{{ $case->client->phone ?? '' }}" class="flex flex-col items-center p-4 bg-orange-50 rounded-sm hover:bg-orange-100 transition-colors border border-orange-100" style="border-radius: 2px !important;">
                            <span class="material-icons text-orange-600 text-lg mb-2">phone</span>
                            <span class="text-[11px] font-medium text-orange-700">Call Client</span>
                        </a>

                        <a href="{{ route('quotation.create') }}?case_id={{ $case->id }}" class="flex flex-col items-center p-4 bg-teal-50 rounded-sm hover:bg-teal-100 transition-colors border border-teal-100" style="border-radius: 2px !important;">
                            <span class="material-icons text-teal-600 text-lg mb-2">request_quote</span>
                            <span class="text-[11px] font-medium text-teal-700">Create Quote</span>
                        </a>

                        <a href="{{ route('file-management.index') }}?case_id={{ $case->id }}" class="flex flex-col items-center p-4 bg-indigo-50 rounded-sm hover:bg-indigo-100 transition-colors border border-indigo-100" style="border-radius: 2px !important;">
                            <span class="material-icons text-indigo-600 text-lg mb-2">folder_open</span>
                            <span class="text-[11px] font-medium text-indigo-700">Manage Files</span>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <div class="flex justify-between items-center pt-4 border-t border-gray-200">
                <div></div>
                <div class="text-xs text-gray-500">
                    @php
                        $systemSettings = \App\Models\SystemSetting::getSystemSettings();
                        $dateFormat = $systemSettings->date_format ?? 'l, j F Y';
                        $timeFormat = $systemSettings->time_format ?? 'g:i:s a';
                        $timezone = $systemSettings->time_zone ?? 'Asia/Kuala_Lumpur';
                        
                        if ($case->updated_at) {
                            $formattedDateTime = $case->updated_at->setTimezone($timezone)->format($dateFormat . ' - ' . $timeFormat);
                            $relativeTime = $case->updated_at->diffForHumans();
                        } else {
                            $formattedDateTime = 'Unknown';
                            $relativeTime = 'Unknown';
                        }
                    @endphp
                    Last updated: {{ $formattedDateTime }} 
                    <span class="text-gray-400">({{ $relativeTime }})</span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Document Modal -->
<div x-data="{ open: false }" 
     @open-modal.window="if ($event.detail === 'add-document') open = true"
     @close-modal.window="open = false"
     x-show="open" 
     x-cloak
     class="fixed inset-0 z-50 overflow-y-auto" 
     style="display: none;">
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity" aria-hidden="true">
            <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
        </div>
        
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                    <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-purple-100 sm:mx-0 sm:h-10 sm:w-10">
                        <span class="material-icons text-purple-600">upload_file</span>
                    </div>
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                        <h3 class="text-sm leading-6 font-medium text-gray-900 mb-4">Add New Document</h3>
                        
                        <form class="space-y-4">
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Document Title *</label>
                                <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-purple-500" placeholder="e.g., Court Filing Document" required>
                            </div>
                            
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Document Type *</label>
                                <select class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-purple-500" required>
                                    <option value="">Select Type</option>
                                    <option value="contract">Contract</option>
                                    <option value="court_filing">Court Filing</option>
                                    <option value="evidence">Evidence</option>
                                    <option value="correspondence">Correspondence</option>
                                    <option value="invoice">Invoice</option>
                                    <option value="other">Other</option>
                                </select>
                            </div>
                            
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Upload File *</label>
                                <div class="border-2 border-dashed border-gray-300 rounded-lg p-4 text-center">
                                    <span class="material-icons text-gray-400 text-2xl mb-2">cloud_upload</span>
                                    <p class="text-xs text-gray-500">Click to upload or drag and drop</p>
                                    <p class="text-xs text-gray-400 mt-1">PDF, DOC, DOCX, JPG, PNG (max 10MB)</p>
                                    <input type="file" class="hidden" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                                </div>
                            </div>
                            
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Description</label>
                                <textarea class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-purple-500" rows="3" placeholder="Enter document description"></textarea>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <button type="button" class="bg-purple-600 hover:bg-purple-700 text-white px-3 py-1 rounded-md text-xs font-medium">
                    Upload Document
                </button>
                <button @click="open = false" type="button" class="mt-3 bg-white text-gray-700 hover:bg-gray-50 px-3 py-1 rounded-md text-xs font-medium border border-gray-300 sm:mt-0 sm:ml-3">
                    Cancel
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Add Timeline Event Modal -->
<div x-data="{ 
    open: false,
    timelineData: {
        title: '',
        event_type: '',
        event_date: '',
        event_time: '',
        description: '',
        location: '',
        add_to_calendar: false,
        reminder: '',
        status: '',
        priority: '',
        case_type: '',
        case_number: '',
        judge_name: '',
        filing_date: '',
        court_location: '',
        hearing_type: '',
        parties_notified: '', // Changed to string
        custom_metadata: []
    },
    isSubmitting: false
}" 
     @open-modal.window="if ($event.detail === 'add-timeline') { open = true; window.activeTimelineModal = $data; }"
     @close-modal.window="open = false; if (window.activeTimelineModal === $data) window.activeTimelineModal = null;"
     x-show="open" 
     x-cloak
     class="fixed inset-0 z-50 overflow-y-auto" 
     style="display: none;">
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity" aria-hidden="true">
            <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
        </div>
        
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                        <!-- Modal Title with Icon, left-aligned and inline with input fields -->
                        <div class="flex items-center gap-x-2 mb-6 pl-1">
                            <span class="material-icons text-green-600 text-xl">event</span>
                            <h3 class="text-lg font-semibold text-gray-900">Add Timeline Event</h3>
                        </div>
                        <div class="border-b border-gray-200 mb-6"></div>
                        
                        <form id="timelineForm" class="space-y-4" @submit.prevent="submitTimelineEvent()">
                            @csrf
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Event Title *</label>
                                <input type="text" x-model="timelineData.title" name="title" class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-green-500" placeholder="e.g., Court Hearing Scheduled" required>
                            </div>
                            
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1">Event Type *</label>
                                    <select x-model="timelineData.event_type" name="event_type" class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-green-500" required>
                                        <option value="">Select Type</option>
                                        <option value="consultation">Consultation</option>
                                        <option value="court_hearing">Court Hearing</option>
                                        <option value="document_filing">Document Filing</option>
                                        <option value="payment">Payment</option>
                                        <option value="settlement">Settlement</option>
                                        <option value="other">Other</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1">Event Status *</label>
                                    <select x-model="timelineData.status" name="status" class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-green-500" required>
                                        <option value="">Select Status</option>
                                        @foreach($eventStatuses as $eventStatus)
                                            <option value="{{ $eventStatus->name }}">{{ $eventStatus->display_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1">Event Date *</label>
                                    <input type="date" x-model="timelineData.event_date" name="event_date" class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-green-500" required>
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1">Event Time</label>
                                    <input type="time" x-model="timelineData.event_time" name="event_time" class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-green-500">
                                </div>
                            </div>
                            
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Description</label>
                                <textarea x-model="timelineData.description" name="description" class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-green-500" rows="3" placeholder="Enter event description"></textarea>
                            </div>

                            <!-- Add to Calendar Option -->
                            <div class="bg-blue-50 p-4 rounded-sm border border-blue-200">
                                <div class="flex items-start space-x-3">
                                    <div class="flex items-center h-5">
                                        <input type="checkbox" x-model="timelineData.add_to_calendar" name="add_to_calendar" value="1"
                                               class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 focus:ring-2">
                                    </div>
                                    <div class="flex-1">
                                        <label class="text-xs font-medium text-gray-700 flex items-center">
                                            <span class="material-icons text-blue-600 text-sm mr-1">event_available</span>
                                            Add to Calendar
                                        </label>
                                        <p class="text-xs text-gray-600 mt-1">
                                            This event will appear in the calendar view and can be used for scheduling and reminders.
                                        </p>
                                    </div>
                                </div>

                                <!-- Calendar-specific fields (shown when checkbox is checked) -->
                                <div x-show="timelineData.add_to_calendar" x-transition class="mt-4 pt-4 border-t border-blue-200">
                                    <div class="grid grid-cols-1 gap-4">
                                        <div>
                                            <label class="block text-xs font-medium text-gray-700 mb-1">Reminder</label>
                                            <select x-model="timelineData.reminder" name="reminder" class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500">
                                                <option value="">No Reminder</option>
                                                <option value="15">15 minutes before</option>
                                                <option value="30">30 minutes before</option>
                                                <option value="60">1 hour before</option>
                                                <option value="1440">1 day before</option>
                                                <option value="2880">2 days before</option>
                                                <option value="10080">1 week before</option>
                                            </select>
                                        </div>
                                        <div class="bg-gray-50 p-3 rounded border">
                                            <div class="flex items-center text-xs text-gray-600">
                                                <span class="material-icons text-sm mr-2">info</span>
                                                <div>
                                                    <p><strong>Location:</strong> Will use Court Location from Case Information</p>
                                                    <p><strong>Category:</strong> Will use Event Type selected above</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- COMPREHENSIVE METADATA FIELDS -->
                            <div class="border-t border-gray-200 pt-4 mt-4">
                                <div class="flex justify-between items-center mb-3">
                                    <div class="flex items-center">
                                        <span class="material-icons text-gray-600 text-sm mr-2">info</span>
                                        <label class="block text-xs font-medium text-gray-700">Additional Metadata (Optional)</label>
                                    </div>
                                    <button type="button" @click="autoFillCurrentCase()" class="text-blue-600 hover:text-blue-700 text-xs font-medium flex items-center">
                                        <span class="material-icons text-xs mr-1">auto_fix_high</span> Auto-fill Current Case
                                    </button>
                                </div>
                                
                                <!-- Case Information -->
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-3 mb-4">
                                    <div>
                                        <label class="block text-xs font-medium text-gray-600 mb-1">Priority</label>
                                        <select x-model="timelineData.priority" name="priority" class="w-full px-2 py-1 border border-gray-300 rounded text-xs focus:outline-none focus:ring-1 focus:ring-green-500">
                                            <option value="">Select Priority</option>
                                            <option value="high">High</option>
                                            <option value="medium">Medium</option>
                                            <option value="low">Low</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-medium text-gray-600 mb-1">Case Type</label>
                                        <select x-model="timelineData.case_type" name="case_type" class="w-full px-2 py-1 border border-gray-300 rounded text-xs focus:outline-none focus:ring-1 focus:ring-green-500">
                                            <option value="">Select Case Type</option>
                                            @foreach($caseTypes as $type)
                                                <option value="{{ $type->description }}">{{ $type->description }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-medium text-gray-600 mb-1">Case Number</label>
                                        <select x-model="timelineData.case_number" name="case_number" class="w-full px-2 py-1 border border-gray-300 rounded text-xs focus:outline-none focus:ring-1 focus:ring-green-500">
                                            <option value="">Select Case Number</option>
                                            @foreach($allCases as $id => $caseNumber)
                                                <option value="{{ $caseNumber }}">{{ $caseNumber }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-medium text-gray-600 mb-1">Judge Name</label>
                                        <input type="text" x-model="timelineData.judge_name" name="judge_name" class="w-full px-2 py-1 border border-gray-300 rounded text-xs focus:outline-none focus:ring-1 focus:ring-green-500" placeholder="e.g., Y.A Dato Ahmad">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-medium text-gray-600 mb-1">Filing Date</label>
                                        <input type="datetime-local" x-model="timelineData.filing_date" name="filing_date" class="w-full px-2 py-1 border border-gray-300 rounded text-xs focus:outline-none focus:ring-1 focus:ring-green-500">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-medium text-gray-600 mb-1">Court Location</label>
                                        <select x-model="timelineData.court_location" name="court_location" class="w-full px-2 py-1 border border-gray-300 rounded text-xs focus:outline-none focus:ring-1 focus:ring-green-500">
                                            <option value="">Select Court Location</option>
                                            @foreach($courtLocations as $location)
                                                <option value="{{ $location }}">{{ $location }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-medium text-gray-600 mb-1">Hearing Type</label>
                                        <input type="text" x-model="timelineData.hearing_type" name="hearing_type" class="w-full px-2 py-1 border border-gray-300 rounded text-xs focus:outline-none focus:ring-1 focus:ring-green-500" placeholder="e.g., First Hearing, Case Management">
                                    </div>
                                    <!-- Parties Notified Dropdown (Single Select) -->
                                    <div>
                                        <label class="block text-xs font-medium text-gray-600 mb-1">Parties to Notify</label>
                                        <select x-model="timelineData.parties_notified" name="parties_notified" class="w-full px-2 py-1 border border-gray-300 rounded text-xs">
                                            <option value="">Select Party to Notify</option>
                                            @foreach($case->parties as $party)
                                                <option value="{{ $party->id }}">{{ $party->name }} [{{ ucfirst($party->party_type) }}]</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                
                                <!-- Custom Metadata (Dynamic Key-Value Pairs) -->
                                <div class="border-t border-gray-200 pt-3">
                                    <div class="flex justify-between items-center mb-2">
                                        <label class="block text-xs font-medium text-gray-600">Custom Metadata</label>
                                        <button type="button" @click="timelineData.custom_metadata.push({ key: '', value: '' })" class="text-green-600 hover:text-green-700 text-xs font-medium flex items-center">
                                            <span class="material-icons text-xs mr-1">add_circle_outline</span> Add Custom Field
                                        </button>
                                    </div>
                                    <template x-for="(meta, index) in timelineData.custom_metadata" :key="index">
                                        <div class="flex space-x-2 mb-2 items-center">
                                            <input type="text" x-model="meta.key" placeholder="Key (e.g., Notification Sent)" class="w-1/3 px-2 py-1 border border-gray-300 rounded text-xs focus:outline-none focus:ring-1 focus:ring-green-500">
                                            <input type="text" x-model="meta.value" placeholder="Value (e.g., Yes)" class="w-2/3 px-2 py-1 border border-gray-300 rounded text-xs focus:outline-none focus:ring-1 focus:ring-green-500">
                                            <button type="button" @click="timelineData.custom_metadata.splice(index, 1)" x-show="timelineData.custom_metadata.length > 1" class="text-red-500 hover:text-red-700">
                                                <span class="material-icons text-sm">remove_circle_outline</span>
                                            </button>
                                        </div>
                                    </template>
                                </div>
                            </div>
                            <!-- END METADATA FIELDS -->
                        </form>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <button type="button" @click="submitTimelineEvent()" class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded-md text-xs font-medium" :disabled="isSubmitting">
                    <span x-text="isSubmitting ? 'Adding...' : 'Add Event'"></span>
                </button>
                <button @click="open = false; resetTimelineForm()" type="button" class="mt-3 bg-white text-gray-700 hover:bg-gray-50 px-3 py-1 rounded-md text-xs font-medium border border-gray-300 sm:mt-0 sm:ml-3">
                    Cancel
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Edit Timeline Event Modals -->
@foreach($case->timeline as $event)
<div x-data="editTimelineModal{{ $event->id }}()"
     id="edit-timeline-modal-{{ $event->id }}"
     @open-modal.window="console.log('Modal event received:', $event.detail, 'Expected:', 'edit-timeline-{{ $event->id }}'); if ($event.detail === 'edit-timeline-{{ $event->id }}') { console.log('Opening modal for event {{ $event->id }}'); open = true; window.activeTimelineModal = $data; }"
     @close-modal.window="open = false; if (window.activeTimelineModal === $data) window.activeTimelineModal = null;"
     x-show="open"
     x-cloak
     class="fixed inset-0 z-50 overflow-y-auto"
     style="display: none;">
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity" aria-hidden="true">
            <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
        </div>

        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                        <!-- Modal Title with Icon, left-aligned and inline with input fields -->
                        <div class="flex items-center gap-x-2 mb-6 pl-1">
                            <span class="material-icons text-blue-600 text-xl">edit</span>
                            <h3 class="text-lg font-semibold text-gray-900">Edit Timeline Event</h3>
                        </div>
                        <div class="border-b border-gray-200 mb-6"></div>

                        <form class="space-y-4" @submit.prevent="submitEditTimelineEventFromForm({{ $event->id }}, $data)">
                            @csrf
                            @method('PUT')
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Event Title *</label>
                                <input type="text" x-model="timelineData.title" name="title" class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="e.g., Court Hearing Scheduled" required>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1">Event Type *</label>
                                    <select x-model="timelineData.event_type" name="event_type" class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                                        <option value="">Select Type</option>
                                        <option value="consultation">Consultation</option>
                                        <option value="court_hearing">Court Hearing</option>
                                        <option value="document_filing">Document Filing</option>
                                        <option value="payment">Payment</option>
                                        <option value="settlement">Settlement</option>
                                        <option value="other">Other</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1">Event Status *</label>
                                    <select x-model="timelineData.status" name="status" class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                                        <option value="">Select Status</option>
                                        @foreach($eventStatuses as $eventStatus)
                                            <option value="{{ $eventStatus->name }}">{{ $eventStatus->display_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1">Event Date *</label>
                                    <input type="date" x-model="timelineData.event_date" name="event_date" class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1">Event Time</label>
                                    <input type="time" x-model="timelineData.event_time" name="event_time" class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500">
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Description</label>
                                <textarea x-model="timelineData.description" name="description" class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500" rows="3" placeholder="Enter event description"></textarea>
                            </div>

                            <!-- Add to Calendar Option -->
                            <div class="bg-blue-50 p-4 rounded-sm border border-blue-200">
                                <div class="flex items-start space-x-3">
                                    <div class="flex items-center h-5">
                                        <input type="checkbox" x-model="timelineData.add_to_calendar" name="add_to_calendar" value="1"
                                               class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 focus:ring-2">
                                    </div>
                                    <div class="flex-1">
                                        <label class="text-xs font-medium text-gray-700 flex items-center">
                                            <span class="material-icons text-blue-600 text-sm mr-1">event_available</span>
                                            Add to Calendar
                                        </label>
                                        <p class="text-xs text-gray-600 mt-1">
                                            This event will appear in the calendar view and can be used for scheduling and reminders.
                                        </p>
                                    </div>
                                </div>

                                <!-- Calendar-specific fields (shown when checkbox is checked) -->
                                <div x-show="timelineData.add_to_calendar" x-transition class="mt-4 pt-4 border-t border-blue-200">
                                    <div class="grid grid-cols-1 gap-4">
                                        <div>
                                            <label class="block text-xs font-medium text-gray-700 mb-1">Reminder</label>
                                            <select x-model="timelineData.reminder" name="reminder" class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500">
                                                <option value="">No Reminder</option>
                                                <option value="15">15 minutes before</option>
                                                <option value="30">30 minutes before</option>
                                                <option value="60">1 hour before</option>
                                                <option value="1440">1 day before</option>
                                                <option value="2880">2 days before</option>
                                                <option value="10080">1 week before</option>
                                            </select>
                                        </div>
                                        <div class="bg-gray-50 p-3 rounded border">
                                            <div class="flex items-center text-xs text-gray-600">
                                                <span class="material-icons text-sm mr-2">info</span>
                                                <div>
                                                    <p><strong>Location:</strong> Will use Court Location from Case Information</p>
                                                    <p><strong>Category:</strong> Will use Event Type selected above</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- COMPREHENSIVE METADATA FIELDS -->
                            <div class="border-t border-gray-200 pt-4 mt-4">
                                <div class="flex justify-between items-center mb-3">
                                    <div class="flex items-center">
                                        <span class="material-icons text-gray-600 text-sm mr-2">info</span>
                                        <label class="block text-xs font-medium text-gray-700">Additional Metadata (Optional)</label>
                                    </div>
                                    <button type="button" @click="autoFillCurrentCase()" class="text-blue-600 hover:text-blue-700 text-xs font-medium flex items-center">
                                        <span class="material-icons text-xs mr-1">auto_fix_high</span> Auto-fill Current Case
                                    </button>
                                </div>

                                <!-- Case Information -->
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-3 mb-4">
                                    <div>
                                        <label class="block text-xs font-medium text-gray-600 mb-1">Priority</label>
                                        <select x-model="timelineData.priority" name="priority" class="w-full px-2 py-1 border border-gray-300 rounded text-xs focus:outline-none focus:ring-1 focus:ring-blue-500">
                                            <option value="">Select Priority</option>
                                            <option value="high">High</option>
                                            <option value="medium">Medium</option>
                                            <option value="low">Low</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-medium text-gray-600 mb-1">Case Type</label>
                                        <select x-model="timelineData.case_type" name="case_type" class="w-full px-2 py-1 border border-gray-300 rounded text-xs focus:outline-none focus:ring-1 focus:ring-blue-500">
                                            <option value="">Select Case Type</option>
                                            @foreach($caseTypes as $type)
                                                <option value="{{ $type->description }}">{{ $type->description }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-medium text-gray-600 mb-1">Case Number</label>
                                        <select x-model="timelineData.case_number" name="case_number" class="w-full px-2 py-1 border border-gray-300 rounded text-xs focus:outline-none focus:ring-1 focus:ring-blue-500">
                                            <option value="">Select Case Number</option>
                                            @foreach($allCases as $id => $caseNumber)
                                                <option value="{{ $caseNumber }}">{{ $caseNumber }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-medium text-gray-600 mb-1">Judge Name</label>
                                        <input type="text" x-model="timelineData.judge_name" name="judge_name" class="w-full px-2 py-1 border border-gray-300 rounded text-xs focus:outline-none focus:ring-1 focus:ring-blue-500" placeholder="e.g., Y.A Dato Ahmad">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-medium text-gray-600 mb-1">Filing Date</label>
                                        <input type="datetime-local" x-model="timelineData.filing_date" name="filing_date" class="w-full px-2 py-1 border border-gray-300 rounded text-xs focus:outline-none focus:ring-1 focus:ring-blue-500">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-medium text-gray-600 mb-1">Court Location</label>
                                        <select x-model="timelineData.court_location" name="court_location" class="w-full px-2 py-1 border border-gray-300 rounded text-xs focus:outline-none focus:ring-1 focus:ring-blue-500">
                                            <option value="">Select Court Location</option>
                                            @foreach($courtLocations as $location)
                                                <option value="{{ $location }}">{{ $location }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-medium text-gray-600 mb-1">Hearing Type</label>
                                        <input type="text" x-model="timelineData.hearing_type" name="hearing_type" class="w-full px-2 py-1 border border-gray-300 rounded text-xs focus:outline-none focus:ring-1 focus:ring-blue-500" placeholder="e.g., First Hearing, Case Management">
                                    </div>
                                    <!-- Parties Notified Dropdown (Single Select) -->
                                    <div>
                                        <label class="block text-xs font-medium text-gray-600 mb-1">Parties to Notify</label>
                                        <select x-model="timelineData.parties_notified" name="parties_notified" class="w-full px-2 py-1 border border-gray-300 rounded text-xs">
                                            <option value="">Select Party to Notify</option>
                                            @foreach($case->parties as $party)
                                                <option value="{{ $party->id }}">{{ $party->name }} [{{ ucfirst($party->party_type) }}]</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <!-- Custom Metadata (Dynamic Key-Value Pairs) -->
                                <div class="border-t border-gray-200 pt-3">
                                    <div class="flex justify-between items-center mb-2">
                                        <label class="block text-xs font-medium text-gray-600">Custom Metadata</label>
                                        <button type="button" @click="timelineData.custom_metadata.push({ key: '', value: '' })" class="text-blue-600 hover:text-blue-700 text-xs font-medium flex items-center">
                                            <span class="material-icons text-xs mr-1">add_circle_outline</span> Add Custom Field
                                        </button>
                                    </div>
                                    <template x-for="(meta, index) in timelineData.custom_metadata" :key="index">
                                        <div class="flex space-x-2 mb-2 items-center">
                                            <input type="text" x-model="meta.key" placeholder="Key (e.g., Notification Sent)" class="w-1/3 px-2 py-1 border border-gray-300 rounded text-xs focus:outline-none focus:ring-1 focus:ring-blue-500">
                                            <input type="text" x-model="meta.value" placeholder="Value (e.g., Yes)" class="w-2/3 px-2 py-1 border border-gray-300 rounded text-xs focus:outline-none focus:ring-1 focus:ring-blue-500">
                                            <button type="button" @click="timelineData.custom_metadata.splice(index, 1)" x-show="timelineData.custom_metadata.length > 1" class="text-red-500 hover:text-red-700">
                                                <span class="material-icons text-sm">remove_circle_outline</span>
                                            </button>
                                        </div>
                                    </template>
                                </div>
                            </div>
                            <!-- END METADATA FIELDS -->
                        </form>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <button type="button" @click="submitEditTimelineEventFromForm({{ $event->id }}, $data)" class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded-md text-xs font-medium" :disabled="isSubmitting">
                    <span x-text="isSubmitting ? 'Updating...' : 'Update Event'"></span>
                </button>
                <button @click="open = false" type="button" class="mt-3 bg-white text-gray-700 hover:bg-gray-50 px-3 py-1 rounded-md text-xs font-medium border border-gray-300 sm:mt-0 sm:ml-3">
                    Cancel
                </button>
            </div>
        </div>
    </div>
</div>
@endforeach

<!-- File Management Modal -->
<div x-data="{ open: false }" 
     @open-modal.window="if ($event.detail === 'file-management') open = true"
     @close-modal.window="open = false"
     x-show="open" 
     x-cloak
     class="fixed inset-0 z-50 overflow-y-auto" 
     style="display: none;">
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity" aria-hidden="true">
            <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
        </div>
        
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                    <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-blue-100 sm:mx-0 sm:h-10 sm:w-10">
                        <span class="material-icons text-blue-600 text-xs">folder</span>
                    </div>
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                        <h3 class="text-sm leading-6 font-medium text-gray-900 mb-4">File Management</h3>
                        
                        <!-- File Upload Section -->
                        <div class="bg-gray-50 p-4 rounded-sm mb-6">
                            <h4 class="text-xs font-semibold text-gray-700 mb-3">Upload New File</h4>
                            <form action="{{ route('file-management.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                                @csrf
                                <input type="hidden" name="case_ref" value="{{ $case->case_number }}">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <!-- File Type -->
                                    <div>
                                        <label class="block text-xs font-medium text-gray-700 mb-2">File Type</label>
                                        <select name="file_type" class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                                            <option value="">Select file type...</option>
                                            @foreach(\App\Models\FileType::active()->orderBy('description')->get(['id','code','description']) as $type)
                                                <option value="{{ $type->id }}">{{ $type->description }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    
                                    <!-- Description -->
                                    <div>
                                        <label class="block text-xs font-medium text-gray-700 mb-2">Description</label>
                                        <input type="text" name="description" class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Enter file description">
                                    </div>
                                </div>
                                
                                <!-- File Upload -->
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-2">Upload Files</label>
                                    <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-blue-400 transition-colors">
                                        <span class="material-icons text-gray-400 text-3xl mb-2">cloud_upload</span>
                                        <p class="text-xs text-gray-600 mb-2">Drag and drop files here, or click to browse</p>
                                        <p class="text-xs text-gray-500">Supports: PDF, DOC, DOCX, JPG, PNG, ZIP, RAR (Max 10MB each)</p>
                                        <input type="file" name="files[]" multiple class="hidden" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png,.zip,.rar" id="modalFileInput">
                                        <button type="button" onclick="document.getElementById('modalFileInput').click()" class="mt-3 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-xs font-medium">
                                            Choose Files
                                        </button>
                                        <div id="modalSelectedFiles" class="mt-3 text-xs text-gray-600"></div>
                                    </div>
                                </div>
                                
                                <div class="flex justify-end">
                                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-2 rounded-md text-xs font-medium">
                                        Upload Files
                                    </button>
                                </div>
                            </form>
                        </div>
                        
                        <!-- File List Section -->
                        <div class="bg-gray-50 p-4 rounded-sm" style="border-radius: 2px !important;">
                            <h4 class="text-xs font-semibold text-gray-700 mb-3">Case Files</h4>
                            @if($case->files->count() > 0)
                                <div class="space-y-3">
                                    @foreach($case->files as $file)
                                    <div class="flex items-center justify-between p-3 bg-white rounded-sm border border-gray-200" style="border-radius: 2px !important;">
                                        <div class="flex items-center space-x-3">
                                            <span class="material-icons {{ $file->fileType ? ($file->fileType->code === 'CO' ? 'text-red-500' : ($file->fileType->code === 'EV' ? 'text-green-500' : 'text-blue-500')) : 'text-gray-500' }} text-xs">
                                                {{ $file->fileType ? ($file->fileType->code === 'CO' ? 'picture_as_pdf' : ($file->fileType->code === 'EV' ? 'image' : 'description')) : 'insert_drive_file' }}
                                            </span>
                                            <div>
                                                <p class="text-[11px] font-medium text-gray-900">{{ $file->file_name ?? 'Document' }}</p>
                                                <p class="text-[11px] text-gray-500">{{ $file->fileType ? $file->fileType->description : 'Unknown' }} • {{ $file->file_size }} • Uploaded {{ $file->created_at->format('d M Y') }}</p>
                                            </div>
                                        </div>
                                        <div class="flex space-x-2">
                                            <a href="{{ route('file-management.download', $file->id) }}" class="p-1 bg-blue-50 rounded-sm hover:bg-blue-100 border border-blue-100" title="Download" style="border-radius: 2px !important;">
                                                <span class="material-icons text-blue-600 text-xs">download</span>
                                            </a>
                                            <button onclick="openStatusModal({{ $file->id }}, '{{ $file->status }}', '{{ $file->taken_by }}', '{{ $file->purpose }}', '{{ $file->expected_return }}', '{{ $file->rack_location }}')" class="p-1 bg-purple-50 rounded-sm hover:bg-purple-100 border border-purple-100" title="Change Status" style="border-radius: 2px !important;">
                                                <span class="material-icons text-purple-600 text-xs">swap_horiz</span>
                                            </button>
                                            <form action="{{ route('file-management.destroy', $file->id) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this file?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="p-1 bg-red-50 rounded-sm hover:bg-red-100 border border-red-100" title="Delete" style="border-radius: 2px !important;">
                                                    <span class="material-icons text-red-600 text-xs">delete</span>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-8">
                                    <span class="material-icons text-gray-400 text-4xl mb-2">folder_open</span>
                                    <p class="text-gray-500 text-sm">No files found for this case.</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <button @click="open = false" type="button" class="bg-gray-600 hover:bg-gray-700 text-white px-3 py-1 rounded-md text-xs font-medium">
                    Close
                </button>
            </div>
        </div>
    </div>
</div>

<!-- File Status Modal -->
<div class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden" id="statusModal">
    <div class="flex items-center justify-center min-h-screen">
        <div class="bg-white rounded-lg p-6 w-96">
            <h3 class="text-lg font-bold mb-4">Change File Status</h3>
            <form id="statusForm" method="POST">
                @csrf
                @method('PATCH')
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium mb-2">Current Status: <span id="currentStatus" class="text-green-600">IN</span></label>
                        <select name="status" id="statusSelect" class="w-full px-3 py-2 border border-gray-300 rounded" onchange="toggleStatusFields()">
                            <option value="IN">IN (File in office)</option>
                            <option value="OUT">OUT (File taken out)</option>
                        </select>
                    </div>
                    <div id="outFields" class="hidden">
                        <div>
                            <label class="block text-sm font-medium mb-2">Taken by:</label>
                            <input type="text" name="taken_by" id="takenBy" class="w-full px-3 py-2 border border-gray-300 rounded" placeholder="Enter name">
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-2">Purpose:</label>
                            <textarea name="purpose" id="purpose" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded" placeholder="Enter purpose"></textarea>
                        </div>
                        <div>
                            <label class="block text-sm form-medium mb-2">Expected Return:</label>
                            <input type="date" name="expected_return" id="expectedReturn" class="w-full px-3 py-2 border border-gray-300 rounded">
                        </div>
                    </div>
                    <div id="inFields">
                        <div>
                            <label class="block text-sm font-medium mb-2">Rack Location:</label>
                            <input type="text" name="rack_location" id="rackLocation" class="w-full px-3 py-2 border border-gray-300 rounded" placeholder="Enter rack location">
                        </div>
                    </div>
                </div>
                <div class="flex justify-end space-x-2 mt-6">
                    <button type="button" onclick="closeStatusModal()" class="px-4 py-2 text-gray-600 border border-gray-300 rounded">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">Update Status</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Global variable to track active modal
window.activeTimelineModal = null;

// Edit Timeline Modal Functions
@foreach($case->timeline as $event)
function editTimelineModal{{ $event->id }}() {
    return {
        open: false,
        timelineData: {
            title: {{ Js::from($event->title ?? '') }},
            event_type: {{ Js::from($event->event_type ?? '') }},
            event_date: {{ Js::from($event->event_date ? $event->event_date->format('Y-m-d') : '') }},
            event_time: {{ Js::from($event->event_date ? $event->event_date->format('H:i') : '') }},
            description: {{ Js::from($event->description ?? '') }},
            location: {{ Js::from($event->location ?? '') }},
            add_to_calendar: {{ Js::from(isset($event->metadata['add_to_calendar']) ? (bool)$event->metadata['add_to_calendar'] : false) }},
            reminder: {{ Js::from($event->metadata['reminder'] ?? '') }},
            status: {{ Js::from($event->status ?? '') }},
            priority: {{ Js::from($event->metadata['priority'] ?? '') }},
            case_type: {{ Js::from($event->metadata['case_type'] ?? '') }},
            case_number: {{ Js::from($event->metadata['case_number'] ?? '') }},
            judge_name: {{ Js::from($event->metadata['judge_name'] ?? '') }},
            filing_date: {{ Js::from($event->metadata['filing_date'] ?? '') }},
            court_location: {{ Js::from($event->metadata['court_location'] ?? '') }},
            hearing_type: {{ Js::from($event->metadata['hearing_type'] ?? '') }},
            parties_notified: {{ Js::from($event->metadata['parties_notified'] ?? '') }},
            custom_metadata: []
        },
        isSubmitting: false
    }
}
@endforeach

// File input handling for modal
document.getElementById('modalFileInput').addEventListener('change', function(e) {
    const files = Array.from(e.target.files);
    const fileList = document.getElementById('modalSelectedFiles');
    
    if (files.length > 0) {
        fileList.innerHTML = '<strong>Selected files:</strong><br>' + 
            files.map(file => file.name).join('<br>');
    } else {
        fileList.innerHTML = '';
    }
});

// Status modal functions
function openStatusModal(fileId, status, takenBy, purpose, expectedReturn, rackLocation) {
    document.getElementById('statusForm').action = `/file-management/${fileId}/status`;
    document.getElementById('statusSelect').value = status;
    document.getElementById('currentStatus').textContent = status;
    document.getElementById('takenBy').value = takenBy || '';
    document.getElementById('purpose').value = purpose || '';
    document.getElementById('expectedReturn').value = expectedReturn || '';
    document.getElementById('rackLocation').value = rackLocation || '';
    
    toggleStatusFields();
    document.getElementById('statusModal').classList.remove('hidden');
}

function closeStatusModal() {
    document.getElementById('statusModal').classList.add('hidden');
}

function toggleStatusFields() {
    const status = document.getElementById('statusSelect').value;
    const outFields = document.getElementById('outFields');
    const inFields = document.getElementById('inFields');
    
    if (status === 'OUT') {
        outFields.classList.remove('hidden');
        inFields.classList.add('hidden');
    } else {
        outFields.classList.remove('hidden');
        inFields.classList.remove('hidden');
    }
}

// Timeline Event Functions
function submitTimelineEvent() {
    // Get the specific timeline modal
    const modal = document.querySelector('[x-data*="timelineData"]');
    if (!modal) {
        return;
    }

    // Get Alpine.js data properly
    const alpine = Alpine.$data(modal);
    if (!alpine) {
        return;
    }
    
    if (alpine.isSubmitting) return;
    
    // Validate required fields
    if (!alpine.timelineData.title || !alpine.timelineData.event_type || !alpine.timelineData.event_date || !alpine.timelineData.status) {
        alert('Please fill in all required fields (Title, Event Type, Event Date, and Status)');
        return;
    }
    
    alpine.isSubmitting = true;
    
    // Get CSRF token from the form
    const csrfToken = modal.querySelector('input[name="_token"]').value;
    
    // Get form data
    const formData = new FormData();
    formData.append('title', alpine.timelineData.title);
    formData.append('event_type', alpine.timelineData.event_type);
    formData.append('event_date', alpine.timelineData.event_date);
    formData.append('event_time', alpine.timelineData.event_time || '');
    formData.append('description', alpine.timelineData.description || '');
    formData.append('location', alpine.timelineData.location || '');
    formData.append('status', alpine.timelineData.status || ''); // Add status to form data
    formData.append('_token', csrfToken);

    // Append calendar fields
    formData.append('add_to_calendar', alpine.timelineData.add_to_calendar ? '1' : '0');
    formData.append('reminder', alpine.timelineData.reminder || '');

    // Append metadata fields
    formData.append('priority', alpine.timelineData.priority || '');
    formData.append('case_type', alpine.timelineData.case_type || '');
    formData.append('case_number', alpine.timelineData.case_number || '');
    formData.append('judge_name', alpine.timelineData.judge_name || '');
    formData.append('filing_date', alpine.timelineData.filing_date || '');
    formData.append('court_location', alpine.timelineData.court_location || '');
    formData.append('hearing_type', alpine.timelineData.hearing_type || '');
    formData.append('parties_notified', alpine.timelineData.parties_notified || '');

    // Append notified parties
    // The parties_notified field is now a single string, not an array of IDs.
    // If you need to send the ID, you'll need to parse it or ensure it's a string.
    // For now, we'll just append the string value.
    formData.append('parties_notified', alpine.timelineData.parties_notified || '');
    
    // Append custom metadata
    alpine.timelineData.custom_metadata.forEach(meta => {
        if (meta.key && meta.value) {
            formData.append(`custom_metadata[${meta.key}]`, meta.value);
        }
    });

    
    // Submit to backend
    fetch(`/case/{{ $case->id }}/timeline`, {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => {
        return response.json();
    })
    .then(data => {
        if (data.success) {
            // Show success message
            alert('Timeline event added successfully!');
            
            // Reset form
            alpine.timelineData = {
                title: '',
                event_type: '',
                event_date: '',
                event_time: '',
                description: '',
                location: '',
                status: '', // Reset status
                priority: '',
                case_type: '',
                case_number: '',
                judge_name: '',
                filing_date: '',
                court_location: '',
                hearing_type: '',
                parties_notified: '', // Reset parties_notified string
                custom_metadata: []
            };
            
            // Close modal
            alpine.open = false;
            
            // Reload page to show new timeline event
            window.location.reload();
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        alert('Failed to add timeline event. Please try again.');
    })
    .finally(() => {
        alpine.isSubmitting = false;
    });
}

// Reset timeline form when modal opens
function resetTimelineForm() {
    const modal = document.querySelector('[x-data*="timelineData"]');
    if (!modal) return;
    
    const alpine = Alpine.$data(modal);
    if (!alpine) return;
    
    alpine.timelineData = {
        title: '',
        event_type: '',
        event_date: '',
        event_time: '',
        description: '',
        location: '',
        status: '', // Reset status
        priority: '',
        case_type: '',
        case_number: '',
        judge_name: '',
        filing_date: '',
        court_location: @json($case->court_location ?? ""),
        hearing_type: '',
        parties_notified: '', // Reset parties_notified string
        custom_metadata: []
    };
}

// Auto-fill current case data
function autoFillCurrentCase() {
    // Try to find the active modal (either add or edit)
    let alpine = null;

    // Check for Add Timeline modal
    const addModal = document.querySelector('[x-data*="timelineData"]');
    if (addModal && addModal.style.display !== 'none') {
        try {
            alpine = addModal._x_dataStack ? addModal._x_dataStack[0] : null;
            if (!alpine && window.Alpine) {
                alpine = window.Alpine.$data(addModal);
            }
        } catch (e) {
            console.error('Error accessing Add modal Alpine data:', e);
        }
    }

    // Check for Edit Timeline modals if add modal not found
    if (!alpine) {
        const editModals = document.querySelectorAll('[id^="edit-timeline-modal-"]');
        console.log('Found edit modals:', editModals.length);

        for (let modal of editModals) {
            try {
                // Get Alpine data first
                let modalAlpine = modal._x_dataStack ? modal._x_dataStack[0] : null;
                if (!modalAlpine && window.Alpine) {
                    modalAlpine = window.Alpine.$data(modal);
                }

                console.log('Modal:', modal.id, 'Alpine data:', modalAlpine);

                // Check if modal is open via Alpine data
                if (modalAlpine && modalAlpine.open === true && modalAlpine.timelineData) {
                    console.log('Found open edit modal:', modal.id);
                    alpine = modalAlpine;
                    break;
                }
            } catch (e) {
                console.error('Error accessing Edit modal Alpine data:', e);
            }
        }
    }

    // Fallback to global active modal
    if (!alpine && window.activeTimelineModal && window.activeTimelineModal.timelineData) {
        console.log('Using global active modal as fallback');
        alpine = window.activeTimelineModal;
    }

    if (!alpine || !alpine.timelineData) {
        console.error('Could not find active modal or Alpine data');
        console.log('Available modals:', document.querySelectorAll('[id^="edit-timeline-modal-"]'));
        console.log('Global active modal:', window.activeTimelineModal);
        alert('Error: Could not access form data. Please make sure a modal is open.');
        return;
    }

    console.log('Auto-filling case data for:', alpine);

    // Populate fields from the current case object
    alpine.timelineData.case_type = {{ Js::from($case->caseType->description ?? "") }};
    alpine.timelineData.case_number = {{ Js::from($case->case_number ?? "") }};
    alpine.timelineData.court_location = {{ Js::from($case->court_location ?? "") }};

    // Set filing date from case creation date
    @if($case->created_at)
        alpine.timelineData.filing_date = {{ Js::from($case->created_at->format('Y-m-d')) }};
    @endif

    // Set location from court location if available
    @if($case->court_location)
        alpine.timelineData.location = {{ Js::from($case->court_location) }};
    @endif

    // Set parties notified
    @if($case->parties->count() > 0)
        alpine.timelineData.parties_notified = {{ $case->parties->first()->id }};
    @else
        alpine.timelineData.parties_notified = '';
    @endif

    // Optional: Auto-fill other fields if they exist
    @if($case->priority_level)
        alpine.timelineData.priority = {{ Js::from($case->priority_level) }};
    @endif
    @if($case->judge_name)
        alpine.timelineData.judge_name = {{ Js::from($case->judge_name) }};
    @endif

    console.log('Auto-filled case data:', alpine.timelineData);

    // Show success message
    alert('Current case data has been auto-filled!');
}

// Edit Timeline Event Function
function submitEditTimelineEvent(eventId) {
    // Get the specific edit timeline modal
    const modal = document.getElementById(`edit-timeline-modal-${eventId}`);
    if (!modal) {
        console.error('Modal not found for event ID:', eventId);
        return;
    }

    // Get Alpine.js data properly
    let alpine;
    try {
        alpine = modal._x_dataStack ? modal._x_dataStack[0] : null;
        if (!alpine && window.Alpine) {
            // Try alternative method
            alpine = window.Alpine.$data(modal);
        }
    } catch (e) {
        console.error('Error accessing Alpine.js data:', e);
    }

    if (!alpine) {
        console.error('Alpine.js data not found for modal:', eventId);
        alert('Error: Could not access form data. Please refresh the page and try again.');
        return;
    }

    if (alpine.isSubmitting) return;

    // Validate required fields
    if (!alpine.timelineData.title || !alpine.timelineData.event_type || !alpine.timelineData.event_date || !alpine.timelineData.status) {
        alert('Please fill in all required fields (Title, Event Type, Event Date, and Status)');
        return;
    }

    alpine.isSubmitting = true;

    // Get form data
    const formData = new FormData();
    formData.append('title', alpine.timelineData.title);
    formData.append('event_type', alpine.timelineData.event_type);
    formData.append('event_date', alpine.timelineData.event_date);
    formData.append('event_time', alpine.timelineData.event_time || '');
    formData.append('description', alpine.timelineData.description || '');
    formData.append('location', alpine.timelineData.location || '');
    formData.append('status', alpine.timelineData.status || '');
    formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
    formData.append('_method', 'PUT');

    // Append metadata fields
    formData.append('priority', alpine.timelineData.priority || '');
    formData.append('case_type', alpine.timelineData.case_type || '');
    formData.append('case_number', alpine.timelineData.case_number || '');
    formData.append('judge_name', alpine.timelineData.judge_name || '');
    formData.append('filing_date', alpine.timelineData.filing_date || '');
    formData.append('court_location', alpine.timelineData.court_location || '');
    formData.append('hearing_type', alpine.timelineData.hearing_type || '');
    formData.append('parties_notified', alpine.timelineData.parties_notified || '');

    // Append custom metadata
    alpine.timelineData.custom_metadata.forEach(meta => {
        if (meta.key && meta.value) {
            formData.append(`custom_metadata[${meta.key}]`, meta.value);
        }
    });



    // Submit to backend
    fetch(`/case/{{ $case->id }}/timeline/${eventId}`, {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => {
        console.log('Response status:', response.status);
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        console.log('Response data:', data);
        if (data.success) {
            // Show success message
            alert('Timeline event updated successfully!');

            // Close modal
            alpine.open = false;

            // Reload page to show updated timeline event
            window.location.reload();
        } else {
            alert('Error: ' + (data.message || 'Unknown error occurred'));
        }
    })
    .catch(error => {
        console.error('Fetch error:', error);
        alert('Failed to update timeline event. Please check console for details.');
    })
    .finally(() => {
        alpine.isSubmitting = false;
    });
}

// Alternative approach using Alpine context directly
function submitEditTimelineEventFromForm(eventId, alpineContext) {
    console.log('submitEditTimelineEventFromForm called with:', eventId, alpineContext);

    if (!alpineContext || !alpineContext.timelineData) {
        console.error('Alpine context or timelineData not available');
        alert('Error: Form data not accessible. Please refresh the page and try again.');
        return;
    }

    if (alpineContext.isSubmitting) {
        console.log('Already submitting, ignoring...');
        return;
    }

    // Validate required fields
    if (!alpineContext.timelineData.title || !alpineContext.timelineData.event_type || !alpineContext.timelineData.event_date || !alpineContext.timelineData.status) {
        alert('Please fill in all required fields (Title, Event Type, Event Date, and Status)');
        return;
    }

    alpineContext.isSubmitting = true;

    // Get form data
    const formData = new FormData();
    formData.append('title', alpineContext.timelineData.title);
    formData.append('event_type', alpineContext.timelineData.event_type);
    formData.append('event_date', alpineContext.timelineData.event_date);
    formData.append('event_time', alpineContext.timelineData.event_time || '');
    formData.append('description', alpineContext.timelineData.description || '');
    formData.append('location', alpineContext.timelineData.location || '');
    formData.append('status', alpineContext.timelineData.status || '');
    formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
    formData.append('_method', 'PUT');

    // Append calendar fields
    formData.append('add_to_calendar', alpineContext.timelineData.add_to_calendar ? '1' : '0');
    formData.append('reminder', alpineContext.timelineData.reminder || '');

    // Append metadata fields
    formData.append('priority', alpineContext.timelineData.priority || '');
    formData.append('case_type', alpineContext.timelineData.case_type || '');
    formData.append('case_number', alpineContext.timelineData.case_number || '');
    formData.append('judge_name', alpineContext.timelineData.judge_name || '');
    formData.append('filing_date', alpineContext.timelineData.filing_date || '');
    formData.append('court_location', alpineContext.timelineData.court_location || '');
    formData.append('hearing_type', alpineContext.timelineData.hearing_type || '');
    formData.append('parties_notified', alpineContext.timelineData.parties_notified || '');

    // Append custom metadata
    if (alpineContext.timelineData.custom_metadata) {
        alpineContext.timelineData.custom_metadata.forEach(meta => {
            if (meta.key && meta.value) {
                formData.append(`custom_metadata[${meta.key}]`, meta.value);
            }
        });
    }

    console.log('Submitting to URL:', `/case/{{ $case->id }}/timeline/${eventId}`);

    // Submit to backend
    fetch(`/case/{{ $case->id }}/timeline/${eventId}`, {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => {
        console.log('Response status:', response.status);
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        console.log('Response data:', data);
        if (data.success) {
            // Show success message
            alert('Timeline event updated successfully!');

            // Close modal
            alpineContext.open = false;

            // Reload page to show updated timeline event
            window.location.reload();
        } else {
            alert('Error: ' + (data.message || 'Unknown error occurred'));
        }
    })
    .catch(error => {
        console.error('Fetch error:', error);
        alert('Failed to update timeline event. Please check console for details.');
    })
    .finally(() => {
        alpineContext.isSubmitting = false;
    });
}

// Delete timeline event function
function deleteTimelineEvent(eventId) {
    if (!confirm('Are you sure you want to delete this timeline event? This action cannot be undone.')) {
        return;
    }

    const formData = new FormData();
    formData.append('_token', '{{ csrf_token() }}');
    formData.append('_method', 'DELETE');

    fetch(`{{ url('/case/' . $case->id . '/timeline') }}/${eventId}`, {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Timeline event deleted successfully!');
            window.location.reload();
        } else {
            alert('Error: ' + (data.message || 'Failed to delete timeline event'));
        }
    })
    .catch(error => {
        console.error('Delete error:', error);
        alert('Failed to delete timeline event. Please try again.');
    });
}
</script>
@endsection