<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('partners', function (Blueprint $table) {
            $table->id();
            $table->string('partner_code')->unique(); // P-001 style
            // Firm Information
            $table->string('firm_name');
            $table->text('address');
            $table->string('contact_no', 50);
            $table->string('email')->nullable();
            // Incharge Person
            $table->string('incharge_name');
            $table->string('incharge_contact', 50);
            $table->string('incharge_email')->nullable();
            $table->enum('status', ['active', 'inactive', 'suspended'])->default('active');
            // Additional Information
            $table->string('specialization')->nullable();
            $table->unsignedInteger('years_of_experience')->nullable();
            $table->string('bar_council_number')->nullable();
            $table->date('registration_date')->nullable();
            // Notes
            $table->text('notes')->nullable();
            // Optional banned
            $table->boolean('is_banned')->default(false);

            $table->timestamps();
            $table->index('firm_name');
            $table->index('status');
            $table->index('is_banned');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('partners');
    }
}; 