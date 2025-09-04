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
        Schema::create('tax_invoice_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tax_invoice_id');
            $table->text('description')->nullable();
            $table->decimal('qty', 10, 2)->default(1);
            $table->string('uom', 32)->default('lot');
            $table->decimal('unit_price', 15, 2)->default(0);
            $table->decimal('discount_percent', 5, 2)->default(0);
            $table->decimal('tax_percent', 5, 2)->default(0);
            $table->decimal('amount', 15, 2)->default(0);
            $table->timestamps();
            
            $table->foreign('tax_invoice_id')->references('id')->on('tax_invoices')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tax_invoice_items');
    }
};
