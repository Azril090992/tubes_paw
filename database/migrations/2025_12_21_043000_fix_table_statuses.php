<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Reset all tables status to 'available'.
        // This is a fix for tables that were wrongly marked as 'reserved'.
        DB::table('tables')->update(['status' => 'available']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No reverse needed really, we are just fixing data.
    }
};
