<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("ALTER TABLE cafes MODIFY latitude DECIMAL(10, 8) NULL");
        DB::statement("ALTER TABLE cafes MODIFY longitude DECIMAL(11, 8) NULL");
        DB::statement("ALTER TABLE cafes MODIFY open_time TIME NULL");
        DB::statement("ALTER TABLE cafes MODIFY close_time TIME NULL");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE cafes MODIFY latitude DECIMAL(10, 8) NOT NULL");
        DB::statement("ALTER TABLE cafes MODIFY longitude DECIMAL(11, 8) NOT NULL");
        DB::statement("ALTER TABLE cafes MODIFY open_time TIME NOT NULL");
        DB::statement("ALTER TABLE cafes MODIFY close_time TIME NOT NULL");
    }
};
