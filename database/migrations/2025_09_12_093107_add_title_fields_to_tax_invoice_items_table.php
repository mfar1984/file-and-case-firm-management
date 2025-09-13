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
        Schema::table('tax_invoice_items', function (Blueprint $table) {
            $table->enum('item_type', ['item', 'title'])->default('item')->after('tax_invoice_id');
            $table->text('title_text')->nullable()->after('item_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tax_invoice_items', function (Blueprint $table) {
            $table->dropColumn(['item_type', 'title_text']);
        });
    }
};
