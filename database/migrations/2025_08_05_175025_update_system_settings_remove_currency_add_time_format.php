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
        Schema::table('system_settings', function (Blueprint $table) {
            // Remove default_currency column
            $table->dropColumn('default_currency');
            
            // Add time_format column
            $table->string('time_format', 20)->default('h:i:s A')->after('date_format');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('system_settings', function (Blueprint $table) {
            // Add back default_currency column
            $table->string('default_currency', 10)->default('MYR')->after('id');
            
            // Remove time_format column
            $table->dropColumn('time_format');
        });
    }
};
