<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CaseReferenceApiController;
use App\Http\Controllers\Api\PublicCaseStatusController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

/*
|--------------------------------------------------------------------------
| Public API Routes (No Authentication Required)
|--------------------------------------------------------------------------
|
| Public endpoints for case status checking from website
| Requires IC/Passport verification for security
| Rate limited to prevent abuse
|
*/

Route::prefix('public')->middleware(['throttle:60,1', \App\Http\Middleware\DynamicCorsMiddleware::class])->group(function () {
    // Public case status check (requires IC/Passport verification)
    // Rate limit: 60 requests per minute per IP
    Route::post('case/{case_reference}/status', [PublicCaseStatusController::class, 'getCaseStatus'])
        ->name('api.public.case.status');

    // Public case timeline (requires IC/Passport verification)
    // Rate limit: 60 requests per minute per IP
    Route::post('case/{case_reference}/timeline', [PublicCaseStatusController::class, 'getCaseTimeline'])
        ->name('api.public.case.timeline');
});

/*
|--------------------------------------------------------------------------
| Case Reference API Routes (Authentication Required)
|--------------------------------------------------------------------------
|
| API endpoints for retrieving case information by case reference (case_number)
| All endpoints require authentication
|
| Format: /api/case/{case_reference}/{endpoint}
| Example: /api/case/2025-08-1APP7I/timeline
|
*/

Route::middleware(['auth'])->prefix('case')->group(function () {
    
    // Get case information by case reference
    Route::get('{case_reference}/info', [CaseReferenceApiController::class, 'getCaseInfo'])
        ->name('api.case.info');
    
    // Get case timeline by case reference
    Route::get('{case_reference}/timeline', [CaseReferenceApiController::class, 'getTimeline'])
        ->name('api.case.timeline');
    
    // Get case documents by case reference
    Route::get('{case_reference}/documents', [CaseReferenceApiController::class, 'getDocuments'])
        ->name('api.case.documents');
    
    // Get case financial information by case reference
    Route::get('{case_reference}/financial', [CaseReferenceApiController::class, 'getFinancialInfo'])
        ->name('api.case.financial');
});

