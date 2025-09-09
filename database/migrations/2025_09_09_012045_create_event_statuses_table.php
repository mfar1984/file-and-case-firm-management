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
        Schema::create('event_statuses', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique(); // Status name: Completed, On Going, etc.
            $table->text('description')->nullable(); // Description of the status
            $table->string('background_color', 50)->default('bg-blue-500'); // Tailwind CSS class
            $table->string('icon', 50)->default('circle'); // Material icon name
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->integer('sort_order')->default(0); // Display order
            $table->timestamps();

            // Indexes
            $table->index('name');
            $table->index('status');
            $table->index('sort_order');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('event_statuses');
    }
};
