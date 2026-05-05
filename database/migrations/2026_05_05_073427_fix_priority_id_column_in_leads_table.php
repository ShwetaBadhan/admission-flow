<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Step 1: Drop the old ENUM column (NO foreign key exists, so skip dropForeign)
        Schema::table('leads', function (Blueprint $table) {
            $table->dropColumn('priority_id');
        });

        // Step 2: Add the new proper foreign key column
        Schema::table('leads', function (Blueprint $table) {
            $table->foreignId('priority_id')
                ->nullable()
                ->after('preferred_intake_id')
                ->constrained('priorities')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('leads', function (Blueprint $table) {
            $table->dropForeign(['priority_id']);
            $table->dropColumn('priority_id');
        });

        Schema::table('leads', function (Blueprint $table) {
            $table->enum('priority_id', ['low', 'medium', 'high', 'urgent'])
                ->default('medium')
                ->after('preferred_intake_id');
        });
    }
};