<?php

/**
 * Test Case Reference API Endpoints
 * Case Reference: 2025-08-1APP7I
 */

// Bootstrap Laravel
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use App\Models\CourtCase;
use App\Http\Controllers\Api\CaseReferenceApiController;
use Illuminate\Http\Request;

// Test configuration
$caseReference = '2025-08-1APP7I';
$testResults = [];

echo "\n";
echo "==========================================\n";
echo "TESTING CASE REFERENCE API\n";
echo "==========================================\n";
echo "Case Reference: $caseReference\n";
echo "Date: " . date('Y-m-d H:i:s') . "\n";
echo "==========================================\n\n";

// Setup authentication
try {
    $user = User::where('email', 'admin@naaelahsaleh.my')->first();
    if (!$user) {
        echo "❌ ERROR: User not found!\n";
        exit(1);
    }
    
    auth()->login($user);
    session(['current_firm_id' => 1]);
    
    echo "✅ Authentication Setup\n";
    echo "   User: {$user->name} ({$user->email})\n";
    echo "   Firm ID: " . session('current_firm_id') . "\n\n";
} catch (Exception $e) {
    echo "❌ Authentication Error: " . $e->getMessage() . "\n";
    exit(1);
}

// Verify case exists
try {
    $case = CourtCase::where('case_number', $caseReference)->first();
    if (!$case) {
        echo "❌ ERROR: Case not found!\n";
        exit(1);
    }
    
    echo "✅ Case Verification\n";
    echo "   Case ID: {$case->id}\n";
    echo "   Case Number: {$case->case_number}\n";
    echo "   Title: {$case->title}\n";
    echo "   Firm ID: {$case->firm_id}\n\n";
} catch (Exception $e) {
    echo "❌ Case Verification Error: " . $e->getMessage() . "\n";
    exit(1);
}

// Initialize controller
$controller = new CaseReferenceApiController();

echo "==========================================\n";
echo "TEST 1: GET CASE INFORMATION\n";
echo "==========================================\n";
echo "Endpoint: GET /api/case/$caseReference/info\n\n";

try {
    $response = $controller->getCaseInfo($caseReference);
    $data = json_decode($response->getContent(), true);
    
    echo "Status Code: " . $response->getStatusCode() . "\n";
    echo "Success: " . ($data['success'] ? '✅ Yes' : '❌ No') . "\n";
    
    if ($data['success']) {
        echo "\n📋 Case Information:\n";
        echo "   Case Number: " . $data['data']['case_number'] . "\n";
        echo "   Title: " . $data['data']['title'] . "\n";
        echo "   Case Type: " . ($data['data']['case_type']['name'] ?? 'N/A') . "\n";
        echo "   Case Status: " . ($data['data']['case_status']['name'] ?? 'N/A') . "\n";
        echo "   Person in Charge: " . ($data['data']['person_in_charge'] ?? 'N/A') . "\n";
        echo "   Court Ref: " . ($data['data']['court_ref'] ?? 'N/A') . "\n";
        echo "   Jurisdiction: " . ($data['data']['jurisdiction'] ?? 'N/A') . "\n";
        echo "   Priority: " . ($data['data']['priority_level'] ?? 'N/A') . "\n";
        echo "   Judge: " . ($data['data']['judge_name'] ?? 'N/A') . "\n";
        echo "   Court Location: " . ($data['data']['court_location'] ?? 'N/A') . "\n";
        echo "   Claim Amount: RM " . ($data['data']['claim_amount'] ?? '0.00') . "\n";
        echo "   Parties: " . count($data['data']['parties']) . "\n";
        echo "   Partners: " . count($data['data']['partners']) . "\n";
        echo "   Created By: " . ($data['data']['created_by']['name'] ?? 'N/A') . "\n";
        echo "   Assigned To: " . ($data['data']['assigned_to']['name'] ?? 'N/A') . "\n";
        
        $testResults['case_info'] = '✅ PASS';
    } else {
        echo "❌ Message: " . $data['message'] . "\n";
        $testResults['case_info'] = '❌ FAIL';
    }
} catch (Exception $e) {
    echo "❌ ERROR: " . $e->getMessage() . "\n";
    $testResults['case_info'] = '❌ ERROR';
}

echo "\n";

echo "==========================================\n";
echo "TEST 2: GET TIMELINE (All Events)\n";
echo "==========================================\n";
echo "Endpoint: GET /api/case/$caseReference/timeline\n\n";

try {
    $request = new Request(['per_page' => 10]);
    $response = $controller->getTimeline($caseReference, $request);
    $data = json_decode($response->getContent(), true);
    
    echo "Status Code: " . $response->getStatusCode() . "\n";
    echo "Success: " . ($data['success'] ? '✅ Yes' : '❌ No') . "\n";
    
    if ($data['success']) {
        echo "\n📅 Timeline Information:\n";
        echo "   Case Reference: " . $data['case_reference'] . "\n";
        echo "   Case Title: " . $data['case_title'] . "\n";
        echo "   Total Events: " . $data['pagination']['total'] . "\n";
        echo "   Current Page: " . $data['pagination']['current_page'] . "\n";
        echo "   Per Page: " . $data['pagination']['per_page'] . "\n";
        
        if (count($data['data']) > 0) {
            echo "\n   📌 Timeline Events:\n";
            foreach ($data['data'] as $index => $event) {
                echo "   " . ($index + 1) . ". {$event['title']}\n";
                echo "      - Event Type: {$event['event_type']}\n";
                echo "      - Status: {$event['status']}\n";
                echo "      - Date: {$event['event_date']}\n";
                echo "      - Created By: " . ($event['created_by']['name'] ?? 'N/A') . "\n";
                
                if (!empty($event['metadata'])) {
                    echo "      - Metadata: " . json_encode($event['metadata']) . "\n";
                }
                
                if (!empty($event['calendar_event'])) {
                    echo "      - Calendar: Linked (ID: {$event['calendar_event']['id']})\n";
                }
                echo "\n";
            }
        }
        
        $testResults['timeline_all'] = '✅ PASS';
    } else {
        echo "❌ Message: " . $data['message'] . "\n";
        $testResults['timeline_all'] = '❌ FAIL';
    }
} catch (Exception $e) {
    echo "❌ ERROR: " . $e->getMessage() . "\n";
    $testResults['timeline_all'] = '❌ ERROR';
}

echo "\n";

echo "==========================================\n";
echo "TEST 3: GET TIMELINE (Filtered - Active)\n";
echo "==========================================\n";
echo "Endpoint: GET /api/case/$caseReference/timeline?status=active\n\n";

try {
    $request = new Request(['status' => 'active', 'per_page' => 5]);
    $response = $controller->getTimeline($caseReference, $request);
    $data = json_decode($response->getContent(), true);
    
    echo "Status Code: " . $response->getStatusCode() . "\n";
    echo "Success: " . ($data['success'] ? '✅ Yes' : '❌ No') . "\n";
    
    if ($data['success']) {
        echo "\n📅 Filtered Timeline (Active):\n";
        echo "   Total Active Events: " . $data['pagination']['total'] . "\n";
        echo "   Showing: " . count($data['data']) . " events\n";
        
        if (count($data['data']) > 0) {
            echo "\n   Active Events:\n";
            foreach ($data['data'] as $index => $event) {
                echo "   " . ($index + 1) . ". {$event['title']} - {$event['event_type']} ({$event['event_date']})\n";
            }
        }
        
        $testResults['timeline_filtered'] = '✅ PASS';
    } else {
        echo "❌ Message: " . $data['message'] . "\n";
        $testResults['timeline_filtered'] = '❌ FAIL';
    }
} catch (Exception $e) {
    echo "❌ ERROR: " . $e->getMessage() . "\n";
    $testResults['timeline_filtered'] = '❌ ERROR';
}

echo "\n";

echo "==========================================\n";
echo "TEST 4: GET DOCUMENTS\n";
echo "==========================================\n";
echo "Endpoint: GET /api/case/$caseReference/documents\n\n";

try {
    $request = new Request(['per_page' => 10]);
    $response = $controller->getDocuments($caseReference, $request);
    $data = json_decode($response->getContent(), true);
    
    echo "Status Code: " . $response->getStatusCode() . "\n";
    echo "Success: " . ($data['success'] ? '✅ Yes' : '❌ No') . "\n";
    
    if ($data['success']) {
        echo "\n📁 Documents Information:\n";
        echo "   Case Reference: " . $data['case_reference'] . "\n";
        echo "   Case Title: " . $data['case_title'] . "\n";
        echo "\n   📊 Summary:\n";
        echo "   - Total Documents: " . $data['summary']['total_documents'] . "\n";
        echo "   - Checked In: " . $data['summary']['checked_in'] . "\n";
        echo "   - Checked Out: " . $data['summary']['checked_out'] . "\n";
        echo "   - Overdue: " . $data['summary']['overdue'] . "\n";
        
        if (count($data['data']) > 0) {
            echo "\n   📄 Documents List:\n";
            foreach ($data['data'] as $index => $doc) {
                echo "   " . ($index + 1) . ". {$doc['file_name']}\n";
                echo "      - Size: {$doc['formatted_size']}\n";
                echo "      - Type: {$doc['mime_type']}\n";
                echo "      - Status: {$doc['status']}\n";
                echo "      - Category: " . ($doc['category']['name'] ?? 'N/A') . "\n";
                
                if ($doc['status'] === 'OUT' && !empty($doc['check_out_info'])) {
                    echo "      - Checked Out By: {$doc['check_out_info']['taken_by']}\n";
                    echo "      - Purpose: {$doc['check_out_info']['purpose']}\n";
                    echo "      - Expected Return: {$doc['check_out_info']['expected_return']}\n";
                    echo "      - Overdue: " . ($doc['check_out_info']['is_overdue'] ? 'Yes' : 'No') . "\n";
                }
                
                if ($doc['status'] === 'IN' && !empty($doc['check_in_info'])) {
                    echo "      - Rack Location: {$doc['check_in_info']['rack_location']}\n";
                }
                echo "\n";
            }
        }
        
        $testResults['documents'] = '✅ PASS';
    } else {
        echo "❌ Message: " . $data['message'] . "\n";
        $testResults['documents'] = '❌ FAIL';
    }
} catch (Exception $e) {
    echo "❌ ERROR: " . $e->getMessage() . "\n";
    $testResults['documents'] = '❌ ERROR';
}

echo "\n";

echo "==========================================\n";
echo "TEST 5: GET FINANCIAL INFORMATION\n";
echo "==========================================\n";
echo "Endpoint: GET /api/case/$caseReference/financial\n\n";

try {
    $request = new Request();
    $response = $controller->getFinancialInfo($caseReference, $request);
    $data = json_decode($response->getContent(), true);
    
    echo "Status Code: " . $response->getStatusCode() . "\n";
    echo "Success: " . ($data['success'] ? '✅ Yes' : '❌ No') . "\n";
    
    if ($data['success']) {
        echo "\n💰 Financial Information:\n";
        echo "   Case Reference: " . $data['case_reference'] . "\n";
        echo "   Case Title: " . $data['case_title'] . "\n";
        
        echo "\n   📊 Financial Summary:\n";
        echo "   - Total Quotations: RM " . $data['summary']['total_quotations'] . "\n";
        echo "   - Total Invoices: RM " . $data['summary']['total_invoices'] . "\n";
        echo "   - Total Paid: RM " . $data['summary']['total_paid'] . "\n";
        echo "   - Outstanding Balance: RM " . $data['summary']['outstanding_balance'] . "\n";
        echo "   - Quotations Count: " . $data['summary']['quotations_count'] . "\n";
        echo "   - Invoices Count: " . $data['summary']['invoices_count'] . "\n";
        echo "   - Receipts Count: " . $data['summary']['receipts_count'] . "\n";
        
        if (count($data['data']['quotations']) > 0) {
            echo "\n   📝 Quotations:\n";
            foreach ($data['data']['quotations'] as $index => $quot) {
                echo "   " . ($index + 1) . ". {$quot['quotation_no']}\n";
                echo "      - Date: {$quot['quotation_date']}\n";
                echo "      - Customer: {$quot['customer_name']}\n";
                echo "      - Total: RM {$quot['total']}\n";
                echo "      - Status: {$quot['status']}\n";
            }
        }
        
        if (count($data['data']['tax_invoices']) > 0) {
            echo "\n   🧾 Tax Invoices:\n";
            foreach ($data['data']['tax_invoices'] as $index => $inv) {
                echo "   " . ($index + 1) . ". {$inv['invoice_no']}\n";
                echo "      - Date: {$inv['invoice_date']}\n";
                echo "      - Due Date: {$inv['due_date']}\n";
                echo "      - Total: RM {$inv['total']}\n";
                echo "      - Status: {$inv['status']}\n";
            }
        }
        
        if (count($data['data']['receipts']) > 0) {
            echo "\n   🧾 Receipts:\n";
            foreach ($data['data']['receipts'] as $index => $rec) {
                echo "   " . ($index + 1) . ". {$rec['receipt_no']}\n";
                echo "      - Date: {$rec['receipt_date']}\n";
                echo "      - Payment Method: {$rec['payment_method']}\n";
                echo "      - Amount Paid: RM {$rec['amount_paid']}\n";
                echo "      - Status: {$rec['status']}\n";
            }
        }
        
        $testResults['financial'] = '✅ PASS';
    } else {
        echo "❌ Message: " . $data['message'] . "\n";
        $testResults['financial'] = '❌ FAIL';
    }
} catch (Exception $e) {
    echo "❌ ERROR: " . $e->getMessage() . "\n";
    $testResults['financial'] = '❌ ERROR';
}

echo "\n";

// Final Summary
echo "==========================================\n";
echo "TEST RESULTS SUMMARY\n";
echo "==========================================\n\n";

$totalTests = count($testResults);
$passedTests = count(array_filter($testResults, fn($r) => $r === '✅ PASS'));
$failedTests = count(array_filter($testResults, fn($r) => $r === '❌ FAIL'));
$errorTests = count(array_filter($testResults, fn($r) => $r === '❌ ERROR'));

foreach ($testResults as $test => $result) {
    echo str_pad($test, 30) . " : $result\n";
}

echo "\n";
echo "Total Tests: $totalTests\n";
echo "Passed: $passedTests ✅\n";
echo "Failed: $failedTests ❌\n";
echo "Errors: $errorTests ⚠️\n";
echo "\n";

if ($passedTests === $totalTests) {
    echo "🎉 ALL TESTS PASSED! 🎉\n";
} else {
    echo "⚠️  SOME TESTS FAILED OR HAD ERRORS\n";
}

echo "==========================================\n\n";

