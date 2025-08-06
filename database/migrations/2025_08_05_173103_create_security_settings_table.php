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
        Schema::create('security_settings', function (Blueprint $table) {
            $table->id();
            $table->boolean('two_factor_auth')->default(false);
            $table->boolean('password_expiry')->default(false);
            $table->integer('password_expiry_days')->default(90);
            $table->boolean('force_password_change')->default(false);
            $table->integer('max_login_attempts')->default(5);
            $table->integer('lockout_duration')->default(30); // minutes
            $table->boolean('session_timeout_enabled')->default(true);
            $table->integer('session_timeout_minutes')->default(120);
            $table->boolean('ip_whitelist_enabled')->default(false);
            $table->text('ip_whitelist')->nullable();
            $table->boolean('audit_log_enabled')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('security_settings');
    }
};
