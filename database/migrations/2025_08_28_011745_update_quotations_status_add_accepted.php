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
        Schema::table('quotations', function (Blueprint $table) {
            // First, drop the existing status column
            $table->dropColumn('status');
        });

        Schema::table('quotations', function (Blueprint $table) {
            // Add new enum status column with proper values
            $table->enum('status', ['pending', 'accepted', 'rejected', 'converted', 'expired', 'cancelled'])->default('pending')->after('remark');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('quotations', function (Blueprint $table) {
            // Drop the enum status column
            $table->dropColumn('status');
        });

        Schema::table('quotations', function (Blueprint $table) {
            // Add back the old enum status column
            $table->enum('status', ['pending', 'approved', 'converted', 'expired', 'cancelled'])->default('pending')->after('remark');
        });
    }
};
