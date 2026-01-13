<?php

// Debug permissions
// Run: php database/scripts/debug_permissions.php

require_once __DIR__ . '/../../vendor/autoload.php';

$app = require_once __DIR__ . '/../../bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;
use App\Models\User;

// Check Admin user (ID 1)
$user = User::find(1);
echo "=== User: " . $user->name . " (ID: " . $user->id . ") ===\n";
echo "firm_id: " . $user->firm_id . "\n";

// Set firm context
setPermissionsTeamId($user->firm_id);

echo "Roles: " . $user->getRoleNames()->implode(', ') . "\n";
echo "Has view-overview: " . ($user->hasPermissionTo('view-overview') ? 'YES' : 'NO') . "\n";
echo "Has view-cases: " . ($user->hasPermissionTo('view-cases') ? 'YES' : 'NO') . "\n";

// Check pivot table
echo "\n=== Pivot Table ===\n";
$pivots = DB::table('model_has_roles')
    ->where('model_id', 1)
    ->where('model_type', 'App\Models\User')
    ->get();

foreach ($pivots as $p) {
    $role = DB::table('roles')->where('id', $p->role_id)->first();
    echo "role: " . $role->name . " | pivot firm_id: " . ($p->firm_id ?? 'NULL') . "\n";
}

// Check if PermissionHelper works
echo "\n=== PermissionHelper Test ===\n";
auth()->login($user);
echo "Logged in as: " . auth()->user()->name . "\n";
echo "PermissionHelper::hasPermission('view-overview'): " . (\App\Helpers\PermissionHelper::hasPermission('view-overview') ? 'YES' : 'NO') . "\n";
