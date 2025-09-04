<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CaseParty extends Model
{
    use HasFactory;

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
    ];

    public function case()
    {
        return $this->belongsTo(CourtCase::class, 'case_id');
    }
}
