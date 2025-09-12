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
        // Add firm_id to pre_quotations table
        Schema::table('pre_quotations', function (Blueprint $table) {
            $table->unsignedBigInteger('firm_id')->nullable()->after('total');
            $table->foreign('firm_id')->references('id')->on('firms')->onDelete('cascade');
            $table->index('firm_id');
        });

        // Add firm_id to quotations table
        Schema::table('quotations', function (Blueprint $table) {
            $table->unsignedBigInteger('firm_id')->nullable()->after('total_words');
            $table->foreign('firm_id')->references('id')->on('firms')->onDelete('cascade');
            $table->index('firm_id');
        });

        // Add firm_id to tax_invoices table
        Schema::table('tax_invoices', function (Blueprint $table) {
            $table->unsignedBigInteger('firm_id')->nullable()->after('status');
            $table->foreign('firm_id')->references('id')->on('firms')->onDelete('cascade');
            $table->index('firm_id');
        });

        // Migrate existing data to default firm (firm_id = 1)
        // Pre-quotations: Set to default firm
        DB::table('pre_quotations')->whereNull('firm_id')->update(['firm_id' => 1]);

        // Quotations: Try to get firm from case, fallback to default
        DB::statement("
            UPDATE quotations 
            SET firm_id = (
                SELECT cases.firm_id 
                FROM cases 
                WHERE cases.id = quotations.case_id
                LIMIT 1
            )
            WHERE quotations.case_id IS NOT NULL 
            AND quotations.firm_id IS NULL
        ");
        
        // Set remaining quotations to default firm
        DB::table('quotations')->whereNull('firm_id')->update(['firm_id' => 1]);

        // Tax Invoices: Try to get firm from case, then quotation, fallback to default
        DB::statement("
            UPDATE tax_invoices 
            SET firm_id = (
                SELECT cases.firm_id 
                FROM cases 
                WHERE cases.id = tax_invoices.case_id
                LIMIT 1
            )
            WHERE tax_invoices.case_id IS NOT NULL 
            AND tax_invoices.firm_id IS NULL
        ");

        DB::statement("
            UPDATE tax_invoices 
            SET firm_id = (
                SELECT quotations.firm_id 
                FROM quotations 
                WHERE quotations.id = tax_invoices.quotation_id
                LIMIT 1
            )
            WHERE tax_invoices.quotation_id IS NOT NULL 
            AND tax_invoices.firm_id IS NULL
        ");

        // Set remaining tax invoices to default firm
        DB::table('tax_invoices')->whereNull('firm_id')->update(['firm_id' => 1]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pre_quotations', function (Blueprint $table) {
            $table->dropForeign(['firm_id']);
            $table->dropIndex(['firm_id']);
            $table->dropColumn('firm_id');
        });

        Schema::table('quotations', function (Blueprint $table) {
            $table->dropForeign(['firm_id']);
            $table->dropIndex(['firm_id']);
            $table->dropColumn('firm_id');
        });

        Schema::table('tax_invoices', function (Blueprint $table) {
            $table->dropForeign(['firm_id']);
            $table->dropIndex(['firm_id']);
            $table->dropColumn('firm_id');
        });
    }
};
