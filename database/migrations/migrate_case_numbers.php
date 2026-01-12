<?php

/**
 * Migration Script: Update Case Numbers to New Format
 * 
 * Old Format: YEAR-MONTH-RANDOMID (e.g., 2025-08-1APP7I)
 * New Format: YEAR-SECTIONCODE-RUNNINGNUMBER-CLIENTABBR (e.g., 2025-CA-1-MFBAR)
 * 
 * This script will:
 * 1. Get all cases with old format
 * 2. For each case, generate new case number based on:
 *    - Year from created_at
 *    - Section type code
 *    - Running number (sequential per year + section + firm)
 *    - First plaintiff/applicant name abbreviation
 * 3. Update case_number field
 * 
 * Usage: php database/migrations/migrate_case_numbers.php
 */

require __DIR__ . '/../../vendor/autoload.php';

$app = require_once __DIR__ . '/../../bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\CourtCase;
use App\Models\SectionType;
use App\Models\CaseParty;
use Illuminate\Support\Facades\DB;

echo "========================================\n";
echo "CASE NUMBER MIGRATION SCRIPT\n";
echo "========================================\n\n";

echo "Old Format: YEAR-MONTH-RANDOMID\n";
echo "New Format: YEAR-SECTIONCODE-RUNNINGNUMBER-CLIENTABBR\n\n";

// Get all cases that need migration (old format)
$cases = CourtCase::withoutGlobalScope(\App\Scopes\FirmScope::class)
    ->where('case_number', 'NOT LIKE', '%-%-%--%') // Old format has 3 dashes, new has 4
    ->orderBy('firm_id')
    ->orderBy('created_at')
    ->get();

echo "Found " . $cases->count() . " cases to migrate\n\n";

if ($cases->count() === 0) {
    echo "✅ No cases to migrate. All cases are already in new format.\n";
    exit(0);
}

// Ask for confirmation
echo "⚠️  WARNING: This will update case numbers for " . $cases->count() . " cases.\n";
echo "Do you want to continue? (yes/no): ";
$handle = fopen("php://stdin", "r");
$line = fgets($handle);
$confirmation = trim($line);
fclose($handle);

if (strtolower($confirmation) !== 'yes') {
    echo "\n❌ Migration cancelled.\n";
    exit(0);
}

echo "\n🚀 Starting migration...\n\n";

// Track running numbers per year + section + firm
$runningNumbers = [];

$successCount = 0;
$errorCount = 0;
$skippedCount = 0;

DB::beginTransaction();

try {
    foreach ($cases as $case) {
        echo "Processing Case ID {$case->id}: {$case->case_number}\n";
        
        // Get year from created_at
        $year = date('Y', strtotime($case->created_at));
        
        // Get section code
        $sectionCode = 'XX';
        if ($case->section) {
            if (is_numeric($case->section)) {
                // It's a section_type_id
                $sectionType = SectionType::withoutGlobalScope(\App\Scopes\FirmScope::class)
                    ->find($case->section);
                if ($sectionType) {
                    $sectionCode = strtoupper($sectionType->code);
                }
            } else {
                // It's a section name (old format)
                $sectionType = SectionType::withoutGlobalScope(\App\Scopes\FirmScope::class)
                    ->where('name', 'LIKE', $case->section . '%')
                    ->first();
                if ($sectionType) {
                    $sectionCode = strtoupper($sectionType->code);
                } else {
                    // Fallback: use first 2 letters
                    $sectionCode = strtoupper(substr($case->section, 0, 2));
                }
            }
        }
        
        // Get running number
        $key = "{$year}-{$sectionCode}-{$case->firm_id}";
        if (!isset($runningNumbers[$key])) {
            // Check if there are any existing cases with new format for this year+section+firm
            $existingMax = CourtCase::withoutGlobalScope(\App\Scopes\FirmScope::class)
                ->where('case_number', 'LIKE', "{$year}-{$sectionCode}-%")
                ->where('firm_id', $case->firm_id)
                ->get()
                ->map(function($c) {
                    $parts = explode('-', $c->case_number);
                    return count($parts) >= 3 ? intval($parts[2]) : 0;
                })
                ->max();
            
            $runningNumbers[$key] = $existingMax ? $existingMax : 0;
        }
        $runningNumbers[$key]++;
        $runningNumber = $runningNumbers[$key];
        
        // Get first plaintiff/applicant name
        $firstParty = CaseParty::withoutGlobalScope(\App\Scopes\FirmScope::class)
            ->where('case_id', $case->id)
            ->whereIn('party_type', ['plaintiff', 'applicant'])
            ->orderBy('id')
            ->first();
        
        $clientName = $firstParty ? $firstParty->name : null;
        
        // Generate client abbreviation
        $clientAbbr = CourtCase::generateClientAbbreviation($clientName);
        
        // Generate new case number
        $newCaseNumber = "{$year}-{$sectionCode}-{$runningNumber}-{$clientAbbr}";
        
        // Check if new case number already exists
        $exists = CourtCase::withoutGlobalScope(\App\Scopes\FirmScope::class)
            ->where('case_number', $newCaseNumber)
            ->where('id', '!=', $case->id)
            ->exists();
        
        if ($exists) {
            echo "  ⚠️  SKIPPED: Case number {$newCaseNumber} already exists\n";
            $skippedCount++;
            continue;
        }
        
        // Update case number
        $oldCaseNumber = $case->case_number;
        $case->case_number = $newCaseNumber;
        $case->save();
        
        echo "  ✅ Updated: {$oldCaseNumber} → {$newCaseNumber}\n";
        echo "     Section: {$sectionCode}, Client: " . ($clientName ?: 'N/A') . "\n\n";
        
        $successCount++;
    }
    
    DB::commit();
    
    echo "\n========================================\n";
    echo "MIGRATION COMPLETE!\n";
    echo "========================================\n\n";
    echo "✅ Successfully migrated: {$successCount} cases\n";
    if ($skippedCount > 0) {
        echo "⚠️  Skipped (duplicates): {$skippedCount} cases\n";
    }
    if ($errorCount > 0) {
        echo "❌ Errors: {$errorCount} cases\n";
    }
    echo "\nTotal processed: " . ($successCount + $skippedCount + $errorCount) . " cases\n";
    
} catch (\Exception $e) {
    DB::rollBack();
    echo "\n❌ ERROR: Migration failed!\n";
    echo "Error: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
    exit(1);
}

