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
        Schema::table('opening_balances', function (Blueprint $table) {
            // Drop the old unique constraint on bank_code only
            $table->dropUnique(['bank_code']);

            // Add new unique constraint on bank_code + firm_id
            $table->unique(['bank_code', 'firm_id'], 'opening_balances_bank_code_firm_id_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('opening_balances', function (Blueprint $table) {
            // Drop the compound unique constraint
            $table->dropUnique('opening_balances_bank_code_firm_id_unique');

            // Restore the old unique constraint on bank_code only
            $table->unique('bank_code');
        });
    }
};
