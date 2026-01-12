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
        Schema::create('case_custom_field_values', function (Blueprint $table) {
            $table->id();
            $table->foreignId('case_id')->constrained('cases')->onDelete('cascade');
            $table->foreignId('custom_field_id')->constrained('section_custom_fields')->onDelete('cascade');
            $table->text('field_value')->nullable(); // Store as text to handle all field types
            $table->timestamps();

            // Indexes
            $table->index(['case_id']);
            $table->index(['custom_field_id']);
            $table->unique(['case_id', 'custom_field_id'], 'unique_case_custom_field');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('case_custom_field_values');
    }
};
