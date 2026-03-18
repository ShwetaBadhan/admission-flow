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
    Schema::table('colleges', function (Blueprint $table) {
        // Option A: ENUM (Simple, direct values)
        $table->enum('status', ['active', 'inactive'])->default('active')->after('website');
        
      
    });
}

public function down(): void
{
    Schema::table('colleges', function (Blueprint $table) {
        $table->dropColumn('status');
    
    });
}
};
