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
            // Drop the existing enum column
            $table->dropColumn('status');
        });

        Schema::table('invoices', function (Blueprint $table) {
            // Add the new enum column with all required statuses
            $table->enum('status', ['draft', 'pending', 'approved', 'sent', 'paid', 'overdue', 'cancelled'])->default('pending')->after('description');
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
            // Drop the new enum column
            $table->dropColumn('status');
        });

        Schema::table('invoices', function (Blueprint $table) {
            // Restore the original enum column
            $table->enum('status', ['draft', 'sent', 'paid', 'overdue'])->default('draft')->after('description');
        });
    }
};
