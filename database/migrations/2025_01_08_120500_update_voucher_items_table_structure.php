<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // Check if voucher_items table exists
        if (!Schema::hasTable('voucher_items')) {
            return;
        }

        Schema::table('voucher_items', function (Blueprint $table) {
            // Add missing columns if they don't exist
            if (!Schema::hasColumn('voucher_items', 'qty')) {
                $table->decimal('qty', 12, 2)->default(1)->after('category');
            }
            if (!Schema::hasColumn('voucher_items', 'uom')) {
                $table->string('uom', 32)->default('lot')->after('qty');
            }
            if (!Schema::hasColumn('voucher_items', 'unit_price')) {
                $table->decimal('unit_price', 12, 2)->default(0)->after('uom');
            }
            if (!Schema::hasColumn('voucher_items', 'discount_percent')) {
                $table->decimal('discount_percent', 5, 2)->default(0)->after('unit_price');
            }
            if (!Schema::hasColumn('voucher_items', 'tax_percent')) {
                $table->decimal('tax_percent', 5, 2)->default(0)->after('discount_percent');
            }
        });
    }

    public function down(): void
    {
        // Check if voucher_items table exists
        if (!Schema::hasTable('voucher_items')) {
            return;
        }

        Schema::table('voucher_items', function (Blueprint $table) {
            // Drop columns if they exist
            $columnsToRemove = ['qty', 'uom', 'unit_price', 'discount_percent', 'tax_percent'];
            foreach ($columnsToRemove as $column) {
                if (Schema::hasColumn('voucher_items', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
