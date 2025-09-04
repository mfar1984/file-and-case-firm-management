<?php

namespace App\Http\Controllers;

use App\Models\Voucher;
use App\Models\VoucherItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class VoucherController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'payee_id' => 'required|exists:payees,id',
            'payment_method' => 'required|string',
            'payment_date' => 'required|date',
            'approved_by' => 'required|string|max:255',
            'remark' => 'nullable|string|max:255',
            'payee_address' => 'nullable|string',
            'contact_person' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:50',
            'expenses' => 'required|string', // JSON array
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $expenses = json_decode($request->input('expenses'), true) ?? [];
        $expenses = array_values(array_filter($expenses, function ($e) {
            return (isset($e['amount']) && (float)$e['amount'] > 0) || !empty($e['description']);
        }));

        if (count($expenses) === 0) {
            return back()->withErrors(['expenses' => 'Please add at least one expense item.'])->withInput();
        }

        return DB::transaction(function () use ($request, $expenses) {
            $voucher = Voucher::create([
                'voucher_no' => Voucher::generateVoucherNo(),
                'payee_id' => $request->payee_id,
                'payee_address' => $request->payee_address,
                'contact_person' => $request->contact_person,
                'phone' => $request->phone,
                'payment_method' => $request->payment_method,
                'payment_date' => $request->payment_date,
                'approved_by' => $request->approved_by,
                'remark' => $request->remark,
                'total_amount' => 0,
            ]);

            $total = 0;
            foreach ($expenses as $row) {
                $amount = (float)($row['amount'] ?? 0);
                $total += $amount;
                VoucherItem::create([
                    'voucher_id' => $voucher->id,
                    'description' => $row['description'] ?? '',
                    'category' => $row['category'] ?? '',
                    'amount' => $amount,
                ]);
            }

            $voucher->update(['total_amount' => $total]);

            return redirect()->route('voucher.index')->with('success', 'Voucher created successfully.');
        });
    }
}


