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
        // Only update tables that need fixing

        // Update event_statuses table - has global unique constraint
        Schema::table('event_statuses', function (Blueprint $table) {
            // Drop old unique constraint
            $table->dropUnique(['name']);
            // Add new composite unique constraint
            $table->unique(['name', 'firm_id'], 'event_statuses_name_firm_id_unique');
        });

        // Update expense_categories table - add unique constraint
        Schema::table('expense_categories', function (Blueprint $table) {
            // Add new composite unique constraint
            $table->unique(['name', 'firm_id'], 'expense_categories_name_firm_id_unique');
        });

        // Note: case_types, case_statuses, file_types already have composite unique constraints
        // specializations doesn't need unique constraint
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Reverse event_statuses table
        Schema::table('event_statuses', function (Blueprint $table) {
            $table->dropUnique(['name', 'firm_id']);
            $table->unique(['name']);
        });

        // Reverse expense_categories table
        Schema::table('expense_categories', function (Blueprint $table) {
            $table->dropUnique(['name', 'firm_id']);
        });
    }
};
