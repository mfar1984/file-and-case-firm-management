<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FileType extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'description',
        'status',
    ];

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
}
