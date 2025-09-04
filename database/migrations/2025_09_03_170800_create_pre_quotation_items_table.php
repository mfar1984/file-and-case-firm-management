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
        Schema::create('pre_quotation_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pre_quotation_id')->constrained('pre_quotations')->cascadeOnDelete();
            $table->text('description')->nullable();
            $table->decimal('qty', 12, 2)->default(1);
            $table->string('uom', 32)->default('lot');
            $table->decimal('unit_price', 12, 2)->default(0);
            $table->decimal('discount_percent', 5, 2)->default(0);
            $table->decimal('tax_percent', 5, 2)->default(0);
            $table->decimal('amount', 12, 2)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pre_quotation_items');
    }
};
