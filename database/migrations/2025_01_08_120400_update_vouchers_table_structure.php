<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // Check if vouchers table exists
        if (!Schema::hasTable('vouchers')) {
            return;
        }

        Schema::table('vouchers', function (Blueprint $table) {
            // Drop payee_id column if it exists
            if (Schema::hasColumn('vouchers', 'payee_id')) {
                $table->dropColumn('payee_id');
            }

            // Add payee_name if it doesn't exist
            if (!Schema::hasColumn('vouchers', 'payee_name')) {
                $table->string('payee_name')->after('voucher_no');
            }

            // Add missing columns if they don't exist
            if (!Schema::hasColumn('vouchers', 'email')) {
                $table->string('email')->nullable()->after('phone');
            }
            if (!Schema::hasColumn('vouchers', 'subtotal')) {
                $table->decimal('subtotal', 12, 2)->default(0)->after('remark');
            }
            if (!Schema::hasColumn('vouchers', 'tax_total')) {
                $table->decimal('tax_total', 12, 2)->default(0)->after('subtotal');
            }
            if (!Schema::hasColumn('vouchers', 'total_words')) {
                $table->string('total_words')->nullable()->after('total_amount');
            }
            if (!Schema::hasColumn('vouchers', 'status')) {
                $table->enum('status', ['draft', 'pending', 'approved', 'paid', 'cancelled'])->default('draft')->after('total_words');
            }

            // Update payment_method to enum if column exists
            if (Schema::hasColumn('vouchers', 'payment_method')) {
                $table->enum('payment_method', ['cash', 'cheque', 'bank_transfer', 'online_banking', 'credit_card'])->default('cash')->change();
            }
        });
    }

    public function down(): void
    {
        // Check if vouchers table exists
        if (!Schema::hasTable('vouchers')) {
            return;
        }

        Schema::table('vouchers', function (Blueprint $table) {
            // Drop columns if they exist
            $columnsToRemove = ['payee_name', 'email', 'subtotal', 'tax_total', 'total_words', 'status'];
            foreach ($columnsToRemove as $column) {
                if (Schema::hasColumn('vouchers', $column)) {
                    $table->dropColumn($column);
                }
            }

            // Add payee_id back if it doesn't exist
            if (!Schema::hasColumn('vouchers', 'payee_id')) {
                $table->unsignedBigInteger('payee_id')->after('voucher_no');
            }

            // Revert payment_method if column exists
            if (Schema::hasColumn('vouchers', 'payment_method')) {
                $table->string('payment_method')->change();
            }
        });
    }
};
