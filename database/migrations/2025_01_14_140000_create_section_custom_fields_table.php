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
        Schema::create('section_custom_fields', function (Blueprint $table) {
            $table->id();
            $table->foreignId('section_type_id')->constrained('section_types')->onDelete('cascade');
            $table->string('field_name', 255); // Label for the field
            $table->enum('field_type', ['text', 'number', 'dropdown', 'checkbox', 'date', 'time', 'datetime'])->default('text');
            $table->string('placeholder', 255)->nullable();
            $table->json('field_options')->nullable(); // For dropdown options, checkbox options
            $table->boolean('is_required')->default(false);
            $table->integer('sort_order')->default(0);
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->foreignId('firm_id')->constrained('firms')->onDelete('cascade');
            $table->timestamps();

            // Indexes
            $table->index(['section_type_id', 'status']);
            $table->index(['firm_id', 'status']);
            $table->index(['field_type', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('section_custom_fields');
    }
};
