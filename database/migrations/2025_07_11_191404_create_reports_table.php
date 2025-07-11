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
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_request_id')->constrained()->onDelete('cascade');
            $table->foreignId('inspector_id')->constrained()->onDelete('cascade');
            $table->date('inspection_date');
            $table->time('inspection_time');
            $table->string('tank_number');
            $table->decimal('product_gauge', 8, 2);
            $table->decimal('water_gauge', 8, 2);
            $table->decimal('temperature', 8, 2);
            $table->boolean('has_roof');
            $table->decimal('roof_weight', 8, 2)->nullable();
            $table->decimal('density', 8, 4);
            $table->decimal('vcf', 8, 4);
            $table->decimal('tov', 10, 2);
            $table->decimal('water_volume', 10, 2);
            $table->decimal('roof_volume', 10, 2);
            $table->decimal('gov', 10, 2);
            $table->decimal('gsv', 10, 2);
            $table->decimal('mt_air', 10, 2);
            $table->text('comments')->nullable();
            $table->string('supporting_file')->nullable();
            $table->enum('status', ['draft', 'submitted', 'approved', 'rejected'])->default('draft');
            $table->timestamp('submitted_at')->nullable();
            $table->timestamp('approved_at')->nullable();
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
        Schema::dropIfExists('reports');
    }
};
