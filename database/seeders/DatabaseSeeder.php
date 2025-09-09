<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        // Call RolePermissionSeeder
        $this->call([
            UserSeeder::class,
            RolePermissionSeeder::class,
            CategorySeeder::class,
            FileTypeSeeder::class,
            SpecializationSeeder::class,
            ExpenseCategorySeeder::class,
            PayeeSeeder::class,
            CaseFileSeeder::class,
            CaseTimelineSeeder::class,
            QuotationSeeder::class,
            VoucherSeeder::class,
            BillSeeder::class,
        ]);
    }
}
