<?php

namespace App\Http\Controllers;

use App\Models\FirmSetting;
use App\Models\Firm;
use App\Services\FirmSettingsService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class FirmSettingsController extends Controller
{
    public function get(): JsonResponse
    {
        // Get current firm context
        $user = auth()->user();
        $firmId = session('current_firm_id') ?? $user->firm_id;

        if ($firmId) {
            $firm = Firm::find($firmId);
            if ($firm) {
                return response()->json([
                    'firm_name' => $firm->name,
                    'registration_number' => $firm->registration_number,
                    'phone_number' => $firm->phone,
                    'fax_number' => $firm->settings['fax_number'] ?? '',
                    'email' => $firm->email,
                    'address' => $firm->address,
                    'website' => $firm->settings['website'] ?? '',
                    'tax_registration_number' => $firm->settings['tax_registration_number'] ?? ''
                ]);
            }
        }

        // Fallback to old FirmSetting for backward compatibility
        $settings = FirmSetting::getFirmSettings();
        return response()->json($settings);
    }

    public function store(Request $request): JsonResponse
    {
        try {
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
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        }

        try {
            // Get current firm context
            $user = auth()->user();
            $firmId = session('current_firm_id') ?? $user->firm_id;

            if ($firmId) {
                $firm = Firm::find($firmId);
                if ($firm) {
                    $oldSettings = $firm->toArray();

                    // Update firm basic fields
                    $firm->update([
                        'name' => $request->firm_name,
                        'registration_number' => $request->registration_number,
                        'phone' => $request->phone_number,
                        'email' => $request->email,
                        'address' => $request->address,
                    ]);

                    // Update firm settings JSON field
                    $settings = $firm->settings ?? [];
                    $settings['fax_number'] = $request->fax_number;
                    $settings['website'] = $request->website;
                    $settings['tax_registration_number'] = $request->tax_registration_number;
                    $firm->update(['settings' => $settings]);

                    // Log settings change
                    activity()
                        ->performedOn($firm)
                        ->causedBy(auth()->user())
                        ->withProperties([
                            'ip' => request()->ip(),
                            'old_settings' => $oldSettings,
                            'new_settings' => $request->all()
                        ])
                        ->log("Firm settings updated for: " . $firm->name);

                    return response()->json([
                        'success' => true,
                        'message' => 'Firm information saved successfully!'
                    ]);
                }
            }

            // Fallback to old FirmSetting for backward compatibility
            $settings = FirmSetting::getFirmSettings();
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
                ->log("Firm settings updated (legacy)");

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
