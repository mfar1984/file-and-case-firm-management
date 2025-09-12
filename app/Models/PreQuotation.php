<?php

namespace App\Models;

use App\Traits\HasFirmScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class PreQuotation extends Model
{
    use HasFactory, LogsActivity, HasFirmScope;

    protected $fillable = [
        'quotation_no',
        'quotation_date',
        'valid_until',
        'payment_terms',
        'remark',
        'status',
        'full_name', // Replace case_id with full_name
        'customer_phone',
        'customer_email',
        'customer_address',
        'subtotal',
        'discount_total',
        'tax_total',
        'total',
        'firm_id',
    ];

    protected $casts = [
        'quotation_date' => 'date',
        'valid_until' => 'date',
        'subtotal' => 'decimal:2',
        'discount_total' => 'decimal:2',
        'tax_total' => 'decimal:2',
        'total' => 'decimal:2',
    ];

    public function items()
    {
        return $this->hasMany(PreQuotationItem::class);
    }

    /**
     * Get the firm that owns this pre-quotation
     */
    public function firm(): BelongsTo
    {
        return $this->belongsTo(Firm::class);
    }

    public function getStatusColorAttribute()
    {
        return match($this->status) {
            'pending' => 'bg-yellow-100 text-yellow-800',
            'accepted' => 'bg-green-100 text-green-800',
            'rejected' => 'bg-red-100 text-red-800',
            'converted' => 'bg-blue-100 text-blue-800',
            'expired' => 'bg-gray-100 text-gray-800',
            'cancelled' => 'bg-red-100 text-red-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    public function getStatusIconAttribute()
    {
        return match($this->status) {
            'pending' => 'schedule',
            'accepted' => 'check_circle',
            'rejected' => 'cancel',
            'converted' => 'receipt',
            'expired' => 'warning',
            'cancelled' => 'block',
            default => 'help',
        };
    }

    /**
     * Get formatted status display text
     */
    public function getStatusDisplayAttribute()
    {
        return match($this->status) {
            'pending' => 'Pending',
            'accepted' => 'Accepted',
            'rejected' => 'Rejected',
            'converted' => 'Converted',
            'expired' => 'Expired',
            'cancelled' => 'Cancelled',
            default => ucfirst($this->status ?? 'pending'),
        };
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['quotation_no', 'full_name', 'status', 'total', 'firm_id'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }
}
