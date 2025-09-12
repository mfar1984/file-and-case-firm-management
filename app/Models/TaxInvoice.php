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

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['invoice_no', 'customer_name', 'status', 'total', 'due_date', 'firm_id'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }
}
