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
        Schema::create('lead_communications', function (Blueprint $table) {
        $table->id();
        $table->foreignId('lead_id')->constrained()->onDelete('cascade');
        $table->enum('type', ['call', 'email', 'meeting', 'note', 'whatsapp']);
        $table->enum('direction', ['inbound', 'outbound'])->nullable();
        $table->string('subject')->nullable();
        $table->text('content');
        $table->timestamp('scheduled_at')->nullable();
        $table->timestamp('completed_at')->nullable();
        $table->enum('status', ['pending', 'completed', 'cancelled'])->default('pending');
        $table->foreignId('created_by')->constrained('users');
        $table->foreignId('assigned_to')->nullable()->constrained('users');
        $table->timestamps();
        $table->softDeletes();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lead_communications');
    }
};
