<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB; // 1. Import DB Facade

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 2. Add the new column with the desired definition
        Schema::table('leads', function (Blueprint $table) {
            $table->enum('priority_id', ['low', 'medium', 'high', 'urgent'])
                  ->default('medium')
                  ->nullable(false);
        });

        // 3. Copy data from the old column to the new column
        DB::statement('UPDATE leads SET priority_id = priority');

        // 4. Drop the old column
        Schema::table('leads', function (Blueprint $table) {
            $table->dropColumn('priority');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // 5. Re-add the old column
        Schema::table('leads', function (Blueprint $table) {
            $table->enum('priority', ['low', 'medium', 'high', 'urgent'])
                  ->default('medium')
                  ->nullable(false);
        });

        // 6. Copy data back
        DB::statement('UPDATE leads SET priority = priority_id');

        // 7. Drop the new column
        Schema::table('leads', function (Blueprint $table) {
            $table->dropColumn('priority_id');
        });
    }
};