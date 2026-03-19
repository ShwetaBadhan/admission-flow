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
        Schema::create('consultant_kyc', function (Blueprint $table) {
        $table->id();
        $table->foreignId('consultant_id')->constrained()->onDelete('cascade');
        $table->string('document_type'); // e.g., 'aadhaar', 'pan', 'gst_certificate'
        $table->string('document_number')->nullable(); // Optional: ID number
        $table->string('file_name');
        $table->string('file_path');
        $table->string('file_size')->nullable();
        $table->text('remarks')->nullable();
        $table->string('uploaded_by')->nullable();
        $table->boolean('is_verified')->nullable(); // null=pending, true=verified, false=rejected
        $table->timestamp('verified_at')->nullable();
        $table->string('verified_by')->nullable();
        $table->timestamps();
        
        // Prevent duplicate document types per consultant (only one pending/verified)
        $table->unique(['consultant_id', 'document_type']);
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('consultant_kyc');
    }
};
