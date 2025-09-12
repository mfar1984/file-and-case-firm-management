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
        // Update partners table - partner_code should be unique per firm
        Schema::table('partners', function (Blueprint $table) {
            // Drop old global unique constraint
            $table->dropUnique(['partner_code']);
            // Add new composite unique constraint (partner_code + firm_id)
            $table->unique(['partner_code', 'firm_id'], 'partners_partner_code_firm_id_unique');
        });

        // Update clients table - client_code should be unique per firm
        Schema::table('clients', function (Blueprint $table) {
            // Drop old global unique constraint
            $table->dropUnique(['client_code']);
            // Add new composite unique constraint (client_code + firm_id)
            $table->unique(['client_code', 'firm_id'], 'clients_client_code_firm_id_unique');
        });

        // Update quotations table - quotation_no should be unique per firm
        Schema::table('quotations', function (Blueprint $table) {
            // Drop old global unique constraint
            $table->dropUnique(['quotation_no']);
            // Add new composite unique constraint (quotation_no + firm_id)
            $table->unique(['quotation_no', 'firm_id'], 'quotations_quotation_no_firm_id_unique');
        });

        // Update pre_quotations table - quotation_no should be unique per firm
        Schema::table('pre_quotations', function (Blueprint $table) {
            // Drop old global unique constraint
            $table->dropUnique(['quotation_no']);
            // Add new composite unique constraint (quotation_no + firm_id)
            $table->unique(['quotation_no', 'firm_id'], 'pre_quotations_quotation_no_firm_id_unique');
        });

        // Update receipts table - receipt_no should be unique per firm
        Schema::table('receipts', function (Blueprint $table) {
            // Drop old global unique constraint
            $table->dropUnique(['receipt_no']);
            // Add new composite unique constraint (receipt_no + firm_id)
            $table->unique(['receipt_no', 'firm_id'], 'receipts_receipt_no_firm_id_unique');
        });

        // Update vouchers table - voucher_no should be unique per firm
        Schema::table('vouchers', function (Blueprint $table) {
            // Drop old global unique constraint
            $table->dropUnique(['voucher_no']);
            // Add new composite unique constraint (voucher_no + firm_id)
            $table->unique(['voucher_no', 'firm_id'], 'vouchers_voucher_no_firm_id_unique');
        });

        // Update bills table - bill_no should be unique per firm
        Schema::table('bills', function (Blueprint $table) {
            // Drop old global unique constraint
            $table->dropUnique(['bill_no']);
            // Add new composite unique constraint (bill_no + firm_id)
            $table->unique(['bill_no', 'firm_id'], 'bills_bill_no_firm_id_unique');
        });

        // Check if tax_invoices table exists and update if needed
        if (Schema::hasTable('tax_invoices')) {
            Schema::table('tax_invoices', function (Blueprint $table) {
                // Check if invoice_no has unique constraint
                try {
                    $table->dropUnique(['invoice_no']);
                    $table->unique(['invoice_no', 'firm_id'], 'tax_invoices_invoice_no_firm_id_unique');
                } catch (\Exception $e) {
                    // If no unique constraint exists, just add the composite one
                    $table->unique(['invoice_no', 'firm_id'], 'tax_invoices_invoice_no_firm_id_unique');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Reverse partners table changes
        Schema::table('partners', function (Blueprint $table) {
            $table->dropUnique(['partner_code', 'firm_id']);
            $table->unique(['partner_code'], 'partners_partner_code_unique');
        });

        // Reverse clients table changes
        Schema::table('clients', function (Blueprint $table) {
            $table->dropUnique(['client_code', 'firm_id']);
            $table->unique(['client_code'], 'clients_client_code_unique');
        });

        // Reverse quotations table changes
        Schema::table('quotations', function (Blueprint $table) {
            $table->dropUnique(['quotation_no', 'firm_id']);
            $table->unique(['quotation_no'], 'quotations_quotation_no_unique');
        });

        // Reverse pre_quotations table changes
        Schema::table('pre_quotations', function (Blueprint $table) {
            $table->dropUnique(['quotation_no', 'firm_id']);
            $table->unique(['quotation_no'], 'pre_quotations_quotation_no_unique');
        });

        // Reverse receipts table changes
        Schema::table('receipts', function (Blueprint $table) {
            $table->dropUnique(['receipt_no', 'firm_id']);
            $table->unique(['receipt_no'], 'receipts_receipt_no_unique');
        });

        // Reverse vouchers table changes
        Schema::table('vouchers', function (Blueprint $table) {
            $table->dropUnique(['voucher_no', 'firm_id']);
            $table->unique(['voucher_no'], 'vouchers_voucher_no_unique');
        });

        // Reverse bills table changes
        Schema::table('bills', function (Blueprint $table) {
            $table->dropUnique(['bill_no', 'firm_id']);
            $table->unique(['bill_no'], 'bills_bill_no_unique');
        });

        // Reverse tax_invoices table changes if exists
        if (Schema::hasTable('tax_invoices')) {
            Schema::table('tax_invoices', function (Blueprint $table) {
                try {
                    $table->dropUnique(['invoice_no', 'firm_id']);
                    $table->unique(['invoice_no'], 'tax_invoices_invoice_no_unique');
                } catch (\Exception $e) {
                    // Ignore if constraint doesn't exist
                }
            });
        }
    }
};
