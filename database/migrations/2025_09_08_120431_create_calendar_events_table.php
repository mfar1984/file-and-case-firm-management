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
        Schema::create('calendar_events', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->datetime('start_date');
            $table->datetime('end_date');
            $table->string('location')->nullable();
            $table->string('category')->default('other');
            $table->integer('reminder_minutes')->nullable();
            $table->unsignedBigInteger('case_id')->nullable();
            $table->unsignedBigInteger('timeline_event_id')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->enum('status', ['active', 'completed', 'cancelled'])->default('active');
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('case_id')->references('id')->on('cases')->onDelete('cascade');
            $table->foreign('timeline_event_id')->references('id')->on('case_timelines')->onDelete('cascade');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');

            // Indexes
            $table->index(['start_date', 'end_date']);
            $table->index('category');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('calendar_events');
    }
};
