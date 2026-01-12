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
        Schema::create('case_initiating_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('section_type_id')->constrained('section_types')->onDelete('cascade');
            $table->string('document_name', 255);
            $table->string('document_code', 100)->nullable(); // for backward compatibility
            $table->integer('sort_order')->default(0);
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->foreignId('firm_id')->constrained('firms')->onDelete('cascade');
            $table->timestamps();

            // Indexes
            $table->index(['section_type_id', 'status']);
            $table->index(['firm_id', 'status']);
            $table->unique(['document_code', 'section_type_id', 'firm_id'], 'unique_document_per_section_firm');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('case_initiating_documents');
    }
};
