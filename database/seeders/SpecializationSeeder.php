<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Specialization;

class SpecializationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $specializations = [
            [
                'specialist_name' => 'Civil Law',
                'description' => 'Legal matters related to civil disputes, contracts, and personal injury cases',
                'status' => 'active'
            ],
            [
                'specialist_name' => 'Criminal Law',
                'description' => 'Legal representation in criminal cases, defense, and prosecution',
                'status' => 'active'
            ],
            [
                'specialist_name' => 'Shariah Law',
                'description' => 'Islamic law matters including family, inheritance, and religious compliance',
                'status' => 'active'
            ],
            [
                'specialist_name' => 'Conveyancing & Real Estate',
                'description' => 'Property law, land transactions, and real estate legal matters',
                'status' => 'active'
            ],
            [
                'specialist_name' => 'Corporate & Commercial Law',
                'description' => 'Business law, corporate governance, and commercial transactions',
                'status' => 'active'
            ],
            [
                'specialist_name' => 'Family Law',
                'description' => 'Marriage, divorce, child custody, and family-related legal matters',
                'status' => 'active'
            ],
            [
                'specialist_name' => 'Employment & Industrial Relations Law',
                'description' => 'Labor law, employment contracts, and workplace legal issues',
                'status' => 'active'
            ],
            [
                'specialist_name' => 'Intellectual Property',
                'description' => 'Patents, trademarks, copyrights, and intellectual property protection',
                'status' => 'active'
            ],
            [
                'specialist_name' => 'Administrative & Constitutional Law',
                'description' => 'Government law, constitutional rights, and administrative procedures',
                'status' => 'active'
            ]
        ];

        foreach ($specializations as $specialization) {
            Specialization::create($specialization);
        }
    }
}
