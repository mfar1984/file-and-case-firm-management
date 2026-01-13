<?php

// Test sidebar permissions
// Run: php database/scripts/test_sidebar.php

require_once __DIR__ . '/../../vendor/autoload.php';

$app = require_once __DIR__ . '/../../bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use App\Helpers\PermissionHelper;

// Login as Admin
$user = User::find(1);
auth()->login($user);

echo "=== Logged in as: " . $user->name . " ===\n";
echo "firm_id: " . $user->firm_id . "\n\n";

echo "=== PermissionHelper Tests ===\n";
echo "view-overview: " . (PermissionHelper::hasPermission('view-overview') ? 'YES' : 'NO') . "\n";
echo "view-calendar: " . (PermissionHelper::hasPermission('view-calendar') ? 'YES' : 'NO') . "\n";
echo "view-cases: " . (PermissionHelper::hasPermission('view-cases') ? 'YES' : 'NO') . "\n";
echo "view-accounting: " . (PermissionHelper::hasPermission('view-accounting') ? 'YES' : 'NO') . "\n";
echo "view-files: " . (PermissionHelper::hasPermission('view-files') ? 'YES' : 'NO') . "\n";
echo "view-settings: " . (PermissionHelper::hasPermission('view-settings') ? 'YES' : 'NO') . "\n";

echo "\n=== hasAnyPermission Test ===\n";
echo "view-cases/clients/partners: " . (PermissionHelper::hasAnyPermission(['view-cases', 'view-clients', 'view-partners']) ? 'YES' : 'NO') . "\n";

echo "\n=== Session Check ===\n";
echo "current_firm_id in session: " . (session('current_firm_id') ?? 'NOT SET') . "\n";
