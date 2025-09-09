<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('vouchers', function (Blueprint $table) {
            // Drop payee_id column and add payee_name
            $table->dropColumn('payee_id');
            $table->string('payee_name')->after('voucher_no');
            
            // Add missing columns
            $table->string('email')->nullable()->after('phone');
            $table->decimal('subtotal', 12, 2)->default(0)->after('remark');
            $table->decimal('tax_total', 12, 2)->default(0)->after('subtotal');
            $table->string('total_words')->nullable()->after('total_amount');
            $table->enum('status', ['draft', 'pending', 'approved', 'paid', 'cancelled'])->default('draft')->after('total_words');
            
            // Update payment_method to enum
            $table->enum('payment_method', ['cash', 'cheque', 'bank_transfer', 'online_banking', 'credit_card'])->default('cash')->change();
        });
    }

    public function down(): void
    {
        Schema::table('vouchers', function (Blueprint $table) {
            // Reverse the changes
            $table->dropColumn(['payee_name', 'email', 'subtotal', 'tax_total', 'total_words', 'status']);
            $table->unsignedBigInteger('payee_id')->after('voucher_no');
            $table->string('payment_method')->change();
        });
    }
};
