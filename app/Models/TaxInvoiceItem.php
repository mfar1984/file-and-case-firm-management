<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaxInvoiceItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'tax_invoice_id',
        'description',
        'qty',
        'uom',
        'unit_price',
        'discount_percent',
        'tax_percent',
        'amount',
    ];

    protected $casts = [
        'qty' => 'decimal:2',
        'unit_price' => 'decimal:2',
        'discount_percent' => 'decimal:2',
        'tax_percent' => 'decimal:2',
        'amount' => 'decimal:2',
    ];

    public function taxInvoice()
    {
        return $this->belongsTo(TaxInvoice::class);
    }
}
