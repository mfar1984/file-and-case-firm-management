<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\CourtCase;
use App\Models\CaseFile;
use Illuminate\Support\Facades\DB;

class MigrateCaseNumbers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cases:migrate-numbers {--dry-run : Run without making changes}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrate all existing case numbers to new format: YEAR-MONTH-6UNIQUEID (e.g., 2025-11-A7JG8K)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $isDryRun = $this->option('dry-run');

        if ($isDryRun) {
            $this->info('🔍 DRY RUN MODE - No changes will be made');
            $this->newLine();
        } else {
            $this->warn('⚠️  LIVE MODE - Changes will be made to the database');
            if (!$this->confirm('Do you want to continue?')) {
                $this->info('Migration cancelled.');
                return 0;
            }
            $this->newLine();
        }

        // Get all cases without firm scope
        $cases = CourtCase::withoutFirmScope()->orderBy('id')->get();

        if ($cases->isEmpty()) {
            $this->info('No cases found to migrate.');
            return 0;
        }

        $this->info("Found {$cases->count()} cases to migrate");
        $this->newLine();

        $successCount = 0;
        $errorCount = 0;
        $skippedCount = 0;

        // Create progress bar
        $bar = $this->output->createProgressBar($cases->count());
        $bar->start();

        foreach ($cases as $case) {
            try {
                // Check if case number already matches new format (YYYY-MM-XXXXXX)
                if ($this->isNewFormat($case->case_number)) {
                    $this->newLine();
                    $this->comment("⏭️  Skipping Case ID {$case->id}: Already in new format ({$case->case_number})");
                    $skippedCount++;
                    $bar->advance();
                    continue;
                }

                $oldCaseNumber = $case->case_number;

                // Generate new case number based on created_at date
                $newCaseNumber = $this->generateNewCaseNumber($case);

                if (!$isDryRun) {
                    DB::beginTransaction();

                    // Update case_number in cases table
                    $case->case_number = $newCaseNumber;
                    $case->save();

                    // Update case_ref in case_files table
                    CaseFile::where('case_ref', $oldCaseNumber)
                        ->update(['case_ref' => $newCaseNumber]);

                    DB::commit();
                }

                $this->newLine();
                $this->info("✅ Case ID {$case->id}: {$oldCaseNumber} → {$newCaseNumber}");

                $successCount++;

            } catch (\Exception $e) {
                if (!$isDryRun) {
                    DB::rollBack();
                }

                $this->newLine();
                $this->error("❌ Failed to migrate Case ID {$case->id}: {$e->getMessage()}");
                $errorCount++;
            }

            $bar->advance();
        }

        $bar->finish();
        $this->newLine(2);

        // Summary
        $this->info('═══════════════════════════════════════════════════════');
        $this->info('                    MIGRATION SUMMARY                  ');
        $this->info('═══════════════════════════════════════════════════════');
        $this->info("Total Cases:     {$cases->count()}");
        $this->info("✅ Migrated:     {$successCount}");
        $this->info("⏭️  Skipped:      {$skippedCount}");
        $this->info("❌ Failed:       {$errorCount}");
        $this->info('═══════════════════════════════════════════════════════');

        if ($isDryRun) {
            $this->newLine();
            $this->warn('🔍 This was a DRY RUN - No changes were made');
            $this->info('Run without --dry-run to apply changes');
        } else {
            $this->newLine();
            $this->info('✅ Migration completed successfully!');
        }

        return 0;
    }

    /**
     * Check if case number is already in new format
     * Format: YYYY-MM-XXXXXX (e.g., 2025-11-A7JG8K)
     */
    private function isNewFormat($caseNumber)
    {
        // Pattern: YYYY-MM-6CHARS (where 6CHARS are uppercase alphanumeric)
        return preg_match('/^\d{4}-\d{2}-[A-Z0-9]{6}$/', $caseNumber);
    }

    /**
     * Generate new case number based on case created_at date
     * Format: YEAR-MONTH-6UNIQUEID
     */
    private function generateNewCaseNumber($case)
    {
        // Use created_at date for year and month
        $createdAt = $case->created_at;
        $year = $createdAt->format('Y');
        $month = $createdAt->format('m');

        // Generate unique 6-character ID
        $attempts = 0;
        $maxAttempts = 100;

        do {
            $uniqueId = $this->generateUniqueId(6);
            $newCaseNumber = "{$year}-{$month}-{$uniqueId}";

            // Check if this case number already exists
            $exists = CourtCase::withoutFirmScope()
                ->where('case_number', $newCaseNumber)
                ->exists();

            $attempts++;

            if ($attempts >= $maxAttempts) {
                throw new \Exception("Unable to generate unique case number after {$maxAttempts} attempts");
            }

        } while ($exists);

        return $newCaseNumber;
    }

    /**
     * Generate random alphanumeric string (uppercase)
     */
    private function generateUniqueId($length = 6)
    {
        $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $charactersLength = strlen($characters);
        $randomString = '';

        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[random_int(0, $charactersLength - 1)];
        }

        return $randomString;
    }
}

