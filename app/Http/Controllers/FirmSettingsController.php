<?php

namespace App\Http\Controllers;

use App\Models\FirmSetting;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class FirmSettingsController extends Controller
{
    public function get(): JsonResponse
    {
        $settings = FirmSetting::getFirmSettings();
        return response()->json($settings);
    }

    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'firm_name' => 'required|string|max:255',
            'registration_number' => 'nullable|string|max:100',
            'phone_number' => 'required|string|max:50',
            'fax_number' => 'nullable|string|max:50',
            'email' => 'required|email|max:255',
            'address' => 'required|string',
            'website' => 'nullable|url|max:255',
            'tax_registration_number' => 'nullable|string|max:100'
        ]);

        try {
            $settings = FirmSetting::getFirmSettings();
            $settings->update($request->all());

            return response()->json([
                'success' => true,
                'message' => 'Firm information saved successfully!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error saving firm information: ' . $e->getMessage()
            ], 500);
        }
    }
}
