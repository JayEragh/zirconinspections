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
        Schema::create('outturn_data_sets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('outturn_report_id')->constrained()->onDelete('cascade');
            $table->string('tank_number');
            $table->enum('data_type', ['initial', 'final']);
            $table->date('inspection_date');
            $table->time('inspection_time');
            $table->decimal('product_gauge', 10, 3);
            $table->decimal('water_gauge', 10, 3);
            $table->decimal('temperature', 5, 2);
            $table->boolean('has_roof')->default(false);
            $table->decimal('roof_weight', 10, 3)->nullable();
            $table->decimal('density', 8, 4);
            $table->decimal('vcf', 8, 4);
            $table->decimal('tov', 10, 3);
            $table->decimal('water_volume', 10, 3);
            $table->decimal('roof_volume', 10, 3)->nullable();
            $table->decimal('gov', 10, 3);
            $table->decimal('gsv', 10, 3);
            $table->decimal('mt_air', 10, 3);
            $table->decimal('mt_vac', 10, 3);
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('outturn_data_sets');
    }
};
