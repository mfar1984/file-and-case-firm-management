<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Voucher extends Model
{
    use HasFactory;

    protected $fillable = [
        'voucher_no',
        'payee_id',
        'payee_address',
        'contact_person',
        'phone',
        'payment_method',
        'payment_date',
        'approved_by',
        'remark',
        'total_amount',
    ];

    protected $casts = [
        'payment_date' => 'date',
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
}


