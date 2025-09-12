<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Password Reset - {{ $firmName ?? 'Naeelah Firm' }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background-color: #1f2937;
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 8px 8px 0 0;
        }
        .content {
            background-color: #f9fafb;
            padding: 30px;
            border-radius: 0 0 8px 8px;
            border: 1px solid #e5e7eb;
        }
        .password-box {
            background-color: #dbeafe;
            border: 2px solid #3b82f6;
            border-radius: 6px;
            padding: 15px;
            margin: 20px 0;
            text-align: center;
        }
        .password-text {
            font-size: 18px;
            font-weight: bold;
            color: #1e40af;
            font-family: monospace;
            letter-spacing: 2px;
        }
        .warning {
            background-color: #fef3c7;
            border: 1px solid #f59e0b;
            border-radius: 6px;
            padding: 15px;
            margin: 20px 0;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            color: #6b7280;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Password Reset</h1>
        <p>{{ $firmName ?? 'Naeelah Firm' }}</p>
    </div>
    
    <div class="content">
        <p>Dear <strong>{{ $user->name }}</strong>,</p>
        
        <p>Your password has been reset by an administrator. Here are your new login credentials:</p>
        
        <div class="password-box">
            <p><strong>Username:</strong> {{ $user->username }}</p>
            <p><strong>New Password:</strong></p>
            <div class="password-text">{{ $newPassword }}</div>
        </div>
        
        <p>Please use these credentials to log in to your account. For security reasons, we recommend changing your password after your first login.</p>
        
        <div class="warning">
            <p><strong>Important:</strong> Keep your password secure and do not share it with anyone.</p>
        </div>
        
        <p>If you did not request this password reset, please contact the administrator immediately.</p>
        
        <p>Best regards,<br>
        <strong>{{ $firmName ?? 'Naeelah Firm' }} Team</strong></p>
    </div>
    
    <div class="footer">
        <p>This is an automated message. Please do not reply to this email.</p>
        <p>&copy; {{ date('Y') }} {{ $firmName ?? 'Naeelah Firm' }}. All rights reserved.</p>
    </div>
</body>
</html> 