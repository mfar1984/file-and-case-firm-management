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
        $request->validate([
            'smtp_host' => 'required|string|max:255',
            'smtp_port' => 'required|integer|min:1|max:65535',
            'email_username' => 'required|email|max:255',
            'email_password' => 'required|string|max:255',
            'from_name' => 'required|string|max:255',
            'from_email' => 'required|email|max:255',
            'encryption' => 'boolean',
            'notify_new_cases' => 'boolean',
            'notify_document_uploads' => 'boolean',
            'notify_case_status' => 'boolean',
            'notify_maintenance' => 'boolean'
        ]);

        try {
            $settings = EmailSetting::getEmailSettings();
            $settings->update($request->all());

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
