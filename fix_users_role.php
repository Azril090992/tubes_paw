<?php
use Illuminate\Support\Facades\DB;

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    echo "Attempting to update users role...\n";
    // Try standard MySQL syntax
    DB::statement("ALTER TABLE users MODIFY role ENUM('user', 'admin', 'cafe_owner') DEFAULT 'user'");
    echo "Users role updated successfully.\n";
} catch (\Exception $e) {
    echo "Error 1: " . $e->getMessage() . "\n";
    try {
        // Try without DEFAULT first? or CHANGE syntax
        DB::statement("ALTER TABLE users CHANGE role role ENUM('user', 'admin', 'cafe_owner') DEFAULT 'user'");
        echo "Users role updated with CHANGE syntax.\n";
    } catch (\Exception $ex) {
        echo "Error 2: " . $ex->getMessage() . "\n";
    }
}
