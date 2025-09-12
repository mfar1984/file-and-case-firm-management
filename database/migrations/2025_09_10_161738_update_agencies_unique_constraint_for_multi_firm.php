<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Update agencies table unique constraint for multi-firm support
        Schema::table('agencies', function (Blueprint $table) {
            // Drop old global unique constraint
            $table->dropUnique(['name']);
            // Add new composite unique constraint (name + firm_id)
            $table->unique(['name', 'firm_id'], 'agencies_name_firm_id_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Reverse agencies table changes
        Schema::table('agencies', function (Blueprint $table) {
            // Drop composite unique constraint
            $table->dropUnique(['name', 'firm_id']);
            // Restore global unique constraint
            $table->unique(['name'], 'agencies_name_unique');
        });
    }
};
