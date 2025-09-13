<?php

namespace App\Http\Controllers;

use App\Models\Quotation;
use App\Models\QuotationItem;
use App\Models\CourtCase;
use App\Models\Client;
use App\Models\FirmSetting;
use App\Models\Firm;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Carbon;
use Illuminate\Http\Request;

class QuotationController extends Controller
{
    public function index()
    {
        // Get quotations with proper firm scope filtering
        $user = auth()->user();

        if ($user->hasRole('Super Administrator')) {
            // Super Admin can see all quotations or filter by session firm
            if (session('current_firm_id')) {
                $quotations = Quotation::forFirm(session('current_firm_id'))
                    ->with(['case', 'items'])
                    ->orderBy('created_at', 'desc')
                    ->get();
            } else {
                $quotations = Quotation::withoutFirmScope()
                    ->with(['case', 'items'])
                    ->orderBy('created_at', 'desc')
                    ->get();
            }
        } else {
            // Regular users see only their firm's quotations (HasFirmScope trait handles this)
            $quotations = Quotation::with(['case', 'items'])
                ->orderBy('created_at', 'desc')
                ->get();
        }

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

        // Get tax categories for dropdown - ensure proper firm filtering
        $user = auth()->user();
        if ($user->hasRole('Super Administrator')) {
            // For Super Admin, use session firm_id or default to user's firm
            $firmId = session('current_firm_id') ?? $user->firm_id;
            $taxCategories = \App\Models\TaxCategory::withoutFirmScope()
                ->where('firm_id', $firmId)
                ->where('status', 'active')
                ->orderBy('sort_order')
                ->orderBy('name')
                ->get();
        } else {
            // For regular users, use HasFirmScope automatic filtering
            $taxCategories = \App\Models\TaxCategory::active()->ordered()->get();
        }

        // Deduplicate by name + rate combination
        $taxCategories = $taxCategories->unique(function ($item) {
            return $item->name . '_' . $item->tax_rate;
        })->values();

        return view('quotation-create', [
            'caseOptions' => $caseOptions,
            'casePrefill' => $casePrefill,
            'qFrom' => $qFrom,
            'taxCategories' => $taxCategories,
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
            'items.*.type' => 'required|string|in:item,title',
            'items.*.title_text' => 'nullable|string',
            'items.*.description' => 'nullable|string',
            'items.*.qty' => 'nullable|numeric|min:0',
            'items.*.uom' => 'nullable|string|max:32',
            'items.*.unit_price' => 'nullable|numeric|min:0',
            'items.*.discount_amount' => 'nullable|numeric|min:0',
            'items.*.tax_percent' => 'nullable|numeric|min:0|max:100',
            'items.*.tax_category_id' => 'nullable|integer|exists:tax_categories,id',
            'items.*.amount' => 'nullable|numeric|min:0',
            'from_quotation' => 'nullable|integer|exists:quotations,id', // Add validation for edit mode
        ]);

        // Check if this is edit mode (from_quotation parameter exists)
        $isEditMode = $request->filled('from_quotation');
        $existingQuotation = null;

        if ($isEditMode) {
            // Find existing quotation for update
            $user = auth()->user();
            if ($user->hasRole('Super Administrator')) {
                $existingQuotation = Quotation::withoutFirmScope()->findOrFail($validated['from_quotation']);
            } else {
                $existingQuotation = Quotation::findOrFail($validated['from_quotation']);
            }
        }

        // Generate quotation number only for new quotations
        if ($isEditMode) {
            $quotationNo = $existingQuotation->quotation_no;
        } else {
            $nextId = (Quotation::max('id') ?? 0) + 1;
            $quotationNo = 'Q-' . str_pad((string)$nextId, 5, '0', STR_PAD_LEFT);
        }

        // Calculate proper subtotal, tax, and total (skip title items)
        $subtotal = 0;
        $taxTotal = 0;

        foreach ($validated['items'] as $item) {
            // Skip title items in calculations
            if ($item['type'] === 'title') {
                continue;
            }

            $itemSubtotal = ($item['qty'] ?? 0) * ($item['unit_price'] ?? 0);
            $itemAfterDiscount = $itemSubtotal - ($item['discount_amount'] ?? 0);
            $itemTax = $itemAfterDiscount * ($item['tax_percent'] ?? 0) / 100;

            $subtotal += $itemAfterDiscount;
            $taxTotal += $itemTax;
        }

        $discountTotal = 0; // For future use
        $total = $subtotal + $taxTotal;

        // Get current firm context
        $user = auth()->user();
        $firmId = session('current_firm_id') ?? $user->firm_id;

        if ($isEditMode) {
            // Update existing quotation
            $existingQuotation->update([
                'case_id' => $validated['case_id'],
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

            // Delete existing items
            $existingQuotation->items()->delete();
            $quotation = $existingQuotation;
        } else {
            // Create new quotation
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
                'firm_id' => $firmId,
            ]);
        }

        foreach ($validated['items'] as $item) {
            if ($item['type'] === 'title') {
                // Create title item
                QuotationItem::create([
                    'quotation_id' => $quotation->id,
                    'item_type' => 'title',
                    'title_text' => $item['title_text'] ?? '',
                    'description' => '',
                    'qty' => 0,
                    'uom' => 'lot',
                    'unit_price' => 0,
                    'discount_amount' => 0,
                    'tax_percent' => 0,
                    'amount' => 0,
                ]);
            } else {
                // Calculate item amount (subtotal after discount, before tax)
                $itemSubtotal = ($item['qty'] ?? 0) * ($item['unit_price'] ?? 0);
                $itemAmount = $itemSubtotal - ($item['discount_amount'] ?? 0);

                QuotationItem::create([
                    'quotation_id' => $quotation->id,
                    'item_type' => 'item',
                    'title_text' => '',
                    'description' => $item['description'] ?? '',
                    'qty' => $item['qty'] ?? 0,
                    'uom' => $item['uom'] ?? 'lot',
                    'unit_price' => $item['unit_price'] ?? 0,
                    'discount_amount' => $item['discount_amount'] ?? 0,
                    'tax_percent' => $item['tax_percent'] ?? 0,
                    'tax_category_id' => $item['tax_category_id'] ?? null,
                    'amount' => $itemAmount,
                ]);
            }
        }

        // Log quotation creation or update
        activity()
            ->performedOn($quotation)
            ->causedBy(auth()->user())
            ->withProperties([
                'ip' => request()->ip(),
                'total_amount' => $total,
                'items_count' => count($validated['items'])
            ])
            ->log("Quotation {$quotation->quotation_no} " . ($isEditMode ? 'updated' : 'created'));

        // Return with appropriate flash message
        $flashMessage = $isEditMode
            ? "Successfully Updated Quotation {$quotation->quotation_no}"
            : 'Quotation created';

        return redirect()->route('quotation.show', $quotation->id)->with('success', $flashMessage);
    }

    public function show($id)
    {
        // Find quotation with firm scope validation
        $user = auth()->user();
        $currentFirmId = session('current_firm_id');

        if ($user->hasRole('Super Administrator') && $currentFirmId) {
            // Super Admin with firm context - respect firm scope
            $quotation = Quotation::forFirm($currentFirmId)
                ->with(['items.taxCategory', 'case'])
                ->findOrFail($id);
        } elseif ($user->hasRole('Super Administrator') && !$currentFirmId) {
            // Super Admin without firm context - can access any quotation (for system management)
            $quotation = Quotation::withoutFirmScope()
                ->with(['items.taxCategory', 'case'])
                ->findOrFail($id);
        } else {
            // Regular users can only access quotations from their firm (HasFirmScope trait handles this)
            $quotation = Quotation::with(['items.taxCategory', 'case'])->findOrFail($id);
        }

        return view('quotation-show', compact('quotation'));
    }

    public function print($id)
    {
        // Find quotation with firm scope validation
        $user = auth()->user();

        if ($user->hasRole('Super Administrator')) {
            // Super Admin can print any quotation
            $quotation = Quotation::withoutFirmScope()
                ->with(['items.taxCategory', 'case'])
                ->findOrFail($id);
        } else {
            // Regular users can only print quotations from their firm (HasFirmScope trait handles this)
            $quotation = Quotation::with(['items.taxCategory', 'case'])->findOrFail($id);
        }

        // Get firm settings for current firm context
        $user = auth()->user();
        $firmId = session('current_firm_id') ?? $user->firm_id;
        $firm = Firm::find($firmId);

        $firmSettings = (object) [
            'firm_name' => $firm ? $firm->name : 'Naeelah Saleh & Associates',
            'registration_number' => $firm ? $firm->registration_number : 'LLP0012345',
            'address' => $firm ? $firm->address : 'No. 123, Jalan Tun Razak, 50400 Kuala Lumpur, Malaysia',
            'phone_number' => $firm ? $firm->phone : '+6019-3186436',
            'email' => $firm ? $firm->email : 'info@naaelahsaleh.my',
            'tax_registration_number' => $firm && isset($firm->settings['tax_registration_number'])
                ? $firm->settings['tax_registration_number']
                : 'W24-2507-32000179'
        ];

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
        try {
            // Find quotation with firm scope validation
            $user = auth()->user();

            if ($user->hasRole('Super Administrator')) {
                // Super Admin can delete any quotation
                $quotation = Quotation::withoutFirmScope()
                    ->with('items')
                    ->find($id);
            } else {
                // Regular users can only delete quotations from their firm (HasFirmScope trait handles this)
                $quotation = Quotation::with('items')->find($id);
            }

            // Check if quotation exists
            if (!$quotation) {
                return response()->json([
                    'success' => false,
                    'message' => "Quotation with ID {$id} not found or you don't have permission to delete it."
                ], 404);
            }

            // Check if quotation can be deleted (only pending status)
            if ($quotation->status !== 'pending') {
                return response()->json([
                    'success' => false,
                    'message' => "Only quotations with 'Pending' status can be deleted. Current status: {$quotation->status}"
                ], 422);
            }

            // Store quotation info for logging
            $quotationNo = $quotation->quotation_no;
            $quotationTotal = $quotation->total;
            $quotationStatus = $quotation->status;

            // Log quotation deletion before deleting
            activity()
                ->performedOn($quotation)
                ->causedBy(auth()->user())
                ->withProperties([
                    'ip' => request()->ip(),
                    'quotation_no' => $quotationNo,
                    'total_amount' => $quotationTotal,
                    'status' => $quotationStatus
                ])
                ->log("Quotation {$quotationNo} deleted");

            // Delete items first, then quotation
            foreach ($quotation->items as $item) {
                $item->delete();
            }
            $quotation->delete();

            return response()->json([
                'success' => true,
                'message' => "Quotation {$quotationNo} has been successfully deleted."
            ]);

        } catch (\Exception $e) {
            \Log::error('Error deleting quotation: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while deleting the quotation: ' . $e->getMessage()
            ], 500);
        }
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


