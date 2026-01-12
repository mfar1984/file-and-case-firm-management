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
        Schema::create('api_security_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('firm_id')->constrained()->onDelete('cascade');
            
            // Rate Limiting
            $table->integer('rate_limit_per_minute')->default(60);
            $table->integer('rate_limit_per_hour')->default(1000);
            
            // CORS Settings
            $table->text('allowed_origins')->nullable(); // JSON array of allowed origins
            $table->boolean('cors_enabled')->default(true);
            
            // IP Security
            $table->boolean('ip_blacklist_enabled')->default(true);
            $table->integer('auto_blacklist_threshold')->default(10); // Failed attempts before auto-blacklist
            $table->integer('blacklist_duration_hours')->default(24);
            
            // Request Logging
            $table->boolean('log_all_requests')->default(true);
            $table->boolean('log_failed_attempts')->default(true);
            
            // API Status
            $table->boolean('api_enabled')->default(true);
            
            $table->timestamps();
            
            // Ensure one setting per firm
            $table->unique('firm_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('api_security_settings');
    }
};

