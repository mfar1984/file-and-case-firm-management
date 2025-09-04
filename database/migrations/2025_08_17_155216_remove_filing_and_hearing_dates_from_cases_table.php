<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('cases', function (Blueprint $table) {
            $table->dropColumn(['filing_date', 'hearing_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('cases', function (Blueprint $table) {
            $table->date('filing_date')->nullable();
            $table->date('hearing_date')->nullable();
        });
    }
};
