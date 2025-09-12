<?php

namespace App\Models;

use App\Traits\HasFirmScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmailSetting extends Model
{
    use HasFactory, HasFirmScope;

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
        'notify_maintenance',
        'notify_user_accounts',
        'firm_id'
    ];

    protected $casts = [
        'encryption' => 'boolean',
        'notify_new_cases' => 'boolean',
        'notify_document_uploads' => 'boolean',
        'notify_case_status' => 'boolean',
        'notify_maintenance' => 'boolean',
        'notify_user_accounts' => 'boolean'
    ];

    /**
     * Get email settings for current firm context
     */
    public static function getEmailSettings()
    {
        // Get current firm context
        $user = auth()->user();
        $firmId = session('current_firm_id') ?? $user->firm_id;

        if ($firmId) {
            $settings = static::where('firm_id', $firmId)->first();
            if ($settings) {
                return $settings;
            }
        }

        // Create default settings for current firm
        return static::create([
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
            'notify_maintenance' => false,
            'notify_user_accounts' => true,
            'firm_id' => $firmId
        ]);
    }

    /**
     * Get the firm that owns this setting
     */
    public function firm(): BelongsTo
    {
        return $this->belongsTo(Firm::class);
    }
}
