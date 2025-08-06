<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmailSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'smtp_host',
        'smtp_port',
        'email_username',
        'email_password',
        'from_name',
        'from_email',
        'encryption',
        'notify_new_cases',
        'notify_document_uploads',
        'notify_case_status',
        'notify_maintenance'
    ];

    protected $casts = [
        'encryption' => 'boolean',
        'notify_new_cases' => 'boolean',
        'notify_document_uploads' => 'boolean',
        'notify_case_status' => 'boolean',
        'notify_maintenance' => 'boolean'
    ];

    /**
     * Get the first (and only) email settings record
     */
    public static function getEmailSettings()
    {
        return static::first() ?? static::create([
            'smtp_host' => 'smtp.gmail.com',
            'smtp_port' => 587,
            'email_username' => 'noreply@naaelahsaleh.my',
            'email_password' => '',
            'from_name' => 'Naeelah Saleh & Associates',
            'from_email' => 'noreply@naaelahsaleh.my',
            'encryption' => true,
            'notify_new_cases' => true,
            'notify_document_uploads' => true,
            'notify_case_status' => true,
            'notify_maintenance' => false
        ]);
    }
}
