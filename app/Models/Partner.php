<?php

namespace App\Models;

use App\Traits\HasFirmScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Partner extends Model
{
    use HasFactory, LogsActivity, HasFirmScope;

    protected $fillable = [
        'partner_code',
        'firm_name',
        'address',
        'contact_no',
        'email',
        'incharge_name',
        'incharge_contact',
        'incharge_email',
        'status',
        'specialization',
        'years_of_experience',
        'bar_council_number',
        'registration_date',
        'notes',
        'is_banned',
        'user_id',
        'firm_id',
    ];

    protected $casts = [
        'is_banned' => 'boolean',
        'registration_date' => 'date',
        'years_of_experience' => 'integer',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function (Partner $partner) {
            if (empty($partner->partner_code)) {
                $nextId = (Partner::max('id') ?? 0) + 1;
                $partner->partner_code = 'P-' . str_pad((string)$nextId, 3, '0', STR_PAD_LEFT);
            }
        });
    }

    public function getStatusBadgeColorAttribute(): string
    {
        return match ($this->status) {
            'active' => 'bg-green-100 text-green-800',
            'inactive' => 'bg-red-100 text-red-800',
            'suspended' => 'bg-yellow-100 text-yellow-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }



    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['partner_code', 'firm_name', 'contact_no', 'email', 'incharge_name', 'status'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }
}