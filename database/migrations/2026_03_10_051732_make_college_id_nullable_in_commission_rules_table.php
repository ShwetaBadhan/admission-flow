<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    // In the new migration file
public function up()
{
    Schema::table('commission_rules', function (Blueprint $table) {
        $table->foreignId('college_id')->nullable()->change();
    });
}

public function down()
{
    Schema::table('commission_rules', function (Blueprint $table) {
        $table->foreignId('college_id')->nullable(false)->change();
    });
}
};
