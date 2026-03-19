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
       
Schema::create('leads', function (Blueprint $table) {
    $table->id();
    $table->string('full_name');
    $table->string('mobile');
    $table->string('email')->nullable();
    $table->foreignId('city_id')->nullable()->constrained()->onDelete('set null');
    $table->foreignId('state_id')->nullable()->constrained()->onDelete('set null');
    $table->string('qualification')->nullable(); // Simple text field
    $table->foreignId('interested_course_id')->nullable()->constrained('courses')->onDelete('set null');
    $table->string('preferred_intake')->nullable();
    $table->foreignId('lead_source_id')->nullable()->constrained('lead_sources')->onDelete('set null');
    $table->enum('priority', ['low', 'medium', 'high', 'urgent'])->default('medium');
    $table->enum('status', ['new', 'contacted', 'qualified', 'proposal', 'negotiation', 'won', 'lost'])->default('new');
    $table->text('notes')->nullable();
    $table->foreignId('consultant_id')->nullable()->constrained('users')->onDelete('set null');
    $table->timestamp('contacted_at')->nullable();
    $table->timestamps();
    $table->softDeletes();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leads');
    }
};
