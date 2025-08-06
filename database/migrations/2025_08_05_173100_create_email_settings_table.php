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
        Schema::create('email_settings', function (Blueprint $table) {
            $table->id();
            $table->string('smtp_host');
            $table->integer('smtp_port');
            $table->string('email_username');
            $table->string('email_password');
            $table->string('from_name');
            $table->string('from_email');
            $table->boolean('encryption')->default(true);
            $table->boolean('notify_new_cases')->default(true);
            $table->boolean('notify_document_uploads')->default(true);
            $table->boolean('notify_case_status')->default(true);
            $table->boolean('notify_maintenance')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('email_settings');
    }
};
