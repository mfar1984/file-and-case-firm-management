<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Receipt;
use App\Models\Bill;
use App\Models\Voucher;
use App\Models\ExpenseCategory;
use App\Models\OpeningBalance;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class JournalReportController extends Controller
{
    public function index(Request $request)
    {
        // Get date range from request or default to current month
        $startDate = $request->get('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->get('end_date', Carbon::now()->endOfMonth()->format('Y-m-d'));
        
        // Get all transactions within date range and convert to journal entries
        $journalEntries = collect();
        
        // Process Receipts (Money In)
        $receipts = Receipt::whereBetween('receipt_date', [$startDate, $endDate])
            ->with(['case', 'quotation', 'taxInvoice'])
            ->get();
            
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
        
        // Get active categories for proper mapping
        $activeCategories = ExpenseCategory::active()->ordered()->get();
        $categoryMap = $activeCategories->pluck('name', 'name')->toArray();

        // Process Bills (Money Out for Expenses)
        $bills = Bill::whereBetween('bill_date', [$startDate, $endDate])
            ->with(['items'])
            ->get();

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
        
        // Process Vouchers (Money Out for Payments)
        $vouchers = Voucher::whereBetween('payment_date', [$startDate, $endDate])
            ->with(['items'])
            ->get();
            
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

        // Process Receipts (Money In)
        $receipts = Receipt::whereBetween('receipt_date', [$startDate, $endDate])
            ->with(['case', 'quotation', 'taxInvoice'])
            ->get();

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
        $firmSettings = (object) [
            'firm_name' => 'Naeelah Saleh & Associates',
            'registration_number' => 'LLP0012345',
            'address' => 'No. 123, Jalan Tun Razak, 50400 Kuala Lumpur, Malaysia',
            'phone_number' => '+6019-3186436',
            'email' => 'info@naaelahsaleh.my',
            'tax_registration_number' => 'W24-2507-32000179'
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
