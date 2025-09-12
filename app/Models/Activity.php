<?php

namespace App\Models;

use Spatie\Activitylog\Models\Activity as SpatieActivity;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Activity extends SpatieActivity
{
    protected $fillable = [
        'log_name',
        'description',
        'subject_type',
        'event',
        'subject_id',
        'causer_type',
        'causer_id',
        'properties',
        'batch_uuid',
        'firm_id',
    ];

    /**
     * Boot the model and auto-assign firm_id
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($activity) {
            if (!$activity->firm_id) {
                // Get current firm context (prioritize session for Super Admin)
                $user = auth()->user();
                if ($user) {
                    // For Super Admin, use session firm_id if available
                    if ($user->hasRole('Super Administrator') && session('current_firm_id')) {
                        $activity->firm_id = session('current_firm_id');
                    } else {
                        // For regular users, use their assigned firm
                        $activity->firm_id = $user->firm_id;
                    }
                } else {
                    // Default to firm 1 if no user context
                    $activity->firm_id = 1;
                }
            }
        });
    }

    /**
     * Get the firm that owns this activity log
     */
    public function firm(): BelongsTo
    {
        return $this->belongsTo(Firm::class);
    }

    /**
     * Scope to filter by firm
     */
    public function scopeForFirm($query, $firmId)
    {
        return $query->where('firm_id', $firmId);
    }

    /**
     * Scope to exclude firm filtering (for system operations)
     */
    public function scopeWithoutFirmScope($query)
    {
        return $query;
    }
}
