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
        Schema::create('pre_quotations', function (Blueprint $table) {
            $table->id();
            $table->string('quotation_no')->unique();
            $table->date('quotation_date');
            $table->date('valid_until')->nullable();
            $table->string('payment_terms')->nullable();
            $table->string('full_name')->nullable(); // Replace case_id with full_name
            $table->string('customer_phone')->nullable();
            $table->string('customer_email')->nullable();
            $table->text('customer_address')->nullable();
            $table->decimal('subtotal', 12, 2)->default(0);
            $table->decimal('discount_total', 12, 2)->default(0);
            $table->decimal('tax_total', 12, 2)->default(0);
            $table->decimal('total', 12, 2)->default(0);
            $table->text('remark')->nullable();
            $table->enum('status', ['pending', 'accepted', 'rejected', 'converted', 'expired', 'cancelled'])->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pre_quotations');
    }
};
