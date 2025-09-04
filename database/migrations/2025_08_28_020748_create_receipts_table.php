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
        Schema::create('receipts', function (Blueprint $table) {
            $table->id();
            $table->string('receipt_no')->unique(); // RCP-00001
            $table->unsignedBigInteger('case_id')->nullable(); // Reference to case
            $table->unsignedBigInteger('quotation_id')->nullable(); // Reference to quotation
            $table->unsignedBigInteger('tax_invoice_id')->nullable(); // Reference to tax invoice
            $table->date('receipt_date'); // Date payment received
            $table->string('payment_reference')->nullable(); // Customer payment reference
            $table->enum('payment_method', ['cash', 'bank_transfer', 'cheque', 'credit_card', 'online_payment', 'other']); // Payment method
            $table->string('bank_name')->nullable(); // Bank name for transfers
            $table->string('cheque_number')->nullable(); // Cheque number
            $table->string('transaction_id')->nullable(); // Online transaction ID
            $table->decimal('amount_paid', 10, 2); // Amount received
            $table->decimal('outstanding_balance', 10, 2)->default(0); // Remaining balance
            $table->text('payment_notes')->nullable(); // Additional payment notes
            $table->enum('status', ['draft', 'confirmed', 'cancelled'])->default('draft');
            $table->timestamps();
            
            // Foreign key constraints
            $table->foreign('case_id')->references('id')->on('cases')->onDelete('set null');
            $table->foreign('quotation_id')->references('id')->on('quotations')->onDelete('set null');
            $table->foreign('tax_invoice_id')->references('id')->on('tax_invoices')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('receipts');
    }
};
