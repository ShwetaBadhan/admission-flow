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
       Schema::create('admission_requests', function (Blueprint $table) {
        $table->id();
        $table->foreignId('lead_id')->constrained()->onDelete('cascade');
        $table->foreignId('college_id')->constrained('colleges');
        $table->foreignId('course_id')->constrained('courses');
        $table->string('intake_session'); // Fall 2025, Spring 2026
        $table->enum('status', ['draft', 'submitted', 'under_review', 'accepted', 'rejected', 'withdrawn'])->default('draft');
        $table->text('application_notes')->nullable();
        $table->string('application_reference')->nullable();
        $table->date('submitted_date')->nullable();
        $table->date('expected_decision_date')->nullable();
        $table->foreignId('submitted_by')->constrained('users');
        $table->timestamps();
        $table->softDeletes();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admission_requests');
    }
};
