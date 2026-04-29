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
        Schema::create('invoice_settings', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_prefix', 20)->default('INV-');
            $table->string('invoice_image')->nullable();
            $table->integer('invoice_due_days')->default(5);
            $table->boolean('enable_round_off')->default(false);
            $table->string('round_off_type')->default('up'); // up, down, nearest
            $table->boolean('show_company_details')->default(true);
            $table->text('invoice_terms')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoice_settings');
    }
};