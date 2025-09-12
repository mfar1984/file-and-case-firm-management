<?php

namespace App\Http\Controllers;

use App\Models\EmailSetting;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class EmailSettingsController extends Controller
{
    public function get(): JsonResponse
    {
        $settings = EmailSetting::getEmailSettings();
        return response()->json($settings);
    }

    public function store(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'smtp_host' => 'nullable|string|max:255',
                'smtp_port' => 'nullable|integer|min:1|max:65535',
                'email_username' => 'nullable|email|max:255',
                'email_password' => 'nullable|string|max:255',
                'from_name' => 'nullable|string|max:255',
                'from_email' => 'nullable|email|max:255',
                'encryption' => 'nullable|boolean',
                'notify_new_cases' => 'nullable|boolean',
                'notify_document_uploads' => 'nullable|boolean',
                'notify_case_status' => 'nullable|boolean',
                'notify_maintenance' => 'nullable|boolean'
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        }

        try {
            $settings = EmailSetting::getEmailSettings();
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
                ->log("Email settings updated");

            return response()->json([
                'success' => true,
                'message' => 'Email settings saved successfully!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error saving email settings: ' . $e->getMessage()
            ], 500);
        }
    }
}
