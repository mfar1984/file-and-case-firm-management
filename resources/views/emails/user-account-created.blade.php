<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Account Created - Naeelah Firm</title>
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
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            text-align: center;
            border-radius: 10px 10px 0 0;
        }
        .content {
            background: #f8f9fa;
            padding: 30px;
            border-radius: 0 0 10px 10px;
        }
        .credentials {
            background: white;
            border: 2px solid #e9ecef;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
        }
        .credential-item {
            margin: 10px 0;
            padding: 10px;
            background: #f8f9fa;
            border-radius: 5px;
        }
        .label {
            font-weight: bold;
            color: #495057;
        }
        .value {
            color: #6c757d;
            font-family: monospace;
            background: #e9ecef;
            padding: 5px 10px;
            border-radius: 3px;
            display: inline-block;
            margin-top: 5px;
        }
        .login-btn {
            display: inline-block;
            background: #007bff;
            color: white;
            padding: 12px 30px;
            text-decoration: none;
            border-radius: 5px;
            margin: 20px 0;
        }
        .warning {
            background: #fff3cd;
            border: 1px solid #ffeaa7;
            color: #856404;
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #dee2e6;
            color: #6c757d;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Welcome to Naeelah Firm</h1>
        <p>Your {{ ucfirst($accountType) }} Account Has Been Created</p>
    </div>
    
    <div class="content">
        <p>Dear <strong>{{ $userData['name'] }}</strong>,</p>
        
        <p>Your {{ $accountType }} account has been successfully created in our system. Below are your login credentials:</p>
        
        <div class="credentials">
            <h3>Login Credentials</h3>
            
            <div class="credential-item">
                <div class="label">Username:</div>
                <div class="value">{{ $userData['username'] }}</div>
            </div>
            
            <div class="credential-item">
                <div class="label">Email Address:</div>
                <div class="value">{{ $userData['email'] }}</div>
            </div>
            
            <div class="credential-item">
                <div class="label">Password:</div>
                <div class="value">{{ $userData['password'] }}</div>
            </div>
            
            <div class="credential-item">
                <div class="label">Account Type:</div>
                <div class="value">{{ ucfirst($accountType) }}</div>
            </div>
        </div>
        
        <div class="warning">
            <strong>Important:</strong> Please change your password after your first login for security purposes.
        </div>
        
        <p>You can now access your account using the button below:</p>
        
        <a href="{{ $loginUrl }}" class="login-btn">Login to Your Account</a>
        
        <p>If you have any questions or need assistance, please contact our support team.</p>
        
        <p>Best regards,<br>
        <strong>Naeelah Firm Team</strong></p>
    </div>
    
    <div class="footer">
        <p>This is an automated message. Please do not reply to this email.</p>
        <p>&copy; {{ date('Y') }} Naeelah Firm. All rights reserved.</p>
    </div>
</body>
</html> 