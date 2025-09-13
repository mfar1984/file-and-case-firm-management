<?php

namespace App\Models;

use App\Traits\HasFirmScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Bill extends Model
{
    use HasFactory, LogsActivity, HasFirmScope;

    protected $fillable = [
        'bill_no',
        'vendor_name',
        'vendor_address',
        'vendor_phone',
        'vendor_email',
        'bill_date',
        'due_date',
        'payment_date',
        'category',
        'description',
        'subtotal',
        'tax_total',
        'total_amount',
        'total_words',
        'payment_method',
        'payment_reference',
        'remark',
        'status',
        'firm_id',
    ];

    protected $casts = [
        'bill_date' => 'date',
        'due_date' => 'date',
        'payment_date' => 'date',
        'subtotal' => 'decimal:2',
        'tax_total' => 'decimal:2',
        'total_amount' => 'decimal:2',
    ];

    public function items()
    {
        return $this->hasMany(BillItem::class);
    }

    public static function generateBillNo(): string
    {
        $year = date('Y');
        $prefix = 'BL-' . $year . '-';

        // Get firm context for bill number generation
        $user = auth()->user();
        $firmId = null;

        if ($user) {
            if ($user->hasRole('Super Administrator') && session('current_firm_id')) {
                $firmId = session('current_firm_id');
            } else {
                $firmId = $user->firm_id;
            }
        }

        // Generate bill number based on firm context
        $query = static::whereYear('created_at', $year)
            ->where('bill_no', 'like', $prefix.'%');

        if ($firmId) {
            $query->where('firm_id', $firmId);
        }

        $max = $query->max('bill_no');

        $nextSeq = 1;
        if ($max) {
            $parts = explode('-', $max);
            $nextSeq = isset($parts[2]) ? ((int) $parts[2]) + 1 : 1;
        }
        return $prefix . str_pad((string)$nextSeq, 3, '0', STR_PAD_LEFT);
    }

    public function getStatusColorAttribute()
    {
        return match($this->status) {
            'draft' => 'bg-gray-100 text-gray-800',
            'pending' => 'bg-yellow-100 text-yellow-800',
            'overdue' => 'bg-red-100 text-red-800',
            'paid' => 'bg-green-100 text-green-800',
            'cancelled' => 'bg-red-100 text-red-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    public function getStatusDisplayAttribute()
    {
        return ucfirst($this->status);
    }

    public function getPaymentMethodDisplayAttribute()
    {
        return match($this->payment_method) {
            'cash' => 'Cash',
            'cheque' => 'Cheque',
            'bank_transfer' => 'Bank Transfer',
            'online_banking' => 'Online Banking',
            'credit_card' => 'Credit Card',
            default => ucfirst($this->payment_method ?? ''),
        };
    }

    public function getIsOverdueAttribute()
    {
        return $this->status !== 'paid' && $this->due_date < now()->toDateString();
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
     * Auto-generate total_words when total_amount is updated
     */
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($bill) {
            if ($bill->isDirty('total_amount')) {
                $bill->total_words = $bill->convertToWords($bill->total_amount);
            }
        });
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['bill_no', 'client_name', 'status', 'total_amount', 'payment_method'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }
}
