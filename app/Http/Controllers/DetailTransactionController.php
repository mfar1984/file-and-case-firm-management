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

class DetailTransactionController extends Controller
{
    public function index(Request $request)
    {
        // Get date range from request or default to current month
        $startDate = $request->get('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->get('end_date', Carbon::now()->endOfMonth()->format('Y-m-d'));
        $accountFilter = $request->get('account_filter', 'all');
        
        // Get opening balances
        $openingBalances = OpeningBalance::where('status', 1)->get();
        
        // Get all transactions within date range
        $receipts = Receipt::whereBetween('receipt_date', [$startDate, $endDate])
            ->with(['case', 'quotation', 'taxInvoice'])
            ->get()
            ->map(function ($receipt) {
                return [
                    'date' => $receipt->receipt_date,
                    'type' => 'Receipt',
                    'reference' => $receipt->receipt_no,
                    'description' => $receipt->customer_name . ' - Payment',
                    'debit' => $receipt->amount_paid,
                    'credit' => 0,
                    'account' => 'Client Account', // Default for receipts
                    'id' => $receipt->id,
                    'route' => 'receipt.show'
                ];
            });
            
        // Get active categories for proper mapping
        $activeCategories = ExpenseCategory::active()->ordered()->get();
        $categoryMap = $activeCategories->pluck('name', 'name')->toArray();

        $bills = Bill::whereBetween('bill_date', [$startDate, $endDate])
            ->with(['items'])
            ->get()
            ->map(function ($bill) use ($categoryMap) {
                // Use proper category or default to 'Other'
                $category = $bill->category && isset($categoryMap[$bill->category])
                    ? $bill->category
                    : 'Other';

                return [
                    'date' => $bill->bill_date,
                    'type' => 'Bill',
                    'reference' => $bill->bill_no,
                    'description' => $bill->vendor_name . ' - ' . ($bill->description ?? 'Bill Payment'),
                    'debit' => 0,
                    'credit' => $bill->total_amount,
                    'account' => 'Expenses - ' . $category,
                    'category' => $category,
                    'id' => $bill->id,
                    'route' => 'bill.show'
                ];
            });
            
        $vouchers = Voucher::whereBetween('payment_date', [$startDate, $endDate])
            ->with(['items'])
            ->get()
            ->map(function ($voucher) use ($categoryMap) {
                // Get primary category from voucher items
                $primaryCategory = 'Other';
                if ($voucher->items->isNotEmpty()) {
                    $firstItem = $voucher->items->first();
                    $primaryCategory = $firstItem->category && isset($categoryMap[$firstItem->category])
                        ? $firstItem->category
                        : 'Other';
                }

                return [
                    'date' => $voucher->payment_date,
                    'type' => 'Voucher',
                    'reference' => $voucher->voucher_no,
                    'description' => $voucher->payee_name . ' - ' . ($voucher->remark ?? 'Payment'),
                    'debit' => 0,
                    'credit' => $voucher->total_amount,
                    'account' => 'Expenses - ' . $primaryCategory,
                    'category' => $primaryCategory,
                    'id' => $voucher->id,
                    'route' => 'voucher.show'
                ];
            });
        
        // Combine all transactions
        $allTransactions = collect()
            ->merge($receipts)
            ->merge($bills)
            ->merge($vouchers)
            ->sortBy('date')
            ->values();
            
        // Filter by account if specified
        if ($accountFilter !== 'all') {
            $allTransactions = $allTransactions->where('account', $accountFilter);
        }
        
        // Calculate running balance
        $runningBalance = 0;
        
        // Add opening balance for selected account
        if ($accountFilter !== 'all') {
            $openingBalance = $openingBalances->where('bank_name', $accountFilter)->first();
            if ($openingBalance) {
                $runningBalance = $openingBalance->debit_myr - $openingBalance->credit_myr;
            }
        } else {
            // Sum all opening balances
            $runningBalance = $openingBalances->sum('debit_myr') - $openingBalances->sum('credit_myr');
        }
        
        $allTransactions = $allTransactions->map(function ($transaction) use (&$runningBalance) {
            $runningBalance += $transaction['debit'] - $transaction['credit'];
            $transaction['running_balance'] = $runningBalance;
            return $transaction;
        });
        
        // Calculate totals
        $totalDebit = $allTransactions->sum('debit');
        $totalCredit = $allTransactions->sum('credit');
        $netChange = $totalDebit - $totalCredit;
        
        // Get account options
        $accountOptions = $openingBalances->pluck('bank_name')->unique()->prepend('All Accounts');
        
        return view('detail-transaction', compact(
            'allTransactions',
            'startDate',
            'endDate',
            'accountFilter',
            'totalDebit',
            'totalCredit',
            'netChange',
            'accountOptions',
            'openingBalances'
        ))->with('transactions', $allTransactions);
    }

    public function print(Request $request)
    {
        $startDate = $request->get('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->get('end_date', Carbon::now()->endOfMonth()->format('Y-m-d'));
        $accountFilter = $request->get('account_filter', 'all');

        $openingBalances = OpeningBalance::where('status', 1)->get();

        $receipts = Receipt::whereBetween('receipt_date', [$startDate, $endDate])
            ->with(['case', 'quotation', 'taxInvoice'])
            ->get()
            ->map(function ($receipt) {
                return [
                    'date' => $receipt->receipt_date,
                    'type' => 'Receipt',
                    'reference' => $receipt->receipt_no,
                    'description' => $receipt->customer_name . ' - Payment',
                    'debit' => $receipt->amount_paid,
                    'credit' => 0,
                    'account' => 'Client Account',
                    'id' => $receipt->id,
                    'route' => 'receipt.show'
                ];
            });

        $activeCategories = ExpenseCategory::active()->ordered()->get();
        $categoryMap = $activeCategories->pluck('name', 'name')->toArray();

        $bills = Bill::whereBetween('bill_date', [$startDate, $endDate])
            ->with(['items'])
            ->get()
            ->map(function ($bill) use ($categoryMap) {
                $primaryCategory = $categoryMap[$bill->category] ?? $bill->category;
                return [
                    'date' => $bill->bill_date,
                    'type' => 'Bill',
                    'reference' => $bill->bill_no,
                    'description' => $bill->vendor_name . ' - ' . $bill->description,
                    'debit' => 0,
                    'credit' => $bill->total_amount,
                    'account' => 'Expenses - ' . $primaryCategory,
                    'id' => $bill->id,
                    'route' => 'bill.show'
                ];
            });

        $vouchers = Voucher::whereBetween('payment_date', [$startDate, $endDate])
            ->with(['items'])
            ->get()
            ->flatMap(function ($voucher) use ($categoryMap) {
                return $voucher->items->map(function ($item) use ($voucher, $categoryMap) {
                    $primaryCategory = $categoryMap[$item->category] ?? $item->category;
                    return [
                        'date' => $voucher->payment_date,
                        'type' => 'Voucher',
                        'reference' => $voucher->voucher_no,
                        'description' => $voucher->payee_name . ' - ' . $item->description,
                        'debit' => 0,
                        'credit' => $item->amount,
                        'account' => 'Expenses - ' . $primaryCategory,
                        'id' => $voucher->id,
                        'route' => 'voucher.show'
                    ];
                });
            });

        $allTransactions = $receipts->concat($bills)->concat($vouchers)->sortBy('date')->values();

        if ($accountFilter !== 'all') {
            $allTransactions = $allTransactions->filter(function ($transaction) use ($accountFilter) {
                return strpos($transaction['account'], $accountFilter) !== false;
            });
        }

        $runningBalance = 0;
        if ($openingBalances->isNotEmpty()) {
            $runningBalance = $openingBalances->sum('debit_myr') - $openingBalances->sum('credit_myr');
        }

        $allTransactions = $allTransactions->map(function ($transaction) use (&$runningBalance) {
            $runningBalance += $transaction['debit'] - $transaction['credit'];
            $transaction['running_balance'] = $runningBalance;
            return $transaction;
        });

        $totalDebit = $allTransactions->sum('debit');
        $totalCredit = $allTransactions->sum('credit');
        $netChange = $totalDebit - $totalCredit;

        // Get firm settings for header
        $firmSettings = (object) [
            'firm_name' => 'Naeelah Saleh & Associates',
            'registration_number' => 'LLP0012345',
            'address' => 'No. 123, Jalan Tun Razak, 50400 Kuala Lumpur, Malaysia',
            'phone_number' => '+6019-3186436',
            'email' => 'info@naaelahsaleh.my',
            'tax_registration_number' => 'W24-2507-32000179'
        ];

        $reportTitle = 'Detail Transaction Report';
        $dateRange = date('d/m/Y', strtotime($startDate)) . ' - ' . date('d/m/Y', strtotime($endDate));

        // Generate PDF
        $pdf = Pdf::loadView('detail-transaction-print', compact(
            'allTransactions', 'startDate', 'endDate', 'totalDebit', 'totalCredit', 'netChange',
            'firmSettings', 'reportTitle', 'dateRange'
        ) + ['transactions' => $allTransactions]);

        $pdf->setPaper('A4', 'portrait');

        // Generate filename with date range
        $filename = 'Detail_Transaction_' . date('Y-m-d', strtotime($startDate)) . '_to_' . date('Y-m-d', strtotime($endDate)) . '.pdf';

        return $pdf->download($filename);
    }
}
