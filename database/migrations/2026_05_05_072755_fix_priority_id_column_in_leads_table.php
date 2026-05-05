<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('leads', function (Blueprint $table) {
            // 🔹 Step 1: Drop the existing foreign key constraint first
            // Laravel names it: {table}_{column}_foreign
            $table->dropForeign(['priority_id']);
        });

        Schema::table('leads', function (Blueprint $table) {
            // 🔹 Step 2: Now drop the ENUM column
            $table->dropColumn('priority_id');
        });

        Schema::table('leads', function (Blueprint $table) {
            // 🔹 Step 3: Add new proper foreign key column
            $table->foreignId('priority_id')
                ->nullable()
                ->after('preferred_intake_id')
                ->constrained('priorities')
                ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('leads', function (Blueprint $table) {
            // Drop new foreign key
            $table->dropForeign(['priority_id']);
            $table->dropColumn('priority_id');
        });

        Schema::table('leads', function (Blueprint $table) {
            // Restore old ENUM column
            $table->enum('priority_id', ['low', 'medium', 'high', 'urgent'])
                ->default('medium')
                ->after('preferred_intake_id');
                
            // Re-add old foreign key if it existed (optional)
            // $table->foreign('priority_id')->references('id')->on('priorities');
        });
    }
};