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
        Schema::create('consultant_college', function (Blueprint $table) {
        $table->id();
        $table->foreignId('consultant_id')->constrained()->onDelete('cascade');
        $table->foreignId('college_id')->constrained()->onDelete('cascade');
        $table->timestamp('locked_at')->useCurrent();
        $table->foreignId('locked_by')->nullable()->constrained('users')->nullOnDelete();
        $table->text('remarks')->nullable();
        $table->unique(['consultant_id', 'college_id']); // Duplicate entry prevent
        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('consultant_college');
    }
};
