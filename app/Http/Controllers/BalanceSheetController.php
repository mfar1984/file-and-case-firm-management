<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Receipt;
use App\Models\Bill;
use App\Models\Voucher;
use App\Models\OpeningBalance;
use Carbon\Carbon;

class BalanceSheetController extends Controller
{
    public function index(Request $request)
    {
        // Get date for balance sheet (default to today)
        $asOfDate = $request->get('as_of_date', Carbon::now()->format('Y-m-d'));
        
        // Calculate Assets
        $assets = $this->calculateAssets($asOfDate);
        
        // Calculate Liabilities
        $liabilities = $this->calculateLiabilities($asOfDate);
        
        // Calculate Equity
        $equity = $this->calculateEquity($asOfDate);
        
        // Verify Balance Sheet equation: Assets = Liabilities + Equity
        $totalAssets = collect($assets)->sum('amount');
        $totalLiabilities = collect($liabilities)->sum('amount');
        $totalEquity = collect($equity)->sum('amount');
        $isBalanced = abs($totalAssets - ($totalLiabilities + $totalEquity)) < 0.01;
        
        return view('balance-sheet', compact(
            'assets',
            'liabilities',
            'equity',
            'totalAssets',
            'totalLiabilities',
            'totalEquity',
            'asOfDate',
            'isBalanced'
        ));
    }
    
    private function calculateAssets($asOfDate)
    {
        $assets = [];
        
        // Current Assets
        
        // 1. Cash and Bank Balances
        $totalOpeningBalance = OpeningBalance::where('status', 1)->sum('debit_myr');

        // Add receipts (money in) up to as_of_date
        $receipts = Receipt::where('receipt_date', '<=', $asOfDate)->sum('amount_paid');

        // Subtract bills and vouchers (money out) up to as_of_date
        $bills = Bill::where('bill_date', '<=', $asOfDate)->sum('total_amount');
        $vouchers = Voucher::where('payment_date', '<=', $asOfDate)->sum('total_amount');

        $totalCashBank = $totalOpeningBalance + $receipts - $bills - $vouchers;
        
        $assets[] = [
            'category' => 'Current Assets',
            'account' => 'Cash and Bank Balances',
            'amount' => $totalCashBank
        ];
        
        // 2. Accounts Receivable (Outstanding invoices)
        // For law firm, this could be unbilled services or outstanding invoices
        $accountsReceivable = 0; // Placeholder - would need to implement based on business logic
        
        $assets[] = [
            'category' => 'Current Assets',
            'account' => 'Accounts Receivable',
            'amount' => $accountsReceivable
        ];
        
        // Fixed Assets (if any)
        // For now, we'll add placeholder values
        $assets[] = [
            'category' => 'Fixed Assets',
            'account' => 'Office Equipment',
            'amount' => 0 // Placeholder
        ];
        
        $assets[] = [
            'category' => 'Fixed Assets',
            'account' => 'Furniture and Fixtures',
            'amount' => 0 // Placeholder
        ];
        
        return $assets;
    }
    
    private function calculateLiabilities($asOfDate)
    {
        $liabilities = [];
        
        // Current Liabilities
        
        // 1. Accounts Payable (Outstanding bills)
        $accountsPayable = Bill::where('bill_date', '<=', $asOfDate)
            ->where('status', '!=', 'paid')
            ->sum('total_amount');
            
        $liabilities[] = [
            'category' => 'Current Liabilities',
            'account' => 'Accounts Payable',
            'amount' => $accountsPayable
        ];
        
        // 2. Accrued Expenses
        $liabilities[] = [
            'category' => 'Current Liabilities',
            'account' => 'Accrued Expenses',
            'amount' => 0 // Placeholder
        ];
        
        // Long-term Liabilities
        $liabilities[] = [
            'category' => 'Long-term Liabilities',
            'account' => 'Long-term Debt',
            'amount' => 0 // Placeholder
        ];
        
        return $liabilities;
    }
    
    private function calculateEquity($asOfDate)
    {
        $equity = [];
        
        // Calculate retained earnings (accumulated profit/loss)
        $totalRevenue = Receipt::where('receipt_date', '<=', $asOfDate)->sum('amount_paid');
        $totalExpenses = Bill::where('bill_date', '<=', $asOfDate)->sum('total_amount') +
                        Voucher::where('payment_date', '<=', $asOfDate)->sum('total_amount');
        
        $retainedEarnings = $totalRevenue - $totalExpenses;
        
        // Owner's Capital (from opening balances)
        $ownerCapital = OpeningBalance::where('status', 1)->sum('debit_myr');

        $equity[] = [
            'category' => 'Owner\'s Equity',
            'account' => 'Capital',
            'amount' => $ownerCapital
        ];
        
        $equity[] = [
            'category' => 'Owner\'s Equity',
            'account' => 'Retained Earnings',
            'amount' => $retainedEarnings
        ];
        
        return $equity;
    }
}
