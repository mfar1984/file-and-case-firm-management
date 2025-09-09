<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Receipt;
use App\Models\Bill;
use App\Models\Voucher;
use App\Models\VoucherItem;
use App\Models\ExpenseCategory;
use App\Models\OpeningBalance;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class TrialBalanceController extends Controller
{
    public function index(Request $request)
    {
        // Get date range from request or default to current month
        $startDate = $request->get('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->get('end_date', Carbon::now()->endOfMonth()->format('Y-m-d'));
        
        // Initialize accounts array
        $accounts = [];
        
        // 1. Cash and Bank Accounts
        $this->processCashAccounts($accounts, $startDate, $endDate);
        
        // 2. Revenue Accounts
        $this->processRevenueAccounts($accounts, $startDate, $endDate);
        
        // 3. Expense Accounts
        $this->processExpenseAccounts($accounts, $startDate, $endDate);
        
        // 4. Asset Accounts
        $this->processAssetAccounts($accounts, $startDate, $endDate);
        
        // 5. Liability Accounts
        $this->processLiabilityAccounts($accounts, $startDate, $endDate);
        
        // 6. Equity Accounts
        $this->processEquityAccounts($accounts, $startDate, $endDate);
        
        // Calculate totals
        $totalDebit = collect($accounts)->sum('debit');
        $totalCredit = collect($accounts)->sum('credit');
        $isBalanced = abs($totalDebit - $totalCredit) < 0.01;
        
        return view('trial-balance', compact(
            'accounts',
            'totalDebit',
            'totalCredit',
            'isBalanced',
            'startDate',
            'endDate'
        ));
    }
    
    private function processCashAccounts(&$accounts, $startDate, $endDate)
    {
        // Calculate final cash balance using proper double-entry logic
        $totalOpeningBalance = OpeningBalance::where('status', 1)->sum('debit_myr');
        $totalReceipts = Receipt::whereBetween('receipt_date', [$startDate, $endDate])
            ->sum('amount_paid');
        $totalBills = Bill::whereBetween('bill_date', [$startDate, $endDate])
            ->sum('total_amount');
        $totalVoucherItems = VoucherItem::whereHas('voucher', function($query) use ($startDate, $endDate) {
            $query->whereBetween('payment_date', [$startDate, $endDate]);
        })->sum('amount');

        // Final cash balance = Opening + Receipts - Bills - Vouchers
        $finalCashBalance = $totalOpeningBalance + $totalReceipts - $totalBills - $totalVoucherItems;

        // Show as single Cash account (combined)
        $accounts[] = [
            'account_name' => 'Cash and Bank',
            'account_type' => 'Asset',
            'debit' => $finalCashBalance > 0 ? $finalCashBalance : 0,
            'credit' => $finalCashBalance < 0 ? abs($finalCashBalance) : 0
        ];
    }
    
    private function processRevenueAccounts(&$accounts, $startDate, $endDate)
    {
        // Legal Fees Revenue
        $legalFees = Receipt::whereBetween('receipt_date', [$startDate, $endDate])
            ->sum('amount_paid');
            
        $accounts[] = [
            'account_name' => 'Legal Fees Revenue',
            'account_type' => 'Revenue',
            'debit' => 0,
            'credit' => $legalFees
        ];
    }
    
    private function processExpenseAccounts(&$accounts, $startDate, $endDate)
    {
        // Get all active expense categories for consistent grouping
        $activeCategories = ExpenseCategory::active()->ordered()->get();
        $categoryMap = $activeCategories->pluck('name', 'name')->toArray();

        // Bills by category with proper categorization
        $bills = Bill::whereBetween('bill_date', [$startDate, $endDate])
            ->selectRaw('category, SUM(total_amount) as total')
            ->groupBy('category')
            ->get();

        foreach ($bills as $bill) {
            // Use proper category mapping or default to 'Other'
            $categoryName = $bill->category && isset($categoryMap[$bill->category])
                ? $bill->category
                : 'Other';

            $accounts[] = [
                'account_name' => 'Expenses - ' . $categoryName,
                'account_type' => 'Expense',
                'debit' => $bill->total,
                'credit' => 0,
                'category' => $categoryName
            ];
        }
        
        // Vouchers by category with proper categorization
        $vouchers = Voucher::whereBetween('payment_date', [$startDate, $endDate])
            ->with(['items'])
            ->get();

        $voucherCategories = [];
        foreach ($vouchers as $voucher) {
            foreach ($voucher->items as $item) {
                // Use proper category mapping or default to 'Other'
                $category = $item->category && isset($categoryMap[$item->category])
                    ? $item->category
                    : 'Other';

                if (!isset($voucherCategories[$category])) {
                    $voucherCategories[$category] = 0;
                }
                $voucherCategories[$category] += $item->amount;
            }
        }

        foreach ($voucherCategories as $category => $amount) {
            $accounts[] = [
                'account_name' => 'Expenses - ' . $category,
                'account_type' => 'Expense',
                'debit' => $amount,
                'credit' => 0,
                'category' => $category
            ];
        }
    }
    
    private function processAssetAccounts(&$accounts, $startDate, $endDate)
    {
        // Accounts Receivable (if any)
        $accounts[] = [
            'account_name' => 'Accounts Receivable',
            'account_type' => 'Asset',
            'debit' => 0, // Placeholder
            'credit' => 0
        ];
        
        // Office Equipment (if any)
        $accounts[] = [
            'account_name' => 'Office Equipment',
            'account_type' => 'Asset',
            'debit' => 0, // Placeholder
            'credit' => 0
        ];
    }
    
    private function processLiabilityAccounts(&$accounts, $startDate, $endDate)
    {
        // Accounts Payable
        $accountsPayable = Bill::where('status', '!=', 'paid')
            ->sum('total_amount');
            
        $accounts[] = [
            'account_name' => 'Accounts Payable',
            'account_type' => 'Liability',
            'debit' => 0,
            'credit' => $accountsPayable
        ];
    }
    
    private function processEquityAccounts(&$accounts, $startDate, $endDate)
    {
        // Owner's Capital (from opening balances)
        $totalOpeningBalance = OpeningBalance::where('status', 1)->sum('debit_myr');

        $accounts[] = [
            'account_name' => 'Owner\'s Capital',
            'account_type' => 'Equity',
            'debit' => 0,
            'credit' => $totalOpeningBalance
        ];

        // Note: Retained Earnings not included in Trial Balance when Revenue and Expense accounts are shown separately
        // This prevents double counting of current period profit/loss
    }

    public function print(Request $request)
    {
        $startDate = $request->get('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->get('end_date', Carbon::now()->endOfMonth()->format('Y-m-d'));

        $accounts = [];

        // Process all account types
        $this->processCashAccounts($accounts, $startDate, $endDate);
        $this->processRevenueAccounts($accounts, $startDate, $endDate);
        $this->processExpenseAccounts($accounts, $startDate, $endDate);
        $this->processLiabilityAccounts($accounts, $startDate, $endDate);
        $this->processEquityAccounts($accounts, $startDate, $endDate);

        // Calculate totals
        $totalDebits = collect($accounts)->sum('debit');
        $totalCredits = collect($accounts)->sum('credit');
        $difference = $totalDebits - $totalCredits;
        $isBalanced = abs($difference) < 0.01;

        // Get firm settings for header
        $firmSettings = (object) [
            'firm_name' => 'Naeelah Saleh & Associates',
            'registration_number' => 'LLP0012345',
            'address' => 'No. 123, Jalan Tun Razak, 50400 Kuala Lumpur, Malaysia',
            'phone_number' => '+6019-3186436',
            'email' => 'info@naaelahsaleh.my',
            'tax_registration_number' => 'W24-2507-32000179'
        ];

        $reportTitle = 'Trial Balance';
        $dateRange = date('d/m/Y', strtotime($startDate)) . ' - ' . date('d/m/Y', strtotime($endDate));

        // Generate PDF
        $pdf = Pdf::loadView('trial-balance-print', compact(
            'accounts', 'totalDebits', 'totalCredits', 'difference', 'isBalanced',
            'startDate', 'endDate', 'firmSettings', 'reportTitle', 'dateRange'
        ));

        $pdf->setPaper('A4', 'portrait');

        // Generate filename with date range
        $filename = 'Trial_Balance_' . date('Y-m-d', strtotime($startDate)) . '_to_' . date('Y-m-d', strtotime($endDate)) . '.pdf';

        return $pdf->download($filename);
    }
}
