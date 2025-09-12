# Multi-Firm Tenancy System Documentation

## Overview

The Naeelah Firm application now supports multi-firm tenancy, allowing multiple law firms to use the same system while maintaining complete data isolation and security.

## Key Features

### 1. Data Isolation
- Each firm's data is completely isolated from other firms
- Users can only access data belonging to their assigned firm
- Super Administrators can switch between firms and access all data

### 2. Role-Based Access Control
- Firm-specific roles and permissions
- Super Administrator role for system-wide access
- Regular users are restricted to their firm's data

### 3. Firm-Specific Settings
- Each firm can have custom settings (logo, contact info, etc.)
- Email templates use firm-specific branding
- PDF reports show correct firm information

## User Roles

### Super Administrator
- Can access all firms' data
- Can switch between firms using the firm selector in header
- Can create and manage firms
- Can assign users to different firms

### Regular Users (Administrator, Firm, Partner, Client)
- Can only access their assigned firm's data
- Cannot see or modify other firms' information
- Automatically filtered to their firm context

## How It Works

### Database Structure
- `firms` table stores firm information
- All major tables have `firm_id` foreign key
- Indexes on `firm_id` for optimal performance

### Automatic Filtering
- `FirmScope` global scope automatically filters queries
- `HasFirmScope` trait applied to relevant models
- Middleware sets firm context for each request

### Models with Firm Isolation
- Client
- Partner
- CourtCase
- Receipt
- Bill
- Voucher
- OpeningBalance
- ExpenseCategory

## User Guide

### For Super Administrators

#### Switching Between Firms
1. Look for the firm selector dropdown in the header
2. Select the firm you want to work with
3. All data will automatically filter to that firm

#### Managing Firms
1. Go to Settings > Firm Management
2. Create, edit, or deactivate firms
3. Assign users to appropriate firms

#### Creating Users for Specific Firms
1. Go to Settings > User Management
2. When creating a user, select their firm
3. Assign appropriate roles

### For Regular Users

#### Accessing Your Data
- All data you see is automatically filtered to your firm
- You cannot access other firms' information
- Your firm information appears in reports and emails

#### Updating Firm Settings
1. Go to Settings > Global Config
2. Update your firm's information
3. Changes apply only to your firm

## Technical Implementation

### Key Components

#### 1. FirmScope Global Scope
```php
// Automatically filters queries by firm_id
class FirmScope implements Scope
{
    public function apply(Builder $builder, Model $model): void
    {
        // Filter by user's firm or session firm for Super Admin
    }
}
```

#### 2. HasFirmScope Trait
```php
// Applied to models that need firm isolation
trait HasFirmScope
{
    protected static function bootHasFirmScope()
    {
        static::addGlobalScope(new FirmScope);
    }
}
```

#### 3. FirmScope Middleware
```php
// Sets firm context for each request
class FirmScope
{
    public function handle(Request $request, Closure $next): Response
    {
        // Set firm context and permissions
    }
}
```

### Security Features

#### Data Isolation
- Queries automatically filtered by firm_id
- No cross-firm data access possible
- Super Admin can bypass with explicit permission

#### Role Isolation
- Spatie Permission teams feature enabled
- Roles are firm-specific
- Permissions respect firm boundaries

#### Session Management
- Firm context stored in session
- Automatic cleanup on logout
- Secure firm switching for Super Admin

## Troubleshooting

### Common Issues

#### "No data visible"
- Check if user has firm_id assigned
- Verify user has appropriate roles
- Ensure firm is active

#### "Permission denied"
- Check user's role assignments
- Verify firm context is set correctly
- Ensure user belongs to correct firm

#### "Cross-firm data access"
- This should never happen - contact system administrator
- Check FirmScope implementation
- Verify middleware is working

### Performance Optimization

#### Database Indexes
- All firm_id columns have indexes
- Query performance optimized for firm filtering
- Regular maintenance recommended

#### Memory Usage
- Firm context cached in session
- Minimal overhead per request
- Efficient query filtering

## Migration Guide

### From Single-Firm to Multi-Firm

1. **Data Migration Completed**
   - All existing data assigned to default firm
   - Users assigned to default firm
   - Settings migrated to firm-specific format

2. **No Action Required**
   - Existing users continue working normally
   - All data remains accessible
   - No workflow changes needed

### Adding New Firms

1. **Create Firm**
   ```php
   $firm = Firm::create([
       'name' => 'New Law Firm',
       'registration_number' => 'LLP1234',
       'address' => 'Firm Address',
       'phone' => '+60123456789',
       'email' => 'info@newfirm.com',
       'status' => 'active'
   ]);
   ```

2. **Create Users for New Firm**
   ```php
   $user = User::create([
       'name' => 'New User',
       'email' => 'user@newfirm.com',
       'firm_id' => $firm->id,
       // other fields...
   ]);
   ```

## Best Practices

### For Developers

1. **Always Use Firm Context**
   - Never bypass FirmScope without explicit reason
   - Use `withoutFirmScope()` only when necessary
   - Test with multiple firms

2. **Security First**
   - Validate firm access in controllers
   - Use middleware for firm context
   - Log firm-switching activities

3. **Performance Considerations**
   - Use eager loading with firm relationships
   - Index all firm_id columns
   - Monitor query performance

### For System Administrators

1. **Regular Maintenance**
   - Monitor firm data growth
   - Clean up inactive firms
   - Review user assignments

2. **Security Auditing**
   - Regular access reviews
   - Monitor cross-firm activities
   - Validate role assignments

3. **Backup Strategy**
   - Firm-specific backup procedures
   - Test restore procedures
   - Document recovery processes

## Support

For technical support or questions about the multi-firm system:

1. Check this documentation first
2. Review system logs for errors
3. Contact system administrator
4. Escalate to development team if needed

---

**Last Updated:** September 9, 2025
**Version:** 1.0
**System:** Naeelah Firm Multi-Tenancy
