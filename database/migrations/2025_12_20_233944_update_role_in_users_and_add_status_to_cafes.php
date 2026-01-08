<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up()
    {
        // Update users table role enum
        // DB::statement("ALTER TABLE users MODIFY role ENUM('user', 'admin', 'cafe_owner') DEFAULT 'user'");


        // Add approval_status to cafes table
        Schema::table('cafes', function (Blueprint $table) {
            if (!Schema::hasColumn('cafes', 'approval_status')) {
                $table->enum('approval_status', ['pending', 'approved', 'rejected'])->default('pending');
            }
        });
    }

    public function down()
    {
        // Revert users role (careful, this might data loss if cafe_owners exist)
        // We will skip reverting enum to avoid complexity/data loss in dev

        Schema::table('cafes', function (Blueprint $table) {
            $table->dropColumn('approval_status');
        });
    }
};
