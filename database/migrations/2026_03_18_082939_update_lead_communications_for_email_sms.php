<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up()
{
    Schema::table('lead_communications', function (Blueprint $table) {
        // Ensure these columns exist:
        $table->string('type', 50)->change(); // already has: call, email, note, etc.
        $table->string('subject')->nullable()->change(); // for email subject
        $table->text('content')->change(); // for message content
        $table->string('status', 20)->default('pending')->change(); // pending/completed
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('lead_communications', function (Blueprint $table) {
            //
        });
    }
};
