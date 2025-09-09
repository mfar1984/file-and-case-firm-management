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
        Schema::table('expense_categories', function (Blueprint $table) {
            $table->integer('sort_order')->default(0)->after('status');
            $table->index('sort_order');
            $table->index(['status', 'sort_order']);
        });

        // Update existing records with sort order based on name
        DB::statement("
            UPDATE expense_categories
            SET sort_order = CASE
                WHEN name = 'Utilities' THEN 10
                WHEN name = 'Rent' THEN 20
                WHEN name = 'Salary' THEN 30
                WHEN name = 'Internet' THEN 40
                WHEN name = 'Supplies' THEN 50
                WHEN name = 'Maintenance' THEN 60
                WHEN name = 'Insurance' THEN 70
                WHEN name = 'Marketing' THEN 80
                WHEN name = 'Other' THEN 90
                ELSE 100
            END
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('expense_categories', function (Blueprint $table) {
            $table->dropIndex(['expense_categories_status_sort_order_index']);
            $table->dropIndex(['expense_categories_sort_order_index']);
            $table->dropColumn('sort_order');
        });
    }
};
