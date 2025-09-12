<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Receipt;
use App\Models\Bill;
use App\Models\Voucher;
use App\Models\ExpenseCategory;
use App\Models\OpeningBalance;
use App\Models\Firm;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class JournalReportController extends Controller
{
    public function index(Request $request)
    {
        // Get date range from request or default to current month
        $startDate = $request->get('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->get('end_date', Carbon::now()->endOfMonth()->format('Y-m-d'));

        // Get all transactions within date range and convert to journal entries with firm scope
        $journalEntries = collect();
        $user = auth()->user();

        // Process Receipts (Money In) with firm scope filtering
        if ($user->hasRole('Super Administrator')) {
            if (session('current_firm_id')) {
                $receipts = Receipt::forFirm(session('current_firm_id'))
                    ->whereBetween('receipt_date', [$startDate, $endDate])
                    ->with(['case', 'quotation', 'taxInvoice'])
                    ->get();
            } else {
                $receipts = Receipt::withoutFirmScope()
                    ->whereBetween('receipt_date', [$startDate, $endDate])
                    ->with(['case', 'quotation', 'taxInvoice'])
                    ->get();
            }
        } else {
            $receipts = Receipt::whereBetween('receipt_date', [$startDate, $endDate])
                ->with(['case', 'quotation', 'taxInvoice'])
                ->get();
        }
            
        foreach ($receipts as $receipt) {
            $journalEntries->push([
                'date' => $receipt->receipt_date,
                'reference' => $receipt->receipt_no,
                'description' => 'Receipt from ' . $receipt->customer_name,
                'account' => 'Cash/Bank Account',
                'debit' => $receipt->amount_paid,
                'credit' => 0,
                'type' => 'Receipt',
                'id' => $receipt->id
            ]);
            
            $journalEntries->push([
                'date' => $receipt->receipt_date,
                'reference' => $receipt->receipt_no,
                'description' => 'Receipt from ' . $receipt->customer_name,
                'account' => 'Revenue - Legal Fees',
                'debit' => 0,
                'credit' => $receipt->amount_paid,
                'type' => 'Receipt',
                'id' => $receipt->id
            ]);
        }
        
        // Get active categories for proper mapping with firm scope
        if ($user->hasRole('Super Administrator')) {
            if (session('current_firm_id')) {
                $activeCategories = ExpenseCategory::forFirm(session('current_firm_id'))->active()->ordered()->get();
            } else {
                $activeCategories = ExpenseCategory::withoutFirmScope()->active()->ordered()->get();
            }
        } else {
            $activeCategories = ExpenseCategory::active()->ordered()->get();
        }
        $categoryMap = $activeCategories->pluck('name', 'name')->toArray();

        // Process Bills (Money Out for Expenses) with firm scope filtering
        if ($user->hasRole('Super Administrator')) {
            if (session('current_firm_id')) {
                $bills = Bill::forFirm(session('current_firm_id'))
                    ->whereBetween('bill_date', [$startDate, $endDate])
                    ->with(['items'])
                    ->get();
            } else {
                $bills = Bill::withoutFirmScope()
                    ->whereBetween('bill_date', [$startDate, $endDate])
                    ->with(['items'])
                    ->get();
            }
        } else {
            $bills = Bill::whereBetween('bill_date', [$startDate, $endDate])
                ->with(['items'])
                ->get();
        }

        foreach ($bills as $bill) {
            // Use proper category mapping or default to 'Other'
            $category = $bill->category && isset($categoryMap[$bill->category])
                ? $bill->category
                : 'Other';

            $journalEntries->push([
                'date' => $bill->bill_date,
                'reference' => $bill->bill_no,
                'description' => 'Bill from ' . $bill->vendor_name,
                'account' => 'Expenses - ' . $category,
                'debit' => $bill->total_amount,
                'credit' => 0,
                'type' => 'Bill',
                'category' => $category,
                'id' => $bill->id
            ]);

            $journalEntries->push([
                'date' => $bill->bill_date,
                'reference' => $bill->bill_no,
                'description' => 'Bill from ' . $bill->vendor_name,
                'account' => 'Cash/Bank Account',
                'debit' => 0,
                'credit' => $bill->total_amount,
                'type' => 'Bill',
                'category' => $category,
                'id' => $bill->id
            ]);
        }
        
        // Process Vouchers (Money Out for Payments) with firm scope filtering
        if ($user->hasRole('Super Administrator')) {
            if (session('current_firm_id')) {
                $vouchers = Voucher::forFirm(session('current_firm_id'))
                    ->whereBetween('payment_date', [$startDate, $endDate])
                    ->with(['items'])
                    ->get();
            } else {
                $vouchers = Voucher::withoutFirmScope()
                    ->whereBetween('payment_date', [$startDate, $endDate])
                    ->with(['items'])
                    ->get();
            }
        } else {
            $vouchers = Voucher::whereBetween('payment_date', [$startDate, $endDate])
                ->with(['items'])
                ->get();
        }
            
        foreach ($vouchers as $voucher) {
            // Get primary category from voucher items
            $primaryCategory = 'Other';
            if ($voucher->items->isNotEmpty()) {
                $firstItem = $voucher->items->first();
                $primaryCategory = $firstItem->category && isset($categoryMap[$firstItem->category])
                    ? $firstItem->category
                    : 'Other';
            }

            $journalEntries->push([
                'date' => $voucher->payment_date,
                'reference' => $voucher->voucher_no,
                'description' => 'Payment to ' . $voucher->payee_name,
                'account' => 'Expenses - ' . $primaryCategory,
                'debit' => $voucher->total_amount,
                'credit' => 0,
                'type' => 'Voucher',
                'category' => $primaryCategory,
                'id' => $voucher->id
            ]);

            $journalEntries->push([
                'date' => $voucher->payment_date,
                'reference' => $voucher->voucher_no,
                'description' => 'Payment to ' . $voucher->payee_name,
                'account' => 'Cash/Bank Account',
                'debit' => 0,
                'credit' => $voucher->total_amount,
                'type' => 'Voucher',
                'category' => $primaryCategory,
                'id' => $voucher->id
            ]);
        }
        
        // Sort by date and reference
        $journalEntries = $journalEntries->sortBy(['date', 'reference'])->values();
        
        // Group by transaction (same date and reference)
        $groupedEntries = $journalEntries->groupBy(function ($entry) {
            return $entry['date']->format('Y-m-d') . '-' . $entry['reference'];
        });
        
        // Calculate totals
        $totalDebit = $journalEntries->sum('debit');
        $totalCredit = $journalEntries->sum('credit');
        
        return view('journal-report', compact(
            'groupedEntries',
            'journalEntries',
            'startDate',
            'endDate',
            'totalDebit',
            'totalCredit'
        ));
    }

    public function print(Request $request)
    {
        $startDate = $request->get('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->get('end_date', Carbon::now()->endOfMonth()->format('Y-m-d'));

        $journalEntries = collect();
        $user = auth()->user();

        // Process Receipts (Money In) with firm scope filtering
        if ($user->hasRole('Super Administrator')) {
            if (session('current_firm_id')) {
                $receipts = Receipt::forFirm(session('current_firm_id'))
                    ->whereBetween('receipt_date', [$startDate, $endDate])
                    ->with(['case', 'quotation', 'taxInvoice'])
                    ->get();
            } else {
                $receipts = Receipt::withoutFirmScope()
                    ->whereBetween('receipt_date', [$startDate, $endDate])
                    ->with(['case', 'quotation', 'taxInvoice'])
                    ->get();
            }
        } else {
            $receipts = Receipt::whereBetween('receipt_date', [$startDate, $endDate])
                ->with(['case', 'quotation', 'taxInvoice'])
                ->get();
        }

        foreach ($receipts as $receipt) {
            $journalEntries->push([
                'date' => $receipt->receipt_date,
                'reference' => $receipt->receipt_no,
                'description' => 'Receipt from ' . $receipt->customer_name,
                'account' => 'Cash/Bank Account',
                'debit' => $receipt->amount_paid,
                'credit' => 0,
                'type' => 'Receipt',
                'id' => $receipt->id
            ]);

            $journalEntries->push([
                'date' => $receipt->receipt_date,
                'reference' => $receipt->receipt_no,
                'description' => 'Receipt from ' . $receipt->customer_name,
                'account' => 'Revenue - Legal Fees',
                'debit' => 0,
                'credit' => $receipt->amount_paid,
                'type' => 'Receipt',
                'id' => $receipt->id
            ]);
        }

        // Process Bills (Money Out)
        $activeCategories = ExpenseCategory::active()->ordered()->get();
        $categoryMap = $activeCategories->pluck('name', 'name')->toArray();

        $bills = Bill::whereBetween('bill_date', [$startDate, $endDate])->get();
        foreach ($bills as $bill) {
            $primaryCategory = $categoryMap[$bill->category] ?? $bill->category;

            $journalEntries->push([
                'date' => $bill->bill_date,
                'reference' => $bill->bill_no,
                'description' => 'Bill from ' . $bill->vendor_name,
                'account' => 'Expenses - ' . $primaryCategory,
                'debit' => $bill->total_amount,
                'credit' => 0,
                'type' => 'Bill',
                'category' => $primaryCategory,
                'id' => $bill->id
            ]);

            $journalEntries->push([
                'date' => $bill->bill_date,
                'reference' => $bill->bill_no,
                'description' => 'Bill from ' . $bill->vendor_name,
                'account' => 'Cash/Bank Account',
                'debit' => 0,
                'credit' => $bill->total_amount,
                'type' => 'Bill',
                'category' => $primaryCategory,
                'id' => $bill->id
            ]);
        }

        // Process Vouchers (Money Out)
        $vouchers = Voucher::whereBetween('payment_date', [$startDate, $endDate])
            ->with(['items'])
            ->get();

        foreach ($vouchers as $voucher) {
            foreach ($voucher->items as $item) {
                $primaryCategory = $categoryMap[$item->category] ?? $item->category;

                $journalEntries->push([
                    'date' => $voucher->payment_date,
                    'reference' => $voucher->voucher_no,
                    'description' => 'Payment to ' . $voucher->payee_name,
                    'account' => 'Expenses - ' . $primaryCategory,
                    'debit' => $item->amount,
                    'credit' => 0,
                    'type' => 'Voucher',
                    'category' => $primaryCategory,
                    'id' => $voucher->id
                ]);
            }

            $journalEntries->push([
                'date' => $voucher->payment_date,
                'reference' => $voucher->voucher_no,
                'description' => 'Payment to ' . $voucher->payee_name,
                'account' => 'Cash/Bank Account',
                'debit' => 0,
                'credit' => $voucher->total_amount,
                'type' => 'Voucher',
                'category' => $primaryCategory,
                'id' => $voucher->id
            ]);
        }

        $journalEntries = $journalEntries->sortBy(['date', 'reference'])->values();

        $groupedEntries = $journalEntries->groupBy(function ($entry) {
            return $entry['date']->format('Y-m-d') . '-' . $entry['reference'];
        });

        $totalDebit = $journalEntries->sum('debit');
        $totalCredit = $journalEntries->sum('credit');

        // Get firm settings for header
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

        $reportTitle = 'Journal Report';
        $dateRange = date('d/m/Y', strtotime($startDate)) . ' - ' . date('d/m/Y', strtotime($endDate));

        // Generate PDF
        $pdf = Pdf::loadView('journal-report-print', compact(
            'groupedEntries', 'journalEntries', 'startDate', 'endDate', 'totalDebit', 'totalCredit',
            'firmSettings', 'reportTitle', 'dateRange'
        ));

        $pdf->setPaper('A4', 'portrait');

        // Generate filename with date range
        $filename = 'Journal_Report_' . date('Y-m-d', strtotime($startDate)) . '_to_' . date('Y-m-d', strtotime($endDate)) . '.pdf';

        return $pdf->download($filename);
    }
}
