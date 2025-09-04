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
        Schema::create('case_timelines', function (Blueprint $table) {
            $table->id();
            $table->foreignId('case_id')->constrained('cases')->onDelete('cascade');
            $table->string('event_type'); // 'case_created', 'case_filed', 'hearing_scheduled', 'case_review', etc.
            $table->string('title'); // Event title
            $table->text('description'); // Event description
            $table->string('status'); // 'completed', 'active', 'pending', 'cancelled'
            $table->timestamp('event_date'); // When the event occurred
            $table->json('metadata')->nullable(); // Additional data like judge name, court info, etc.
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
            
            // Indexes for better performance
            $table->index(['case_id', 'event_date']);
            $table->index(['status', 'event_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('case_timelines');
    }
};
