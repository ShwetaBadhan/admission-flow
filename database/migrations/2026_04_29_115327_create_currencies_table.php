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
         Schema::create('currencies', function (Blueprint $table) {
            $table->id();
            $table->string('name');           // Dollar, Rupee, Euro
            $table->string('code', 3)->unique(); // USD, INR, EUR
            $table->string('symbol', 10);     // $, ₹, €
            $table->decimal('exchange_rate', 10, 4); // Rate vs base currency
            $table->boolean('is_default')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('currencies');
    }
};
