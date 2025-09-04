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
            // Add missing columns
            $table->string('case_number')->nullable()->after('id');
            $table->unsignedBigInteger('case_type_id')->nullable()->after('case_type');
            $table->unsignedBigInteger('case_status_id')->nullable()->after('case_status');
            $table->string('priority_level')->default('medium')->after('case_status_id');
            $table->string('judge_name')->nullable()->after('court_name');
            $table->unsignedBigInteger('created_by')->nullable()->after('judge_name');
            $table->unsignedBigInteger('assigned_to')->nullable()->after('created_by');
            
            // Add foreign key constraints
            $table->foreign('case_type_id')->references('id')->on('case_types')->onDelete('set null');
            $table->foreign('case_status_id')->references('id')->on('case_statuses')->onDelete('set null');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('assigned_to')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cases', function (Blueprint $table) {
            // Drop foreign keys first
            $table->dropForeign(['case_type_id']);
            $table->dropForeign(['case_status_id']);
            $table->dropForeign(['created_by']);
            $table->dropForeign(['assigned_to']);
            
            // Drop columns
            $table->dropColumn([
                'case_number',
                'case_type_id', 
                'case_status_id',
                'priority_level',
                'judge_name',
                'created_by',
                'assigned_to'
            ]);
        });
    }
};
