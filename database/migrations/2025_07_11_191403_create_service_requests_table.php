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
        Schema::create('service_requests', function (Blueprint $table) {
            $table->id();
            $table->string('service_id')->unique();
            $table->foreignId('client_id')->constrained()->onDelete('cascade');
            $table->foreignId('inspector_id')->nullable()->constrained()->onDelete('set null');
            $table->string('depot');
            $table->string('product');
            $table->decimal('quantity_gsv', 10, 2);
            $table->decimal('quantity_mt', 10, 2);
            $table->string('outturn_file')->nullable();
            $table->string('quality_certificate_file')->nullable();
            $table->text('tank_numbers');
            $table->text('service_type');
            $table->text('specific_instructions')->nullable();
            $table->enum('status', ['pending', 'assigned', 'in_progress', 'completed', 'cancelled'])->default('pending');
            $table->timestamp('assigned_at')->nullable();
            $table->timestamp('completed_at')->nullable();
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
        Schema::dropIfExists('service_requests');
    }
};
