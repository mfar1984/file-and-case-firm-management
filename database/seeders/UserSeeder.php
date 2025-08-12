<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Firm User
        $firmUser = User::create([
            'name' => 'Ahmad Firm',
            'email' => 'firm@naaelahsaleh.my',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);
        $firmUser->assignRole('Firm');

        // Create Partner User
        $partnerUser = User::create([
            'name' => 'Siti Partner',
            'email' => 'partner@naaelahsaleh.my',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);
        $partnerUser->assignRole('Partner');

        // Create Client User
        $clientUser = User::create([
            'name' => 'Ali Client',
            'email' => 'client@naaelahsaleh.my',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);
        $clientUser->assignRole('Client');

        // Create Staff User
        $staffUser = User::create([
            'name' => 'Fatimah Staff',
            'email' => 'staff@naaelahsaleh.my',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);
        $staffUser->assignRole('Staff');

        // Create another Firm User
        $firmUser2 = User::create([
            'name' => 'Zainab Firm',
            'email' => 'firm2@naaelahsaleh.my',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);
        $firmUser2->assignRole('Firm');

        // Create another Partner User
        $partnerUser2 = User::create([
            'name' => 'Hassan Partner',
            'email' => 'partner2@naaelahsaleh.my',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);
        $partnerUser2->assignRole('Partner');

        // Create another Client User
        $clientUser2 = User::create([
            'name' => 'Aisha Client',
            'email' => 'client2@naaelahsaleh.my',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);
        $clientUser2->assignRole('Client');

        // Create another Staff User
        $staffUser2 = User::create([
            'name' => 'Omar Staff',
            'email' => 'staff2@naaelahsaleh.my',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);
        $staffUser2->assignRole('Staff');

        $this->command->info('Users created successfully with roles assigned!');
        $this->command->info('Firm Users: firm@naaelahsaleh.my, firm2@naaelahsaleh.my (password: password)');
        $this->command->info('Partner Users: partner@naaelahsaleh.my, partner2@naaelahsaleh.my (password: password)');
        $this->command->info('Client Users: client@naaelahsaleh.my, client2@naaelahsaleh.my (password: password)');
        $this->command->info('Staff Users: staff@naaelahsaleh.my, staff2@naaelahsaleh.my (password: password)');
    }
}
