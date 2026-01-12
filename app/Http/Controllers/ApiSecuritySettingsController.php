<?php

namespace App\Http\Controllers;

use App\Models\ApiSecuritySetting;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ApiSecuritySettingsController extends Controller
{
    public function get(): JsonResponse
    {
        $settings = ApiSecuritySetting::getApiSecuritySettings();
        
        // Decode allowed_origins for frontend
        $settingsArray = $settings->toArray();
        $settingsArray['allowed_origins_array'] = $settings->getAllowedOriginsArray();
        
        return response()->json($settingsArray);
    }

    public function store(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'rate_limit_per_minute' => 'required|integer|min:1|max:1000',
                'rate_limit_per_hour' => 'required|integer|min:1|max:10000',
                'allowed_origins_array' => 'nullable|array',
                'allowed_origins_array.*' => 'nullable|string|url',
                'cors_enabled' => 'nullable|boolean',
                'ip_blacklist_enabled' => 'nullable|boolean',
                'auto_blacklist_threshold' => 'required|integer|min:1|max:100',
                'blacklist_duration_hours' => 'required|integer|min:1|max:168',
                'log_all_requests' => 'nullable|boolean',
                'log_failed_attempts' => 'nullable|boolean',
                'api_enabled' => 'nullable|boolean',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        }

        try {
            $settings = ApiSecuritySetting::getApiSecuritySettings();
            $oldSettings = $settings->toArray();
            
            // Prepare data
            $data = $request->except('allowed_origins_array');
            
            // Handle allowed_origins array
            if ($request->has('allowed_origins_array')) {
                $origins = array_filter($request->input('allowed_origins_array', []));
                $data['allowed_origins'] = json_encode(array_values($origins));
            }
            
            $settings->update($data);

            // Log settings change
            activity()
                ->performedOn($settings)
                ->causedBy(auth()->user())
                ->withProperties([
                    'ip' => request()->ip(),
                    'old_settings' => $oldSettings,
                    'new_settings' => $data
                ])
                ->log("API Security settings updated");

            // Clear CORS cache to apply new settings immediately
            \Illuminate\Support\Facades\Cache::forget('api_security_allowed_origins');

            return response()->json([
                'success' => true,
                'message' => 'API Security settings saved successfully!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error saving API Security settings: ' . $e->getMessage()
            ], 500);
        }
    }

}

