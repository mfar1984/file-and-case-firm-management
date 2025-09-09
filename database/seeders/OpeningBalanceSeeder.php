<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\OpeningBalance;

class OpeningBalanceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Seeder disabled - Opening balances should be created manually by users
        // This ensures only real data entered by users exists in the system

        // No automatic data creation - users must add their own opening balances
        // through the Settings/Global interface
    }
}
