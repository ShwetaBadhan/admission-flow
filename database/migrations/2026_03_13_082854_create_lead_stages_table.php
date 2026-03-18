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
         Schema::create('lead_stages', function (Blueprint $table) {
        $table->id();
        $table->foreignId('lead_id')->constrained()->onDelete('cascade');
        $table->string('stage'); // new, contacted, qualified, proposal, negotiation, won, lost
        $table->text('notes')->nullable();
        $table->foreignId('updated_by')->constrained('users');
        $table->timestamp('completed_at')->nullable();
        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lead_stages');
    }
};
