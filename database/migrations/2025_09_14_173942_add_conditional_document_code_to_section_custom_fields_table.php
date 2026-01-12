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
        Schema::table('section_custom_fields', function (Blueprint $table) {
            $table->string('conditional_document_code', 50)->nullable()->after('field_options');
            $table->index(['conditional_document_code', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('section_custom_fields', function (Blueprint $table) {
            $table->dropIndex(['conditional_document_code', 'status']);
            $table->dropColumn('conditional_document_code');
        });
    }
};
