<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasFirmScope;

class SectionCustomField extends Model
{
    use HasFactory, HasFirmScope;

    protected $fillable = [
        'section_type_id',
        'field_name',
        'field_type',
        'placeholder',
        'field_options',
        'conditional_document_code',
        'is_required',
        'sort_order',
        'status',
        'firm_id',
    ];

    protected $casts = [
        'field_options' => 'array',
        'is_required' => 'boolean',
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
     * Relationship with CaseCustomFieldValues
     */
    public function caseValues()
    {
        return $this->hasMany(CaseCustomFieldValue::class, 'custom_field_id');
    }

    /**
     * Scope for active fields
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
        return $query->orderBy('sort_order')->orderBy('field_name');
    }

    /**
     * Scope for conditional fields based on document code
     */
    public function scopeConditionalFor($query, $documentCode)
    {
        return $query->where('conditional_document_code', $documentCode);
    }

    /**
     * Scope for non-conditional fields (always show)
     */
    public function scopeNonConditional($query)
    {
        return $query->whereNull('conditional_document_code');
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

    /**
     * Get field type display text
     */
    public function getFieldTypeDisplayAttribute()
    {
        return match($this->field_type) {
            'text' => 'Text',
            'number' => 'Number',
            'dropdown' => 'Dropdown',
            'checkbox' => 'Checkbox',
            'date' => 'Date',
            'time' => 'Time',
            'datetime' => 'Date & Time',
            default => 'Unknown',
        };
    }

    /**
     * Get required display text
     */
    public function getRequiredDisplayAttribute()
    {
        return $this->is_required ? 'Yes' : 'No';
    }
}
