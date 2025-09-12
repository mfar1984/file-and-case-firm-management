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
        // Drop the global unique constraint on email
        Schema::table('users', function (Blueprint $table) {
            $table->dropUnique(['email']);
        });

        // Add composite unique constraint: email + firm_id
        Schema::table('users', function (Blueprint $table) {
            $table->unique(['email', 'firm_id'], 'users_email_firm_unique');
            $table->index(['email', 'firm_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop the composite unique constraint
        Schema::table('users', function (Blueprint $table) {
            $table->dropUnique('users_email_firm_unique');
            $table->dropIndex(['email', 'firm_id']);
        });

        // Restore the global unique constraint on email
        Schema::table('users', function (Blueprint $table) {
            $table->unique('email');
        });
    }
};
