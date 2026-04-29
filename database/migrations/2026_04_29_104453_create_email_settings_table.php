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
        Schema::create('email_settings', function (Blueprint $table) {
            $table->id();
            $table->string('provider')->unique(); // php_mailer, smtp, sendgrid, mailgun, etc.
            $table->string('name'); // PHP Mailer, SMTP, SendGrid
            $table->string('logo')->nullable();
            $table->json('config')->nullable(); // Store provider-specific config as JSON
            $table->boolean('is_active')->default(false);
            $table->boolean('is_connected')->default(false);
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('email_settings');
    }
};
