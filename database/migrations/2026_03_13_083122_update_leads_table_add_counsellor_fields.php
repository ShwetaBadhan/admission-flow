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
          Schema::table('leads', function (Blueprint $table) {
        // If consultant_id exists, you can alias it or add counsellor_id separately
        if (!Schema::hasColumn('leads', 'counsellor_id')) {
            $table->foreignId('counsellor_id')->nullable()->constrained('users')->after('consultant_id');
        }
        $table->foreignId('assigned_by')->nullable()->constrained('users')->after('counsellor_id');
        $table->timestamp('assigned_at')->nullable()->after('assigned_by');
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
          Schema::table('leads', function (Blueprint $table) {
        // If consultant_id exists, you can alias it or add counsellor_id separately
        if (!Schema::hasColumn('leads', 'counsellor_id')) {
            $table->foreignId('counsellor_id')->nullable()->constrained('users')->after('consultant_id');
        }
        $table->foreignId('assigned_by')->nullable()->constrained('users')->after('counsellor_id');
        $table->timestamp('assigned_at')->nullable()->after('assigned_by');
    });
    }
};
