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
        Schema::table('users', function (Blueprint $table) {
            $table->timestamp('phone_verified_at')->nullable()->after('phone');
            $table->boolean('two_factor_enabled')->default(false)->after('password');
            $table->timestamp('password_updated_at')->nullable()->after('updated_at');
            $table->timestamp('account_deactivated_at')->nullable()->after('updated_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                 'phone_verified_at', 'two_factor_enabled',
                'password_updated_at', 'account_deactivated_at'
            ]);
        });
    }
};
