<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB; // Added this import for DB facade

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Change ENUM values: remove 'rejected', add 'declined'
        DB::statement("ALTER TABLE reports MODIFY status ENUM('draft', 'submitted', 'approved', 'declined') DEFAULT 'draft'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Revert ENUM values: add 'rejected' back, remove 'declined'
        DB::statement("ALTER TABLE reports MODIFY status ENUM('draft', 'submitted', 'approved', 'rejected') DEFAULT 'draft'");
    }
};
