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
        Schema::create('outturn_reports', function (Blueprint $table) {
            $table->id();
            $table->string('report_title');
            $table->foreignId('service_request_id')->constrained()->onDelete('cascade');
            $table->foreignId('inspector_id')->constrained()->onDelete('cascade');
            $table->foreignId('client_id')->constrained()->onDelete('cascade');
            $table->string('bdc_name'); // From service request depot
            $table->date('report_date');
            $table->decimal('total_gov_initial', 10, 3)->default(0);
            $table->decimal('total_gov_final', 10, 3)->default(0);
            $table->decimal('total_gsv_initial', 10, 3)->default(0);
            $table->decimal('total_gsv_final', 10, 3)->default(0);
            $table->decimal('total_mt_air_initial', 10, 3)->default(0);
            $table->decimal('total_mt_air_final', 10, 3)->default(0);
            $table->decimal('total_mt_vac_initial', 10, 3)->default(0);
            $table->decimal('total_mt_vac_final', 10, 3)->default(0);
            $table->decimal('total_gov_difference', 10, 3)->default(0);
            $table->decimal('total_gsv_difference', 10, 3)->default(0);
            $table->decimal('total_mt_air_difference', 10, 3)->default(0);
            $table->decimal('total_mt_vac_difference', 10, 3)->default(0);
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
        Schema::dropIfExists('outturn_reports');
    }
};
