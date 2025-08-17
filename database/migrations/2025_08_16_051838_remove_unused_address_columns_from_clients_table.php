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
            $table->dropColumn([
                'address_line1',
                'address_line2',
                'address_line3',
                'postcode',
                'city',
                'state',
                'country',
                'address_current',
                'address_correspondence'
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->string('address_line1')->nullable()->after('email');
            $table->string('address_line2')->nullable()->after('address_line1');
            $table->string('address_line3')->nullable()->after('address_line2');
            $table->string('postcode')->nullable()->after('address_line3');
            $table->string('city')->nullable()->after('postcode');
            $table->string('state')->nullable()->after('city');
            $table->string('country')->nullable()->after('state');
            $table->text('address_current')->nullable()->after('country');
            $table->text('address_correspondence')->nullable()->after('address_current');
        });
    }
};
