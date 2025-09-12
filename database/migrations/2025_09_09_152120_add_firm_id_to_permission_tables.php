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
        // Add firm_id to roles table
        Schema::table('roles', function (Blueprint $table) {
            $table->foreignId('firm_id')->nullable()->constrained('firms')->onDelete('cascade');
            $table->index('firm_id');
        });

        // Add firm_id to model_has_roles table
        Schema::table('model_has_roles', function (Blueprint $table) {
            $table->foreignId('firm_id')->nullable()->constrained('firms')->onDelete('cascade');
            $table->index('firm_id');
        });

        // Add firm_id to model_has_permissions table
        Schema::table('model_has_permissions', function (Blueprint $table) {
            $table->foreignId('firm_id')->nullable()->constrained('firms')->onDelete('cascade');
            $table->index('firm_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('roles', function (Blueprint $table) {
            $table->dropForeign(['firm_id']);
            $table->dropColumn('firm_id');
        });

        Schema::table('model_has_roles', function (Blueprint $table) {
            $table->dropForeign(['firm_id']);
            $table->dropColumn('firm_id');
        });

        Schema::table('model_has_permissions', function (Blueprint $table) {
            $table->dropForeign(['firm_id']);
            $table->dropColumn('firm_id');
        });
    }
};
