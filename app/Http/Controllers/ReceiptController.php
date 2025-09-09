<?php

namespace App\Http\Controllers;

use App\Models\Receipt;
use App\Models\Quotation;
use App\Models\TaxInvoice;
use App\Models\CourtCase;
use App\Models\FirmSetting;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReceiptController extends Controller
{
    public function index()
    {
        $receipts = Receipt::with(['case', 'quotation', 'taxInvoice'])
            ->orderBy('created_at', 'desc')
            ->get();
            
        return view('receipt', compact('receipts'));
    }

    public function create(Request $request)
    {
        // Get tax invoices for selection (exclude fully paid invoices)
        $taxInvoices = TaxInvoice::whereIn('status', ['sent', 'partially_paid'])
            ->with(['case', 'items'])
            ->orderBy('created_at', 'desc')
            ->get(['id', 'invoice_no', 'case_id', 'total'])
            ->filter(function($invoice) {
                // Additional filter: exclude invoices with zero outstanding balance
                $previousPayments = Receipt::where('tax_invoice_id', $invoice->id)->sum('amount_paid');
                $outstandingBalance = max($invoice->total - $previousPayments, 0);
                return $outstandingBalance > 0; // Only show invoices with outstanding balance
            });

        // Get case options
        $caseOptions = CourtCase::orderBy('case_number')->get(['id', 'case_number']);

        // Prefill from tax invoice if provided
        $prefillData = null;
        if ($request->filled('from_invoice')) {
            $invoice = TaxInvoice::with(['case', 'items'])->find($request->integer('from_invoice'));
            if ($invoice) {
                // Calculate real outstanding balance by deducting previous payments
                $previousPayments = Receipt::where('tax_invoice_id', $invoice->id)->sum('amount_paid');
                $realOutstandingBalance = max($invoice->total - $previousPayments, 0);
                
                $prefillData = [
                    'type' => 'invoice',
                    'tax_invoice_id' => $invoice->id,
                    'case_id' => $invoice->case_id,
                    'case_number' => $invoice->case?->case_number,
                    'customer_name' => $invoice->customer_name,
                    'total_amount' => $invoice->total,
                    'outstanding_balance' => $realOutstandingBalance, // Real outstanding balance
                    'previous_payments' => $previousPayments,
                ];
            }
        }

        return view('receipt-create', compact('taxInvoices', 'caseOptions', 'prefillData'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'case_id' => 'nullable|integer|exists:cases,id',
            'quotation_id' => 'nullable|integer|exists:quotations,id',
            'tax_invoice_id' => 'nullable|integer|exists:tax_invoices,id',
            'receipt_date' => 'required|date',
            'payment_reference' => 'nullable|string|max:255',
            'payment_method' => 'required|in:cash,bank_transfer,cheque,credit_card,online_payment,other',
            'bank_name' => 'nullable|string|max:255',
            'cheque_number' => 'nullable|string|max:255',
            'transaction_id' => 'nullable|string|max:255',
            'amount_paid' => 'required|numeric|min:0.01',
            'outstanding_balance' => 'required|numeric|min:0',
            'payment_notes' => 'nullable|string',
        ]);

        try {
            DB::beginTransaction();

            // Generate receipt number
            $receiptNo = Receipt::generateReceiptNumber();

            // Auto-determine case_id if not provided
            $caseId = $validated['case_id'];
            if (!$caseId && $validated['tax_invoice_id']) {
                $invoice = TaxInvoice::find($validated['tax_invoice_id']);
                if ($invoice && $invoice->case_id) {
                    $caseId = $invoice->case_id;
                }
            }

            // Create receipt
            $receipt = Receipt::create([
                'receipt_no' => $receiptNo,
                'case_id' => $caseId,
                'quotation_id' => $validated['quotation_id'] ?? null,
                'tax_invoice_id' => $validated['tax_invoice_id'] ?? null,
                'receipt_date' => $validated['receipt_date'],
                'payment_reference' => $validated['payment_reference'] ?? null,
                'payment_method' => $validated['payment_method'],
                'bank_name' => $validated['bank_name'] ?? null,
                'cheque_number' => $validated['cheque_number'] ?? null,
                'transaction_id' => $validated['transaction_id'] ?? null,
                'amount_paid' => $validated['amount_paid'],
                'outstanding_balance' => $validated['outstanding_balance'],
                'payment_notes' => $validated['payment_notes'] ?? null,
                'status' => 'confirmed',
            ]);

            // Update related entities
            if ($validated['tax_invoice_id']) {
                $invoice = TaxInvoice::find($validated['tax_invoice_id']);
                if ($invoice) {
                    // Update invoice status if fully paid
                    if ($validated['outstanding_balance'] <= 0) {
                        $invoice->update(['status' => 'paid']);
                    } else {
                        $invoice->update(['status' => 'partially_paid']);
                    }
                }
            }

            // Log receipt creation
            activity()
                ->performedOn($receipt)
                ->causedBy(auth()->user())
                ->withProperties([
                    'ip' => request()->ip(),
                    'receipt_no' => $receipt->receipt_no,
                    'amount_paid' => $receipt->amount_paid,
                    'payment_method' => $receipt->payment_method
                ])
                ->log("Receipt {$receipt->receipt_no} created");

            DB::commit();

            return redirect()->route('receipt.show', $receipt->id)
                ->with('success', 'Receipt created successfully');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->withErrors(['error' => 'Failed to create receipt: ' . $e->getMessage()]);
        }
    }

    public function show($id)
    {
        $receipt = Receipt::with(['case', 'quotation', 'taxInvoice'])->findOrFail($id);
        return view('receipt-show', compact('receipt'));
    }

    public function print($id)
    {
        $receipt = Receipt::with(['case', 'quotation', 'taxInvoice'])->findOrFail($id);
        $firmSettings = FirmSetting::getFirmSettings();

        $pdf = Pdf::loadView('receipt-print', compact('receipt', 'firmSettings'));
        return $pdf->download('receipt-' . $receipt->receipt_no . '.pdf');
    }

    public function edit($id)
    {
        $receipt = Receipt::with(['case', 'quotation', 'taxInvoice'])->findOrFail($id);
        
        // Get quotations and tax invoices for selection
        $quotations = Quotation::where('status', 'accepted')
            ->with(['case', 'items'])
            ->orderBy('created_at', 'desc')
            ->get(['id', 'quotation_no', 'case_id', 'total']);

        $taxInvoices = TaxInvoice::whereIn('status', ['sent', 'paid', 'partially_paid'])
            ->with(['case', 'items'])
            ->orderBy('created_at', 'desc')
            ->get(['id', 'invoice_no', 'case_id', 'total']);

        $caseOptions = CourtCase::orderBy('case_number')->get(['id', 'case_number']);

        return view('receipt-edit', compact('receipt', 'quotations', 'taxInvoices', 'caseOptions'));
    }

    public function update(Request $request, $id)
    {
        $receipt = Receipt::findOrFail($id);
        
        $validated = $request->validate([
            'case_id' => 'nullable|integer|exists:cases,id',
            'quotation_id' => 'nullable|integer|exists:quotations,id',
            'tax_invoice_id' => 'nullable|integer|exists:tax_invoices,id',
            'receipt_date' => 'required|date',
            'payment_reference' => 'nullable|string|max:255',
            'payment_method' => 'required|in:cash,bank_transfer,cheque,credit_card,online_payment,other',
            'bank_name' => 'nullable|string|max:255',
            'cheque_number' => 'nullable|string|max:255',
            'transaction_id' => 'nullable|string|max:255',
            'amount_paid' => 'required|numeric|min:0.01',
            'outstanding_balance' => 'required|numeric|min:0',
            'payment_notes' => 'nullable|string',
        ]);

        try {
            DB::beginTransaction();

            // Update receipt
            $receipt->update($validated);

            // Update related entities status
            if ($validated['quotation_id']) {
                $quotation = Quotation::find($validated['quotation_id']);
                if ($quotation) {
                    if ($validated['outstanding_balance'] <= 0) {
                        $quotation->update(['status' => 'converted']);
                    }
                }
            }

            if ($validated['tax_invoice_id']) {
                $invoice = TaxInvoice::find($validated['tax_invoice_id']);
                if ($invoice) {
                    if ($validated['outstanding_balance'] <= 0) {
                        $invoice->update(['status' => 'paid']);
                    } else {
                        $invoice->update(['status' => 'partially_paid']);
                    }
                }
            }

            DB::commit();

            return redirect()->route('receipt.show', $receipt->id)
                ->with('success', 'Receipt updated successfully');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->withErrors(['error' => 'Failed to update receipt: ' . $e->getMessage()]);
        }
    }

    public function destroy($id)
    {
        try {
            $receipt = Receipt::findOrFail($id);

            // Log receipt deletion before deleting
            activity()
                ->performedOn($receipt)
                ->causedBy(auth()->user())
                ->withProperties([
                    'ip' => request()->ip(),
                    'receipt_no' => $receipt->receipt_no,
                    'amount_paid' => $receipt->amount_paid,
                    'payment_method' => $receipt->payment_method
                ])
                ->log("Receipt {$receipt->receipt_no} deleted");

            $receipt->delete();

            return response()->json([
                'success' => true,
                'message' => 'Receipt deleted successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete receipt: ' . $e->getMessage()
            ], 500);
        }
    }
}
