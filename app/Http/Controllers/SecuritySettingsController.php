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
        try {
            $request->validate([
                'two_factor_auth' => 'nullable|boolean',
                'password_expiry' => 'nullable|boolean',
                'password_expiry_days' => 'nullable|integer|min:1|max:365',
                'force_password_change' => 'nullable|boolean',
                'max_login_attempts' => 'nullable|integer|min:1|max:10',
                'lockout_duration' => 'nullable|integer|min:1|max:1440',
                'session_timeout_enabled' => 'nullable|boolean',
                'session_timeout_minutes' => 'nullable|integer|min:1|max:1440',
                'ip_whitelist_enabled' => 'nullable|boolean',
                'ip_whitelist' => 'nullable|string',
                'audit_log_enabled' => 'nullable|boolean'
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        }

        try {
            $settings = SecuritySetting::getSecuritySettings();
            $oldSettings = $settings->toArray();
            $settings->update($request->all());

            // Log settings change
            activity()
                ->performedOn($settings)
                ->causedBy(auth()->user())
                ->withProperties([
                    'ip' => request()->ip(),
                    'old_settings' => $oldSettings,
                    'new_settings' => $request->all()
                ])
                ->log("Security settings updated");

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
