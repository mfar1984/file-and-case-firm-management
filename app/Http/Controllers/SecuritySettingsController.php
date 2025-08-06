<?php

namespace App\Http\Controllers;

use App\Models\SecuritySetting;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class SecuritySettingsController extends Controller
{
    public function get(): JsonResponse
    {
        $settings = SecuritySetting::getSecuritySettings();
        return response()->json($settings);
    }

    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'two_factor_auth' => 'boolean',
            'password_expiry' => 'boolean',
            'password_expiry_days' => 'required|integer|min:1|max:365',
            'force_password_change' => 'boolean',
            'max_login_attempts' => 'required|integer|min:1|max:10',
            'lockout_duration' => 'required|integer|min:1|max:1440',
            'session_timeout_enabled' => 'boolean',
            'session_timeout_minutes' => 'required|integer|min:1|max:1440',
            'ip_whitelist_enabled' => 'boolean',
            'ip_whitelist' => 'nullable|string',
            'audit_log_enabled' => 'boolean'
        ]);

        try {
            $settings = SecuritySetting::getSecuritySettings();
            $settings->update($request->all());

            return response()->json([
                'success' => true,
                'message' => 'Security settings saved successfully!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error saving security settings: ' . $e->getMessage()
            ], 500);
        }
    }
}
