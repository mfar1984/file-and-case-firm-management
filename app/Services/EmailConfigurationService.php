<?php

namespace App\Services;

use App\Models\EmailSetting;
use Illuminate\Support\Facades\Config;

class EmailConfigurationService
{
    /**
     * Configure email settings from database
     */
    public static function configureEmailSettings()
    {
        $emailSettings = EmailSetting::getEmailSettings();

        // Configure mail settings
        Config::set('mail.default', 'smtp');
        Config::set('mail.mailers.smtp.transport', 'smtp');
        Config::set('mail.mailers.smtp.host', $emailSettings->smtp_host);
        Config::set('mail.mailers.smtp.port', $emailSettings->smtp_port);
        Config::set('mail.mailers.smtp.username', $emailSettings->email_username);
        Config::set('mail.mailers.smtp.password', $emailSettings->email_password);
        Config::set('mail.mailers.smtp.encryption', $emailSettings->encryption ? 'tls' : null);
        Config::set('mail.mailers.smtp.timeout', null);
        Config::set('mail.mailers.smtp.local_domain', env('MAIL_EHLO_DOMAIN', parse_url((string) env('APP_URL', 'http://localhost'), PHP_URL_HOST)));

        // Configure from address
        Config::set('mail.from.address', $emailSettings->from_email);
        Config::set('mail.from.name', $emailSettings->from_name);

        return $emailSettings;
    }

    /**
     * Get email settings for use in mail classes
     */
    public static function getEmailSettings()
    {
        return EmailSetting::getEmailSettings();
    }

    /**
     * Check if email is properly configured
     */
    public static function isEmailConfigured()
    {
        $settings = self::getEmailSettings();
        
        return !empty($settings->smtp_host) && 
               !empty($settings->smtp_port) && 
               !empty($settings->email_username) && 
               !empty($settings->email_password) &&
               !empty($settings->from_email);
    }
} 