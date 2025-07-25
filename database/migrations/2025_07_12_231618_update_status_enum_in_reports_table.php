<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // PostgreSQL doesn't support ENUM modification directly
        // We need to drop and recreate the column or use a different approach
        Schema::table('reports', function (Blueprint $table) {
            // First, drop the existing column
            $table->dropColumn('status');
        });
        
        // Then add it back with the new enum values
        Schema::table('reports', function (Blueprint $table) {
            $table->enum('status', ['draft', 'submitted', 'approved', 'declined'])->default('draft')->after('id');
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
            $table->dropColumn('status');
        });
        
        Schema::table('reports', function (Blueprint $table) {
            $table->enum('status', ['draft', 'submitted', 'approved', 'rejected'])->default('draft')->after('id');
        });
    }
};
