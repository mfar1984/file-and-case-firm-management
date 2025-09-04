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
        Schema::table('quotation_items', function (Blueprint $table) {
            // Rename discount_percent to discount_amount and change type
            $table->renameColumn('discount_percent', 'discount_amount');
        });

        // Update the column type in a separate statement
        Schema::table('quotation_items', function (Blueprint $table) {
            $table->decimal('discount_amount', 10, 2)->default(0)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('quotation_items', function (Blueprint $table) {
            $table->decimal('discount_percent', 5, 2)->default(0)->change();
        });

        Schema::table('quotation_items', function (Blueprint $table) {
            $table->renameColumn('discount_amount', 'discount_percent');
        });
    }
};
