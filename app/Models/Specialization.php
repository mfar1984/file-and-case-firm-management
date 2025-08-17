<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Specialization extends Model
{
    use HasFactory;

    protected $fillable = [
        'specialist_name',
        'description',
        'status'
    ];

    protected $casts = [
        'status' => 'string'
    ];

    /**
     * Scope for active specializations
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope for inactive specializations
     */
    public function scopeInactive($query)
    {
        return $query->where('status', 'inactive');
    }
}
