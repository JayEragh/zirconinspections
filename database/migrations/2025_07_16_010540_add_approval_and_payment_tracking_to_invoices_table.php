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
            $table->timestamp('approved_at')->nullable()->after('paid_at');
            $table->timestamp('sent_to_client_at')->nullable()->after('approved_at');
            $table->date('payment_deadline')->nullable()->after('sent_to_client_at');
            $table->boolean('overdue_notification_sent')->default(false)->after('payment_deadline');
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
            $table->dropColumn(['approved_at', 'sent_to_client_at', 'payment_deadline', 'overdue_notification_sent']);
        });
    }
};
