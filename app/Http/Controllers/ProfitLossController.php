<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Receipt;
use App\Models\Bill;
use App\Models\Voucher;
use App\Models\ExpenseCategory;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class ProfitLossController extends Controller
{
    public function index(Request $request)
    {
        // Get date range from request or default to current month
        $startDate = $request->get('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->get('end_date', Carbon::now()->endOfMonth()->format('Y-m-d'));
        
        // Calculate Revenue
        $revenue = $this->calculateRevenue($startDate, $endDate);
        
        // Calculate Expenses
        $expenses = $this->calculateExpenses($startDate, $endDate);
        
        // Calculate totals
        $totalRevenue = collect($revenue)->sum('amount');
        $totalExpenses = collect($expenses)->sum('amount');
        $netProfit = $totalRevenue - $totalExpenses;
        
        // Calculate profit margin
        $profitMargin = $totalRevenue > 0 ? ($netProfit / $totalRevenue) * 100 : 0;
        
        // Get category breakdown for enhanced reporting
        $categoryBreakdown = $this->getCategoryBreakdown($expenses);
        $expenseCategories = ExpenseCategory::active()->ordered()->get();

        return view('profit-loss', compact(
            'revenue',
            'expenses',
            'totalRevenue',
            'totalExpenses',
            'netProfit',
            'profitMargin',
            'startDate',
            'endDate',
            'categoryBreakdown',
            'expenseCategories'
        ));
    }
    
    private function calculateRevenue($startDate, $endDate)
    {
        $revenue = [];
        
        // Legal Fees from Receipts
        $receipts = Receipt::whereBetween('receipt_date', [$startDate, $endDate])
            ->with(['quotation', 'taxInvoice'])
            ->get();
            
        // Group receipts by service type
        $legalFees = 0;
        $consultationFees = 0;
        $otherFees = 0;
        
        foreach ($receipts as $receipt) {
            // Categorize based on related quotation or tax invoice
            if ($receipt->quotation) {
                // Check quotation items for categorization
                $hasConsultation = $receipt->quotation->items()
                    ->where('description', 'like', '%consultation%')
                    ->exists();
                    
                if ($hasConsultation) {
                    $consultationFees += $receipt->amount_paid;
                } else {
                    $legalFees += $receipt->amount_paid;
                }
            } elseif ($receipt->taxInvoice) {
                // Check tax invoice items for categorization
                $hasConsultation = $receipt->taxInvoice->items()
                    ->where('description', 'like', '%consultation%')
                    ->exists();
                    
                if ($hasConsultation) {
                    $consultationFees += $receipt->amount_paid;
                } else {
                    $legalFees += $receipt->amount_paid;
                }
            } else {
                $otherFees += $receipt->amount_paid;
            }
        }
        
        $revenue[] = [
            'category' => 'Revenue',
            'account' => 'Legal Fees',
            'amount' => $legalFees
        ];
        
        $revenue[] = [
            'category' => 'Revenue',
            'account' => 'Consultation Fees',
            'amount' => $consultationFees
        ];
        
        $revenue[] = [
            'category' => 'Revenue',
            'account' => 'Other Income',
            'amount' => $otherFees
        ];
        
        return $revenue;
    }
    
    private function calculateExpenses($startDate, $endDate)
    {
        $expenses = [];

        // Get all active expense categories for consistent grouping
        $activeCategories = ExpenseCategory::active()->ordered()->get();
        $categoryMap = $activeCategories->pluck('name', 'name')->toArray();

        // Operating Expenses from Bills
        $bills = Bill::whereBetween('bill_date', [$startDate, $endDate])
            ->with(['items'])
            ->get();

        // Group bills by category with proper categorization
        $expenseCategories = [];

        foreach ($bills as $bill) {
            // Use proper category mapping or default to 'Other'
            $category = $bill->category && isset($categoryMap[$bill->category])
                ? $bill->category
                : 'Other';

            if (!isset($expenseCategories[$category])) {
                $expenseCategories[$category] = 0;
            }

            $expenseCategories[$category] += $bill->total_amount;
        }

        // Add bill expenses with proper category structure
        foreach ($expenseCategories as $category => $amount) {
            $expenses[] = [
                'category' => 'Operating Expenses',
                'account' => $category,
                'amount' => $amount,
                'type' => 'bill'
            ];
        }
        
        // Administrative Expenses from Vouchers
        $vouchers = Voucher::whereBetween('payment_date', [$startDate, $endDate])
            ->with(['items'])
            ->get();

        // Group vouchers by category with proper categorization
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

        // Add voucher expenses with proper category structure
        foreach ($voucherCategories as $category => $amount) {
            $expenses[] = [
                'category' => 'Administrative Expenses',
                'account' => $category,
                'amount' => $amount,
                'type' => 'voucher'
            ];
        }
        
        // If no expenses found, add placeholder
        if (empty($expenses)) {
            $expenses[] = [
                'category' => 'Operating Expenses',
                'account' => 'No expenses recorded',
                'amount' => 0
            ];
        }
        
        return $expenses;
    }

    private function getCategoryBreakdown($expenses)
    {
        $breakdown = [];

        // Group expenses by account (category) name
        foreach ($expenses as $expense) {
            $account = $expense['account'];

            if (!isset($breakdown[$account])) {
                $breakdown[$account] = [
                    'name' => $account,
                    'operating' => 0,
                    'administrative' => 0,
                    'total' => 0
                ];
            }

            if ($expense['category'] === 'Operating Expenses') {
                $breakdown[$account]['operating'] += $expense['amount'];
            } else {
                $breakdown[$account]['administrative'] += $expense['amount'];
            }

            $breakdown[$account]['total'] += $expense['amount'];
        }

        // Sort by total amount descending
        uasort($breakdown, function($a, $b) {
            return $b['total'] <=> $a['total'];
        });

        return $breakdown;
    }

    public function print(Request $request)
    {
        $startDate = $request->get('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->get('end_date', Carbon::now()->endOfMonth()->format('Y-m-d'));

        // Calculate revenue with smart categorization
        $revenueBreakdown = $this->calculateRevenue($startDate, $endDate);
        $totalRevenue = collect($revenueBreakdown)->sum('amount');

        // Calculate expenses with category breakdown
        $expenseBreakdown = $this->calculateExpenses($startDate, $endDate);
        $totalExpenses = collect($expenseBreakdown)->sum('amount');

        // Calculate net profit
        $netProfit = $totalRevenue - $totalExpenses;
        $profitMargin = $totalRevenue > 0 ? ($netProfit / $totalRevenue) * 100 : 0;

        // Get firm settings for header
        $firmSettings = (object) [
            'firm_name' => 'Naeelah Saleh & Associates',
            'registration_number' => 'LLP0012345',
            'address' => 'No. 123, Jalan Tun Razak, 50400 Kuala Lumpur, Malaysia',
            'phone_number' => '+6019-3186436',
            'email' => 'info@naaelahsaleh.my',
            'tax_registration_number' => 'W24-2507-32000179'
        ];

        $reportTitle = 'Profit & Loss Statement';
        $dateRange = date('d/m/Y', strtotime($startDate)) . ' - ' . date('d/m/Y', strtotime($endDate));

        // Generate PDF
        $pdf = Pdf::loadView('profit-loss-print', compact(
            'revenueBreakdown', 'expenseBreakdown', 'totalRevenue', 'totalExpenses',
            'netProfit', 'profitMargin', 'startDate', 'endDate',
            'firmSettings', 'reportTitle', 'dateRange'
        ));

        $pdf->setPaper('A4', 'portrait');

        // Generate filename with date range
        $filename = 'Profit_Loss_' . date('Y-m-d', strtotime($startDate)) . '_to_' . date('Y-m-d', strtotime($endDate)) . '.pdf';

        return $pdf->download($filename);
    }
}
