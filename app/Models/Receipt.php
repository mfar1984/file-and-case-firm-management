<?php

namespace App\Models;

use App\Traits\HasFirmScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Receipt extends Model
{
    use HasFactory, LogsActivity, HasFirmScope;

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
        'amount_paid_words',
        'outstanding_balance',
        'payment_notes',
        'status',
        'firm_id',
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

    /**
     * Convert number to words in Malaysian Ringgit format
     */
    public function convertToWords($number)
    {
        if ($number == 0) {
            return 'Zero Ringgit Only';
        }

        $ones = [
            '', 'One', 'Two', 'Three', 'Four', 'Five', 'Six', 'Seven', 'Eight', 'Nine',
            'Ten', 'Eleven', 'Twelve', 'Thirteen', 'Fourteen', 'Fifteen', 'Sixteen',
            'Seventeen', 'Eighteen', 'Nineteen'
        ];

        $tens = [
            '', '', 'Twenty', 'Thirty', 'Forty', 'Fifty', 'Sixty', 'Seventy', 'Eighty', 'Ninety'
        ];

        $scales = ['', 'Thousand', 'Million', 'Billion'];

        // Split into ringgit and sen
        $parts = explode('.', number_format($number, 2, '.', ''));
        $ringgit = (int)$parts[0];
        $sen = (int)$parts[1];

        $result = '';

        if ($ringgit > 0) {
            $result .= $this->convertNumberToWords($ringgit, $ones, $tens, $scales) . ' Ringgit';
        }

        if ($sen > 0) {
            if ($ringgit > 0) {
                $result .= ' And ';
            }
            $result .= $this->convertNumberToWords($sen, $ones, $tens, $scales) . ' Sen';
        }

        return $result . ' Only';
    }

    private function convertNumberToWords($number, $ones, $tens, $scales)
    {
        if ($number == 0) {
            return '';
        }

        $result = '';
        $scaleIndex = 0;

        while ($number > 0) {
            $chunk = $number % 1000;
            if ($chunk != 0) {
                $chunkWords = $this->convertChunkToWords($chunk, $ones, $tens);
                if ($scaleIndex > 0) {
                    $chunkWords .= ' ' . $scales[$scaleIndex];
                }
                $result = $chunkWords . ($result ? ' ' . $result : '');
            }
            $number = intval($number / 1000);
            $scaleIndex++;
        }

        return $result;
    }

    private function convertChunkToWords($number, $ones, $tens)
    {
        $result = '';

        // Hundreds
        if ($number >= 100) {
            $result .= $ones[intval($number / 100)] . ' Hundred';
            $number %= 100;
            if ($number > 0) {
                $result .= ' ';
            }
        }

        // Tens and ones
        if ($number >= 20) {
            $result .= $tens[intval($number / 10)];
            $number %= 10;
            if ($number > 0) {
                $result .= ' ' . $ones[$number];
            }
        } elseif ($number > 0) {
            $result .= $ones[$number];
        }

        return $result;
    }

    /**
     * Auto-generate amount_paid_words when amount_paid is updated
     */
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($receipt) {
            if ($receipt->isDirty('amount_paid')) {
                $receipt->amount_paid_words = $receipt->convertToWords($receipt->amount_paid);
            }
        });
    }



    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['receipt_no', 'customer_name', 'amount_paid', 'payment_method', 'receipt_date'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }
}
