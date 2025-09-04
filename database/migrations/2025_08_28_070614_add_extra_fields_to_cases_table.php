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
        Schema::table('cases', function (Blueprint $table) {
            $table->string('file_ref')->nullable()->after('case_number');
            $table->string('court_ref')->nullable()->after('file_ref');
            $table->string('jurisdiction')->nullable()->after('court_ref');
            $table->string('section')->nullable()->after('jurisdiction');
            $table->string('initiating_document')->nullable()->after('section');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cases', function (Blueprint $table) {
            $table->dropColumn(['file_ref', 'court_ref', 'jurisdiction', 'section', 'initiating_document']);
        });
    }
};
