<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Rename from singular to plural
        Schema::rename('contact_stage', 'contact_stages');
    }

    public function down(): void
    {
        // Revert back to singular if needed
        Schema::rename('contact_stages', 'contact_stage');
    }
};