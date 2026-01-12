<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CourtCase;
use App\Models\CaseTimeline;
use App\Models\CaseFile;
use App\Models\TaxInvoice;
use App\Models\Receipt;
use App\Models\Quotation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CaseReferenceApiController extends Controller
{
    /**
     * Get case information by case reference
     * 
     * @param string $caseReference
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCaseInfo($caseReference)
    {
        try {
            // Find case by case_number with firm scope
            $user = auth()->user();
            $currentFirmId = session('current_firm_id');

            if ($user->hasRole('Super Administrator') && $currentFirmId) {
                $case = CourtCase::forFirm($currentFirmId)
                    ->where('case_number', $caseReference)
                    ->with([
                        'caseType',
                        'caseStatus',
                        'createdBy',
                        'assignedTo',
                        'parties',
                        'partners.partner'
                    ])
                    ->first();
            } elseif ($user->hasRole('Super Administrator') && !$currentFirmId) {
                $case = CourtCase::withoutFirmScope()
                    ->where('case_number', $caseReference)
                    ->with([
                        'caseType',
                        'caseStatus',
                        'createdBy',
                        'assignedTo',
                        'parties',
                        'partners.partner'
                    ])
                    ->first();
            } else {
                $case = CourtCase::where('case_number', $caseReference)
                    ->with([
                        'caseType',
                        'caseStatus',
                        'createdBy',
                        'assignedTo',
                        'parties',
                        'partners.partner'
                    ])
                    ->first();
            }

            if (!$case) {
                return response()->json([
                    'success' => false,
                    'message' => 'Case not found',
                    'case_reference' => $caseReference
                ], 404);
            }

            // Format response
            return response()->json([
                'success' => true,
                'message' => 'Case information retrieved successfully',
                'data' => [
                    'id' => $case->id,
                    'case_number' => $case->case_number,
                    'title' => $case->title,
                    'description' => $case->description,
                    'case_type' => [
                        'id' => $case->caseType->id ?? null,
                        'name' => $case->caseType->name ?? null,
                    ],
                    'case_status' => [
                        'id' => $case->caseStatus->id ?? null,
                        'name' => $case->caseStatus->name ?? null,
                        'status' => $case->caseStatus->status ?? null,
                    ],
                    'person_in_charge' => $case->person_in_charge,
                    'court_ref' => $case->court_ref,
                    'jurisdiction' => $case->jurisdiction,
                    'section' => $case->section,
                    'initiating_document' => $case->initiating_document,
                    'name_of_property' => $case->name_of_property,
                    'others_document' => $case->others_document,
                    'priority_level' => $case->priority_level,
                    'judge_name' => $case->judge_name,
                    'court_location' => $case->court_location,
                    'claim_amount' => $case->claim_amount,
                    'notes' => $case->notes,
                    'created_by' => [
                        'id' => $case->createdBy->id ?? null,
                        'name' => $case->createdBy->name ?? null,
                        'email' => $case->createdBy->email ?? null,
                    ],
                    'assigned_to' => [
                        'id' => $case->assignedTo->id ?? null,
                        'name' => $case->assignedTo->name ?? null,
                        'email' => $case->assignedTo->email ?? null,
                    ],
                    'parties' => $case->parties->map(function ($party) {
                        return [
                            'id' => $party->id,
                            'name' => $party->name,
                            'ic_passport' => $party->ic_passport,
                            'party_type' => $party->party_type,
                            'phone' => $party->phone,
                            'email' => $party->email,
                            'address' => $party->address,
                            'gender' => $party->gender,
                            'nationality' => $party->nationality,
                        ];
                    }),
                    'partners' => $case->partners->map(function ($casePartner) {
                        return [
                            'id' => $casePartner->id,
                            'partner_id' => $casePartner->partner_id,
                            'partner_code' => $casePartner->partner->partner_code ?? null,
                            'name' => $casePartner->partner->name ?? null,
                            'email' => $casePartner->partner->email ?? null,
                            'phone' => $casePartner->partner->phone ?? null,
                        ];
                    }),
                    'firm_id' => $case->firm_id,
                    'created_at' => $case->created_at,
                    'updated_at' => $case->updated_at,
                ]
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve case information',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get case timeline by case reference
     * 
     * @param string $caseReference
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getTimeline($caseReference, Request $request)
    {
        try {
            // Find case by case_number
            $user = auth()->user();
            $currentFirmId = session('current_firm_id');

            if ($user->hasRole('Super Administrator') && $currentFirmId) {
                $case = CourtCase::forFirm($currentFirmId)
                    ->where('case_number', $caseReference)
                    ->first();
            } elseif ($user->hasRole('Super Administrator') && !$currentFirmId) {
                $case = CourtCase::withoutFirmScope()
                    ->where('case_number', $caseReference)
                    ->first();
            } else {
                $case = CourtCase::where('case_number', $caseReference)->first();
            }

            if (!$case) {
                return response()->json([
                    'success' => false,
                    'message' => 'Case not found',
                    'case_reference' => $caseReference
                ], 404);
            }

            // Build query for timeline
            $query = CaseTimeline::where('case_id', $case->id)
                ->with(['createdBy', 'calendarEvent']);

            // Apply filters
            if ($request->has('status')) {
                $query->where('status', $request->status);
            }

            if ($request->has('event_type')) {
                $query->where('event_type', $request->event_type);
            }

            if ($request->has('from') && $request->has('to')) {
                $query->whereBetween('event_date', [
                    $request->from . ' 00:00:00',
                    $request->to . ' 23:59:59'
                ]);
            }

            // Sorting
            $sortBy = $request->get('sort_by', 'event_date');
            $sortOrder = $request->get('sort_order', 'desc');
            $query->orderBy($sortBy, $sortOrder);

            // Pagination
            $perPage = $request->get('per_page', 15);
            $timeline = $query->paginate($perPage);

            // Format response
            return response()->json([
                'success' => true,
                'message' => 'Timeline retrieved successfully',
                'case_reference' => $caseReference,
                'case_id' => $case->id,
                'case_title' => $case->title,
                'data' => $timeline->map(function ($event) {
                    return [
                        'id' => $event->id,
                        'event_type' => $event->event_type,
                        'title' => $event->title,
                        'description' => $event->description,
                        'status' => $event->status,
                        'event_date' => $event->event_date,
                        'metadata' => $event->metadata, // Custom metadata (JSON)
                        'created_by' => [
                            'id' => $event->createdBy->id ?? null,
                            'name' => $event->createdBy->name ?? null,
                            'email' => $event->createdBy->email ?? null,
                        ],
                        'calendar_event' => $event->calendarEvent ? [
                            'id' => $event->calendarEvent->id,
                            'title' => $event->calendarEvent->title,
                            'start_date' => $event->calendarEvent->start_date,
                            'end_date' => $event->calendarEvent->end_date,
                            'reminder' => $event->calendarEvent->reminder,
                        ] : null,
                        'created_at' => $event->created_at,
                        'updated_at' => $event->updated_at,
                    ];
                }),
                'pagination' => [
                    'total' => $timeline->total(),
                    'per_page' => $timeline->perPage(),
                    'current_page' => $timeline->currentPage(),
                    'last_page' => $timeline->lastPage(),
                    'from' => $timeline->firstItem(),
                    'to' => $timeline->lastItem(),
                ]
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve timeline',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get case documents by case reference
     *
     * @param string $caseReference
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getDocuments($caseReference, Request $request)
    {
        try {
            // Find case by case_number
            $user = auth()->user();
            $currentFirmId = session('current_firm_id');

            if ($user->hasRole('Super Administrator') && $currentFirmId) {
                $case = CourtCase::forFirm($currentFirmId)
                    ->where('case_number', $caseReference)
                    ->first();
            } elseif ($user->hasRole('Super Administrator') && !$currentFirmId) {
                $case = CourtCase::withoutFirmScope()
                    ->where('case_number', $caseReference)
                    ->first();
            } else {
                $case = CourtCase::where('case_number', $caseReference)->first();
            }

            if (!$case) {
                return response()->json([
                    'success' => false,
                    'message' => 'Case not found',
                    'case_reference' => $caseReference
                ], 404);
            }

            // Build query for documents
            $query = CaseFile::where('case_ref', $caseReference)
                ->with(['fileType']);

            // Apply filters
            if ($request->has('status')) {
                $query->where('status', $request->status);
            }

            if ($request->has('category_id')) {
                $query->where('category_id', $request->category_id);
            }

            if ($request->has('mime_type')) {
                $query->where('mime_type', 'like', '%' . $request->mime_type . '%');
            }

            // Sorting
            $sortBy = $request->get('sort_by', 'created_at');
            $sortOrder = $request->get('sort_order', 'desc');
            $query->orderBy($sortBy, $sortOrder);

            // Pagination
            $perPage = $request->get('per_page', 15);
            $documents = $query->paginate($perPage);

            // Format response
            return response()->json([
                'success' => true,
                'message' => 'Documents retrieved successfully',
                'case_reference' => $caseReference,
                'case_id' => $case->id,
                'case_title' => $case->title,
                'data' => $documents->map(function ($file) {
                    return [
                        'id' => $file->id,
                        'file_name' => $file->file_name,
                        'file_path' => $file->file_path,
                        'file_size' => $file->file_size,
                        'formatted_size' => $file->formatted_size,
                        'mime_type' => $file->mime_type,
                        'description' => $file->description,
                        'status' => $file->status,
                        'category' => [
                            'id' => $file->fileType->id ?? null,
                            'name' => $file->fileType->name ?? null,
                        ],
                        'check_out_info' => $file->status === 'OUT' ? [
                            'taken_by' => $file->taken_by,
                            'purpose' => $file->purpose,
                            'expected_return' => $file->expected_return,
                            'is_overdue' => $file->is_overdue,
                        ] : null,
                        'check_in_info' => $file->status === 'IN' ? [
                            'actual_return' => $file->actual_return,
                            'rack_location' => $file->rack_location,
                        ] : null,
                        'created_at' => $file->created_at,
                        'updated_at' => $file->updated_at,
                    ];
                }),
                'pagination' => [
                    'total' => $documents->total(),
                    'per_page' => $documents->perPage(),
                    'current_page' => $documents->currentPage(),
                    'last_page' => $documents->lastPage(),
                    'from' => $documents->firstItem(),
                    'to' => $documents->lastItem(),
                ],
                'summary' => [
                    'total_documents' => CaseFile::where('case_ref', $caseReference)->count(),
                    'checked_in' => CaseFile::where('case_ref', $caseReference)->where('status', 'IN')->count(),
                    'checked_out' => CaseFile::where('case_ref', $caseReference)->where('status', 'OUT')->count(),
                    'overdue' => CaseFile::where('case_ref', $caseReference)
                        ->where('status', 'OUT')
                        ->where('expected_return', '<', now())
                        ->count(),
                ]
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve documents',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get case financial information by case reference
     *
     * @param string $caseReference
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getFinancialInfo($caseReference, Request $request)
    {
        try {
            // Find case by case_number
            $user = auth()->user();
            $currentFirmId = session('current_firm_id');

            if ($user->hasRole('Super Administrator') && $currentFirmId) {
                $case = CourtCase::forFirm($currentFirmId)
                    ->where('case_number', $caseReference)
                    ->first();
            } elseif ($user->hasRole('Super Administrator') && !$currentFirmId) {
                $case = CourtCase::withoutFirmScope()
                    ->where('case_number', $caseReference)
                    ->first();
            } else {
                $case = CourtCase::where('case_number', $caseReference)->first();
            }

            if (!$case) {
                return response()->json([
                    'success' => false,
                    'message' => 'Case not found',
                    'case_reference' => $caseReference
                ], 404);
            }

            // Get quotations
            $quotations = Quotation::where('case_id', $case->id)
                ->with(['items'])
                ->orderBy('quotation_date', 'desc')
                ->get();

            // Get tax invoices
            $taxInvoices = TaxInvoice::where('case_id', $case->id)
                ->with(['items', 'quotation'])
                ->orderBy('invoice_date', 'desc')
                ->get();

            // Get receipts
            $receipts = Receipt::where('case_id', $case->id)
                ->with(['quotation', 'taxInvoice'])
                ->orderBy('receipt_date', 'desc')
                ->get();

            // Calculate totals
            $totalQuotations = $quotations->sum('total');
            $totalInvoices = $taxInvoices->sum('total');
            $totalPaid = $receipts->sum('amount_paid');
            $outstandingBalance = $totalInvoices - $totalPaid;

            // Format response
            return response()->json([
                'success' => true,
                'message' => 'Financial information retrieved successfully',
                'case_reference' => $caseReference,
                'case_id' => $case->id,
                'case_title' => $case->title,
                'data' => [
                    'quotations' => $quotations->map(function ($quotation) {
                        return [
                            'id' => $quotation->id,
                            'quotation_no' => $quotation->quotation_no,
                            'quotation_date' => $quotation->quotation_date,
                            'valid_until' => $quotation->valid_until,
                            'customer_name' => $quotation->customer_name,
                            'customer_phone' => $quotation->customer_phone,
                            'customer_email' => $quotation->customer_email,
                            'subtotal' => $quotation->subtotal,
                            'discount_total' => $quotation->discount_total,
                            'tax_total' => $quotation->tax_total,
                            'total' => $quotation->total,
                            'status' => $quotation->status,
                            'payment_terms' => $quotation->payment_terms,
                            'items_count' => $quotation->items->count(),
                            'created_at' => $quotation->created_at,
                        ];
                    }),
                    'tax_invoices' => $taxInvoices->map(function ($invoice) {
                        return [
                            'id' => $invoice->id,
                            'invoice_no' => $invoice->invoice_no,
                            'invoice_date' => $invoice->invoice_date,
                            'due_date' => $invoice->due_date,
                            'customer_name' => $invoice->customer_name,
                            'customer_phone' => $invoice->customer_phone,
                            'customer_email' => $invoice->customer_email,
                            'subtotal' => $invoice->subtotal,
                            'discount_total' => $invoice->discount_total,
                            'tax_total' => $invoice->tax_total,
                            'total' => $invoice->total,
                            'status' => $invoice->status,
                            'payment_terms' => $invoice->payment_terms,
                            'quotation_no' => $invoice->quotation->quotation_no ?? null,
                            'items_count' => $invoice->items->count(),
                            'created_at' => $invoice->created_at,
                        ];
                    }),
                    'receipts' => $receipts->map(function ($receipt) {
                        return [
                            'id' => $receipt->id,
                            'receipt_no' => $receipt->receipt_no,
                            'receipt_date' => $receipt->receipt_date,
                            'payment_method' => $receipt->payment_method,
                            'payment_reference' => $receipt->payment_reference,
                            'bank_name' => $receipt->bank_name,
                            'cheque_number' => $receipt->cheque_number,
                            'transaction_id' => $receipt->transaction_id,
                            'amount_paid' => $receipt->amount_paid,
                            'outstanding_balance' => $receipt->outstanding_balance,
                            'status' => $receipt->status,
                            'quotation_no' => $receipt->quotation->quotation_no ?? null,
                            'invoice_no' => $receipt->taxInvoice->invoice_no ?? null,
                            'created_at' => $receipt->created_at,
                        ];
                    }),
                ],
                'summary' => [
                    'total_quotations' => number_format($totalQuotations, 2),
                    'total_invoices' => number_format($totalInvoices, 2),
                    'total_paid' => number_format($totalPaid, 2),
                    'outstanding_balance' => number_format($outstandingBalance, 2),
                    'quotations_count' => $quotations->count(),
                    'invoices_count' => $taxInvoices->count(),
                    'receipts_count' => $receipts->count(),
                ]
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve financial information',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}

