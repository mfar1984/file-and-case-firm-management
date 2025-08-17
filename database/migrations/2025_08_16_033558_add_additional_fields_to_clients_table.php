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
        Schema::table('clients', function (Blueprint $table) {
            $table->string('party_type')->nullable()->after('client_code');
            $table->string('identity_type')->nullable()->after('party_type');
            $table->string('gender')->nullable()->after('identity_type');
            $table->string('nationality')->nullable()->after('gender');
            $table->string('race')->nullable()->after('nationality');
            $table->string('fax')->nullable()->after('phone');
            $table->string('mobile')->nullable()->after('fax');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->dropColumn([
                'party_type',
                'identity_type', 
                'gender',
                'nationality',
                'race',
                'fax',
                'mobile'
            ]);
        });
    }
};
