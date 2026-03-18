<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    // In the migration file:
public function up()
{
    Schema::table('lead_communications', function (Blueprint $table) {
        $table->string('call_status', 100)->nullable()->after('direction');
    });
}

public function down()
{
    Schema::table('lead_communications', function (Blueprint $table) {
        $table->dropColumn('call_status');
    });
}
};
