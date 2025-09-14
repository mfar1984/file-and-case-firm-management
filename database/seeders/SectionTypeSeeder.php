<?php

namespace Database\Seeders;

use App\Models\SectionType;
use App\Models\Firm;
use Illuminate\Database\Seeder;

class SectionTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Default Section Types
        $sectionTypes = [
            [
                'code' => 'CA',
                'name' => 'Civil',
                'description' => 'Civil law cases including disputes, contracts, and civil litigation',
                'status' => 'active'
            ],
            [
                'code' => 'CR',
                'name' => 'Criminal',
                'description' => 'Criminal law cases including offenses, violations, and criminal proceedings',
                'status' => 'active'
            ],
            [
                'code' => 'PB',
                'name' => 'Probate / Letter of Administration',
                'description' => 'Estate administration, probate proceedings, and letters of administration',
                'status' => 'active'
            ],
            [
                'code' => 'CVY',
                'name' => 'Conveyancing',
                'description' => 'Property transactions, transfers, and conveyancing matters',
                'status' => 'active'
            ],
        ];

        // Get all firms to seed section types for each firm
        $firms = Firm::all();

        foreach ($firms as $firm) {
            foreach ($sectionTypes as $sectionType) {
                // Check if already exists to avoid duplicate
                $exists = SectionType::where('code', $sectionType['code'])
                    ->where('firm_id', $firm->id)
                    ->exists();

                if (!$exists) {
                    SectionType::create([
                        'code' => $sectionType['code'],
                        'firm_id' => $firm->id,
                        'name' => $sectionType['name'],
                        'description' => $sectionType['description'],
                        'status' => $sectionType['status'],
                    ]);
                }
            }
        }

        // Also create global section types (firm_id = null) for system defaults
        foreach ($sectionTypes as $sectionType) {
            // Check if already exists to avoid duplicate
            $exists = SectionType::where('code', $sectionType['code'])
                ->whereNull('firm_id')
                ->exists();

            if (!$exists) {
                SectionType::create([
                    'code' => $sectionType['code'],
                    'firm_id' => null,
                    'name' => $sectionType['name'],
                    'description' => $sectionType['description'],
                    'status' => $sectionType['status'],
                ]);
            }
        }
    }
}
