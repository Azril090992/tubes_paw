<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up()
    {
        DB::statement("ALTER TABLE bookings MODIFY payment_status VARCHAR(255) DEFAULT 'unpaid'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // No simple reverse for "change to string" if we don't know exact previous state, 
        // but we can leave it as string.
    }
};
