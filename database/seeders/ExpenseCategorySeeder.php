<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ExpenseCategory;

class ExpenseCategorySeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            ['name' => 'Utilities', 'description' => 'Electricity, water, etc.', 'status' => 'active'],
            ['name' => 'Rent', 'description' => 'Office rental', 'status' => 'active'],
            ['name' => 'Salary', 'description' => 'Staff salaries and allowances', 'status' => 'active'],
            ['name' => 'Internet', 'description' => 'Internet services', 'status' => 'active'],
            ['name' => 'Supplies', 'description' => 'Office supplies and stationery', 'status' => 'active'],
            ['name' => 'Maintenance', 'description' => 'Repairs and maintenance', 'status' => 'active'],
            ['name' => 'Insurance', 'description' => 'Insurance premiums', 'status' => 'active'],
            ['name' => 'Marketing', 'description' => 'Marketing and advertising', 'status' => 'active'],
            ['name' => 'Other', 'description' => 'Miscellaneous expenses', 'status' => 'active'],
        ];

        foreach ($items as $item) {
            ExpenseCategory::firstOrCreate(
                ['name' => $item['name']],
                $item
            );
        }
    }
}


