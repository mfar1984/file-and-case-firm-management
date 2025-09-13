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
        Schema::table('tax_categories', function (Blueprint $table) {
            // Remove the unique constraint that prevents duplicate names within the same firm
            $table->dropUnique('tax_categories_name_firm_id_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tax_categories', function (Blueprint $table) {
            // Restore the unique constraint
            $table->unique(['name', 'firm_id'], 'tax_categories_name_firm_id_unique');
        });
    }
};
