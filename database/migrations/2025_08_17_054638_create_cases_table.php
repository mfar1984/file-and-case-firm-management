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
        Schema::create('cases', function (Blueprint $table) {
            $table->id();
            $table->string('case_ref')->unique(); // Case Reference
            $table->string('file_ref')->unique(); // File Reference
            $table->string('court_ref')->nullable(); // Court Reference
            $table->string('case_type')->nullable(); // Case Type
            $table->string('case_status')->default('pending'); // Case Status
            $table->text('case_title'); // Case Title
            $table->text('case_description')->nullable(); // Case Description
            $table->decimal('claim_amount', 15, 2)->nullable(); // Claim Amount
            $table->string('currency', 3)->default('MYR'); // Currency
            $table->date('filing_date')->nullable(); // Filing Date
            $table->date('hearing_date')->nullable(); // Hearing Date
            $table->string('court_name')->nullable(); // Court Name
            $table->string('judge_name')->nullable(); // Judge Name
            $table->text('notes')->nullable(); // Notes
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cases');
    }
};
