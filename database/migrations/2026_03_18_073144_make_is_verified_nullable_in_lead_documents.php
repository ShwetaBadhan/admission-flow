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
    Schema::table('lead_documents', function (Blueprint $table) {
        $table->boolean('is_verified')->nullable()->default(null)->change();
    });
}

public function down()
{
    Schema::table('lead_documents', function (Blueprint $table) {
        $table->boolean('is_verified')->nullable(false)->default(0)->change();
    });
}
};
