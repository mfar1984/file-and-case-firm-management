<?php

namespace App\Http\Controllers;

use App\Models\PreQuotation;
use App\Models\PreQuotationItem;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Carbon;
use Illuminate\Http\Request;

class PreQuotationController extends Controller
{
    public function index()
    {
        // Get all pre-quotations with related data
        $preQuotations = PreQuotation::with(['items'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('pre-quotation', compact('preQuotations'));
    }

    public function create(Request $request)
    {
        // No case options needed for pre-quotation
        // All fields are optional
        return view('pre-quotation-create');
    }

    public function store(Request $request)
    {
        // Validate request - all fields are optional for pre-quotation
        $request->validate([
            'quotation_date' => 'required|date',
            'valid_until' => 'nullable|date',
            'payment_terms' => 'nullable|string',
            'full_name' => 'nullable|string',
            'customer_phone' => 'nullable|string',
            'customer_email' => 'nullable|email',
            'customer_address' => 'nullable|string',
            'remark' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.description' => 'nullable|string',
            'items.*.qty' => 'required|numeric|min:0',
            'items.*.uom' => 'nullable|string',
            'items.*.unit_price' => 'required|numeric|min:0',
            'items.*.discount_percent' => 'nullable|numeric|min:0|max:100',
            'items.*.tax_percent' => 'nullable|numeric|min:0|max:100',
        ]);

        // Generate quotation number
        $lastQuotation = PreQuotation::orderBy('id', 'desc')->first();
        $nextNumber = $lastQuotation ? (int)substr($lastQuotation->quotation_no, 3) + 1 : 1;
        $quotationNo = 'PQ-' . str_pad($nextNumber, 5, '0', STR_PAD_LEFT);

        // Calculate totals
        $subtotal = 0;
        $discountTotal = 0;
        $taxTotal = 0;

        foreach ($request->items as $item) {
            $qty = (float)$item['qty'];
            $unitPrice = (float)$item['unit_price'];
            $discountPercent = (float)($item['discount_percent'] ?? 0);
            $taxPercent = (float)($item['tax_percent'] ?? 0);

            $lineTotal = $qty * $unitPrice;
            $discountAmount = $lineTotal * ($discountPercent / 100);
            $afterDiscount = $lineTotal - $discountAmount;
            $taxAmount = $afterDiscount * ($taxPercent / 100);

            $subtotal += $lineTotal;
            $discountTotal += $discountAmount;
            $taxTotal += $taxAmount;
        }

        $total = $subtotal - $discountTotal + $taxTotal;

        // Create pre-quotation
        $preQuotation = PreQuotation::create([
            'quotation_no' => $quotationNo,
            'quotation_date' => $request->quotation_date,
            'valid_until' => $request->valid_until,
            'payment_terms' => $request->payment_terms,
            'full_name' => $request->full_name,
            'customer_phone' => $request->customer_phone,
            'customer_email' => $request->customer_email,
            'customer_address' => $request->customer_address,
            'remark' => $request->remark,
            'subtotal' => $subtotal,
            'discount_total' => $discountTotal,
            'tax_total' => $taxTotal,
            'total' => $total,
            'status' => 'pending',
        ]);

        // Create items
        foreach ($request->items as $item) {
            $qty = (float)$item['qty'];
            $unitPrice = (float)$item['unit_price'];
            $discountPercent = (float)($item['discount_percent'] ?? 0);
            $taxPercent = (float)($item['tax_percent'] ?? 0);

            $lineTotal = $qty * $unitPrice;
            $discountAmount = $lineTotal * ($discountPercent / 100);
            $afterDiscount = $lineTotal - $discountAmount;
            $taxAmount = $afterDiscount * ($taxPercent / 100);
            $amount = $afterDiscount + $taxAmount;

            PreQuotationItem::create([
                'pre_quotation_id' => $preQuotation->id,
                'description' => $item['description'],
                'qty' => $qty,
                'uom' => $item['uom'] ?? 'lot',
                'unit_price' => $unitPrice,
                'discount_percent' => $discountPercent,
                'tax_percent' => $taxPercent,
                'amount' => $amount,
            ]);
        }

        return redirect()->route('pre-quotation.index')->with('success', 'Pre-quotation created successfully!');
    }

    public function show($id)
    {
        $preQuotation = PreQuotation::with(['items'])->findOrFail($id);
        return view('pre-quotation-show', compact('preQuotation'));
    }

    public function edit($id)
    {
        $preQuotation = PreQuotation::with(['items'])->findOrFail($id);
        return view('pre-quotation-create', compact('preQuotation'));
    }

    public function update(Request $request, $id)
    {
        $preQuotation = PreQuotation::findOrFail($id);

        // Validate request - all fields are optional for pre-quotation
        $request->validate([
            'quotation_date' => 'required|date',
            'valid_until' => 'nullable|date',
            'payment_terms' => 'nullable|string',
            'full_name' => 'nullable|string',
            'customer_phone' => 'nullable|string',
            'customer_email' => 'nullable|email',
            'customer_address' => 'nullable|string',
            'remark' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.description' => 'nullable|string',
            'items.*.qty' => 'required|numeric|min:0',
            'items.*.uom' => 'nullable|string',
            'items.*.unit_price' => 'required|numeric|min:0',
            'items.*.discount_percent' => 'nullable|numeric|min:0|max:100',
            'items.*.tax_percent' => 'nullable|numeric|min:0|max:100',
        ]);

        // Calculate totals
        $subtotal = 0;
        $discountTotal = 0;
        $taxTotal = 0;

        foreach ($request->items as $item) {
            $qty = (float)$item['qty'];
            $unitPrice = (float)$item['unit_price'];
            $discountPercent = (float)($item['discount_percent'] ?? 0);
            $taxPercent = (float)($item['tax_percent'] ?? 0);

            $lineTotal = $qty * $unitPrice;
            $discountAmount = $lineTotal * ($discountPercent / 100);
            $afterDiscount = $lineTotal - $discountAmount;
            $taxAmount = $afterDiscount * ($taxPercent / 100);

            $subtotal += $lineTotal;
            $discountTotal += $discountAmount;
            $taxTotal += $taxAmount;
        }

        $total = $subtotal - $discountTotal + $taxTotal;

        // Update pre-quotation
        $preQuotation->update([
            'quotation_date' => $request->quotation_date,
            'valid_until' => $request->valid_until,
            'payment_terms' => $request->payment_terms,
            'full_name' => $request->full_name,
            'customer_phone' => $request->customer_phone,
            'customer_email' => $request->customer_email,
            'customer_address' => $request->customer_address,
            'remark' => $request->remark,
            'subtotal' => $subtotal,
            'discount_total' => $discountTotal,
            'tax_total' => $taxTotal,
            'total' => $total,
        ]);

        // Delete existing items and create new ones
        $preQuotation->items()->delete();

        foreach ($request->items as $item) {
            $qty = (float)$item['qty'];
            $unitPrice = (float)$item['unit_price'];
            $discountPercent = (float)($item['discount_percent'] ?? 0);
            $taxPercent = (float)($item['tax_percent'] ?? 0);

            $lineTotal = $qty * $unitPrice;
            $discountAmount = $lineTotal * ($discountPercent / 100);
            $afterDiscount = $lineTotal - $discountAmount;
            $taxAmount = $afterDiscount * ($taxPercent / 100);
            $amount = $afterDiscount + $taxAmount;

            PreQuotationItem::create([
                'pre_quotation_id' => $preQuotation->id,
                'description' => $item['description'],
                'qty' => $qty,
                'uom' => $item['uom'] ?? 'lot',
                'unit_price' => $unitPrice,
                'discount_percent' => $discountPercent,
                'tax_percent' => $taxPercent,
                'amount' => $amount,
            ]);
        }

        return redirect()->route('pre-quotation.show', $preQuotation->id)->with('success', 'Pre-quotation updated successfully!');
    }

    public function destroy($id)
    {
        $preQuotation = PreQuotation::findOrFail($id);
        $preQuotation->delete();

        return redirect()->route('pre-quotation.index')->with('success', 'Pre-quotation deleted successfully!');
    }
}
