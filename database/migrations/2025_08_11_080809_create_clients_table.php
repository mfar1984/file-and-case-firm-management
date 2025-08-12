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
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->string('client_code')->unique(); // CL-0001
            $table->string('name');
            $table->string('ic_passport');
            $table->string('phone');
            $table->string('email')->nullable();
            $table->text('address_current');
            $table->text('address_correspondence')->nullable();
            $table->string('tin_no')->nullable();
            $table->string('job')->nullable();
            $table->decimal('salary', 12, 2)->nullable();
            $table->unsignedInteger('dependent')->default(0);
            $table->string('family_contact_name')->nullable();
            $table->string('family_contact_phone')->nullable();
            $table->text('family_address')->nullable();
            $table->string('agent_banker')->nullable();
            $table->string('financier_bank')->nullable();
            $table->text('lawyers_parties')->nullable();
            $table->text('notes')->nullable();
            $table->boolean('is_banned')->default(false);
            $table->timestamps();

            $table->index('name');
            $table->index('ic_passport');
            $table->index('phone');
            $table->index('is_banned');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clients');
    }
};
