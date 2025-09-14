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
        Schema::create('section_types', function (Blueprint $table) {
            $table->id();
            $table->string('code', 10); // CA, CR, PB, CVY
            $table->string('name', 100); // Civil, Criminal, etc.
            $table->text('description')->nullable(); // Detailed description
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->unsignedBigInteger('firm_id')->nullable();
            $table->timestamps();
            
            // Foreign key
            $table->foreign('firm_id')->references('id')->on('firms')->onDelete('cascade');
            
            // Indexes
            $table->index('code');
            $table->index('name');
            $table->index('status');
            $table->index('firm_id');
            
            // Unique constraint per firm
            $table->unique(['code', 'firm_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('section_types');
    }
};
