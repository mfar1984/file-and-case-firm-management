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
        // Add firm_id to system_settings table (only if not exists)
        if (!Schema::hasColumn('system_settings', 'firm_id')) {
            Schema::table('system_settings', function (Blueprint $table) {
                $table->unsignedBigInteger('firm_id')->nullable()->after('time_zone');
                $table->foreign('firm_id')->references('id')->on('firms')->onDelete('cascade');
                $table->index('firm_id');
            });
        }

        // Add firm_id to email_settings table (only if not exists)
        if (!Schema::hasColumn('email_settings', 'firm_id')) {
            Schema::table('email_settings', function (Blueprint $table) {
                $table->unsignedBigInteger('firm_id')->nullable()->after('email_password');
                $table->foreign('firm_id')->references('id')->on('firms')->onDelete('cascade');
                $table->index('firm_id');
            });
        }

        // Add firm_id to security_settings table (only if not exists)
        if (!Schema::hasColumn('security_settings', 'firm_id')) {
            Schema::table('security_settings', function (Blueprint $table) {
                $table->unsignedBigInteger('firm_id')->nullable()->after('password_expiry_days');
                $table->foreign('firm_id')->references('id')->on('firms')->onDelete('cascade');
                $table->index('firm_id');
            });
        }

        // Add firm_id to weather_settings table (only if not exists)
        if (!Schema::hasColumn('weather_settings', 'firm_id')) {
            Schema::table('weather_settings', function (Blueprint $table) {
                $table->unsignedBigInteger('firm_id')->nullable()->after('api_key');
                $table->foreign('firm_id')->references('id')->on('firms')->onDelete('cascade');
                $table->index('firm_id');
            });
        }

        // Migrate existing data to default firm (firm_id = 1)
        DB::table('system_settings')->whereNull('firm_id')->update(['firm_id' => 1]);
        DB::table('email_settings')->whereNull('firm_id')->update(['firm_id' => 1]);
        DB::table('security_settings')->whereNull('firm_id')->update(['firm_id' => 1]);
        DB::table('weather_settings')->whereNull('firm_id')->update(['firm_id' => 1]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('system_settings', function (Blueprint $table) {
            $table->dropForeign(['firm_id']);
            $table->dropIndex(['firm_id']);
            $table->dropColumn('firm_id');
        });

        Schema::table('email_settings', function (Blueprint $table) {
            $table->dropForeign(['firm_id']);
            $table->dropIndex(['firm_id']);
            $table->dropColumn('firm_id');
        });

        Schema::table('security_settings', function (Blueprint $table) {
            $table->dropForeign(['firm_id']);
            $table->dropIndex(['firm_id']);
            $table->dropColumn('firm_id');
        });

        Schema::table('weather_settings', function (Blueprint $table) {
            $table->dropForeign(['firm_id']);
            $table->dropIndex(['firm_id']);
            $table->dropColumn('firm_id');
        });
    }
};
