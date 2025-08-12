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
        Schema::create('file_types', function (Blueprint $table) {
            $table->id();
            $table->string('code', 20)->unique(); // e.g., PDF, DOCX, XLSX, IMG
            $table->string('description'); // e.g., PDF Document, Word Document
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();

            $table->index('code');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('file_types');
    }
};
