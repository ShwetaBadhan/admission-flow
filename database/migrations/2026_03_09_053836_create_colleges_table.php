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
        Schema::create('colleges', function (Blueprint $table) {
            $table->id();
            
            // Basic Info
            $table->string('name');
            $table->string('college_image')->nullable(); // Path to stored image
            
            // Relationships (Foreign Keys)
            // Assumes you have 'states' and 'cities' tables already created
            $table->foreignId('state_id')->constrained('states')->onDelete('cascade');
            $table->foreignId('city_id')->constrained('cities')->onDelete('cascade');
            
            // Details
            $table->text('programs'); // Comma separated string (e.g., "B.Tech, MBA, BBA")
            $table->date('application_deadline')->nullable();
            $table->string('fees_range'); // e.g., "50,000 - 1,00,000"
            
            // Contact Info
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('website')->nullable();
            
            $table->timestamps();
            
            // Optional: Add indexes for faster searching
            $table->index('name');
            $table->index('state_id');
            $table->index('city_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('colleges');
    }
};