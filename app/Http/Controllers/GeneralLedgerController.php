<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\OpeningBalance;
use App\Models\Receipt;
use App\Models\Bill;
use App\Models\Voucher;
use App\Models\ExpenseCategory;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class GeneralLedgerController extends Controller
{
    public function index(Request $request)
    {
        $datePreset = $request->get('date_preset', 'current_month_td');

        // Calculate dates based on preset
        $dates = $this->calculateDatesFromPreset($datePreset);

        // Override with custom dates if provided
        $fromDate = $request->get('from_date', $dates['from_date']);
        $toDate = $request->get('to_date', $dates['to_date']);

        // For custom preset, report date is same as to date
        // For other presets, use calculated report date
        if ($datePreset === 'custom') {
            $reportDate = $toDate;
        } else {
            $reportDate = $request->get('report_date', $dates['report_date']);
        }

        // Always generate ledger data
        $ledgerData = $this->generateLedgerData($reportDate, $fromDate, $toDate);

        // Extract accounts for compatibility
        $accounts = collect($ledgerData)->map(function ($account) {
            return [
                'name' => $account['name'] ?? 'Unknown Account',
                'balance' => $account['closing_balance'] ?? 0,
                'transactions' => $account['transactions'] ?? []
            ];
        })->toArray();

        return view('general-ledger', compact('reportDate', 'fromDate', 'toDate', 'ledgerData', 'datePreset', 'accounts'));
    }

    public function print(Request $request)
    {
        $datePreset = $request->get('date_preset', 'current_month_td');
        $dates = $this->calculateDatesFromPreset($datePreset);
        $fromDate = $request->get('from_date', $dates['from_date']);
        $toDate = $request->get('to_date', $dates['to_date']);

        if ($datePreset === 'custom') {
            $reportDate = $toDate;
        } else {
            $reportDate = $request->get('report_date', $dates['report_date']);
        }

        $ledgerData = $this->generateLedgerData($reportDate, $fromDate, $toDate);
        $accounts = collect($ledgerData)->map(function ($account) {
            return [
                'name' => $account['name'] ?? 'Unknown Account',
                'balance' => $account['closing_balance'] ?? 0,
                'transactions' => $account['transactions'] ?? []
            ];
        })->toArray();

        // Get firm settings for header
        $firmSettings = (object) [
            'firm_name' => 'Naeelah Saleh & Associates',
            'registration_number' => 'LLP0012345',
            'address' => 'No. 123, Jalan Tun Razak, 50400 Kuala Lumpur, Malaysia',
            'phone_number' => '+6019-3186436',
            'email' => 'info@naaelahsaleh.my',
            'tax_registration_number' => 'W24-2507-32000179'
        ];

        $reportTitle = 'General Ledger';
        $dateRange = date('d/m/Y', strtotime($fromDate)) . ' - ' . date('d/m/Y', strtotime($toDate));

        // Generate PDF
        $pdf = Pdf::loadView('general-ledger-print', compact(
            'ledgerData', 'accounts', 'fromDate', 'toDate', 'reportDate',
            'firmSettings', 'reportTitle', 'dateRange'
        ));

        $pdf->setPaper('A4', 'portrait');

        // Generate filename with date range
        $filename = 'General_Ledger_' . date('Y-m-d', strtotime($fromDate)) . '_to_' . date('Y-m-d', strtotime($toDate)) . '.pdf';

        return $pdf->download($filename);
    }

    private function calculateDatesFromPreset($preset)
    {
        $today = now();
        $currentYear = $today->year;
        $currentMonth = $today->month;

        switch ($preset) {
            case 'current_fiscal_ytd':
                // Assuming fiscal year starts April 1st
                $fiscalYearStart = $currentMonth >= 4 ?
                    Carbon::create($currentYear, 4, 1) :
                    Carbon::create($currentYear - 1, 4, 1);
                return [
                    'report_date' => $today->format('Y-m-d'),
                    'from_date' => $fiscalYearStart->format('Y-m-d'),
                    'to_date' => $today->format('Y-m-d')
                ];

            case 'current_fiscal_year':
                $fiscalStart = $currentMonth >= 4 ?
                    Carbon::create($currentYear, 4, 1) :
                    Carbon::create($currentYear - 1, 4, 1);
                $fiscalEnd = $currentMonth >= 4 ?
                    Carbon::create($currentYear + 1, 3, 31) :
                    Carbon::create($currentYear, 3, 31);
                return [
                    'report_date' => $fiscalEnd->format('Y-m-d'),
                    'from_date' => $fiscalStart->format('Y-m-d'),
                    'to_date' => $fiscalEnd->format('Y-m-d')
                ];

            case 'last_fiscal_year':
                $lastFiscalStart = $currentMonth >= 4 ?
                    Carbon::create($currentYear - 1, 4, 1) :
                    Carbon::create($currentYear - 2, 4, 1);
                $lastFiscalEnd = $currentMonth >= 4 ?
                    Carbon::create($currentYear, 3, 31) :
                    Carbon::create($currentYear - 1, 3, 31);
                return [
                    'report_date' => $lastFiscalEnd->format('Y-m-d'),
                    'from_date' => $lastFiscalStart->format('Y-m-d'),
                    'to_date' => $lastFiscalEnd->format('Y-m-d')
                ];

            case 'current_quarter_td':
                $quarterStart = $today->copy()->firstOfQuarter();
                return [
                    'report_date' => $today->format('Y-m-d'),
                    'from_date' => $quarterStart->format('Y-m-d'),
                    'to_date' => $today->format('Y-m-d')
                ];

            case 'current_quarter':
                $quarterStart = $today->copy()->firstOfQuarter();
                $quarterEnd = $today->copy()->lastOfQuarter();
                return [
                    'report_date' => $quarterEnd->format('Y-m-d'),
                    'from_date' => $quarterStart->format('Y-m-d'),
                    'to_date' => $quarterEnd->format('Y-m-d')
                ];

            case 'last_quarter':
                $lastQuarterStart = $today->copy()->subQuarter()->firstOfQuarter();
                $lastQuarterEnd = $today->copy()->subQuarter()->lastOfQuarter();
                return [
                    'report_date' => $lastQuarterEnd->format('Y-m-d'),
                    'from_date' => $lastQuarterStart->format('Y-m-d'),
                    'to_date' => $lastQuarterEnd->format('Y-m-d')
                ];

            case 'current_month_td':
                $monthStart = $today->copy()->startOfMonth();
                return [
                    'report_date' => $today->format('Y-m-d'),
                    'from_date' => $monthStart->format('Y-m-d'),
                    'to_date' => $today->format('Y-m-d')
                ];

            case 'current_month':
                $monthStart = $today->copy()->startOfMonth();
                $monthEnd = $today->copy()->endOfMonth();
                return [
                    'report_date' => $monthEnd->format('Y-m-d'),
                    'from_date' => $monthStart->format('Y-m-d'),
                    'to_date' => $monthEnd->format('Y-m-d')
                ];

            case 'last_month':
                $lastMonthStart = $today->copy()->subMonth()->startOfMonth();
                $lastMonthEnd = $today->copy()->subMonth()->endOfMonth();
                return [
                    'report_date' => $lastMonthEnd->format('Y-m-d'),
                    'from_date' => $lastMonthStart->format('Y-m-d'),
                    'to_date' => $lastMonthEnd->format('Y-m-d')
                ];

            case 'custom':
            default:
                return [
                    'report_date' => $today->format('Y-m-d'),
                    'from_date' => $today->startOfMonth()->format('Y-m-d'),
                    'to_date' => $today->format('Y-m-d')
                ];
        }
    }

    private function generateLedgerData($reportDate, $fromDate, $toDate)
    {
        $ledgerData = [];

        // Get active expense categories for proper grouping
        $activeCategories = ExpenseCategory::active()->ordered()->get();
        $categoryMap = $activeCategories->pluck('name', 'name')->toArray();

        // 1. Cash/Bank Accounts from Opening Balances
        $openingBalances = OpeningBalance::active()->orderBy('bank_code')->get();

        foreach ($openingBalances as $balance) {
            // Calculate actual transactions for this account
            $receipts = Receipt::whereBetween('receipt_date', [$fromDate, $toDate])->sum('amount_paid');
            $bills = Bill::whereBetween('bill_date', [$fromDate, $toDate])->sum('total_amount');
            $vouchers = Voucher::whereBetween('payment_date', [$fromDate, $toDate])->sum('total_amount');

            $debitTransactions = $receipts; // Money in
            $creditTransactions = $bills + $vouchers; // Money out
            $netChange = $debitTransactions - $creditTransactions;
            $closingBalance = ($balance->debit_myr - $balance->credit_myr) + $netChange;

            $ledgerData[] = [
                'name' => $balance->bank_name . ' (' . $balance->bank_code . ')',
                'account_type' => 'Asset',
                'opening_balance' => $balance->debit_myr - $balance->credit_myr,
                'debit' => $debitTransactions,
                'credit' => $creditTransactions,
                'net_change' => $netChange,
                'closing_balance' => $closingBalance
            ];
        }

        // 2. Revenue Accounts
        $totalRevenue = Receipt::whereBetween('receipt_date', [$fromDate, $toDate])->sum('amount_paid');
        if ($totalRevenue > 0) {
            $ledgerData[] = [
                'name' => 'Revenue - Legal Fees',
                'account_type' => 'Revenue',
                'opening_balance' => 0,
                'debit' => 0,
                'credit' => $totalRevenue,
                'net_change' => -$totalRevenue, // Credit increases revenue
                'closing_balance' => -$totalRevenue
            ];
        }

        // 3. Expense Accounts by Category
        $this->addExpenseAccounts($ledgerData, $fromDate, $toDate, $categoryMap);

        return $ledgerData;
    }

    private function addExpenseAccounts(&$ledgerData, $fromDate, $toDate, $categoryMap)
    {
        // Bills by category
        $bills = Bill::whereBetween('bill_date', [$fromDate, $toDate])
            ->selectRaw('category, SUM(total_amount) as total')
            ->groupBy('category')
            ->get();

        foreach ($bills as $bill) {
            $category = $bill->category && isset($categoryMap[$bill->category])
                ? $bill->category
                : 'Other';

            $ledgerData[] = [
                'name' => 'Expenses - ' . $category . ' (Bills)',
                'account_type' => 'Expense',
                'opening_balance' => 0,
                'debit' => $bill->total,
                'credit' => 0,
                'net_change' => $bill->total,
                'closing_balance' => $bill->total
            ];
        }

        // Vouchers by category
        $vouchers = Voucher::whereBetween('payment_date', [$fromDate, $toDate])
            ->with(['items'])
            ->get();

        $voucherCategories = [];
        foreach ($vouchers as $voucher) {
            foreach ($voucher->items as $item) {
                $category = $item->category && isset($categoryMap[$item->category])
                    ? $item->category
                    : 'Other';

                if (!isset($voucherCategories[$category])) {
                    $voucherCategories[$category] = 0;
                }
                $voucherCategories[$category] += $item->total_amount;
            }
        }

        foreach ($voucherCategories as $category => $amount) {
            $ledgerData[] = [
                'name' => 'Expenses - ' . $category . ' (Vouchers)',
                'account_type' => 'Expense',
                'opening_balance' => 0,
                'debit' => $amount,
                'credit' => 0,
                'net_change' => $amount,
                'closing_balance' => $amount
            ];
        }
    }
}
