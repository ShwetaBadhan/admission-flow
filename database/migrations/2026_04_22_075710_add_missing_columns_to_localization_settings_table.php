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
       Schema::table('localization_settings', function (Blueprint $table) {
        $table->string('financial_year')->nullable()->after('time_format');
        $table->unsignedTinyInteger('start_month')->nullable()->after('financial_year');
        $table->string('country_restriction')->default('allow_all')->after('thousand_separator');
        $table->string('allowed_files')->nullable()->after('country_restriction');
        $table->unsignedInteger('max_file_size')->default(5)->after('allowed_files');
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('localization_settings', function (Blueprint $table) {
            //
        });
    }
};
