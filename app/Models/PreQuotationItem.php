<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PreQuotationItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'pre_quotation_id',
        'item_type',
        'title_text',
        'description',
        'qty',
        'uom',
        'unit_price',
        'discount_percent',
        'tax_percent',
        'tax_category_id',
        'amount',
    ];

    protected $casts = [
        'qty' => 'decimal:2',
        'unit_price' => 'decimal:2',
        'discount_percent' => 'decimal:2',
        'tax_percent' => 'decimal:2',
        'amount' => 'decimal:2',
    ];

    public function preQuotation()
    {
        return $this->belongsTo(PreQuotation::class);
    }

    public function taxCategory()
    {
        return $this->belongsTo(TaxCategory::class);
    }
}
