<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasFirmScope;

class CaseInitiatingDocument extends Model
{
    use HasFactory, HasFirmScope;

    protected $fillable = [
        'section_type_id',
        'document_name',
        'document_code',
        'sort_order',
        'status',
        'firm_id',
    ];

    protected $casts = [
        'sort_order' => 'integer',
    ];

    /**
     * Relationship with SectionType
     */
    public function sectionType()
    {
        return $this->belongsTo(SectionType::class);
    }

    /**
     * Scope for active documents
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope for ordering
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('document_name');
    }

    /**
     * Get status color for display
     */
    public function getStatusColorAttribute()
    {
        return match($this->status) {
            'active' => 'bg-green-100 text-green-800',
            'inactive' => 'bg-red-100 text-red-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    /**
     * Get status display text
     */
    public function getStatusDisplayAttribute()
    {
        return match($this->status) {
            'active' => 'Active',
            'inactive' => 'Inactive',
            default => 'Unknown',
        };
    }
}
