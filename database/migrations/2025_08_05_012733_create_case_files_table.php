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
        Schema::create('case_files', function (Blueprint $table) {
            $table->id();
            $table->string('case_ref')->nullable(); // Case reference (C-001, C-002, etc.)
            $table->string('file_name'); // Original file name
            $table->string('file_path'); // Storage path
            $table->string('file_type'); // contract, evidence, correspondence, court_document, invoice, other
            $table->string('file_size'); // File size in bytes/format
            $table->string('mime_type'); // File MIME type
            $table->text('description')->nullable(); // File description
            $table->enum('status', ['IN', 'OUT'])->default('IN'); // File status
            $table->string('taken_by')->nullable(); // Who took the file out
            $table->text('purpose')->nullable(); // Purpose for taking file out
            $table->date('expected_return')->nullable(); // Expected return date
            $table->date('actual_return')->nullable(); // Actual return date
            $table->string('rack_location')->nullable(); // Physical rack location when IN
            $table->timestamps();
            
            // Indexes
            $table->index('case_ref');
            $table->index('file_type');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('case_files');
    }
};
