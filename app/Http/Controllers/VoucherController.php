<?php

namespace App\Http\Controllers;

use App\Models\Voucher;
use App\Models\VoucherItem;
use App\Models\ExpenseCategory;
use App\Models\Payee;
use App\Models\FirmSetting;
use App\Models\Firm;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class VoucherController extends Controller
{
    public function index()
    {
        // Get vouchers with proper firm scope filtering
        $user = auth()->user();

        if ($user->hasRole('Super Administrator')) {
            // Super Admin can see all vouchers or filter by session firm
            if (session('current_firm_id')) {
                $vouchers = Voucher::forFirm(session('current_firm_id'))
                    ->with(['items'])
                    ->orderBy('created_at', 'desc')
                    ->get();
            } else {
                $vouchers = Voucher::withoutFirmScope()
                    ->with(['items'])
                    ->orderBy('created_at', 'desc')
                    ->get();
            }
        } else {
            // Regular users see only their firm's vouchers (HasFirmScope trait handles this)
            $vouchers = Voucher::with(['items'])
                ->orderBy('created_at', 'desc')
                ->get();
        }

        return view('voucher', compact('vouchers'));
    }

    public function create()
    {
        // Get expense categories and payees with proper firm scope filtering
        $user = auth()->user();

        if ($user->hasRole('Super Administrator')) {
            // Super Admin can see data from session firm or all firms
            if (session('current_firm_id')) {
                $expenseCategories = ExpenseCategory::forFirm(session('current_firm_id'))
                    ->active()
                    ->ordered()
                    ->get();
                $payees = Payee::forFirm(session('current_firm_id'))
                    ->active()
                    ->orderBy('name')
                    ->get();
            } else {
                $expenseCategories = ExpenseCategory::withoutFirmScope()
                    ->active()
                    ->ordered()
                    ->get();
                $payees = Payee::withoutFirmScope()
                    ->active()
                    ->orderBy('name')
                    ->get();
            }
        } else {
            // Regular users see only their firm's data (HasFirmScope trait handles this)
            $expenseCategories = ExpenseCategory::active()->ordered()->get();
            $payees = Payee::active()->orderBy('name')->get();
        }

        return view('voucher-create', compact('expenseCategories', 'payees'));
    }

    public function show($id)
    {
        // Find voucher with firm scope validation
        $user = auth()->user();

        if ($user->hasRole('Super Administrator')) {
            // Super Admin can access any voucher
            $voucher = Voucher::withoutFirmScope()
                ->with(['items'])
                ->findOrFail($id);
        } else {
            // Regular users can only access vouchers from their firm (HasFirmScope trait handles this)
            $voucher = Voucher::with(['items'])->findOrFail($id);
        }

        return view('voucher-show', compact('voucher'));
    }

    public function print($id)
    {
        $voucher = Voucher::with(['items'])->findOrFail($id);

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

        $pdf = Pdf::loadView('voucher-print', compact('voucher', 'firmSettings'));
        return $pdf->download('voucher-' . $voucher->voucher_no . '.pdf');
    }

    public function edit($id)
    {
        // Find voucher with firm scope validation
        $user = auth()->user();

        if ($user->hasRole('Super Administrator')) {
            // Super Admin can edit any voucher
            $voucher = Voucher::withoutFirmScope()
                ->with(['items'])
                ->findOrFail($id);

            // Get expense categories based on voucher's firm or session firm
            if (session('current_firm_id')) {
                $expenseCategories = ExpenseCategory::forFirm(session('current_firm_id'))
                    ->active()
                    ->ordered()
                    ->get();
            } else {
                // If no session firm, use the voucher's firm
                $expenseCategories = ExpenseCategory::forFirm($voucher->firm_id)
                    ->active()
                    ->ordered()
                    ->get();
            }
        } else {
            // Regular users can only edit vouchers from their firm (HasFirmScope trait handles this)
            $voucher = Voucher::with(['items'])->findOrFail($id);
            // Get firm-scoped expense categories for regular users
            $expenseCategories = ExpenseCategory::active()->ordered()->get();
        }

        // Get payees for the dropdown (same logic as create method)
        if ($user->hasRole('Super Administrator')) {
            if (session('current_firm_id')) {
                $payees = Payee::forFirm(session('current_firm_id'))->active()->orderBy('name')->get();
            } else {
                // If no session firm, use the voucher's firm
                $payees = Payee::forFirm($voucher->firm_id)->active()->orderBy('name')->get();
            }
        } else {
            $payees = Payee::active()->orderBy('name')->get();
        }

        return view('voucher-edit', compact('voucher', 'expenseCategories', 'payees'));
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
        // Find voucher with firm scope validation
        $user = auth()->user();

        if ($user->hasRole('Super Administrator')) {
            // Super Admin can update any voucher
            $voucher = Voucher::withoutFirmScope()->findOrFail($id);
        } else {
            // Regular users can only update vouchers from their firm (HasFirmScope trait handles this)
            $voucher = Voucher::findOrFail($id);
        }

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
        // Find voucher with firm scope validation
        $user = auth()->user();

        if ($user->hasRole('Super Administrator')) {
            // Super Admin can delete any voucher
            $voucher = Voucher::withoutFirmScope()->findOrFail($id);
        } else {
            // Regular users can only delete vouchers from their firm (HasFirmScope trait handles this)
            $voucher = Voucher::findOrFail($id);
        }

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


