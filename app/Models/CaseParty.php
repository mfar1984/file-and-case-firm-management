<?php

namespace App\Models;

use App\Traits\HasFirmScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class CaseParty extends Model
{
    use HasFactory, LogsActivity, HasFirmScope;

    protected $fillable = [
        'case_id',
        'party_type',
        'name',
        'ic_passport',
        'phone',
        'email',
        'username',
        'password',
        'gender',
        'nationality',
        'firm_id',
    ];

    public function case()
    {
        return $this->belongsTo(CourtCase::class, 'case_id');
    }

    /**
     * Get the firm that owns this case party
     */
    public function firm()
    {
        return $this->belongsTo(Firm::class);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['case_id', 'party_type', 'name', 'ic_passport', 'firm_id'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }
}
