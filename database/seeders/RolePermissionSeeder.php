<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Create permissions
        $permissions = [
            // Dashboard & Overview
            'view-dashboard',
            'view-overview',
            
            // Case Management
            'view-cases',
            'create-cases',
            'edit-cases',
            'delete-cases',
            'assign-cases',
            
            // Client Management
            'view-clients',
            'create-clients',
            'edit-clients',
            'delete-clients',
            
            // Partner Management
            'view-partners',
            'create-partners',
            'edit-partners',
            'delete-partners',
            
            // File Management
            'view-files',
            'upload-files',
            'download-files',
            'delete-files',
            'checkout-files',
            'return-files',
            
            // Accounting
            'view-accounting',
            'create-quotations',
            'edit-quotations',
            'delete-quotations',
            'create-invoices',
            'edit-invoices',
            'delete-invoices',
            'create-receipts',
            'edit-receipts',
            'delete-receipts',
            'create-vouchers',
            'edit-vouchers',
            'delete-vouchers',
            'create-bills',
            'edit-bills',
            'delete-bills',
            
            // Calendar
            'view-calendar',
            'create-events',
            'edit-events',
            'delete-events',
            
            // Settings
            'view-settings',
            'manage-firm-settings',
            'manage-system-settings',
            'manage-email-settings',
            'manage-security-settings',
            'manage-weather-settings',
            'manage-roles',
            'manage-users',
            'manage-permissions',
            
            // User Management
            'view-users',
            'create-users',
            'edit-users',
            'delete-users',
            'assign-roles',
            
            // Reports & Analytics
            'view-reports',
            'export-reports',
            
            // System Administration
            'access-admin-panel',
            'manage-system-logs',
            'backup-system',
            'restore-system',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Create roles
        $roles = [
            'Administrator' => [
                'description' => 'Full system access and control',
                'permissions' => Permission::all()->pluck('name')->toArray()
            ],
            'Firm' => [
                'description' => 'Firm staff with comprehensive access',
                'permissions' => [
                    'view-dashboard', 'view-overview',
                    'view-cases', 'create-cases', 'edit-cases', 'assign-cases',
                    'view-clients', 'create-clients', 'edit-clients',
                    'view-partners', 'create-partners', 'edit-partners',
                    'view-files', 'upload-files', 'download-files', 'checkout-files', 'return-files',
                    'view-accounting', 'create-quotations', 'edit-quotations', 'create-invoices', 'edit-invoices',
                    'create-receipts', 'edit-receipts', 'create-vouchers', 'edit-vouchers',
                    'create-bills', 'edit-bills',
                    'view-calendar', 'create-events', 'edit-events',
                    'view-settings', 'manage-firm-settings', 'manage-weather-settings',
                    'view-reports', 'export-reports'
                ]
            ],
            'Partner' => [
                'description' => 'External partner with case access',
                'permissions' => [
                    'view-dashboard', 'view-overview',
                    'view-cases', 'edit-cases',
                    'view-clients',
                    'view-files', 'download-files',
                    'view-accounting',
                    'view-calendar'
                ]
            ],
            'Client' => [
                'description' => 'Client access to their own cases',
                'permissions' => [
                    'view-dashboard',
                    'view-overview', // Client overview (isolated to their cases)
                    'view-cases', // Limited to own cases
                    'view-files', // Limited to own case files
                    'view-calendar' // Limited to own case events
                ]
            ],
            'Staff' => [
                'description' => 'General staff with limited access',
                'permissions' => [
                    'view-dashboard', 'view-overview',
                    'view-cases', 'create-cases',
                    'view-clients', 'create-clients',
                    'view-files', 'upload-files', 'download-files',
                    'view-accounting', 'create-quotations', 'create-invoices',
                    'view-calendar', 'create-events'
                ]
            ]
        ];

        foreach ($roles as $roleName => $roleData) {
            $role = Role::firstOrCreate([
                'name' => $roleName
            ], [
                'description' => $roleData['description']
            ]);

            $role->syncPermissions($roleData['permissions']);
        }

        // Assign Administrator role to first user if exists
        $firstUser = User::first();
        if ($firstUser) {
            $firstUser->assignRole('Administrator');
        }
    }
} 