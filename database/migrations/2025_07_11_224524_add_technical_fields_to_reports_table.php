<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('reports', function (Blueprint $table) {
            // Technical inspection fields
            $table->date('inspection_date')->nullable();
            $table->timestamp('inspection_time')->nullable();
            $table->string('tank_number')->nullable();
            $table->decimal('product_gauge', 8, 2)->nullable();
            $table->decimal('water_gauge', 8, 2)->nullable();
            $table->decimal('temperature', 8, 2)->nullable();
            $table->boolean('has_roof')->default(false);
            $table->decimal('roof_weight', 8, 2)->nullable();
            $table->decimal('density', 8, 4)->nullable();
            $table->decimal('vcf', 8, 4)->nullable();
            $table->decimal('tov', 8, 2)->nullable();
            $table->decimal('water_volume', 8, 2)->nullable();
            $table->decimal('roof_volume', 8, 2)->nullable();
            $table->decimal('gov', 8, 2)->nullable();
            $table->decimal('gsv', 8, 2)->nullable();
            $table->decimal('mt_air', 8, 2)->nullable();
            $table->string('supporting_file')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('reports', function (Blueprint $table) {
            $table->dropColumn([
                'inspection_date',
                'inspection_time',
                'tank_number',
                'product_gauge',
                'water_gauge',
                'temperature',
                'has_roof',
                'roof_weight',
                'density',
                'vcf',
                'tov',
                'water_volume',
                'roof_volume',
                'gov',
                'gsv',
                'mt_air',
                'supporting_file'
            ]);
        });
    }
};
