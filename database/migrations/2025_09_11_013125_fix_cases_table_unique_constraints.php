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
        Schema::table('cases', function (Blueprint $table) {
            // Drop the existing global unique constraint
            $table->dropUnique(['case_number']);

            // Add composite unique constraint for case_number + firm_id
            $table->unique(['case_number', 'firm_id'], 'cases_case_number_firm_id_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cases', function (Blueprint $table) {
            // Drop the composite unique constraint
            $table->dropUnique(['case_number', 'firm_id']);

            // Restore the original global unique constraint
            $table->unique(['case_number'], 'cases_case_number_unique');
        });
    }
};
