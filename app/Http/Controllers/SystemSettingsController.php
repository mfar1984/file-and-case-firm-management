<?php

namespace App\Http\Controllers;

use App\Models\SystemSetting;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class SystemSettingsController extends Controller
{
    public function get(): JsonResponse
    {
        $settings = SystemSetting::getSystemSettings();
        return response()->json($settings);
    }

    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'date_format' => 'required|string|max:20',
            'time_format' => 'required|string|max:20',
            'time_zone' => 'required|string|max:50',
            'maintenance_mode' => 'boolean',
            'maintenance_message' => 'nullable|string',
            'session_timeout' => 'required|integer|min:1|max:1440',
            'debug_mode' => 'boolean'
        ]);

        try {
            $settings = SystemSetting::getSystemSettings();
            $settings->update($request->all());

            return response()->json([
                'success' => true,
                'message' => 'System settings saved successfully!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error saving system settings: ' . $e->getMessage()
            ], 500);
        }
    }
}
