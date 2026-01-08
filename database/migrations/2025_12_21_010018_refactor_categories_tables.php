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
        // 1. Rename table if old one exists
        if (Schema::hasTable('menu_categories')) {
            Schema::rename('menu_categories', 'categories');
        }

        // 2. Adjust columns in categories
        if (Schema::hasTable('categories')) {
            Schema::table('categories', function (Blueprint $table) {
                if (Schema::hasColumn('categories', 'cafe_id')) {
                    // Try to drop foreign key if it likely exists
                    // We know based on previous debug it is menu_categories_cafe_id_foreign
                    try {
                        $table->dropForeign('menu_categories_cafe_id_foreign');
                    } catch (\Exception $e) {
                        try {
                            $table->dropForeign(['cafe_id']);
                        } catch (\Exception $ex) {
                        }
                    }
                    $table->dropColumn('cafe_id');
                }
            });
        }

        // 3. Add category_id to cafes
        if (Schema::hasTable('cafes') && !Schema::hasColumn('cafes', 'category_id')) {
            Schema::table('cafes', function (Blueprint $table) {
                $table->foreignId('category_id')->nullable()->constrained('categories')->onDelete('set null');
            });
        }

        // 4. Update menus table
        if (Schema::hasTable('menus')) {
            if (Schema::hasColumn('menus', 'menu_category_id')) {
                // Drop FK if exists
                try {
                    Schema::table('menus', function (Blueprint $table) {
                        $table->dropForeign('menus_menu_category_id_foreign');
                    });
                } catch (\Exception $e) {
                }

                DB::statement("ALTER TABLE menus CHANGE menu_category_id category_id BIGINT UNSIGNED NOT NULL");
            }

            if (Schema::hasColumn('menus', 'category_id')) {
                // Re-add FK
                try {
                    Schema::table('menus', function (Blueprint $table) {
                        $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
                    });
                } catch (\Exception $e) {
                }
            }
        }
    }

    public function down(): void
    {
        // Simplified down for emergency
    }
};
