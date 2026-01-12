<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CourtCase;
use App\Models\CaseTimeline;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PublicCaseStatusController extends Controller
{
    /**
     * Get case status by case reference and IC/Passport verification
     * 
     * @param string $caseReference
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCaseStatus($caseReference, Request $request)
    {
        // Log public API access for security audit
        Log::info('Public API Access - Case Status', [
            'case_reference' => $caseReference,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'timestamp' => now(),
        ]);

        try {
            // Validate IC/Passport
            $request->validate([
                'ic_passport' => 'required|string',
            ]);

            $icPassport = $request->input('ic_passport');

            // Find case by case_number (without firm scope for public access)
            $case = CourtCase::withoutFirmScope()
                ->where('case_number', $caseReference)
                ->with([
                    'caseType' => function ($query) {
                        $query->withoutGlobalScope(\App\Scopes\FirmScope::class);
                    },
                    'caseStatus' => function ($query) {
                        $query->withoutGlobalScope(\App\Scopes\FirmScope::class);
                    },
                    'parties' => function ($query) {
                        $query->withoutGlobalScope(\App\Scopes\FirmScope::class)
                              ->select('id', 'case_id', 'name', 'ic_passport', 'party_type', 'phone', 'email', 'firm_id');
                    },
                ])
                ->first();

            if (!$case) {
                return response()->json([
                    'success' => false,
                    'message' => 'Case not found. Please check your case reference number.',
                ], 404);
            }

            // Verify IC/Passport matches one of the parties
            $matchingParty = $case->parties->first(function ($party) use ($icPassport) {
                return $party->ic_passport === $icPassport;
            });

            if (!$matchingParty) {
                // Log failed verification attempt
                Log::warning('Public API - IC/Passport Verification Failed', [
                    'case_reference' => $caseReference,
                    'ip_address' => $request->ip(),
                    'timestamp' => now(),
                ]);

                return response()->json([
                    'success' => false,
                    'message' => 'IC/Passport verification failed. Please ensure you are a party in this case.',
                ], 403);
            }

            // Log successful access
            Log::info('Public API - Case Status Retrieved Successfully', [
                'case_reference' => $caseReference,
                'party_type' => $matchingParty->party_type,
                'ip_address' => $request->ip(),
            ]);

            // Return limited case information (only what's needed for public view)
            return response()->json([
                'success' => true,
                'message' => 'Case information retrieved successfully',
                'data' => [
                    'case_number' => $case->case_number,
                    'title' => $case->title,
                    'description' => $case->description,
                    'case_type' => $case->caseType ? [
                        'id' => $case->caseType->id,
                        'name' => $case->caseType->name,
                    ] : null,
                    'case_status' => $case->caseStatus ? [
                        'id' => $case->caseStatus->id,
                        'name' => $case->caseStatus->name,
                        'status' => $case->caseStatus->status,
                    ] : null,
                    'person_in_charge' => $case->person_in_charge,
                    'court_ref' => $case->court_ref,
                    'jurisdiction' => $case->jurisdiction,
                    'priority_level' => $case->priority_level,
                    'judge_name' => $case->judge_name,
                    'court_location' => $case->court_location,
                    'claim_amount' => $case->claim_amount,
                    'created_at' => $case->created_at,
                    'parties' => $case->parties->map(function ($party) {
                        return [
                            'name' => $party->name,
                            'party_type' => $party->party_type,
                            'ic_passport' => $party->ic_passport,
                            'phone' => $party->phone,
                            'email' => $party->email,
                        ];
                    }),
                ],
            ], 200);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            Log::error('Public Case Status Error: ' . $e->getMessage(), [
                'case_reference' => $caseReference,
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while retrieving case information',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error',
            ], 500);
        }
    }

    /**
     * Get case timeline by case reference and IC/Passport verification
     *
     * @param string $caseReference
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCaseTimeline($caseReference, Request $request)
    {
        // Log public API access for security audit
        Log::info('Public API Access - Case Timeline', [
            'case_reference' => $caseReference,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'timestamp' => now(),
        ]);

        try {
            // Validate IC/Passport
            $request->validate([
                'ic_passport' => 'required|string',
            ]);

            $icPassport = $request->input('ic_passport');

            // Find case by case_number (without firm scope for public access)
            $case = CourtCase::withoutFirmScope()
                ->where('case_number', $caseReference)
                ->with([
                    'parties' => function ($query) {
                        $query->withoutGlobalScope(\App\Scopes\FirmScope::class)
                              ->select('id', 'case_id', 'ic_passport', 'firm_id');
                    },
                ])
                ->first();

            if (!$case) {
                return response()->json([
                    'success' => false,
                    'message' => 'Case not found. Please check your case reference number.',
                ], 404);
            }

            // Verify IC/Passport matches one of the parties
            $matchingParty = $case->parties->first(function ($party) use ($icPassport) {
                return $party->ic_passport === $icPassport;
            });

            if (!$matchingParty) {
                // Log failed verification attempt
                Log::warning('Public API - IC/Passport Verification Failed (Timeline)', [
                    'case_reference' => $caseReference,
                    'ip_address' => $request->ip(),
                    'timestamp' => now(),
                ]);

                return response()->json([
                    'success' => false,
                    'message' => 'IC/Passport verification failed. Please ensure you are a party in this case.',
                ], 403);
            }

            // Log successful access
            Log::info('Public API - Case Timeline Retrieved Successfully', [
                'case_reference' => $caseReference,
                'party_type' => $matchingParty->party_type,
                'ip_address' => $request->ip(),
            ]);

            // Get timeline events (without firm scope)
            // Show all timeline events regardless of status for public viewing
            $timeline = CaseTimeline::withoutGlobalScope(\App\Scopes\FirmScope::class)
                ->where('case_id', $case->id)
                ->with(['eventStatus' => function ($query) {
                    $query->withoutGlobalScope(\App\Scopes\FirmScope::class);
                }])
                ->orderBy('event_date', 'desc')
                ->orderBy('created_at', 'desc')
                ->get()
                ->map(function ($event) {
                    return [
                        'id' => $event->id,
                        'event_type' => $event->event_type,
                        'title' => $event->title,
                        'description' => $event->description,
                        'event_date' => $event->event_date,
                        'status' => $event->status,
                        'status_color' => $event->getStatusColor(),
                        'status_icon' => $event->getStatusIcon(),
                        'status_label' => $event->eventStatus ? $event->eventStatus->name : $event->status,
                        'custom_metadata' => $event->custom_metadata,
                        'created_at' => $event->created_at,
                    ];
                });

            return response()->json([
                'success' => true,
                'message' => 'Timeline retrieved successfully',
                'data' => $timeline,
            ], 200);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            Log::error('Public Case Timeline Error: ' . $e->getMessage(), [
                'case_reference' => $caseReference,
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while retrieving timeline information',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error',
            ], 500);
        }
    }
}

