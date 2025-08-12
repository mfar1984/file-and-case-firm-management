<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CaseStatus extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'color',
        'status',
    ];

    protected $casts = [
        'status' => 'string',
        'color' => 'string',
    ];

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
}
