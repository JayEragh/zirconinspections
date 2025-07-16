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
        Schema::table('invoices', function (Blueprint $table) {
            $table->decimal('nhil_tax', 10, 2)->default(0.00)->after('amount');
            $table->decimal('getfund_tax', 10, 2)->default(0.00)->after('nhil_tax');
            $table->decimal('covid_tax', 10, 2)->default(0.00)->after('getfund_tax');
            $table->decimal('subtotal', 10, 2)->default(0.00)->after('covid_tax');
            $table->decimal('total_amount', 10, 2)->default(0.00)->after('subtotal');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropColumn(['nhil_tax', 'getfund_tax', 'covid_tax', 'subtotal', 'total_amount']);
        });
    }
};
