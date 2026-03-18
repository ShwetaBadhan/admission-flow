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
    Schema::table('colleges', function (Blueprint $table) {
        $table->string('programs')->nullable()->change();
    });
}

public function down()
{
    Schema::table('colleges', function (Blueprint $table) {
        $table->string('programs')->nullable(false)->change();
    });
}
};
