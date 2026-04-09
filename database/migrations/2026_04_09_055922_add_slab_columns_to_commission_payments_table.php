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
        Schema::table('commission_payments', function (Blueprint $table) {
            // Base commission amount (before slab bonus)
            $table->decimal('base_amount', 12, 2)->nullable()->after('calculated_amount')
                ->comment('Base commission before slab bonus');
            
            // Slab bonus amount
            $table->decimal('slab_bonus_amount', 12, 2)->default(0)->after('base_amount')
                ->comment('Extra bonus from slab rules');
            
            // JSON field to store which slabs were applied
            $table->json('slab_details')->nullable()->after('slab_bonus_amount')
                ->comment('Array of applied slab rules with details');
            
            // Flag to quickly check if any slab was applied
            $table->boolean('slab_applied')->default(false)->after('slab_details');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('commission_payments', function (Blueprint $table) {
            $table->dropColumn([
                'base_amount',
                'slab_bonus_amount', 
                'slab_details',
                'slab_applied'
            ]);
        });
    }
};