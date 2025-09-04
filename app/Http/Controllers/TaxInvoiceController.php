<?php

namespace App\Http\Controllers;

use App\Models\TaxInvoice;
use App\Models\TaxInvoiceItem;
use App\Models\Quotation;
use App\Models\CourtCase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TaxInvoiceController extends Controller
{
    public function index()
    {
        $taxInvoices = TaxInvoice::with(['case', 'quotation'])->latest()->get();
        return view('tax-invoice', compact('taxInvoices'));
    }

    public function create(Request $request)
    {
        // Get quotations for selection
        $quotations = Quotation::latest()->take(50)->get(['id', 'quotation_no']);
        
        // Prefill from quotation if provided
        $qFrom = null;
        if ($request->filled('from_quotation')) {
            $qFrom = Quotation::with(['items', 'case.parties'])->find($request->integer('from_quotation'));
        }

        // Get case options if case_id is provided
        $caseOptions = null;
        if ($request->filled('case_id')) {
            $caseOptions = CourtCase::where('id', $request->integer('case_id'))->get(['id', 'case_number']);
        }

        // Prepare default items
        $defaultItems = [
            [
                'description' => 'Legal Services',
                'qty' => 1,
                'uom' => 'lot',
                'price' => 1000,
                'disc' => 0,
                'tax' => 6
            ]
        ];

        // Prepare items from quotation if available
        $items = $qFrom && $qFrom->items ? 
            $qFrom->items->map(function($i) {
                return [
                    'description' => $i->description,
                    'qty' => (float) $i->qty,
                    'uom' => $i->uom,
                    'price' => (float) $i->unit_price,
                    'disc' => (float) ($i->discount_percent ?? 0),
                    'tax' => (float) ($i->tax_percent ?? 0),
                ];
            })->toArray() : $defaultItems;

        return view('tax-invoice-create', compact('quotations', 'qFrom', 'caseOptions', 'items'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'case_id' => 'nullable|integer|exists:cases,id',
            'quotation_id' => 'nullable|integer|exists:quotations,id',
            'invoice_date' => 'required|date',
            'due_date' => 'required|date|after_or_equal:invoice_date',
            'payment_terms' => 'nullable|string|max:100',
            'remark' => 'nullable|string|max:1000',
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'nullable|string|max:100',
            'customer_email' => 'nullable|email|max:255',
            'customer_address' => 'required|string',
            'contact_person' => 'nullable|string|max:255',
            'contact_phone' => 'nullable|string|max:100',
            'items' => 'required|array|min:1',
            'items.*.description' => 'nullable|string',
            'items.*.qty' => 'required|numeric|min:0.01',
            'items.*.uom' => 'required|string|max:32',
            'items.*.unit_price' => 'required|numeric|min:0',
            'items.*.discount_percent' => 'nullable|numeric|min:0|max:100',
            'items.*.tax_percent' => 'nullable|numeric|min:0|max:100',
            'items.*.amount' => 'required|numeric|min:0',
        ]);

        try {
            DB::beginTransaction();

            // Generate invoice number
            $nextId = (TaxInvoice::max('id') ?? 0) + 1;
            $invoiceNo = 'INV-' . str_pad((string)$nextId, 5, '0', STR_PAD_LEFT);

            // Calculate totals
            $subtotal = collect($validated['items'])->sum('amount');
            $discountTotal = 0; // Can be refined later
            $taxTotal = 0; // Can be refined later
            $total = $subtotal; // Simple for now

            // Get case_id and customer details from quotation if not provided
            $caseId = $validated['case_id'] ?? null;
            $customerDetails = [
                'customer_name' => $validated['customer_name'],
                'customer_phone' => $validated['customer_phone'] ?? null,
                'customer_email' => $validated['customer_email'] ?? null,
                'customer_address' => $validated['customer_address'],
                'contact_person' => $validated['contact_person'] ?? null,
                'contact_phone' => $validated['contact_phone'] ?? null,
            ];
            
            if ($validated['quotation_id']) {
                $quotation = Quotation::find($validated['quotation_id']);
                if ($quotation) {
                    $caseId = $quotation->case_id;
                    
                    // Copy customer details from quotation if not provided
                    if (empty($customerDetails['customer_phone']) && $quotation->customer_phone) {
                        $customerDetails['customer_phone'] = $quotation->customer_phone;
                    }
                    if (empty($customerDetails['customer_email']) && $quotation->customer_email) {
                        $customerDetails['customer_email'] = $quotation->customer_email;
                    }
                    if (empty($customerDetails['contact_person']) && $quotation->customer_name) {
                        $customerDetails['contact_person'] = $quotation->customer_name;
                    }
                    if (empty($customerDetails['contact_phone']) && $quotation->customer_phone) {
                        $customerDetails['contact_phone'] = $quotation->customer_phone;
                    }
                }
            }

            $taxInvoice = TaxInvoice::create([
                'invoice_no' => $invoiceNo,
                'case_id' => $caseId,
                'quotation_id' => $validated['quotation_id'] ?? null,
                'invoice_date' => $validated['invoice_date'],
                'due_date' => $validated['due_date'],
                'payment_terms' => $validated['payment_terms'] ?? 'net_30',
                'remark' => $validated['remark'] ?? null,
                'customer_name' => $customerDetails['customer_name'],
                'customer_phone' => $customerDetails['customer_phone'],
                'customer_email' => $customerDetails['customer_email'],
                'customer_address' => $customerDetails['customer_address'],
                'contact_person' => $customerDetails['contact_person'],
                'contact_phone' => $customerDetails['contact_phone'],
                'subtotal' => $subtotal,
                'discount_total' => $discountTotal,
                'tax_total' => $taxTotal,
                'total' => $total,
                'status' => 'draft',
            ]);

            // Create invoice items
            foreach ($validated['items'] as $item) {
                TaxInvoiceItem::create([
                    'tax_invoice_id' => $taxInvoice->id,
                    'description' => $item['description'] ?? '',
                    'qty' => $item['qty'],
                    'uom' => $item['uom'],
                    'unit_price' => $item['unit_price'],
                    'discount_percent' => $item['discount_percent'] ?? 0,
                    'tax_percent' => $item['tax_percent'] ?? 0,
                    'amount' => $item['amount'],
                ]);
            }

            // Update quotation status to 'converted' if this invoice is created from a quotation
            if ($validated['quotation_id']) {
                $quotation = Quotation::find($validated['quotation_id']);
                if ($quotation) {
                    $quotation->update(['status' => 'converted']);
                }
            }

            DB::commit();

            return redirect()->route('tax-invoice.show', $taxInvoice->id)
                ->with('success', 'Tax Invoice created successfully');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->withErrors(['error' => 'Failed to create tax invoice: ' . $e->getMessage()]);
        }
    }

    public function show($id)
    {
        $taxInvoice = TaxInvoice::with(['items', 'case', 'quotation'])->findOrFail($id);
        return view('tax-invoice-show', compact('taxInvoice'));
    }

    public function edit($id)
    {
        $taxInvoice = TaxInvoice::with(['items', 'case', 'quotation'])->findOrFail($id);
        
        // Get quotations for selection
        $quotations = Quotation::latest()->take(50)->get(['id', 'quotation_no']);
        
        // Prepare items for Alpine.js
        $items = $taxInvoice->items->map(function($i) {
            return [
                'description' => $i->description,
                'qty' => (float) $i->qty,
                'uom' => $i->uom,
                'price' => (float) $i->unit_price,
                'disc' => (float) ($i->discount_percent ?? 0),
                'tax' => (float) ($i->tax_percent ?? 0),
            ];
        })->toArray();

        return view('tax-invoice-edit', compact('taxInvoice', 'quotations', 'items'));
    }

    public function update(Request $request, $id)
    {
        $taxInvoice = TaxInvoice::findOrFail($id);
        
        $validated = $request->validate([
            'case_id' => 'nullable|integer|exists:cases,id',
            'quotation_id' => 'nullable|integer|exists:quotations,id',
            'invoice_date' => 'required|date',
            'due_date' => 'required|date|after_or_equal:invoice_date',
            'payment_terms' => 'nullable|string|max:100',
            'remark' => 'nullable|string|max:1000',
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'nullable|string|max:100',
            'customer_email' => 'nullable|email|max:255',
            'customer_address' => 'required|string',
            'contact_person' => 'nullable|string|max:255',
            'contact_phone' => 'nullable|string|max:100',
            'items' => 'required|array|min:1',
            'items.*.description' => 'nullable|string',
            'items.*.qty' => 'required|numeric|min:0.01',
            'items.*.uom' => 'required|string|max:32',
            'items.*.unit_price' => 'required|numeric|min:0',
            'items.*.discount_percent' => 'nullable|numeric|min:0|max:100',
            'items.*.tax_percent' => 'nullable|numeric|min:0|max:100',
            'items.*.amount' => 'required|numeric|min:0',
        ]);

        try {
            DB::beginTransaction();

            // Calculate totals
            $subtotal = collect($validated['items'])->sum('amount');
            $discountTotal = 0; // Can be refined later
            $taxTotal = 0; // Can be refined later
            $total = $subtotal; // Simple for now

            // Update tax invoice
            $taxInvoice->update([
                'case_id' => $validated['case_id'] ?? null,
                'quotation_id' => $validated['quotation_id'] ?? null,
                'invoice_date' => $validated['invoice_date'],
                'due_date' => $validated['due_date'],
                'payment_terms' => $validated['payment_terms'] ?? 'net_30',
                'remark' => $validated['remark'] ?? null,
                'customer_name' => $validated['customer_name'],
                'customer_phone' => $validated['customer_phone'] ?? null,
                'customer_email' => $validated['customer_email'] ?? null,
                'customer_address' => $validated['customer_address'],
                'contact_person' => $validated['contact_person'] ?? null,
                'contact_phone' => $validated['contact_phone'] ?? null,
                'subtotal' => $subtotal,
                'discount_total' => $discountTotal,
                'tax_total' => $taxTotal,
                'total' => $total,
            ]);

            // Delete existing items and create new ones
            $taxInvoice->items()->delete();
            
            foreach ($validated['items'] as $item) {
                TaxInvoiceItem::create([
                    'tax_invoice_id' => $taxInvoice->id,
                    'description' => $item['description'] ?? '',
                    'qty' => $item['qty'],
                    'uom' => $item['uom'],
                    'unit_price' => $item['unit_price'],
                    'discount_percent' => $item['discount_percent'] ?? 0,
                    'tax_percent' => $item['tax_percent'] ?? 0,
                    'amount' => $item['amount'],
                ]);
            }

            DB::commit();

            return redirect()->route('tax-invoice.show', $taxInvoice->id)
                ->with('success', 'Tax Invoice updated successfully');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->withErrors(['error' => 'Failed to update tax invoice: ' . $e->getMessage()]);
        }
    }

    public function destroy($id)
    {
        try {
            DB::beginTransaction();
            
            $taxInvoice = TaxInvoice::findOrFail($id);
            $quotationId = $taxInvoice->quotation_id;
            
            // Delete associated items first
            $taxInvoice->items()->delete();
            
            // Delete the tax invoice
            $taxInvoice->delete();
            
            // Check if quotation needs status rollback
            if ($quotationId) {
                $quotation = Quotation::find($quotationId);
                if ($quotation) {
                    // Check if there are any remaining tax invoices for this quotation
                    $remainingInvoices = TaxInvoice::where('quotation_id', $quotationId)->count();
                    
                    if ($remainingInvoices === 0) {
                        // No more invoices, rollback quotation status to 'accepted'
                        $quotation->update(['status' => 'accepted']);
                    }
                    // If there are remaining invoices, keep status as 'converted'
                }
            }
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Tax Invoice deleted successfully'
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete tax invoice: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Send tax invoice (change status to sent)
     */
    public function send($id)
    {
        $taxInvoice = TaxInvoice::findOrFail($id);
        
        if ($taxInvoice->status === 'draft') {
            $taxInvoice->update(['status' => 'sent']);
            return response()->json(['success' => true, 'message' => 'Tax invoice sent successfully']);
        }
        
        return response()->json(['success' => false, 'message' => 'Only draft invoices can be sent'], 400);
    }

    /**
     * Mark tax invoice as paid
     */
    public function markAsPaid($id)
    {
        $taxInvoice = TaxInvoice::findOrFail($id);
        
        if (in_array($taxInvoice->status, ['sent', 'partially_paid', 'overdue'])) {
            $taxInvoice->update(['status' => 'paid']);
            return response()->json(['success' => true, 'message' => 'Tax invoice marked as paid']);
        }
        
        return response()->json(['success' => false, 'message' => 'Invoice cannot be marked as paid'], 400);
    }

    /**
     * Mark tax invoice as partially paid
     */
    public function markAsPartiallyPaid($id)
    {
        $taxInvoice = TaxInvoice::findOrFail($id);
        
        if (in_array($taxInvoice->status, ['sent', 'overdue'])) {
            $taxInvoice->update(['status' => 'partially_paid']);
            return response()->json(['success' => true, 'message' => 'Tax invoice marked as partially paid']);
        }
        
        return response()->json(['success' => false, 'message' => 'Invoice cannot be marked as partially paid'], 400);
    }

    /**
     * Mark tax invoice as overdue
     */
    public function markAsOverdue($id)
    {
        $taxInvoice = TaxInvoice::findOrFail($id);
        
        if (in_array($taxInvoice->status, ['sent', 'partially_paid'])) {
            $taxInvoice->update(['status' => 'overdue']);
            return response()->json(['success' => true, 'message' => 'Tax invoice marked as overdue']);
        }
        
        return response()->json(['success' => false, 'message' => 'Invoice cannot be marked as overdue'], 400);
    }

    /**
     * Cancel tax invoice
     */
    public function cancel($id)
    {
        $taxInvoice = TaxInvoice::findOrFail($id);
        
        if (in_array($taxInvoice->status, ['draft', 'sent', 'partially_paid', 'overdue'])) {
            $taxInvoice->update(['status' => 'cancelled']);
            return response()->json(['success' => true, 'message' => 'Tax invoice cancelled successfully']);
        }
        
        return response()->json(['success' => false, 'message' => 'Invoice cannot be cancelled'], 400);
    }
}
