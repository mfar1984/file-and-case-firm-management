<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\CaseType;
use App\Models\CaseStatus;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        // Case Types
        $caseTypes = [
            ['code' => 'CR', 'description' => 'Criminal', 'status' => 'active'],
            ['code' => 'CA', 'description' => 'Civil Action', 'status' => 'active'],
            ['code' => 'PB', 'description' => 'Probate/ Letter of Administration', 'status' => 'active'],
            ['code' => 'CVY', 'description' => 'Conveyancing', 'status' => 'active'],
            ['code' => 'HN', 'description' => 'Bankruptcy', 'status' => 'active'],
            ['code' => 'HB', 'description' => 'Hibah', 'status' => 'active'],
            ['code' => 'AGT', 'description' => 'Agreement', 'status' => 'active'],
            ['code' => 'NOD', 'description' => 'Notice of Demand', 'status' => 'active'],
            ['code' => 'MISC', 'description' => 'Miscellaneous', 'status' => 'active'],
        ];

        foreach ($caseTypes as $type) {
            CaseType::create($type);
        }

        // Case Statuses
        $caseStatuses = [
            [
                'name' => 'Consultation',
                'description' => 'Initial consultation with client',
                'color' => 'blue',
                'status' => 'active'
            ],
            [
                'name' => 'Quotation',
                'description' => 'Fee quotation provided to client',
                'color' => 'yellow',
                'status' => 'active'
            ],
            [
                'name' => 'Open file',
                'description' => 'Case file opened and active',
                'color' => 'green',
                'status' => 'active'
            ],
            [
                'name' => 'Proceed',
                'description' => 'Case proceeding with legal action',
                'color' => 'purple',
                'status' => 'active'
            ],
            [
                'name' => 'Closed file',
                'description' => 'Case completed and file closed',
                'color' => 'gray',
                'status' => 'active'
            ],
            [
                'name' => 'Cancel',
                'description' => 'Case cancelled or withdrawn',
                'color' => 'red',
                'status' => 'active'
            ],
        ];

        foreach ($caseStatuses as $status) {
            CaseStatus::create($status);
        }

        $this->command->info('Categories seeded successfully!');
        $this->command->info('Created ' . count($caseTypes) . ' case types and ' . count($caseStatuses) . ' case statuses.');
    }
}
