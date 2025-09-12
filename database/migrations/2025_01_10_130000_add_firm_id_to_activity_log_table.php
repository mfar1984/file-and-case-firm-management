<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $tableName = config('activitylog.table_name', 'activity_log');
        
        // Check if activity_log table exists
        if (!Schema::hasTable($tableName)) {
            return;
        }

        Schema::table($tableName, function (Blueprint $table) use ($tableName) {
            // Only add firm_id if it doesn't exist
            if (!Schema::hasColumn($tableName, 'firm_id')) {
                $table->unsignedBigInteger('firm_id')->nullable()->after('causer_id');
                $table->foreign('firm_id')->references('id')->on('firms')->onDelete('set null');
                $table->index('firm_id');
            }
        });

        // Migrate existing activity logs to default firm (firm_id = 1) if firms table exists
        if (Schema::hasTable('firms') && DB::table('firms')->where('id', 1)->exists()) {
            // Update activities that have a causer (user) - assign to user's firm
            DB::statement("
                UPDATE {$tableName} 
                SET firm_id = (
                    SELECT users.firm_id 
                    FROM users 
                    WHERE users.id = {$tableName}.causer_id 
                    AND {$tableName}.causer_type = 'App\\\\Models\\\\User'
                    LIMIT 1
                )
                WHERE causer_type = 'App\\\\Models\\\\User' 
                AND causer_id IS NOT NULL 
                AND firm_id IS NULL
            ");
            
            // Update remaining activities without causer to default firm
            DB::table($tableName)->whereNull('firm_id')->update(['firm_id' => 1]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $tableName = config('activitylog.table_name', 'activity_log');
        
        // Check if activity_log table exists
        if (!Schema::hasTable($tableName)) {
            return;
        }

        Schema::table($tableName, function (Blueprint $table) use ($tableName) {
            // Only drop if column exists
            if (Schema::hasColumn($tableName, 'firm_id')) {
                $table->dropForeign(['firm_id']);
                $table->dropIndex(['firm_id']);
                $table->dropColumn('firm_id');
            }
        });
    }
};
