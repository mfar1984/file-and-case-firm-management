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
        // Check if agencies table exists
        if (!Schema::hasTable('agencies')) {
            return;
        }

        Schema::table('agencies', function (Blueprint $table) {
            // Only add firm_id if it doesn't exist
            if (!Schema::hasColumn('agencies', 'firm_id')) {
                $table->unsignedBigInteger('firm_id')->nullable()->after('status');
                $table->foreign('firm_id')->references('id')->on('firms')->onDelete('cascade');
                $table->index('firm_id');
            }
        });

        // Migrate existing agencies to default firm (firm_id = 1) if firms table exists
        if (Schema::hasTable('firms') && DB::table('firms')->where('id', 1)->exists()) {
            DB::table('agencies')->whereNull('firm_id')->update(['firm_id' => 1]);
        }

        // Make firm_id required after migration if column exists
        if (Schema::hasColumn('agencies', 'firm_id')) {
            Schema::table('agencies', function (Blueprint $table) {
                $table->unsignedBigInteger('firm_id')->nullable(false)->change();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Check if agencies table exists
        if (!Schema::hasTable('agencies')) {
            return;
        }

        Schema::table('agencies', function (Blueprint $table) {
            // Only drop if column exists
            if (Schema::hasColumn('agencies', 'firm_id')) {
                $table->dropForeign(['firm_id']);
                $table->dropIndex(['firm_id']);
                $table->dropColumn('firm_id');
            }
        });
    }
};
