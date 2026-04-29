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
        Schema::create('payment_methods', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // PayPal, Stripe, Razorpay, Apple Pay
            $table->string('slug')->unique(); // paypal, stripe, razorpay, applepay
            $table->string('logo')->nullable();
            $table->string('email')->nullable();
            $table->text('api_key')->nullable();
            $table->text('secret_key')->nullable();
            $table->boolean('is_active')->default(false);
            $table->boolean('is_connected')->default(false);
            $table->text('description')->nullable(); // JSON for extra config
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_methods');
    }
};
