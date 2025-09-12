<?php

namespace App\Models;

use App\Traits\HasFirmScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ExpenseCategory extends Model
{
    use HasFactory, HasFirmScope;

    protected $fillable = [
        'name',
        'description',
        'status',
        'sort_order',
        'firm_id',
    ];

    protected $attributes = [
        'status' => 'active',
        'sort_order' => 0,
    ];

    protected $casts = [
        'sort_order' => 'integer',
    ];

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('name');
    }

    public function getStatusColorAttribute()
    {
        return match($this->status) {
            'active' => 'bg-green-100 text-green-800',
            'inactive' => 'bg-red-100 text-red-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    public function getStatusDisplayAttribute()
    {
        return ucfirst($this->status);
    }

    // Validation rules
    public static function validationRules($firmId = null)
    {
        if (!$firmId) {
            $user = auth()->user();
            $firmId = session('current_firm_id') ?? $user->firm_id;
        }

        return [
            'name' => 'required|string|max:255|unique:expense_categories,name,NULL,id,firm_id,' . $firmId,
            'description' => 'nullable|string|max:500',
            'status' => 'required|in:active,inactive',
            'sort_order' => 'nullable|integer|min:0',
        ];
    }

    public static function updateValidationRules($id, $firmId = null)
    {
        if (!$firmId) {
            $user = auth()->user();
            $firmId = session('current_firm_id') ?? $user->firm_id;
        }

        return [
            'name' => 'required|string|max:255|unique:expense_categories,name,' . $id . ',id,firm_id,' . $firmId,
            'description' => 'nullable|string|max:500',
            'status' => 'required|in:active,inactive',
            'sort_order' => 'nullable|integer|min:0',
        ];
    }


}