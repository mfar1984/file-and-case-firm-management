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
        Schema::create('weather_settings', function (Blueprint $table) {
            $table->id();
            $table->string('api_provider')->default('tomorrow_io'); // tomorrow_io, openweathermap, etc.
            $table->string('api_key')->nullable();
            $table->string('location_name')->default('Kuala Lumpur');
            $table->string('postcode')->nullable();
            $table->string('country')->default('Malaysia');
            $table->string('state')->default('Kuala Lumpur');
            $table->string('city')->default('Kuala Lumpur');
            $table->decimal('latitude', 10, 8)->default(3.1390);
            $table->decimal('longitude', 11, 8)->default(101.6869);
            $table->string('units')->default('metric'); // metric, imperial
            $table->boolean('is_active')->default(true);
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('weather_settings');
    }
};
