# Role & Permission System Guide

## Overview
Naeelah Firm menggunakan **Spatie Laravel Permission** untuk mengelola roles dan permissions. Sistem ini memungkinkan kontrol akses yang granular untuk berbagai fitur aplikasi.

## Installation & Setup

### 1. Package Installation
```bash
composer require spatie/laravel-permission
php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider"
php artisan migrate
```

### 2. User Model Integration
```php
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;
    // ...
}
```

### 3. Database Structure
- `roles` table: Menyimpan roles dengan kolom `description`
- `permissions` table: Menyimpan permissions
- `model_has_roles` table: Pivot table untuk user-role relationship
- `model_has_permissions` table: Pivot table untuk user-permission relationship
- `role_has_permissions` table: Pivot table untuk role-permission relationship

## Default Roles & Permissions

### Roles
1. **Administrator**
   - Full system access and control
   - Semua permissions

2. **Firm**
   - Firm staff with comprehensive access
   - Akses ke cases, clients, partners, files, accounting, calendar, settings

3. **Partner**
   - External partner with case access
   - Akses terbatas ke cases, clients, files, accounting, calendar

4. **Client**
   - Client access to their own cases
   - Akses hanya ke kasus sendiri dan dokumen terkait

5. **Staff**
   - General staff with limited access
   - Akses dasar untuk operasi sehari-hari

### Permissions Categories

#### Dashboard & Overview
- `view-dashboard`
- `view-overview`

#### Case Management
- `view-cases`
- `create-cases`
- `edit-cases`
- `delete-cases`
- `assign-cases`

#### Client Management
- `view-clients`
- `create-clients`
- `edit-clients`
- `delete-clients`

#### Partner Management
- `view-partners`
- `create-partners`
- `edit-partners`
- `delete-partners`

#### File Management
- `view-files`
- `upload-files`
- `download-files`
- `delete-files`
- `checkout-files`
- `return-files`

#### Accounting
- `view-accounting`
- `create-quotations`
- `edit-quotations`
- `delete-quotations`
- `create-invoices`
- `edit-invoices`
- `delete-invoices`
- `create-receipts`
- `edit-receipts`
- `delete-receipts`
- `create-vouchers`
- `edit-vouchers`
- `delete-vouchers`
- `create-bills`
- `edit-bills`
- `delete-bills`

#### Calendar
- `view-calendar`
- `create-events`
- `edit-events`
- `delete-events`

#### Settings
- `view-settings`
- `manage-firm-settings`
- `manage-system-settings`
- `manage-email-settings`
- `manage-security-settings`
- `manage-weather-settings`
- `manage-roles`
- `manage-users`
- `manage-permissions`

#### User Management
- `view-users`
- `create-users`
- `edit-users`
- `delete-users`
- `assign-roles`

#### Reports & Analytics
- `view-reports`
- `export-reports`

#### System Administration
- `access-admin-panel`
- `manage-system-logs`
- `backup-system`
- `restore-system`

## Usage Examples

### 1. Checking Permissions in Controllers
```php
// Check single permission
if (auth()->user()->hasPermissionTo('view-cases')) {
    // User can view cases
}

// Check multiple permissions (any)
if (auth()->user()->hasAnyPermission(['view-cases', 'create-cases'])) {
    // User can view or create cases
}

// Check multiple permissions (all)
if (auth()->user()->hasAllPermissions(['view-cases', 'create-cases'])) {
    // User can view AND create cases
}
```

### 2. Checking Roles
```php
// Check single role
if (auth()->user()->hasRole('Administrator')) {
    // User is administrator
}

// Check multiple roles
if (auth()->user()->hasAnyRole(['Administrator', 'Firm'])) {
    // User is administrator or firm staff
}
```

### 3. Route Protection with Middleware
```php
// Single permission
Route::middleware(['auth', 'permission:manage-roles'])->group(function () {
    Route::get('/settings/role', [RoleController::class, 'index']);
});

// Multiple permissions (any)
Route::middleware(['auth', 'permission:view-cases|create-cases'])->group(function () {
    // Routes accessible with either permission
});
```

### 4. Permission Checks in Views
```php
@php
use App\Helpers\PermissionHelper;
@endphp

@if(PermissionHelper::hasPermission('view-cases'))
    <a href="{{ route('case.index') }}">View Cases</a>
@endif

@if(PermissionHelper::hasAnyPermission(['create-cases', 'edit-cases']))
    <button>Manage Cases</button>
@endif
```

### 5. Assigning Roles to Users
```php
// Assign single role
$user->assignRole('Firm');

// Assign multiple roles
$user->assignRole(['Firm', 'Staff']);

// Sync roles (removes existing, adds new)
$user->syncRoles(['Partner']);
```

### 6. Assigning Permissions to Roles
```php
// Assign single permission
$role->givePermissionTo('view-cases');

// Assign multiple permissions
$role->givePermissionTo(['view-cases', 'create-cases']);

// Sync permissions (removes existing, adds new)
$role->syncPermissions(['view-cases', 'create-cases', 'edit-cases']);
```

## Helper Functions

### PermissionHelper Class
```php
// Check permissions
PermissionHelper::hasPermission('view-cases');
PermissionHelper::hasAnyPermission(['view-cases', 'create-cases']);
PermissionHelper::hasAllPermissions(['view-cases', 'create-cases']);

// Check roles
PermissionHelper::hasRole('Administrator');
PermissionHelper::hasAnyRole(['Administrator', 'Firm']);

// Get user data
PermissionHelper::getUserPermissions();
PermissionHelper::getUserRoles();
```

## Role Management Interface

### Features
- **View Roles**: List semua roles dengan permissions dan user count
- **Create Role**: Buat role baru dengan permissions
- **Edit Role**: Update role name, description, dan permissions
- **Delete Role**: Hapus role (hanya jika tidak ada user yang menggunakan)
- **Permission Management**: Assign/unassign permissions ke roles

### Access Control
- Hanya user dengan permission `manage-roles` yang bisa akses
- Role Administrator tidak bisa dihapus
- Role yang sedang digunakan user tidak bisa dihapus

## Security Best Practices

### 1. Always Check Permissions
```php
// In controllers
public function index()
{
    if (!auth()->user()->hasPermissionTo('view-cases')) {
        abort(403, 'Unauthorized action.');
    }
    // ...
}
```

### 2. Use Middleware for Route Protection
```php
Route::middleware(['auth', 'permission:manage-users'])->group(function () {
    // Protected routes
});
```

### 3. Validate in Forms
```php
// In form requests
public function rules()
{
    if (!auth()->user()->hasPermissionTo('edit-cases')) {
        abort(403);
    }
    return [
        // validation rules
    ];
}
```

### 4. Hide UI Elements
```php
@if(PermissionHelper::hasPermission('create-cases'))
    <button>Create New Case</button>
@endif
```

## Database Seeding

### Running Seeders
```bash
php artisan db:seed --class=RolePermissionSeeder
```

### Custom Seeding
```php
// Create permission
Permission::create(['name' => 'custom-permission']);

// Create role
$role = Role::create([
    'name' => 'Custom Role',
    'description' => 'Custom role description'
]);

// Assign permissions to role
$role->givePermissionTo(['view-cases', 'create-cases']);

// Assign role to user
$user->assignRole('Custom Role');
```

## Troubleshooting

### Common Issues

1. **Permission Not Found Error**
   - Ensure permission exists in database
   - Check permission name spelling
   - Run seeder if permissions missing

2. **Role Not Found Error**
   - Ensure role exists in database
   - Check role name spelling
   - Run seeder if roles missing

3. **Middleware Not Working**
   - Check middleware registration in `bootstrap/app.php`
   - Ensure user is authenticated
   - Verify permission name in route

4. **Helper Not Found**
   - Run `composer dump-autoload`
   - Check helper file exists
   - Verify autoload configuration in `composer.json`

### Debug Commands
```bash
# Check roles and permissions
php artisan tinker --execute="use Spatie\Permission\Models\Role; use Spatie\Permission\Models\Permission; Role::all()->each(function(\$role) { echo \$role->name . ' - ' . (\$role->description ?? 'No description') . PHP_EOL; });"

# Check user permissions
php artisan tinker --execute="use App\Models\User; \$user = User::first(); echo 'User: ' . \$user->name . PHP_EOL; echo 'Roles: ' . \$user->roles->pluck('name')->implode(', ') . PHP_EOL; echo 'Permissions: ' . \$user->getAllPermissions()->pluck('name')->implode(', ') . PHP_EOL;"
```

## API Endpoints

### Role Management
- `GET /settings/role` - List roles
- `POST /settings/role` - Create role
- `GET /settings/role/{id}/edit` - Get role for editing
- `PUT /settings/role/{id}` - Update role
- `DELETE /settings/role/{id}` - Delete role
- `GET /settings/role/{id}/users` - Get users with role
- `GET /settings/permissions` - Get all permissions

### Required Permissions
- `manage-roles` - Access to role management
- `manage-users` - Access to user management
- `manage-permissions` - Access to permission management

## Future Enhancements

1. **Role Hierarchy**: Implement role inheritance
2. **Permission Groups**: Group related permissions
3. **Audit Logging**: Track permission changes
4. **Temporary Permissions**: Time-based permissions
5. **API Permissions**: Separate API permissions
6. **Multi-tenancy**: Role-based tenant access 