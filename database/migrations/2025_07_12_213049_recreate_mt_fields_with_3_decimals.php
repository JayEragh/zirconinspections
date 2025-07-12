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
        // Drop and recreate quantity_mt column in service_requests table
        Schema::table('service_requests', function (Blueprint $table) {
            $table->dropColumn('quantity_mt');
        });
        
        Schema::table('service_requests', function (Blueprint $table) {
            $table->decimal('quantity_mt', 10, 3)->after('quantity_gsv');
        });

        // Drop and recreate mt_air column in inspection_data_sets table
        Schema::table('inspection_data_sets', function (Blueprint $table) {
            $table->dropColumn('mt_air');
        });
        
        Schema::table('inspection_data_sets', function (Blueprint $table) {
            $table->decimal('mt_air', 12, 3)->nullable()->after('gsv');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert service_requests table
        Schema::table('service_requests', function (Blueprint $table) {
            $table->dropColumn('quantity_mt');
        });
        
        Schema::table('service_requests', function (Blueprint $table) {
            $table->decimal('quantity_mt', 10, 2)->after('quantity_gsv');
        });

        // Revert inspection_data_sets table
        Schema::table('inspection_data_sets', function (Blueprint $table) {
            $table->dropColumn('mt_air');
        });
        
        Schema::table('inspection_data_sets', function (Blueprint $table) {
            $table->decimal('mt_air', 12, 2)->nullable()->after('gsv');
        });
    }
};
