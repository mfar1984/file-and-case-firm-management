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
        Schema::table('case_files', function (Blueprint $table) {
            $table->unsignedBigInteger('firm_id')->nullable()->after('rack_location');
            $table->foreign('firm_id')->references('id')->on('firms')->onDelete('cascade');
            $table->index('firm_id');
        });

        // Migrate existing case files to appropriate firms based on case_ref
        DB::statement("
            UPDATE case_files 
            SET firm_id = (
                SELECT cases.firm_id 
                FROM cases 
                WHERE cases.case_number = case_files.case_ref
                LIMIT 1
            )
            WHERE case_files.case_ref IS NOT NULL 
            AND case_files.firm_id IS NULL
        ");

        // Set default firm for files without case_ref
        DB::table('case_files')->whereNull('firm_id')->update(['firm_id' => 1]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('case_files', function (Blueprint $table) {
            $table->dropForeign(['firm_id']);
            $table->dropIndex(['firm_id']);
            $table->dropColumn('firm_id');
        });
    }
};
