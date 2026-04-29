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
        Schema::create('gdpr_cookies', function (Blueprint $table) {
            $table->id();
            $table->string('cookie_position')->default('right'); // left, right, bottom, top
            $table->string('agree_button_text')->default('Accept Cookies');
            $table->string('decline_button_text')->default('Decline');
            $table->boolean('show_decline_button')->default(true);
            $table->longText('cookie_content')->nullable();
            $table->string('cookies_page_link')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gdpr_cookies');
    }
};
