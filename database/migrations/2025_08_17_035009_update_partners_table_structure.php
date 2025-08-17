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
        Schema::table('partners', function (Blueprint $table) {
            // Drop old columns that don't match the new structure
            $table->dropColumn(['name', 'phone', 'active_cases']);
            
            // Add new columns that are missing
            $table->string('firm_name')->after('partner_code');
            $table->string('contact_no', 50)->after('firm_name');
            $table->string('incharge_name')->after('email');
            $table->string('incharge_contact', 50)->after('incharge_name');
            $table->string('incharge_email')->nullable()->after('incharge_contact');
            $table->enum('status', ['active', 'inactive', 'suspended'])->default('active')->after('incharge_email');
            $table->unsignedInteger('years_of_experience')->nullable()->after('specialization');
            $table->string('bar_council_number')->nullable()->after('years_of_experience');
            $table->date('registration_date')->nullable()->after('bar_council_number');
            
            // Add indexes
            $table->index('firm_name');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('partners', function (Blueprint $table) {
            // Remove new columns
            $table->dropColumn([
                'firm_name', 'contact_no', 'incharge_name', 'incharge_contact', 
                'incharge_email', 'status', 'years_of_experience', 
                'bar_council_number', 'registration_date'
            ]);
            
            // Remove indexes
            $table->dropIndex(['firm_name']);
            $table->dropIndex(['status']);
            
            // Add back old columns
            $table->string('name')->after('partner_code');
            $table->string('phone')->after('name');
            $table->integer('active_cases')->default(0)->after('specialization');
        });
    }
};
