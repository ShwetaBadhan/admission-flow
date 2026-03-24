<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('commission_payments', function (Blueprint $table) {
        $table->id();
        $table->foreignId('consultant_id')->constrained('consultants');
        $table->foreignId('admission_request_id')->constrained('admission_requests');
        $table->foreignId('commission_rule_id')->constrained('commission_rules');
        $table->foreignId('lead_id')->constrained('leads');
        $table->string('payment_type'); // 'fixed_amount' or 'percentage'
        $table->decimal('commission_value', 10, 2);
        $table->decimal('calculated_amount', 15, 2);
        $table->string('currency', 3)->default('USD');
        $table->string('status')->default('pending'); // pending, paid, cancelled
        $table->date('payment_date')->nullable();
        $table->string('payment_reference')->nullable();
        $table->text('notes')->nullable();
        $table->foreignId('created_by')->constrained('users');
        $table->foreignId('paid_by')->nullable()->constrained('users');
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('commission_payments');
    }
};
