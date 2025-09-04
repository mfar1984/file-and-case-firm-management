<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Payee;

class PayeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $payees = [
            [
                'name' => 'TNB Berhad',
                'address' => 'Level 1, Podium Block, Menara TNB, Jalan Bukit Pantai, 59100 Kuala Lumpur',
                'contact_person' => 'Customer Service',
                'phone' => '03-2299 9999',
                'email' => 'customerservice@tnb.com.my',
                'category' => 'Utilities',
                'is_active' => true,
            ],
            [
                'name' => 'Office Rent Sdn Bhd',
                'address' => 'Suite 12-01, Level 12, Menara 1 Sentrum, Jalan 1 Sentrum, 50100 Kuala Lumpur',
                'contact_person' => 'Property Manager',
                'phone' => '03-2020 2020',
                'email' => 'info@officerent.com.my',
                'category' => 'Rent',
                'is_active' => true,
            ],
            [
                'name' => 'Internet Provider Sdn Bhd',
                'address' => 'Level 15, Menara TM, Jalan Pantai Baharu, 59200 Kuala Lumpur',
                'contact_person' => 'Technical Support',
                'phone' => '03-1234 5678',
                'email' => 'support@internet.com.my',
                'category' => 'Internet',
                'is_active' => true,
            ],
            [
                'name' => 'Office Supplies Enterprise',
                'address' => 'No. 45, Jalan Tun Razak, 50400 Kuala Lumpur',
                'contact_person' => 'Sales Manager',
                'phone' => '03-9876 5432',
                'email' => 'sales@supplies.com.my',
                'category' => 'Supplies',
                'is_active' => true,
            ],
            [
                'name' => 'Maintenance Service Co',
                'address' => 'Lot 123, Jalan Ampang, 68000 Ampang, Selangor',
                'contact_person' => 'Service Coordinator',
                'phone' => '03-5555 6666',
                'email' => 'service@maintenance.com.my',
                'category' => 'Maintenance',
                'is_active' => true,
            ],
            [
                'name' => 'Staff Salary Management',
                'address' => 'Internal Department',
                'contact_person' => 'HR Manager',
                'phone' => '03-1111 2222',
                'email' => 'hr@company.com.my',
                'category' => 'Salary',
                'is_active' => true,
            ],
            [
                'name' => 'Insurance Provider Bhd',
                'address' => 'Level 8, Menara Insurans, Jalan Tun Razak, 50400 Kuala Lumpur',
                'contact_person' => 'Account Manager',
                'phone' => '03-3333 4444',
                'email' => 'accounts@insurance.com.my',
                'category' => 'Insurance',
                'is_active' => true,
            ],
            [
                'name' => 'Marketing Agency Sdn Bhd',
                'address' => 'Suite 5-02, Level 5, Menara Marketing, Jalan Sultan Ismail, 50250 Kuala Lumpur',
                'contact_person' => 'Creative Director',
                'phone' => '03-7777 8888',
                'email' => 'creative@marketing.com.my',
                'category' => 'Marketing',
                'is_active' => true,
            ],
        ];

        foreach ($payees as $payee) {
            Payee::create($payee);
        }
    }
}
