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
        Schema::table('tax_invoices', function (Blueprint $table) {
            // First, drop the existing status column
            $table->dropColumn('status');
        });

        Schema::table('tax_invoices', function (Blueprint $table) {
            // Add new enum status column with partially_paid
            $table->enum('status', ['draft', 'sent', 'partially_paid', 'paid', 'overdue', 'cancelled'])->default('draft')->after('total');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tax_invoices', function (Blueprint $table) {
            // Drop the enum status column
            $table->dropColumn('status');
        });

        Schema::table('tax_invoices', function (Blueprint $table) {
            // Add back the old enum status column
            $table->enum('status', ['draft', 'sent', 'paid', 'overdue', 'cancelled'])->default('draft')->after('total');
        });
    }
};
