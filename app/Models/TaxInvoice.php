<?php

namespace App\Models;

use App\Traits\HasFirmScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class TaxInvoice extends Model
{
    use HasFactory, LogsActivity, HasFirmScope;

    protected $fillable = [
        'invoice_no',
        'case_id',
        'quotation_id',
        'invoice_date',
        'due_date',
        'payment_terms',
        'remark',
        'customer_name',
        'customer_phone',
        'customer_email',
        'customer_address',
        'contact_person',
        'contact_phone',
        'subtotal',
        'discount_total',
        'tax_total',
        'total',
        'total_words',
        'status',
        'firm_id',
    ];

    protected $casts = [
        'invoice_date' => 'date',
        'due_date' => 'date',
        'subtotal' => 'decimal:2',
        'discount_total' => 'decimal:2',
        'tax_total' => 'decimal:2',
        'total' => 'decimal:2',
    ];

    public function case()
    {
        return $this->belongsTo(CourtCase::class, 'case_id');
    }

    public function quotation()
    {
        return $this->belongsTo(Quotation::class, 'quotation_id');
    }

    /**
     * Get the firm that owns this tax invoice
     */
    public function firm(): BelongsTo
    {
        return $this->belongsTo(Firm::class);
    }

    public function items()
    {
        return $this->hasMany(TaxInvoiceItem::class);
    }

    public function getStatusColorAttribute()
    {
        return match($this->status) {
            'draft' => 'bg-gray-100 text-gray-800',
            'sent' => 'bg-blue-100 text-blue-800',
            'partially_paid' => 'bg-yellow-100 text-yellow-800',
            'paid' => 'bg-green-100 text-green-800',
            'overdue' => 'bg-red-100 text-red-800',
            'cancelled' => 'bg-red-100 text-red-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    public function getStatusIconAttribute()
    {
        return match($this->status) {
            'draft' => 'edit',
            'sent' => 'send',
            'partially_paid' => 'schedule',
            'paid' => 'check_circle',
            'overdue' => 'warning',
            'cancelled' => 'cancel',
            default => 'help',
        };
    }

    /**
     * Get formatted status display text
     */
    public function getStatusDisplayAttribute()
    {
        return match($this->status) {
            'draft' => 'Draft',
            'sent' => 'Sent',
            'partially_paid' => 'Partially Paid',
            'paid' => 'Paid',
            'overdue' => 'Overdue',
            'cancelled' => 'Cancelled',
            default => ucfirst($this->status ?? 'draft'),
        };
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
     * Auto-generate total_words when total is updated
     */
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($taxInvoice) {
            if ($taxInvoice->isDirty('total')) {
                $taxInvoice->total_words = $taxInvoice->convertToWords($taxInvoice->total);
            }
        });
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['invoice_no', 'customer_name', 'status', 'total', 'due_date', 'firm_id'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }
}
