<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('leads', function (Blueprint $table) {
            $table->id();
            $table->string('full_name');
            $table->string('mobile');
            $table->string('email')->nullable();
            $table->unsignedBigInteger('city_id')->nullable();
            $table->unsignedBigInteger('state_id')->nullable();
            $table->unsignedBigInteger('qualification_id')->nullable();
            $table->unsignedBigInteger('interested_course_id')->nullable();
            $table->unsignedBigInteger('preferred_intake_id')->nullable();
            $table->unsignedBigInteger('lead_source_id')->nullable();
            $table->unsignedBigInteger('priority_id')->nullable(); // ✅ BIGINT, not ENUM
            $table->enum('status', ['new', 'contacted', 'qualified', 'proposal', 'negotiation', 'won', 'lost'])->default('new');
            $table->text('notes')->nullable();
            $table->unsignedBigInteger('consultant_id')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Foreign keys
            $table->foreign('city_id')->references('id')->on('cities')->nullOnDelete();
            $table->foreign('state_id')->references('id')->on('states')->nullOnDelete();
            $table->foreign('qualification_id')->references('id')->on('qualifications')->nullOnDelete();
            $table->foreign('interested_course_id')->references('id')->on('courses')->nullOnDelete();
            $table->foreign('preferred_intake_id')->references('id')->on('intakes')->nullOnDelete();
            $table->foreign('lead_source_id')->references('id')->on('lead_sources')->nullOnDelete();
            $table->foreign('priority_id')->references('id')->on('priorities')->nullOnDelete(); // ✅ Foreign key
            $table->foreign('consultant_id')->references('id')->on('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('leads');
    }
};