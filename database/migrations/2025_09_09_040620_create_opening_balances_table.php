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
        Schema::create('opening_balances', function (Blueprint $table) {
            $table->id();
            $table->string('bank_code', 20);
            $table->string('bank_name', 255);
            $table->string('currency', 10)->default('MYR');
            $table->decimal('debit', 15, 2)->default(0);
            $table->decimal('credit', 15, 2)->default(0);
            $table->decimal('debit_myr', 15, 2)->default(0);
            $table->decimal('credit_myr', 15, 2)->default(0);
            $table->decimal('exchange_rate', 10, 4)->default(1.0000);
            $table->boolean('status')->default(true);
            $table->timestamps();

            $table->unique('bank_code');
            $table->index(['status', 'bank_code']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('opening_balances');
    }
};
