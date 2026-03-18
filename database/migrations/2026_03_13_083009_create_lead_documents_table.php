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
        Schema::create('lead_documents', function (Blueprint $table) {
        $table->id();
        $table->foreignId('lead_id')->constrained()->onDelete('cascade');
        $table->string('document_type'); // passport, marksheets, sop, lor, etc.
        $table->string('file_name');
        $table->string('file_path');
        $table->string('file_size')->nullable();
        $table->string('uploaded_by')->nullable();
        $table->boolean('is_verified')->default(false);
        $table->text('remarks')->nullable();
        $table->timestamps();
        $table->softDeletes();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lead_documents');
    }
};
