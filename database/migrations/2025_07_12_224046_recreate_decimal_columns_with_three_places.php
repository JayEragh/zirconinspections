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
            // Drop existing columns
            $table->dropColumn([
                'product_gauge', 'water_gauge', 'temperature', 'roof_weight',
                'density', 'vcf', 'tov', 'water_volume', 'roof_volume',
                'gov', 'gsv', 'mt_air'
            ]);
        });

        Schema::table('inspection_data_sets', function (Blueprint $table) {
            // Recreate columns with 3 decimal places
            $table->decimal('product_gauge', 10, 3)->nullable();
            $table->decimal('water_gauge', 10, 3)->nullable();
            $table->decimal('temperature', 10, 3)->nullable();
            $table->decimal('roof_weight', 10, 3)->nullable();
            $table->decimal('density', 10, 3)->nullable();
            $table->decimal('vcf', 10, 3)->nullable();
            $table->decimal('tov', 10, 3)->nullable();
            $table->decimal('water_volume', 10, 3)->nullable();
            $table->decimal('roof_volume', 10, 3)->nullable();
            $table->decimal('gov', 10, 3)->nullable();
            $table->decimal('gsv', 10, 3)->nullable();
            $table->decimal('mt_air', 10, 3)->nullable();
        });

        // Update service_requests table
        Schema::table('service_requests', function (Blueprint $table) {
            $table->dropColumn(['quantity_gsv', 'quantity_mt']);
        });

        Schema::table('service_requests', function (Blueprint $table) {
            $table->decimal('quantity_gsv', 10, 3)->nullable();
            $table->decimal('quantity_mt', 10, 3)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert inspection_data_sets table
        Schema::table('inspection_data_sets', function (Blueprint $table) {
            $table->dropColumn([
                'product_gauge', 'water_gauge', 'temperature', 'roof_weight',
                'density', 'vcf', 'tov', 'water_volume', 'roof_volume',
                'gov', 'gsv', 'mt_air'
            ]);
        });

        Schema::table('inspection_data_sets', function (Blueprint $table) {
            $table->decimal('product_gauge', 10, 2)->nullable();
            $table->decimal('water_gauge', 10, 2)->nullable();
            $table->decimal('temperature', 10, 2)->nullable();
            $table->decimal('roof_weight', 10, 2)->nullable();
            $table->decimal('density', 10, 4)->nullable();
            $table->decimal('vcf', 10, 4)->nullable();
            $table->decimal('tov', 10, 2)->nullable();
            $table->decimal('water_volume', 10, 2)->nullable();
            $table->decimal('roof_volume', 10, 2)->nullable();
            $table->decimal('gov', 10, 2)->nullable();
            $table->decimal('gsv', 10, 2)->nullable();
            $table->decimal('mt_air', 10, 3)->nullable();
        });

        // Revert service_requests table
        Schema::table('service_requests', function (Blueprint $table) {
            $table->dropColumn(['quantity_gsv', 'quantity_mt']);
        });

        Schema::table('service_requests', function (Blueprint $table) {
            $table->decimal('quantity_gsv', 10, 2)->nullable();
            $table->decimal('quantity_mt', 10, 3)->nullable();
        });
    }
};
