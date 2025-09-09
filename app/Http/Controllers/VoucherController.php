<?php

namespace App\Http\Controllers;

use App\Models\Voucher;
use App\Models\VoucherItem;
use App\Models\ExpenseCategory;
use App\Models\FirmSetting;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class VoucherController extends Controller
{
    public function index()
    {
        $vouchers = Voucher::with(['items'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('voucher', compact('vouchers'));
    }

    public function create()
    {
        $expenseCategories = ExpenseCategory::active()->ordered()->get();
        return view('voucher-create', compact('expenseCategories'));
    }

    public function show($id)
    {
        $voucher = Voucher::with(['items'])->findOrFail($id);
        return view('voucher-show', compact('voucher'));
    }

    public function print($id)
    {
        $voucher = Voucher::with(['items'])->findOrFail($id);
        $firmSettings = FirmSetting::getFirmSettings();

        $pdf = Pdf::loadView('voucher-print', compact('voucher', 'firmSettings'));
        return $pdf->download('voucher-' . $voucher->voucher_no . '.pdf');
    }

    public function edit($id)
    {
        $voucher = Voucher::with(['items'])->findOrFail($id);
        $expenseCategories = ExpenseCategory::active()->ordered()->get();
        return view('voucher-edit', compact('voucher', 'expenseCategories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'payee_name' => 'required|string|max:255',
            'payee_address' => 'nullable|string',
            'contact_person' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:255',
            'payment_method' => 'required|string',
            'payment_date' => 'required|date',
            'approved_by' => 'required|string|max:255',
            'remark' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.description' => 'required|string',
            'items.*.category' => 'nullable|string',
            'items.*.qty' => 'required|numeric|min:0.01',
            'items.*.uom' => 'required|string|max:32',
            'items.*.unit_price' => 'required|numeric|min:0',
            'items.*.discount_percent' => 'nullable|numeric|min:0|max:100',
            'items.*.tax_percent' => 'nullable|numeric|min:0|max:100',
        ]);

        return DB::transaction(function () use ($validated) {
            $voucher = Voucher::create([
                'voucher_no' => Voucher::generateVoucherNo(),
                'payee_name' => $validated['payee_name'],
                'payee_address' => $validated['payee_address'],
                'contact_person' => $validated['contact_person'],
                'phone' => $validated['phone'],
                'email' => $validated['email'],
                'payment_method' => $validated['payment_method'],
                'payment_date' => $validated['payment_date'],
                'approved_by' => $validated['approved_by'],
                'remark' => $validated['remark'],
                'status' => 'draft',
            ]);

            $subtotal = 0;
            $taxTotal = 0;

            foreach ($validated['items'] as $item) {
                $qty = $item['qty'];
                $unitPrice = $item['unit_price'];
                $discountPercent = $item['discount_percent'] ?? 0;
                $taxPercent = $item['tax_percent'] ?? 0;

                $lineTotal = $qty * $unitPrice;
                $discountAmount = $lineTotal * ($discountPercent / 100);
                $afterDiscount = $lineTotal - $discountAmount;
                $taxAmount = $afterDiscount * ($taxPercent / 100);
                $amount = $afterDiscount + $taxAmount;

                VoucherItem::create([
                    'voucher_id' => $voucher->id,
                    'description' => $item['description'],
                    'category' => $item['category'],
                    'qty' => $qty,
                    'uom' => $item['uom'],
                    'unit_price' => $unitPrice,
                    'discount_percent' => $discountPercent,
                    'tax_percent' => $taxPercent,
                    'amount' => $amount,
                ]);

                $subtotal += $afterDiscount;
                $taxTotal += $taxAmount;
            }

            $voucher->update([
                'subtotal' => $subtotal,
                'tax_total' => $taxTotal,
                'total_amount' => $subtotal + $taxTotal,
            ]);

            // Log voucher creation
            activity()
                ->performedOn($voucher)
                ->causedBy(auth()->user())
                ->withProperties([
                    'ip' => request()->ip(),
                    'voucher_no' => $voucher->voucher_no,
                    'total_amount' => $voucher->total_amount,
                    'payee_name' => $voucher->payee_name
                ])
                ->log("Voucher {$voucher->voucher_no} created");

            return redirect()->route('voucher.index')->with('success', 'Voucher created successfully.');
        });
    }

    public function update(Request $request, $id)
    {
        $voucher = Voucher::findOrFail($id);

        $validated = $request->validate([
            'payee_name' => 'required|string|max:255',
            'payee_address' => 'nullable|string',
            'contact_person' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:255',
            'payment_method' => 'required|string',
            'payment_date' => 'required|date',
            'approved_by' => 'required|string|max:255',
            'remark' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.description' => 'required|string',
            'items.*.category' => 'nullable|string',
            'items.*.qty' => 'required|numeric|min:0.01',
            'items.*.uom' => 'required|string|max:32',
            'items.*.unit_price' => 'required|numeric|min:0',
            'items.*.discount_percent' => 'nullable|numeric|min:0|max:100',
            'items.*.tax_percent' => 'nullable|numeric|min:0|max:100',
        ]);

        return DB::transaction(function () use ($voucher, $validated) {
            $voucher->update([
                'payee_name' => $validated['payee_name'],
                'payee_address' => $validated['payee_address'],
                'contact_person' => $validated['contact_person'],
                'phone' => $validated['phone'],
                'email' => $validated['email'],
                'payment_method' => $validated['payment_method'],
                'payment_date' => $validated['payment_date'],
                'approved_by' => $validated['approved_by'],
                'remark' => $validated['remark'],
            ]);

            // Delete existing items
            $voucher->items()->delete();

            $subtotal = 0;
            $taxTotal = 0;

            foreach ($validated['items'] as $item) {
                $qty = $item['qty'];
                $unitPrice = $item['unit_price'];
                $discountPercent = $item['discount_percent'] ?? 0;
                $taxPercent = $item['tax_percent'] ?? 0;

                $lineTotal = $qty * $unitPrice;
                $discountAmount = $lineTotal * ($discountPercent / 100);
                $afterDiscount = $lineTotal - $discountAmount;
                $taxAmount = $afterDiscount * ($taxPercent / 100);
                $amount = $afterDiscount + $taxAmount;

                VoucherItem::create([
                    'voucher_id' => $voucher->id,
                    'description' => $item['description'],
                    'category' => $item['category'],
                    'qty' => $qty,
                    'uom' => $item['uom'],
                    'unit_price' => $unitPrice,
                    'discount_percent' => $discountPercent,
                    'tax_percent' => $taxPercent,
                    'amount' => $amount,
                ]);

                $subtotal += $afterDiscount;
                $taxTotal += $taxAmount;
            }

            $voucher->update([
                'subtotal' => $subtotal,
                'tax_total' => $taxTotal,
                'total_amount' => $subtotal + $taxTotal,
            ]);

            return redirect()->route('voucher.show', $voucher->id)->with('success', 'Voucher updated successfully.');
        });
    }

    public function updateStatus(Request $request, $id)
    {
        $voucher = Voucher::findOrFail($id);

        $request->validate([
            'status' => 'required|in:draft,pending,approved,paid,cancelled'
        ]);

        $oldStatus = $voucher->status;
        $voucher->update([
            'status' => $request->status
        ]);

        // Log status change
        activity()
            ->performedOn($voucher)
            ->causedBy(auth()->user())
            ->withProperties([
                'ip' => request()->ip(),
                'old_status' => $oldStatus,
                'new_status' => $request->status
            ])
            ->log("Voucher {$voucher->voucher_no} status changed from {$oldStatus} to {$request->status}");

        return redirect()->back()->with('success', 'Voucher status updated successfully.');
    }

    public function destroy($id)
    {
        $voucher = Voucher::findOrFail($id);

        // Log voucher deletion before deleting
        activity()
            ->performedOn($voucher)
            ->causedBy(auth()->user())
            ->withProperties([
                'ip' => request()->ip(),
                'voucher_no' => $voucher->voucher_no,
                'total_amount' => $voucher->total_amount,
                'payee_name' => $voucher->payee_name,
                'status' => $voucher->status
            ])
            ->log("Voucher {$voucher->voucher_no} deleted");

        $voucher->delete();

        return redirect()->route('voucher.index')->with('success', 'Voucher deleted successfully.');
    }
}


