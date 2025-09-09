<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class CaseFile extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'case_ref',
        'file_name',
        'file_path',
        'category_id',
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
        $fileSize = $this->file_size;
        
        // If file_size is already formatted (e.g., "2.5 MB"), return as is
        if (is_string($fileSize) && preg_match('/^\d+(\.\d+)?\s*(B|KB|MB|GB)$/i', $fileSize)) {
            return $fileSize;
        }
        
        // If it's numeric, format it
        if (is_numeric($fileSize)) {
            $bytes = (float) $fileSize;
            $units = ['B', 'KB', 'MB', 'GB'];
            
            for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
                $bytes /= 1024;
            }
            
            return round($bytes, 1) . ' ' . $units[$i];
        }
        
        // Fallback
        return $fileSize ?? 'Unknown';
    }

    /**
     * Get the file size in bytes
     */
    public function getSizeInBytesAttribute()
    {
        $fileSize = $this->file_size;
        
        // If it's already numeric, return as is
        if (is_numeric($fileSize)) {
            return (int) $fileSize;
        }
        
        // If it's formatted string, convert to bytes
        if (is_string($fileSize) && preg_match('/^(\d+(?:\.\d+)?)\s*(B|KB|MB|GB)$/i', $fileSize, $matches)) {
            $size = (float) $matches[1];
            $unit = strtoupper($matches[2]);
            
            switch ($unit) {
                case 'B':
                    return (int) $size;
                case 'KB':
                    return (int) ($size * 1024);
                case 'MB':
                    return (int) ($size * 1024 * 1024);
                case 'GB':
                    return (int) ($size * 1024 * 1024 * 1024);
                default:
                    return 0;
            }
        }
        
        return 0;
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
        return $query->where('category_id', $fileType);
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

    /**
     * Scope for ordering by file size
     */
    public function scopeOrderBySize($query, $direction = 'desc')
    {
        return $query->orderByRaw('
            CASE 
                WHEN file_size REGEXP "^[0-9]+$" THEN CAST(file_size AS UNSIGNED)
                WHEN file_size REGEXP "^[0-9]+(\.[0-9]+)?\\s*KB$" THEN CAST(SUBSTRING_INDEX(file_size, " ", 1) AS DECIMAL(10,2)) * 1024
                WHEN file_size REGEXP "^[0-9]+(\.[0-9]+)?\\s*MB$" THEN CAST(SUBSTRING_INDEX(file_size, " ", 1) AS DECIMAL(10,2)) * 1024 * 1024
                WHEN file_size REGEXP "^[0-9]+(\.[0-9]+)?\\s*GB$" THEN CAST(SUBSTRING_INDEX(file_size, " ", 1) AS DECIMAL(10,2)) * 1024 * 1024 * 1024
                ELSE 0
            END ' . $direction
        );
    }

    /**
     * Scope for filtering by file size range
     */
    public function scopeSizeBetween($query, $minSize, $maxSize)
    {
        return $query->whereRaw('
            CASE 
                WHEN file_size REGEXP "^[0-9]+$" THEN CAST(file_size AS UNSIGNED)
                WHEN file_size REGEXP "^[0-9]+(\.[0-9]+)?\\s*KB$" THEN CAST(SUBSTRING_INDEX(file_size, " ", 1) AS DECIMAL(10,2)) * 1024
                WHEN file_size REGEXP "^[0-9]+(\.[0-9]+)?\\s*MB$" THEN CAST(SUBSTRING_INDEX(file_size, " ", 1) AS DECIMAL(10,2)) * 1024 * 1024
                WHEN file_size REGEXP "^[0-9]+(\.[0-9]+)?\\s*GB$" THEN CAST(SUBSTRING_INDEX(file_size, " ", 1) AS DECIMAL(10,2)) * 1024 * 1024 * 1024
                ELSE 0
            END BETWEEN ? AND ?', [$minSize, $maxSize]
        );
    }

    public function fileType()
    {
        return $this->belongsTo(FileType::class, 'category_id');
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['case_ref', 'file_name', 'status', 'category_id', 'file_size'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }
}
