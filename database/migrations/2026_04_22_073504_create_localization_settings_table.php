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
        Schema::create('localization_settings', function (Blueprint $table) {
            $table->id();
            $table->string('time_zone')->default('UTC');
            $table->string('start_week')->default('Monday');
            $table->string('date_format')->default('d M Y');
            $table->string('time_format')->default('12 hrs');
            $table->string('default_language')->default('English');
            $table->boolean('language_switcher')->default(true);
            
            // Currency Information
            $table->string('currency')->default('USD');
            $table->string('currency_symbol')->default('$');
            $table->string('currency_position')->default('left'); // $100 or 100$
            $table->string('decimal_separator')->default('.');
            $table->string('thousand_separator')->default(',');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('localization_settings');
    }
};