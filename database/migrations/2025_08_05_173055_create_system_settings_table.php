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
        Schema::create('system_settings', function (Blueprint $table) {
            $table->id();
            $table->string('date_format', 20)->default('j M Y');
            $table->string('time_format', 20)->default('h:i:s A');
            $table->string('time_zone', 50)->default('Asia/Kuala_Lumpur');
            $table->string('language', 10)->default('en');
            $table->boolean('maintenance_mode')->default(false);
            $table->text('maintenance_message')->nullable();
            $table->integer('session_timeout')->default(120); // minutes
            $table->boolean('debug_mode')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('system_settings');
    }
};
