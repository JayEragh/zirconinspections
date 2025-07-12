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
        // Update inspection_data_sets table
        Schema::table('inspection_data_sets', function (Blueprint $table) {
            $table->decimal('product_gauge', 10, 3)->change();
            $table->decimal('water_gauge', 10, 3)->change();
            $table->decimal('temperature', 10, 3)->change();
            $table->decimal('roof_weight', 10, 3)->change();
            $table->decimal('density', 10, 3)->change();
            $table->decimal('vcf', 10, 3)->change();
            $table->decimal('tov', 10, 3)->change();
            $table->decimal('water_volume', 10, 3)->change();
            $table->decimal('roof_volume', 10, 3)->change();
            $table->decimal('gov', 10, 3)->change();
            $table->decimal('gsv', 10, 3)->change();
            $table->decimal('mt_air', 10, 3)->change();
        });

        // Update service_requests table
        Schema::table('service_requests', function (Blueprint $table) {
            $table->decimal('quantity_gsv', 10, 3)->change();
            $table->decimal('quantity_mt', 10, 3)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert inspection_data_sets table
        Schema::table('inspection_data_sets', function (Blueprint $table) {
            $table->decimal('product_gauge', 10, 2)->change();
            $table->decimal('water_gauge', 10, 2)->change();
            $table->decimal('temperature', 10, 2)->change();
            $table->decimal('roof_weight', 10, 2)->change();
            $table->decimal('density', 10, 4)->change();
            $table->decimal('vcf', 10, 4)->change();
            $table->decimal('tov', 10, 2)->change();
            $table->decimal('water_volume', 10, 2)->change();
            $table->decimal('roof_volume', 10, 2)->change();
            $table->decimal('gov', 10, 2)->change();
            $table->decimal('gsv', 10, 2)->change();
            $table->decimal('mt_air', 10, 3)->change();
        });

        // Revert service_requests table
        Schema::table('service_requests', function (Blueprint $table) {
            $table->decimal('quantity_gsv', 10, 2)->change();
            $table->decimal('quantity_mt', 10, 3)->change();
        });
    }
};
