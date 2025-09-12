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
        // Add firm_id to case_parties table
        Schema::table('case_parties', function (Blueprint $table) {
            $table->unsignedBigInteger('firm_id')->nullable()->after('nationality');
            $table->foreign('firm_id')->references('id')->on('firms')->onDelete('cascade');
            $table->index('firm_id');
        });

        // Add firm_id to case_timelines table
        Schema::table('case_timelines', function (Blueprint $table) {
            $table->unsignedBigInteger('firm_id')->nullable()->after('created_by');
            $table->foreign('firm_id')->references('id')->on('firms')->onDelete('cascade');
            $table->index('firm_id');
        });

        // Migrate existing case parties to appropriate firms based on case
        DB::statement("
            UPDATE case_parties
            SET firm_id = (
                SELECT cases.firm_id
                FROM cases
                WHERE cases.id = case_parties.case_id
                LIMIT 1
            )
            WHERE case_parties.firm_id IS NULL
        ");

        // Migrate existing case timelines to appropriate firms based on case
        DB::statement("
            UPDATE case_timelines
            SET firm_id = (
                SELECT cases.firm_id
                FROM cases
                WHERE cases.id = case_timelines.case_id
                LIMIT 1
            )
            WHERE case_timelines.firm_id IS NULL
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('case_parties', function (Blueprint $table) {
            $table->dropForeign(['firm_id']);
            $table->dropIndex(['firm_id']);
            $table->dropColumn('firm_id');
        });

        Schema::table('case_timelines', function (Blueprint $table) {
            $table->dropForeign(['firm_id']);
            $table->dropIndex(['firm_id']);
            $table->dropColumn('firm_id');
        });
    }
};
