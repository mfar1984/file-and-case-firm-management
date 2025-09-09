<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Receipt extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'receipt_no',
        'case_id',
        'quotation_id',
        'tax_invoice_id',
        'receipt_date',
        'payment_reference',
        'payment_method',
        'bank_name',
        'cheque_number',
        'transaction_id',
        'amount_paid',
        'outstanding_balance',
        'payment_notes',
        'status',
    ];

    protected $casts = [
        'receipt_date' => 'date',
        'amount_paid' => 'decimal:2',
        'outstanding_balance' => 'decimal:2',
    ];

    /**
     * Get the case associated with the receipt
     */
    public function case()
    {
        return $this->belongsTo(CourtCase::class, 'case_id');
    }

    /**
     * Get the quotation associated with the receipt
     */
    public function quotation()
    {
        return $this->belongsTo(Quotation::class, 'quotation_id');
    }

    /**
     * Get the tax invoice associated with the receipt
     */
    public function taxInvoice()
    {
        return $this->belongsTo(TaxInvoice::class, 'tax_invoice_id');
    }

    /**
     * Get the customer name from related entities
     */
    public function getCustomerNameAttribute()
    {
        if ($this->taxInvoice) {
            return $this->taxInvoice->customer_name;
        }
        if ($this->quotation) {
            return $this->quotation->customer_name;
        }
        if ($this->case) {
            return $this->case->parties->first()?->name ?? 'N/A';
        }
        return 'N/A';
    }

    /**
     * Get the case number from related entities
     */
    public function getCaseNumberAttribute()
    {
        if ($this->case) {
            return $this->case->case_number;
        }
        if ($this->taxInvoice && $this->taxInvoice->case) {
            return $this->taxInvoice->case->case_number;
        }
        if ($this->quotation && $this->quotation->case) {
            return $this->quotation->case->case_number;
        }
        return 'N/A';
    }

    /**
     * Get the total amount from related entities
     */
    public function getTotalAmountAttribute()
    {
        if ($this->taxInvoice) {
            return $this->taxInvoice->total;
        }
        if ($this->quotation) {
            return $this->quotation->total;
        }
        return 0;
    }

    /**
     * Get the payment method display name
     */
    public function getPaymentMethodDisplayAttribute()
    {
        return match($this->payment_method) {
            'cash' => 'Cash',
            'bank_transfer' => 'Bank Transfer',
            'cheque' => 'Cheque',
            'credit_card' => 'Credit Card',
            'online_payment' => 'Online Payment',
            'other' => 'Other',
            default => 'Unknown',
        };
    }

    /**
     * Get the status color for UI
     */
    public function getStatusColorAttribute()
    {
        return match($this->status) {
            'draft' => 'bg-gray-100 text-gray-800',
            'confirmed' => 'bg-green-100 text-green-800',
            'cancelled' => 'bg-red-100 text-red-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    /**
     * Get the status icon for UI
     */
    public function getStatusIconAttribute()
    {
        return match($this->status) {
            'draft' => 'edit',
            'confirmed' => 'check_circle',
            'cancelled' => 'cancel',
            default => 'help',
        };
    }

    /**
     * Generate receipt number
     */
    public static function generateReceiptNumber()
    {
        $nextId = (self::max('id') ?? 0) + 1;
        return 'RCP-' . str_pad((string)$nextId, 5, '0', STR_PAD_LEFT);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['receipt_no', 'customer_name', 'amount_paid', 'payment_method', 'receipt_date'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }
}
