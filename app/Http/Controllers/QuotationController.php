<?php

namespace App\Http\Controllers;

use App\Models\Quotation;
use App\Models\QuotationItem;
use App\Models\CourtCase;
use App\Models\Client;
use App\Models\FirmSetting;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Carbon;
use Illuminate\Http\Request;

class QuotationController extends Controller
{
    public function index()
    {
        // Get all quotations with related data
        $quotations = Quotation::with(['case', 'items'])
            ->orderBy('created_at', 'desc')
            ->get();
            
        return view('quotation', compact('quotations'));
    }
    public function create(Request $request)
    {
        // Case options and prefill map (applicant/respondent basic contact + address)
        $caseOptions = CourtCase::with(['parties' => function ($q) {
            $q->orderBy('id');
        }])->orderBy('case_number')->get(['id', 'case_number']);

        $casePrefill = $caseOptions->map(function ($c) {
            $app = $c->parties->firstWhere('party_type', 'plaintiff')
                ?? $c->parties->firstWhere('party_type', 'applicant');
            $resp = $c->parties->firstWhere('party_type', 'defendant')
                ?? $c->parties->firstWhere('party_type', 'respondent');

            $appAddress = '';
            $respAddress = '';
            if ($app) {
                $client = Client::where('name', $app->name)
                    ->where(function ($q) use ($app) {
                        if (isset($app->ic_passport)) {
                            $q->where('ic_passport', $app->ic_passport);
                        }
                    })->first();
                $appAddress = optional(optional($client)->primaryAddress)->full_address ?? '';
            }
            if ($resp) {
                $client = Client::where('name', $resp->name)
                    ->where(function ($q) use ($resp) {
                        if (isset($resp->ic_passport)) {
                            $q->where('ic_passport', $resp->ic_passport);
                        }
                    })->first();
                $respAddress = optional(optional($client)->primaryAddress)->full_address ?? '';
            }

            return [
                'id' => $c->id,
                'case_number' => $c->case_number,
                'applicant' => [
                    'name' => $app->name ?? '',
                    'phone' => $app->phone ?? '',
                    'email' => $app->email ?? '',
                    'address' => $appAddress,
                ],
                'respondent' => [
                    'name' => $resp->name ?? '',
                    'phone' => $resp->phone ?? '',
                    'email' => $resp->email ?? '',
                    'address' => $respAddress,
                ],
            ];
        })->keyBy('id');

        // Prefill from an existing quotation when editing/duplicating
        $qFrom = null;
        if ($request->filled('from_quotation')) {
            $qFrom = Quotation::with('items', 'case')->find($request->integer('from_quotation'));
        }

        return view('quotation-create', [
            'caseOptions' => $caseOptions,
            'casePrefill' => $casePrefill,
            'qFrom' => $qFrom,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'case_id' => 'required|integer|exists:cases,id',
            'quotation_date' => 'required|date',
            'valid_until' => 'nullable|date|after_or_equal:quotation_date',
            'payment_terms' => 'nullable|string|max:100',
            'remark' => 'nullable|string|max:1000',
            'customer_name' => 'nullable|string|max:255',
            'customer_phone' => 'nullable|string|max:100',
            'customer_email' => 'nullable|email|max:255',
            'customer_address' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.description' => 'nullable|string',
            'items.*.qty' => 'required|numeric|min:0.01',
            'items.*.uom' => 'required|string|max:32',
            'items.*.unit_price' => 'required|numeric|min:0',
            'items.*.discount_amount' => 'nullable|numeric|min:0',
            'items.*.tax_percent' => 'nullable|numeric|min:0|max:100',
            'items.*.amount' => 'required|numeric|min:0',
        ]);

        // Generate quotation number (simple incremental)
        $nextId = (Quotation::max('id') ?? 0) + 1;
        $quotationNo = 'Q-' . str_pad((string)$nextId, 5, '0', STR_PAD_LEFT);

        // Calculate proper subtotal, tax, and total
        $subtotal = 0;
        $taxTotal = 0;

        foreach ($validated['items'] as $item) {
            $itemSubtotal = $item['qty'] * $item['unit_price'];
            $itemAfterDiscount = $itemSubtotal - ($item['discount_amount'] ?? 0);
            $itemTax = $itemAfterDiscount * ($item['tax_percent'] ?? 0) / 100;

            $subtotal += $itemAfterDiscount;
            $taxTotal += $itemTax;
        }

        $discountTotal = 0; // For future use
        $total = $subtotal + $taxTotal;

        $quotation = Quotation::create([
            'case_id' => $validated['case_id'],
            'quotation_no' => $quotationNo,
            'quotation_date' => $validated['quotation_date'],
            'valid_until' => $validated['valid_until'] ?? Carbon::parse($validated['quotation_date'])->addDays(30)->toDateString(),
            'payment_terms' => $validated['payment_terms'] ?? null,
            'remark' => $validated['remark'] ?? null,
            'customer_name' => $validated['customer_name'] ?? null,
            'customer_phone' => $validated['customer_phone'] ?? null,
            'customer_email' => $validated['customer_email'] ?? null,
            'customer_address' => $validated['customer_address'] ?? null,
            'subtotal' => $subtotal,
            'discount_total' => $discountTotal,
            'tax_total' => $taxTotal,
            'total' => $total,
        ]);

        foreach ($validated['items'] as $item) {
            // Calculate item amount (subtotal after discount, before tax)
            $itemSubtotal = $item['qty'] * $item['unit_price'];
            $itemAmount = $itemSubtotal - ($item['discount_amount'] ?? 0);

            QuotationItem::create([
                'quotation_id' => $quotation->id,
                'description' => $item['description'] ?? '',
                'qty' => $item['qty'],
                'uom' => $item['uom'],
                'unit_price' => $item['unit_price'],
                'discount_amount' => $item['discount_amount'] ?? 0,
                'tax_percent' => $item['tax_percent'] ?? 0,
                'amount' => $itemAmount,
            ]);
        }

        // Log quotation creation
        activity()
            ->performedOn($quotation)
            ->causedBy(auth()->user())
            ->withProperties([
                'ip' => request()->ip(),
                'total_amount' => $total,
                'items_count' => count($validated['items'])
            ])
            ->log("Quotation {$quotation->quotation_no} created");

        return redirect()->route('quotation.show', $quotation->id)->with('success', 'Quotation created');
    }

    public function show($id)
    {
        $quotation = Quotation::with('items', 'case')->findOrFail($id);
        return view('quotation-show', compact('quotation'));
    }

    public function print($id)
    {
        $quotation = Quotation::with('items', 'case')->findOrFail($id);
        $firmSettings = FirmSetting::getFirmSettings();

        // Calculate page numbers based on items count
        $itemsPerPage = 15; // Estimate items per page (adjust based on PDF layout)
        $totalItems = $quotation->items->count();
        $totalPages = max(1, ceil($totalItems / $itemsPerPage));
        
        // Log quotation print
        activity()
            ->performedOn($quotation)
            ->causedBy(auth()->user())
            ->withProperties([
                'ip' => request()->ip(),
                'action' => 'print'
            ])
            ->log("Quotation {$quotation->quotation_no} printed");

        $pdf = Pdf::loadView('quotation-print', compact('quotation', 'firmSettings', 'totalPages'));
        return $pdf->download('quotation-' . $quotation->quotation_no . '.pdf');
    }

    public function destroy($id)
    {
        $quotation = Quotation::with('items')->findOrFail($id);

        // Log quotation deletion before deleting
        activity()
            ->performedOn($quotation)
            ->causedBy(auth()->user())
            ->withProperties([
                'ip' => request()->ip(),
                'quotation_no' => $quotation->quotation_no,
                'total_amount' => $quotation->total,
                'status' => $quotation->status
            ])
            ->log("Quotation {$quotation->quotation_no} deleted");

        // Simple delete with cascade of items
        foreach ($quotation->items as $item) {
            $item->delete();
        }
        $quotation->delete();
        return response()->json(['ok' => true]);
    }

    public function accept($id)
    {
        try {
            $quotation = Quotation::findOrFail($id);
            
            // Only allow accepting if status is pending
            if ($quotation->status !== 'pending') {
                return response()->json([
                    'success' => false,
                    'message' => 'Only pending quotations can be accepted'
                ], 400);
            }
            
            $quotation->update(['status' => 'accepted']);

            // Log the accept action
            activity()
                ->performedOn($quotation)
                ->causedBy(auth()->user())
                ->withProperties([
                    'ip' => request()->ip(),
                    'old_status' => 'pending',
                    'new_status' => 'accepted'
                ])
                ->log("Quotation {$quotation->quotation_no} accepted");

            return response()->json([
                'success' => true,
                'message' => 'Quotation accepted successfully',
                'new_status' => 'accepted'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to accept quotation: ' . $e->getMessage()
            ], 500);
        }
    }

    public function reject($id)
    {
        try {
            $quotation = Quotation::findOrFail($id);
            
            // Only allow rejecting if status is pending
            if ($quotation->status !== 'pending') {
                return response()->json([
                    'success' => false,
                    'message' => 'Only pending quotations can be rejected'
                ], 400);
            }
            
            $quotation->update(['status' => 'rejected']);

            // Log the reject action
            activity()
                ->performedOn($quotation)
                ->causedBy(auth()->user())
                ->withProperties([
                    'ip' => request()->ip(),
                    'old_status' => 'pending',
                    'new_status' => 'rejected'
                ])
                ->log("Quotation {$quotation->quotation_no} rejected");

            return response()->json([
                'success' => true,
                'message' => 'Quotation rejected successfully',
                'new_status' => 'rejected'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to reject quotation: ' . $e->getMessage()
            ], 500);
        }
    }

    public function cancel($id)
    {
        try {
            $quotation = Quotation::findOrFail($id);
            
            // Allow cancelling if status is pending, accepted, or rejected
            if (!in_array($quotation->status, ['pending', 'accepted', 'rejected'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Only pending, accepted, or rejected quotations can be cancelled'
                ], 400);
            }
            
            $oldStatus = $quotation->status;
            $quotation->update(['status' => 'cancelled']);

            // Log the cancel action
            activity()
                ->performedOn($quotation)
                ->causedBy(auth()->user())
                ->withProperties([
                    'ip' => request()->ip(),
                    'old_status' => $oldStatus,
                    'new_status' => 'cancelled'
                ])
                ->log("Quotation {$quotation->quotation_no} cancelled");

            return response()->json([
                'success' => true,
                'message' => 'Quotation cancelled successfully',
                'new_status' => 'cancelled'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to cancel quotation: ' . $e->getMessage()
            ], 500);
        }
    }

    public function reactivate($id)
    {
        try {
            $quotation = Quotation::findOrFail($id);
            
            // Allow reactivating if status is cancelled or rejected
            if (!in_array($quotation->status, ['cancelled', 'rejected'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Only cancelled or rejected quotations can be reactivated'
                ], 400);
            }
            
            $oldStatus = $quotation->status;
            $quotation->update(['status' => 'pending']);

            // Log the reactivate action
            activity()
                ->performedOn($quotation)
                ->causedBy(auth()->user())
                ->withProperties([
                    'ip' => request()->ip(),
                    'old_status' => $oldStatus,
                    'new_status' => 'pending'
                ])
                ->log("Quotation {$quotation->quotation_no} reactivated");

            return response()->json([
                'success' => true,
                'message' => 'Quotation reactivated successfully',
                'new_status' => 'pending'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to reactivate quotation: ' . $e->getMessage()
            ], 500);
        }
    }
}


