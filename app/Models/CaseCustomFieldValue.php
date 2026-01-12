<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CaseCustomFieldValue extends Model
{
    use HasFactory;

    protected $fillable = [
        'case_id',
        'custom_field_id',
        'field_value',
    ];

    /**
     * Relationship with Case
     */
    public function case()
    {
        return $this->belongsTo(CaseModel::class, 'case_id');
    }

    /**
     * Relationship with SectionCustomField
     */
    public function customField()
    {
        return $this->belongsTo(SectionCustomField::class, 'custom_field_id');
    }

    /**
     * Get formatted value based on field type
     */
    public function getFormattedValueAttribute()
    {
        $fieldType = $this->customField->field_type ?? 'text';
        
        return match($fieldType) {
            'number' => is_numeric($this->field_value) ? number_format($this->field_value, 2) : $this->field_value,
            'date' => $this->field_value ? \Carbon\Carbon::parse($this->field_value)->format('d/m/Y') : '',
            'time' => $this->field_value ? \Carbon\Carbon::parse($this->field_value)->format('H:i') : '',
            'datetime' => $this->field_value ? \Carbon\Carbon::parse($this->field_value)->format('d/m/Y H:i') : '',
            'checkbox' => $this->field_value ? 'Yes' : 'No',
            default => $this->field_value,
        };
    }

    /**
     * Get display value for different field types
     */
    public function getDisplayValueAttribute()
    {
        $fieldType = $this->customField->field_type ?? 'text';
        
        if ($fieldType === 'checkbox') {
            return $this->field_value ? '✓ Yes' : '✗ No';
        }
        
        if ($fieldType === 'dropdown' && $this->customField->field_options) {
            $options = $this->customField->field_options;
            foreach ($options as $option) {
                if (isset($option['value']) && $option['value'] === $this->field_value) {
                    return $option['label'] ?? $this->field_value;
                }
            }
        }
        
        return $this->formatted_value;
    }
}
