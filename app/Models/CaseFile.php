<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CaseFile extends Model
{
    use HasFactory;

    protected $fillable = [
        'case_ref',
        'file_name',
        'file_path',
        'file_type',
        'file_size',
        'mime_type',
        'description',
        'status',
        'taken_by',
        'purpose',
        'expected_return',
        'actual_return',
        'rack_location',
    ];

    protected $casts = [
        'expected_return' => 'date',
        'actual_return' => 'date',
    ];

    /**
     * Get the formatted file size
     */
    public function getFormattedSizeAttribute()
    {
        $bytes = $this->file_size;
        $units = ['B', 'KB', 'MB', 'GB'];
        
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, 1) . ' ' . $units[$i];
    }

    /**
     * Get the file icon based on MIME type
     */
    public function getFileIconAttribute()
    {
        $mimeType = $this->mime_type;
        
        if (str_contains($mimeType, 'pdf')) {
            return 'picture_as_pdf';
        } elseif (str_contains($mimeType, 'word') || str_contains($mimeType, 'document')) {
            return 'description';
        } elseif (str_contains($mimeType, 'image')) {
            return 'image';
        } elseif (str_contains($mimeType, 'zip') || str_contains($mimeType, 'rar')) {
            return 'folder_zip';
        } else {
            return 'insert_drive_file';
        }
    }

    /**
     * Get the file icon color based on MIME type
     */
    public function getFileIconColorAttribute()
    {
        $mimeType = $this->mime_type;
        
        if (str_contains($mimeType, 'pdf')) {
            return 'text-red-500';
        } elseif (str_contains($mimeType, 'word') || str_contains($mimeType, 'document')) {
            return 'text-blue-500';
        } elseif (str_contains($mimeType, 'image')) {
            return 'text-green-500';
        } elseif (str_contains($mimeType, 'zip') || str_contains($mimeType, 'rar')) {
            return 'text-purple-500';
        } else {
            return 'text-gray-500';
        }
    }

    /**
     * Get the file type badge color
     */
    public function getFileTypeBadgeColorAttribute()
    {
        $typeColors = [
            'contract' => 'bg-blue-100 text-blue-800',
            'evidence' => 'bg-green-100 text-green-800',
            'correspondence' => 'bg-yellow-100 text-yellow-800',
            'court_document' => 'bg-purple-100 text-purple-800',
            'invoice' => 'bg-orange-100 text-orange-800',
            'other' => 'bg-gray-100 text-gray-800',
        ];
        
        return $typeColors[$this->file_type] ?? 'bg-gray-100 text-gray-800';
    }

    /**
     * Get the status badge color
     */
    public function getStatusBadgeColorAttribute()
    {
        return $this->status === 'IN' 
            ? 'bg-green-100 text-green-800' 
            : 'bg-red-100 text-red-800';
    }

    /**
     * Scope for filtering by case reference
     */
    public function scopeByCase($query, $caseRef)
    {
        return $query->where('case_ref', $caseRef);
    }

    /**
     * Scope for filtering by file type
     */
    public function scopeByType($query, $fileType)
    {
        return $query->where('file_type', $fileType);
    }

    /**
     * Scope for filtering by status
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Check if file is overdue (OUT for more than expected return date)
     */
    public function getIsOverdueAttribute()
    {
        if ($this->status === 'OUT' && $this->expected_return) {
            return now()->isAfter($this->expected_return);
        }
        return false;
    }
}
