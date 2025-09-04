<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CasePartner extends Model
{
    use HasFactory;

    protected $fillable = [
        'case_id',
        'partner_id',
        'role',
    ];

    public function case()
    {
        return $this->belongsTo(CourtCase::class, 'case_id');
    }

    public function partner()
    {
        return $this->belongsTo(Partner::class);
    }
}
