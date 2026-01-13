<?php

// Fix NULL firm_id in model_has_roles table
// Run: php artisan tinker database/scripts/fix_role_firm_id.php

require_once __DIR__ . '/../../vendor/autoload.php';

$app = require_once __DIR__ . '/../../bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;
use App\Models\User;

$pivots = DB::table('model_has_roles')
    ->whereNull('firm_id')
    ->where('model_type', 'App\Models\User')
    ->get();

echo "Found " . $pivots->count() . " with NULL firm_id\n";

foreach ($pivots as $p) {
    $user = User::find($p->model_id);
    if ($user && $user->firm_id) {
        DB::table('model_has_roles')
            ->where('model_id', $p->model_id)
            ->where('model_type', 'App\Models\User')
            ->where('role_id', $p->role_id)
            ->update(['firm_id' => $user->firm_id]);
        echo "Fixed: " . $user->name . " -> firm_id: " . $user->firm_id . "\n";
    }
}

app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
echo "Done!\n";
