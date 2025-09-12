<?php

namespace App\Http\Controllers;

use App\Models\Bill;
use App\Models\BillItem;
use App\Models\ExpenseCategory;
use App\Models\FirmSetting;
use App\Models\Firm;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BillController extends Controller
{
    public function index()
    {
        // Get bills with proper firm scope filtering
        $user = auth()->user();

        if ($user->hasRole('Super Administrator')) {
            // Super Admin can see all bills or filter by session firm
            if (session('current_firm_id')) {
                $bills = Bill::forFirm(session('current_firm_id'))
                    ->with(['items'])
                    ->orderBy('created_at', 'desc')
                    ->get();
            } else {
                $bills = Bill::withoutFirmScope()
                    ->with(['items'])
                    ->orderBy('created_at', 'desc')
                    ->get();
            }
        } else {
            // Regular users see only their firm's bills (HasFirmScope trait handles this)
            $bills = Bill::with(['items'])
                ->orderBy('created_at', 'desc')
                ->get();
        }

        return view('bill', compact('bills'));
    }

    public function create()
    {
        // Get expense categories with proper firm scope filtering
        $user = auth()->user();

        if ($user->hasRole('Super Administrator')) {
            // Super Admin can see expense categories from session firm or all firms
            if (session('current_firm_id')) {
                $expenseCategories = ExpenseCategory::forFirm(session('current_firm_id'))
                    ->active()
                    ->ordered()
                    ->get();
            } else {
                $expenseCategories = ExpenseCategory::withoutFirmScope()
                    ->active()
                    ->ordered()
                    ->get();
            }
        } else {
            // Regular users see only their firm's expense categories (HasFirmScope trait handles this)
            $expenseCategories = ExpenseCategory::active()->ordered()->get();
        }

        return view('bill-create', compact('expenseCategories'));
    }

    public function show($id)
    {
        // Find bill with firm scope validation
        $user = auth()->user();

        if ($user->hasRole('Super Administrator')) {
            // Super Admin can access any bill
            $bill = Bill::withoutFirmScope()
                ->with(['items'])
                ->findOrFail($id);
        } else {
            // Regular users can only access bills from their firm (HasFirmScope trait handles this)
            $bill = Bill::with(['items'])->findOrFail($id);
        }

        return view('bill-show', compact('bill'));
    }

    public function print($id)
    {
        $bill = Bill::with(['items'])->findOrFail($id);

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

        $pdf = Pdf::loadView('bill-print', compact('bill', 'firmSettings'));
        return $pdf->download('bill-' . $bill->bill_no . '.pdf');
    }

    public function edit($id)
    {
        // Find bill with firm scope validation
        $user = auth()->user();

        if ($user->hasRole('Super Administrator')) {
            // Super Admin can edit any bill
            $bill = Bill::withoutFirmScope()
                ->with(['items'])
                ->findOrFail($id);

            // Get expense categories based on bill's firm or session firm
            if (session('current_firm_id')) {
                $expenseCategories = ExpenseCategory::forFirm(session('current_firm_id'))
                    ->active()
                    ->ordered()
                    ->get();
            } else {
                // If no session firm, use the bill's firm
                $expenseCategories = ExpenseCategory::forFirm($bill->firm_id)
                    ->active()
                    ->ordered()
                    ->get();
            }
        } else {
            // Regular users can only edit bills from their firm (HasFirmScope trait handles this)
            $bill = Bill::with(['items'])->findOrFail($id);
            // Get firm-scoped expense categories for regular users
            $expenseCategories = ExpenseCategory::active()->ordered()->get();
        }

        return view('bill-edit', compact('bill', 'expenseCategories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'vendor_name' => 'required|string|max:255',
            'vendor_address' => 'nullable|string',
            'vendor_phone' => 'nullable|string|max:50',
            'vendor_email' => 'nullable|email|max:255',
            'bill_date' => 'required|date',
            'due_date' => 'required|date|after_or_equal:bill_date',
            'category' => 'nullable|string|max:255',
            'description' => 'nullable|string',
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
            $bill = Bill::create([
                'bill_no' => Bill::generateBillNo(),
                'vendor_name' => $validated['vendor_name'],
                'vendor_address' => $validated['vendor_address'],
                'vendor_phone' => $validated['vendor_phone'],
                'vendor_email' => $validated['vendor_email'],
                'bill_date' => $validated['bill_date'],
                'due_date' => $validated['due_date'],
                'category' => $validated['category'],
                'description' => $validated['description'],
                'remark' => $validated['remark'],
                'status' => 'pending',
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

                BillItem::create([
                    'bill_id' => $bill->id,
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

            $bill->update([
                'subtotal' => $subtotal,
                'tax_total' => $taxTotal,
                'total_amount' => $subtotal + $taxTotal,
            ]);

            // Log bill creation
            activity()
                ->performedOn($bill)
                ->causedBy(auth()->user())
                ->withProperties([
                    'ip' => request()->ip(),
                    'bill_no' => $bill->bill_no,
                    'total_amount' => $bill->total_amount,
                    'vendor_name' => $bill->vendor_name
                ])
                ->log("Bill {$bill->bill_no} created");

            return redirect()->route('bill.index')->with('success', 'Bill created successfully.');
        });
    }

    public function update(Request $request, $id)
    {
        // Find bill with firm scope validation
        $user = auth()->user();

        if ($user->hasRole('Super Administrator')) {
            // Super Admin can update any bill
            $bill = Bill::withoutFirmScope()->findOrFail($id);
        } else {
            // Regular users can only update bills from their firm (HasFirmScope trait handles this)
            $bill = Bill::findOrFail($id);
        }
        
        $validated = $request->validate([
            'vendor_name' => 'required|string|max:255',
            'vendor_address' => 'nullable|string',
            'vendor_phone' => 'nullable|string|max:50',
            'vendor_email' => 'nullable|email|max:255',
            'bill_date' => 'required|date',
            'due_date' => 'required|date|after_or_equal:bill_date',
            'category' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'payment_method' => 'nullable|string',
            'payment_reference' => 'nullable|string',
            'payment_date' => 'nullable|date',
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

        return DB::transaction(function () use ($bill, $validated) {
            $bill->update([
                'vendor_name' => $validated['vendor_name'],
                'vendor_address' => $validated['vendor_address'],
                'vendor_phone' => $validated['vendor_phone'],
                'vendor_email' => $validated['vendor_email'],
                'bill_date' => $validated['bill_date'],
                'due_date' => $validated['due_date'],
                'category' => $validated['category'],
                'description' => $validated['description'],
                'payment_method' => $validated['payment_method'],
                'payment_reference' => $validated['payment_reference'],
                'payment_date' => $validated['payment_date'],
                'remark' => $validated['remark'],
            ]);

            // Delete existing items
            $bill->items()->delete();

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

                BillItem::create([
                    'bill_id' => $bill->id,
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

            $bill->update([
                'subtotal' => $subtotal,
                'tax_total' => $taxTotal,
                'total_amount' => $subtotal + $taxTotal,
            ]);

            return redirect()->route('bill.show', $bill->id)->with('success', 'Bill updated successfully.');
        });
    }

    public function updateStatus(Request $request, $id)
    {
        $bill = Bill::findOrFail($id);

        $request->validate([
            'status' => 'required|in:draft,pending,overdue,paid,cancelled'
        ]);

        $oldStatus = $bill->status;
        $bill->update([
            'status' => $request->status
        ]);

        // Log status change
        activity()
            ->performedOn($bill)
            ->causedBy(auth()->user())
            ->withProperties([
                'ip' => request()->ip(),
                'old_status' => $oldStatus,
                'new_status' => $request->status
            ])
            ->log("Bill {$bill->bill_no} status changed from {$oldStatus} to {$request->status}");

        return redirect()->back()->with('success', 'Bill status updated successfully.');
    }

    public function destroy($id)
    {
        // Find bill with firm scope validation
        $user = auth()->user();

        if ($user->hasRole('Super Administrator')) {
            // Super Admin can delete any bill
            $bill = Bill::withoutFirmScope()->findOrFail($id);
        } else {
            // Regular users can only delete bills from their firm (HasFirmScope trait handles this)
            $bill = Bill::findOrFail($id);
        }

        // Log bill deletion before deleting
        activity()
            ->performedOn($bill)
            ->causedBy(auth()->user())
            ->withProperties([
                'ip' => request()->ip(),
                'bill_no' => $bill->bill_no,
                'total_amount' => $bill->total_amount,
                'vendor_name' => $bill->vendor_name,
                'status' => $bill->status
            ])
            ->log("Bill {$bill->bill_no} deleted");

        $bill->delete();

        return redirect()->route('bill.index')->with('success', 'Bill deleted successfully.');
    }
}
