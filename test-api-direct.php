<?php

/**
 * Direct API Test Script
 * Test Case Reference API endpoints directly
 */

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

// Test case reference
$caseReference = '2025-08-1APP7I';

echo "==========================================\n";
echo "Testing Case Reference API\n";
echo "Case Reference: $caseReference\n";
echo "==========================================\n\n";

// Login as admin
$user = App\Models\User::where('email', 'admin@naaelahsaleh.my')->first();
if (!$user) {
    echo "❌ User not found!\n";
    exit(1);
}

auth()->login($user);
session(['current_firm_id' => 1]);

echo "✅ Logged in as: {$user->name} ({$user->email})\n";
echo "✅ Firm ID: " . session('current_firm_id') . "\n\n";

// Test 1: Get Case Info
echo "==========================================\n";
echo "Test 1: Get Case Info\n";
echo "==========================================\n";

$controller = new App\Http\Controllers\Api\CaseReferenceApiController();
$response = $controller->getCaseInfo($caseReference);
$data = json_decode($response->getContent(), true);

echo "Status Code: " . $response->getStatusCode() . "\n";
echo "Success: " . ($data['success'] ? 'Yes' : 'No') . "\n";
if ($data['success']) {
    echo "Case Number: " . $data['data']['case_number'] . "\n";
    echo "Title: " . $data['data']['title'] . "\n";
    echo "Case Type: " . ($data['data']['case_type']['name'] ?? 'N/A') . "\n";
    echo "Case Status: " . ($data['data']['case_status']['name'] ?? 'N/A') . "\n";
    echo "Person in Charge: " . $data['data']['person_in_charge'] . "\n";
    echo "Parties Count: " . count($data['data']['parties']) . "\n";
    echo "Partners Count: " . count($data['data']['partners']) . "\n";
} else {
    echo "Message: " . $data['message'] . "\n";
}
echo "\n";

// Test 2: Get Timeline
echo "==========================================\n";
echo "Test 2: Get Timeline\n";
echo "==========================================\n";

$request = new Illuminate\Http\Request(['per_page' => 5]);
$response = $controller->getTimeline($caseReference, $request);
$data = json_decode($response->getContent(), true);

echo "Status Code: " . $response->getStatusCode() . "\n";
echo "Success: " . ($data['success'] ? 'Yes' : 'No') . "\n";
if ($data['success']) {
    echo "Case Reference: " . $data['case_reference'] . "\n";
    echo "Case Title: " . $data['case_title'] . "\n";
    echo "Timeline Events: " . count($data['data']) . "\n";
    echo "Total Events: " . $data['pagination']['total'] . "\n";
    
    if (count($data['data']) > 0) {
        echo "\nFirst Event:\n";
        $event = $data['data'][0];
        echo "  - ID: " . $event['id'] . "\n";
        echo "  - Title: " . $event['title'] . "\n";
        echo "  - Event Type: " . $event['event_type'] . "\n";
        echo "  - Status: " . $event['status'] . "\n";
        echo "  - Event Date: " . $event['event_date'] . "\n";
        if (!empty($event['metadata'])) {
            echo "  - Metadata: " . json_encode($event['metadata']) . "\n";
        }
    }
} else {
    echo "Message: " . $data['message'] . "\n";
}
echo "\n";

// Test 3: Get Documents
echo "==========================================\n";
echo "Test 3: Get Documents\n";
echo "==========================================\n";

$request = new Illuminate\Http\Request(['per_page' => 5]);
$response = $controller->getDocuments($caseReference, $request);
$data = json_decode($response->getContent(), true);

echo "Status Code: " . $response->getStatusCode() . "\n";
echo "Success: " . ($data['success'] ? 'Yes' : 'No') . "\n";
if ($data['success']) {
    echo "Case Reference: " . $data['case_reference'] . "\n";
    echo "Case Title: " . $data['case_title'] . "\n";
    echo "Documents: " . count($data['data']) . "\n";
    echo "Total Documents: " . $data['summary']['total_documents'] . "\n";
    echo "Checked In: " . $data['summary']['checked_in'] . "\n";
    echo "Checked Out: " . $data['summary']['checked_out'] . "\n";
    echo "Overdue: " . $data['summary']['overdue'] . "\n";
    
    if (count($data['data']) > 0) {
        echo "\nFirst Document:\n";
        $doc = $data['data'][0];
        echo "  - ID: " . $doc['id'] . "\n";
        echo "  - File Name: " . $doc['file_name'] . "\n";
        echo "  - File Size: " . $doc['formatted_size'] . "\n";
        echo "  - MIME Type: " . $doc['mime_type'] . "\n";
        echo "  - Status: " . $doc['status'] . "\n";
    }
} else {
    echo "Message: " . $data['message'] . "\n";
}
echo "\n";

// Test 4: Get Financial Info
echo "==========================================\n";
echo "Test 4: Get Financial Information\n";
echo "==========================================\n";

$request = new Illuminate\Http\Request();
$response = $controller->getFinancialInfo($caseReference, $request);
$data = json_decode($response->getContent(), true);

echo "Status Code: " . $response->getStatusCode() . "\n";
echo "Success: " . ($data['success'] ? 'Yes' : 'No') . "\n";
if ($data['success']) {
    echo "Case Reference: " . $data['case_reference'] . "\n";
    echo "Case Title: " . $data['case_title'] . "\n";
    echo "\nFinancial Summary:\n";
    echo "  - Total Quotations: RM " . $data['summary']['total_quotations'] . "\n";
    echo "  - Total Invoices: RM " . $data['summary']['total_invoices'] . "\n";
    echo "  - Total Paid: RM " . $data['summary']['total_paid'] . "\n";
    echo "  - Outstanding Balance: RM " . $data['summary']['outstanding_balance'] . "\n";
    echo "  - Quotations Count: " . $data['summary']['quotations_count'] . "\n";
    echo "  - Invoices Count: " . $data['summary']['invoices_count'] . "\n";
    echo "  - Receipts Count: " . $data['summary']['receipts_count'] . "\n";
} else {
    echo "Message: " . $data['message'] . "\n";
}
echo "\n";

echo "==========================================\n";
echo "✅ All API Tests Complete!\n";
echo "==========================================\n";

