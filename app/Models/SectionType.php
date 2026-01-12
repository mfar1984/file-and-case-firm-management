<?php

namespace App\Models;

use App\Traits\HasFirmScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SectionType extends Model
{
    use HasFactory, HasFirmScope;

    protected $fillable = [
        'code',
        'name',
        'description',
        'status',
        'firm_id',
    ];

    protected $casts = [
        'status' => 'string',
    ];

    /**
     * The relationships that should always be loaded.
     */
    protected $with = ['initiatingDocuments', 'customFields'];

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('code')->orderBy('name');
    }

    /**
     * Get the firm that owns this section type
     */
    public function firm(): BelongsTo
    {
        return $this->belongsTo(Firm::class);
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
        return ucfirst($this->status);
    }

    /**
     * Relationship with CaseInitiatingDocuments
     */
    public function initiatingDocuments()
    {
        return $this->hasMany(CaseInitiatingDocument::class);
    }

    /**
     * Relationship with active CaseInitiatingDocuments
     */
    public function activeInitiatingDocuments()
    {
        return $this->hasMany(CaseInitiatingDocument::class)->active()->ordered();
    }

    /**
     * Relationship with SectionCustomFields
     */
    public function customFields()
    {
        return $this->hasMany(SectionCustomField::class);
    }

    /**
     * Relationship with active SectionCustomFields
     */
    public function activeCustomFields()
    {
        return $this->hasMany(SectionCustomField::class)->active()->ordered();
    }

    /**
     * Get form value for backward compatibility
     */
    public function getFormValueAttribute()
    {
        return match($this->code) {
            'CA' => 'civil',
            'CR' => 'criminal',
            'CVY' => 'conveyancing',
            'PB' => 'probate',
            default => strtolower($this->code),
        };
    }
}
