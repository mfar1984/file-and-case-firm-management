<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Quotation;
use Carbon\Carbon;

class UpdateExpiredQuotations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'quotations:update-expired';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update quotations that have passed their valid_until date to expired status';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $today = Carbon::today();
        
        // Find quotations that are not already expired/cancelled/converted and have passed their valid_until date
        $expiredQuotations = Quotation::where('valid_until', '<', $today)
            ->whereIn('status', ['pending', 'accepted', 'rejected'])
            ->get();
        
        if ($expiredQuotations->isEmpty()) {
            $this->info('No quotations need to be expired.');
            return 0;
        }
        
        $count = 0;
        foreach ($expiredQuotations as $quotation) {
            $quotation->update(['status' => 'expired']);
            $count++;
            $this->line("Expired quotation: {$quotation->quotation_no} (Valid until: {$quotation->valid_until})");
        }
        
        $this->info("Successfully expired {$count} quotation(s).");
        return 0;
    }
}
