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
        // Tax Rates Table
        Schema::create('tax_rates', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->decimal('rate', 5, 2); // e.g., 10.00 for 10%
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Tax Groups Table
        Schema::create('tax_groups', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->json('sub_taxes'); // Store tax rate IDs as JSON array
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tax_groups');
        Schema::dropIfExists('tax_rates');
    }
};
