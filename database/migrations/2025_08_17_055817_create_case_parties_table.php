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
        Schema::create('case_parties', function (Blueprint $table) {
            $table->id();
            $table->foreignId('case_id')->constrained()->onDelete('cascade');
            $table->string('party_type'); // 'plaintiff' or 'defendant'
            $table->string('name'); // Party name
            $table->string('ic_passport')->nullable(); // IC or passport number
            $table->string('phone')->nullable(); // Phone number
            $table->string('email')->nullable(); // Email address
            $table->string('username')->nullable(); // Generated username
            $table->string('password')->nullable(); // Generated password
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('case_parties');
    }
};
