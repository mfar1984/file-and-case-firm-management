<?php

namespace App\Models;

use App\Traits\HasFirmScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OpeningBalance extends Model
{
    use HasFactory, HasFirmScope;

    protected $fillable = [
        'bank_code',
        'bank_name',
        'currency',
        'debit',
        'credit',
        'debit_myr',
        'credit_myr',
        'exchange_rate',
        'status',
        'firm_id'
    ];

    protected $casts = [
        'debit' => 'decimal:2',
        'credit' => 'decimal:2',
        'debit_myr' => 'decimal:2',
        'credit_myr' => 'decimal:2',
        'exchange_rate' => 'decimal:4',
        'status' => 'boolean'
    ];

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', true);
    }

    public function scopeByBankCode($query, $bankCode)
    {
        return $query->where('bank_code', $bankCode);
    }

    // Accessors
    public function getNetBalanceAttribute()
    {
        return $this->debit - $this->credit;
    }

    public function getNetBalanceMyrAttribute()
    {
        return $this->debit_myr - $this->credit_myr;
    }

    // Helper methods
    public function updateMyrAmounts()
    {
        if ($this->currency !== 'MYR') {
            $this->debit_myr = $this->debit * $this->exchange_rate;
            $this->credit_myr = $this->credit * $this->exchange_rate;
        } else {
            $this->debit_myr = $this->debit;
            $this->credit_myr = $this->credit;
        }
        $this->save();
    }


}
