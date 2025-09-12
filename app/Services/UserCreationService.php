<?php

namespace App\Services;

use App\Models\User;
use App\Mail\UserAccountCreated;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class UserCreationService
{
    /**
     * Create a user account for a client
     */
    public static function createUserForClient($clientData)
    {
        // Generate username from client name using @client format
        $username = self::generateClientUsername($clientData['name']);
        
        // Generate password
        $password = self::generatePassword();
        
        // Generate professional email format for username
        $generatedEmail = self::generateClientEmail($clientData['name']);
        
        // Use actual client email if provided, otherwise use generated email
        $email = !empty($clientData['email']) ? $clientData['email'] : $generatedEmail;
        
        // Get firm context for user creation
        $currentUser = auth()->user();
        $firmId = null;
        if ($currentUser) {
            // For Super Admin, use session firm_id if available
            if ($currentUser->hasRole('Super Administrator') && session('current_firm_id')) {
                $firmId = session('current_firm_id');
            } else {
                // For regular users, use their assigned firm
                $firmId = $currentUser->firm_id;
            }
        } else {
            // Default to firm 1 if no user context
            $firmId = 1;
        }

        // Create user
        $user = User::create([
            'name' => $clientData['name'],
            'username' => $username,
            'email' => $email,
            'phone' => $clientData['phone'] ?? null,
            'password' => Hash::make($password),
            'department' => 'Client',
            'notes' => 'Auto-generated user account for client: ' . $clientData['name'],
            'firm_id' => $firmId,
        ]);

        // Assign client role
        $user->assignRole('client');

        // Send email verification notification
        if (EmailConfigurationService::isEmailConfigured()) {
            try {
                EmailConfigurationService::configureEmailSettings();
                $user->sendEmailVerificationNotification();
            } catch (\Exception $e) {
                \Log::error('Failed to send email verification: ' . $e->getMessage());
            }
        }

        // Send email notification if email is provided and email is configured
        if (!empty($clientData['email']) && EmailConfigurationService::isEmailConfigured()) {
            // Check if user account notifications are enabled
            $emailSettings = EmailConfigurationService::getEmailSettings();
            if ($emailSettings->notify_user_accounts) {
                try {
                    // Configure email settings from database
                    EmailConfigurationService::configureEmailSettings();

                    // Get firm name for email
                    $firmId = session('current_firm_id') ?? auth()->user()->firm_id;
                    $firm = \App\Models\Firm::find($firmId);
                    $firmName = $firm ? $firm->name : 'Naeelah Firm';

                    Mail::to($clientData['email'])->send(new UserAccountCreated([
                        'name' => $clientData['name'],
                        'username' => $username,
                        'email' => $email,
                        'password' => $password,
                    ], 'client', $firmName));
                } catch (\Exception $e) {
                    // Log error but don't fail the creation
                    \Log::error('Failed to send email notification: ' . $e->getMessage());
                }
            }
        }

        return [
            'user' => $user,
            'username' => $username,
            'password' => $password,
        ];
    }

    /**
     * Create a user account for a partner
     */
    public static function createUserForPartner($partnerData)
    {
        // Generate username from firm name using @partner format
        $username = self::generatePartnerUsername($partnerData['firm_name']);
        
        // Generate password
        $password = self::generatePassword();
        
        // Generate professional email format for username
        $generatedEmail = self::generatePartnerEmail($partnerData['firm_name']);
        
        // Use actual partner email if provided, otherwise use generated email
        $email = !empty($partnerData['incharge_email']) ? $partnerData['incharge_email'] : $generatedEmail;
        
        // Get firm context for user creation
        $currentUser = auth()->user();
        $firmId = null;
        if ($currentUser) {
            // For Super Admin, use session firm_id if available
            if ($currentUser->hasRole('Super Administrator') && session('current_firm_id')) {
                $firmId = session('current_firm_id');
            } else {
                // For regular users, use their assigned firm
                $firmId = $currentUser->firm_id;
            }
        } else {
            // Default to firm 1 if no user context
            $firmId = 1;
        }

        // Create user
        $user = User::create([
            'name' => $partnerData['incharge_name'],
            'username' => $username,
            'email' => $email,
            'phone' => $partnerData['incharge_contact'] ?? null,
            'password' => Hash::make($password),
            'department' => 'Partner',
            'notes' => 'Auto-generated user account for partner firm: ' . $partnerData['firm_name'],
            'firm_id' => $firmId,
        ]);

        // Assign partner role
        $user->assignRole('partner');

        // Send email verification notification
        if (EmailConfigurationService::isEmailConfigured()) {
            try {
                EmailConfigurationService::configureEmailSettings();
                $user->sendEmailVerificationNotification();
            } catch (\Exception $e) {
                \Log::error('Failed to send email verification: ' . $e->getMessage());
            }
        }

        // Send email notification if email is provided and email is configured
        if (!empty($partnerData['incharge_email']) && EmailConfigurationService::isEmailConfigured()) {
            // Check if user account notifications are enabled
            $emailSettings = EmailConfigurationService::getEmailSettings();
            if ($emailSettings->notify_user_accounts) {
                try {
                    // Configure email settings from database
                    EmailConfigurationService::configureEmailSettings();

                    // Get firm name for email
                    $firmId = session('current_firm_id') ?? auth()->user()->firm_id;
                    $firm = \App\Models\Firm::find($firmId);
                    $firmName = $firm ? $firm->name : 'Naeelah Firm';

                    Mail::to($partnerData['incharge_email'])->send(new UserAccountCreated([
                        'name' => $partnerData['incharge_name'],
                        'username' => $username,
                        'email' => $email,
                        'password' => $password,
                    ], 'partner', $firmName));
                } catch (\Exception $e) {
                    // Log error but don't fail the creation
                    \Log::error('Failed to send email notification: ' . $e->getMessage());
                }
            }
        }

        return [
            'user' => $user,
            'username' => $username,
            'password' => $password,
        ];
    }

    /**
     * Generate unique username for partners using @partner format
     */
    private static function generatePartnerUsername($firmName)
    {
        $baseUsername = Str::slug($firmName, '') . '@partner';
        $username = $baseUsername;
        $counter = 1;

        while (User::where('username', $username)->exists()) {
            $username = Str::slug($firmName, '') . $counter . '@partner';
            $counter++;
        }

        return $username;
    }

    /**
     * Generate unique username for clients using @client format
     */
    private static function generateClientUsername($clientName)
    {
        $baseUsername = Str::slug($clientName, '') . '@client';
        $username = $baseUsername;
        $counter = 1;

        while (User::where('username', $username)->exists()) {
            $username = Str::slug($clientName, '') . $counter . '@client';
            $counter++;
        }

        return $username;
    }

    /**
     * Generate unique username (legacy method - kept for backward compatibility)
     */
    private static function generateUsername($name, $type)
    {
        $baseUsername = Str::slug($name, '') . '_' . $type;
        $username = $baseUsername;
        $counter = 1;

        while (User::where('username', $username)->exists()) {
            $username = $baseUsername . '_' . $counter;
            $counter++;
        }

        return $username;
    }

    /**
     * Generate random password
     */
    private static function generatePassword()
    {
        return Str::random(8);
    }

    /**
     * Generate a professional email format for partners.
     * This method takes the firm name and attempts to create a more readable email.
     * If the firm name is too short, it appends a unique ID.
     */
    private static function generatePartnerEmail($firmName)
    {
        $firmName = Str::slug($firmName, '');
        $email = $firmName;

        // If the firm name is very short (less than 5 characters), append a unique ID
        if (strlen($firmName) < 5) {
            $email .= '_' . Str::random(4);
        }

        // Ensure uniqueness
        $counter = 1;
        while (User::where('username', $email . '@partner')->exists()) {
            $email = $firmName . '_' . $counter;
            $counter++;
        }

        return $email . '@partner';
    }

    /**
     * Generate a professional email format for clients.
     * This method takes the client name and attempts to create a more readable email.
     * If the client name is too short, it appends a unique ID.
     */
    private static function generateClientEmail($clientName)
    {
        $clientName = Str::slug($clientName, '');
        $email = $clientName;

        // If the client name is very short (less than 5 characters), append a unique ID
        if (strlen($clientName) < 5) {
            $email .= '_' . Str::random(4);
        }

        // Ensure uniqueness
        $counter = 1;
        while (User::where('username', $email . '@client')->exists()) {
            $email = $clientName . '_' . $counter;
            $counter++;
        }

        return $email . '@client';
    }
} 