<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Voucher extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'voucher_no',
        'payee_name',
        'payee_address',
        'contact_person',
        'phone',
        'email',
        'payment_method',
        'payment_date',
        'approved_by',
        'remark',
        'subtotal',
        'tax_total',
        'total_amount',
        'total_words',
        'status',
    ];

    protected $casts = [
        'payment_date' => 'date',
        'subtotal' => 'decimal:2',
        'tax_total' => 'decimal:2',
        'total_amount' => 'decimal:2',
    ];

    public function items()
    {
        return $this->hasMany(VoucherItem::class);
    }

    public static function generateVoucherNo(): string
    {
        $year = date('Y');
        $prefix = 'PV-' . $year . '-';
        $max = static::whereYear('created_at', $year)
            ->where('voucher_no', 'like', $prefix.'%')
            ->max('voucher_no');

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
            'approved' => 'bg-blue-100 text-blue-800',
            'paid' => 'bg-green-100 text-green-800',
            'cancelled' => 'bg-red-100 text-red-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    public function getStatusDisplayAttribute()
    {
        return ucfirst($this->status);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['voucher_no', 'payee_name', 'status', 'total_amount', 'payment_method'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    public function getPaymentMethodDisplayAttribute()
    {
        return match($this->payment_method) {
            'cash' => 'Cash',
            'cheque' => 'Cheque',
            'bank_transfer' => 'Bank Transfer',
            'online_banking' => 'Online Banking',
            'credit_card' => 'Credit Card',
            default => ucfirst($this->payment_method),
        };
    }
}


