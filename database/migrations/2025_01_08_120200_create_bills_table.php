<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('bills', function (Blueprint $table) {
            $table->id();
            $table->string('bill_no')->unique();
            $table->string('vendor_name');
            $table->text('vendor_address')->nullable();
            $table->string('vendor_phone')->nullable();
            $table->string('vendor_email')->nullable();
            $table->date('bill_date');
            $table->date('due_date');
            $table->date('payment_date')->nullable();
            $table->string('category')->nullable();
            $table->text('description')->nullable();
            $table->decimal('subtotal', 12, 2)->default(0);
            $table->decimal('tax_total', 12, 2)->default(0);
            $table->decimal('total_amount', 12, 2)->default(0);
            $table->string('total_words')->nullable();
            $table->enum('payment_method', ['cash', 'cheque', 'bank_transfer', 'online_banking', 'credit_card'])->nullable();
            $table->string('payment_reference')->nullable();
            $table->text('remark')->nullable();
            $table->enum('status', ['draft', 'pending', 'overdue', 'paid', 'cancelled'])->default('draft');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bills');
    }
};
