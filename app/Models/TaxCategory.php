<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasFirmScope;

class TaxCategory extends Model
{
    use HasFactory, HasFirmScope;

    protected $fillable = [
        'name',
        'description',
        'tax_rate',
        'status',
        'sort_order',
        'firm_id'
    ];

    protected $casts = [
        'tax_rate' => 'decimal:2',
        'sort_order' => 'integer'
    ];

    /**
     * Get the firm that owns the tax category.
     */
    public function firm()
    {
        return $this->belongsTo(Firm::class);
    }

    /**
     * Scope a query to only include active tax categories.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope a query to order by sort order.
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('name');
    }
}
