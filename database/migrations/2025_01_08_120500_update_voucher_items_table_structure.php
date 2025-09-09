<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('voucher_items', function (Blueprint $table) {
            // Add missing columns
            $table->decimal('qty', 12, 2)->default(1)->after('category');
            $table->string('uom', 32)->default('lot')->after('qty');
            $table->decimal('unit_price', 12, 2)->default(0)->after('uom');
            $table->decimal('discount_percent', 5, 2)->default(0)->after('unit_price');
            $table->decimal('tax_percent', 5, 2)->default(0)->after('discount_percent');
        });
    }

    public function down(): void
    {
        Schema::table('voucher_items', function (Blueprint $table) {
            $table->dropColumn(['qty', 'uom', 'unit_price', 'discount_percent', 'tax_percent']);
        });
    }
};
