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
        Schema::create('slab_rules', function (Blueprint $table) {
            $table->id();
            
            // Scope: Consultant (nullable = apply to all)
            $table->foreignId('consultant_id')->nullable()->constrained('consultants')->nullOnDelete();
            
            // Scope: College (nullable = apply to all colleges)
            $table->foreignId('college_id')->nullable()->constrained('colleges')->nullOnDelete();
            
            // Course scope (optional - if null, applies to all courses)
            $table->string('course_name')->nullable();
            
            // Threshold Configuration
            $table->integer('threshold')->comment('Min admissions to trigger this slab (e.g., 5, 10, 15)');
            
            // Bonus Configuration
            $table->enum('bonus_type', ['fixed_amount', 'percentage_of_commission', 'percentage_of_fee']);
            $table->decimal('bonus_value', 10, 2)->comment('Bonus amount or percentage');
            $table->string('currency', 3)->default('INR');
            
            // Behavior
            $table->boolean('retroactive')->default(true)->comment('Apply bonus to all previous admissions when threshold hit');
            $table->enum('scope', ['per_college', 'global'])->default('per_college')
                ->comment('per_college=slab per college, global=across all colleges');
            
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->text('notes')->nullable();
            
            $table->timestamps();
            
            // Indexes for fast lookup
            $table->index(['consultant_id', 'college_id', 'course_name', 'status']);
            $table->index(['threshold', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('slab_rules');
    }
};
