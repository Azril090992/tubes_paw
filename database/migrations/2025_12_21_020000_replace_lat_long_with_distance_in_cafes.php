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
        Schema::table('cafes', function (Blueprint $table) {
            if (Schema::hasColumn('cafes', 'latitude')) {
                $table->dropColumn('latitude');
            }
            if (Schema::hasColumn('cafes', 'longitude')) {
                $table->dropColumn('longitude');
            }
            if (!Schema::hasColumn('cafes', 'distance')) {
                $table->decimal('distance', 8, 2)->nullable()->after('address')->comment('Manual distance in km');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cafes', function (Blueprint $table) {
            $table->dropColumn('distance');
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
        });
    }
};
