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
        Schema::create('inspection_data_sets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('report_id')->constrained()->onDelete('cascade');
            $table->date('inspection_date');
            $table->time('inspection_time');
            $table->string('tank_number', 50);
            $table->decimal('product_gauge', 8, 2);
            $table->decimal('water_gauge', 8, 2);
            $table->decimal('temperature', 5, 2);
            $table->boolean('has_roof')->default(false);
            $table->decimal('roof_weight', 10, 2)->nullable();
            $table->decimal('density', 8, 4);
            $table->decimal('vcf', 8, 4);
            $table->decimal('tov', 12, 2);
            $table->decimal('water_volume', 12, 2);
            $table->decimal('roof_volume', 12, 2)->nullable();
            $table->decimal('gov', 12, 2)->nullable();
            $table->decimal('gsv', 12, 2)->nullable();
            $table->decimal('mt_air', 12, 2)->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inspection_data_sets');
    }
};
