<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('commission_rules', function (Blueprint $table) {
            $table->id();
            
            // Relationships
            $table->foreignId('consultant_id')->constrained('consultants')->onDelete('cascade');
            $table->foreignId('college_id')->constrained('colleges')->onDelete('cascade');
            
            // Since courses are in colleges table, store course name directly
            $table->string('course_name');
            
            // Commission Details
            $table->enum('commission_type', ['fixed_amount', 'percentage'])->default('fixed_amount');
            $table->decimal('commission_value', 10, 2);
            $table->string('currency', 3)->default('INR');
            
            // Status & Notes
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->text('notes')->nullable();
            
            $table->timestamps();
            
            // Indexes
            $table->index(['consultant_id', 'status']);
            $table->index(['college_id', 'course_name']);
            
            // Unique constraint to prevent duplicate rules
            $table->unique(['consultant_id', 'college_id', 'course_name'], 'unique_commission_rule');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('commission_rules');
    }
};