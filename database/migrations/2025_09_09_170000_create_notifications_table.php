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
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('firm_id');
            $table->string('type'); // calendar_reminder, case_update, document_upload, etc.
            $table->string('title');
            $table->text('message');
            $table->string('icon')->nullable(); // Material Icons name
            $table->string('url')->nullable(); // Link to relevant page
            $table->string('notifiable_type')->nullable(); // CalendarEvent, Case, etc.
            $table->unsignedBigInteger('notifiable_id')->nullable();
            $table->timestamp('read_at')->nullable();
            $table->timestamp('scheduled_at')->nullable(); // For scheduled notifications
            $table->timestamp('sent_at')->nullable(); // When notification was actually sent
            $table->json('data')->nullable(); // Additional data
            $table->timestamps();

            // Foreign keys
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('firm_id')->references('id')->on('firms')->onDelete('cascade');

            // Indexes
            $table->index(['user_id', 'firm_id']);
            $table->index(['user_id', 'read_at']);
            $table->index(['scheduled_at', 'sent_at']);
            $table->index(['notifiable_type', 'notifiable_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
