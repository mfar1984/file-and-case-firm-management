<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Client extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'name',
        'ic_passport',
        'phone',
        'email',
        'tin_no',
        'job',
        'salary',
        'dependent',
        'family_contact_name',
        'family_contact_phone',
        'family_address',
        'agent_banker',
        'financier_bank',
        'lawyers_parties',
        'notes',
        'client_code',
        'party_type',
        'identity_type',
        'gender',
        'nationality',
        'race',
        'fax',
        'mobile',
        'user_id',
    ];

    protected $casts = [
        'is_banned' => 'boolean',
        'salary' => 'decimal:2',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function (Client $client) {
            if (empty($client->client_code)) {
                $nextId = (Client::max('id') ?? 0) + 1;
                $client->client_code = 'CL-' . str_pad((string)$nextId, 3, '0', STR_PAD_LEFT);
            }
        });
    }

    public function getStatusBadgeColorAttribute(): string
    {
        return $this->is_banned ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800';
    }

    /**
     * Get all addresses for the client.
     */
    public function addresses()
    {
        return $this->hasMany(ClientAddress::class);
    }

    /**
     * Get the primary address for the client.
     */
    public function primaryAddress()
    {
        return $this->hasOne(ClientAddress::class)->where('is_primary', true);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['name', 'ic_passport', 'phone', 'email', 'job'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }
}
