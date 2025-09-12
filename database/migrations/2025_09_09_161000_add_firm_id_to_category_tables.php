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
        // Add firm_id to case_types table
        Schema::table('case_types', function (Blueprint $table) {
            $table->unsignedBigInteger('firm_id')->nullable()->after('status');
            $table->foreign('firm_id')->references('id')->on('firms')->onDelete('cascade');
            $table->index('firm_id');
        });

        // Add firm_id to case_statuses table
        Schema::table('case_statuses', function (Blueprint $table) {
            $table->unsignedBigInteger('firm_id')->nullable()->after('status');
            $table->foreign('firm_id')->references('id')->on('firms')->onDelete('cascade');
            $table->index('firm_id');
        });

        // Add firm_id to file_types table
        Schema::table('file_types', function (Blueprint $table) {
            $table->unsignedBigInteger('firm_id')->nullable()->after('status');
            $table->foreign('firm_id')->references('id')->on('firms')->onDelete('cascade');
            $table->index('firm_id');
        });

        // Add firm_id to specializations table
        Schema::table('specializations', function (Blueprint $table) {
            $table->unsignedBigInteger('firm_id')->nullable()->after('status');
            $table->foreign('firm_id')->references('id')->on('firms')->onDelete('cascade');
            $table->index('firm_id');
        });

        // Add firm_id to event_statuses table
        Schema::table('event_statuses', function (Blueprint $table) {
            $table->unsignedBigInteger('firm_id')->nullable()->after('sort_order');
            $table->foreign('firm_id')->references('id')->on('firms')->onDelete('cascade');
            $table->index('firm_id');
        });

        // Add firm_id to payees table
        Schema::table('payees', function (Blueprint $table) {
            $table->unsignedBigInteger('firm_id')->nullable()->after('is_active');
            $table->foreign('firm_id')->references('id')->on('firms')->onDelete('cascade');
            $table->index('firm_id');
        });

        // Migrate existing data to default firm (firm_id = 1)
        DB::table('case_types')->whereNull('firm_id')->update(['firm_id' => 1]);
        DB::table('case_statuses')->whereNull('firm_id')->update(['firm_id' => 1]);
        DB::table('file_types')->whereNull('firm_id')->update(['firm_id' => 1]);
        DB::table('specializations')->whereNull('firm_id')->update(['firm_id' => 1]);
        DB::table('event_statuses')->whereNull('firm_id')->update(['firm_id' => 1]);
        DB::table('payees')->whereNull('firm_id')->update(['firm_id' => 1]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove firm_id from case_types table
        Schema::table('case_types', function (Blueprint $table) {
            $table->dropForeign(['firm_id']);
            $table->dropIndex(['firm_id']);
            $table->dropColumn('firm_id');
        });

        // Remove firm_id from case_statuses table
        Schema::table('case_statuses', function (Blueprint $table) {
            $table->dropForeign(['firm_id']);
            $table->dropIndex(['firm_id']);
            $table->dropColumn('firm_id');
        });

        // Remove firm_id from file_types table
        Schema::table('file_types', function (Blueprint $table) {
            $table->dropForeign(['firm_id']);
            $table->dropIndex(['firm_id']);
            $table->dropColumn('firm_id');
        });

        // Remove firm_id from specializations table
        Schema::table('specializations', function (Blueprint $table) {
            $table->dropForeign(['firm_id']);
            $table->dropIndex(['firm_id']);
            $table->dropColumn('firm_id');
        });

        // Remove firm_id from event_statuses table
        Schema::table('event_statuses', function (Blueprint $table) {
            $table->dropForeign(['firm_id']);
            $table->dropIndex(['firm_id']);
            $table->dropColumn('firm_id');
        });

        // Remove firm_id from payees table
        Schema::table('payees', function (Blueprint $table) {
            $table->dropForeign(['firm_id']);
            $table->dropIndex(['firm_id']);
            $table->dropColumn('firm_id');
        });
    }
};
