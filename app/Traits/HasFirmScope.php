<?php

namespace App\Traits;

use App\Models\Firm;
use App\Scopes\FirmScope;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait HasFirmScope
{
    /**
     * Boot the trait and add the global scope.
     */
    protected static function bootHasFirmScope()
    {
        static::addGlobalScope(new FirmScope);

        // Auto-assign firm_id on creation
        static::creating(function ($model) {
            if (!$model->firm_id) {
                $user = auth()->user();
                if ($user) {
                    // For Super Admin, use session firm_id if available
                    if ($user->hasRole('Super Administrator') && session('current_firm_id')) {
                        $model->firm_id = session('current_firm_id');
                    } else {
                        // For regular users, use their assigned firm
                        $model->firm_id = $user->firm_id;
                    }
                } else {
                    // Default to firm 1 if no user context
                    $model->firm_id = 1;
                }
            }
        });
    }

    /**
     * Get the firm that owns the model.
     */
    public function firm(): BelongsTo
    {
        return $this->belongsTo(Firm::class);
    }

    /**
     * Scope a query to exclude firm filtering (for system administrators).
     */
    public function scopeWithoutFirmScope($query)
    {
        return $query->withoutGlobalScope(FirmScope::class);
    }

    /**
     * Scope a query to include specific firm.
     */
    public function scopeForFirm($query, $firmId)
    {
        return $query->withoutGlobalScope(FirmScope::class)->where('firm_id', $firmId);
    }

    /**
     * Scope a query to include multiple firms.
     */
    public function scopeForFirms($query, array $firmIds)
    {
        return $query->withoutGlobalScope(FirmScope::class)->whereIn('firm_id', $firmIds);
    }
}
