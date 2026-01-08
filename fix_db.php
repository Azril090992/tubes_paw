<?php
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    echo "Updating users role...\n";
    DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('user', 'admin', 'cafe_owner') NOT NULL DEFAULT 'user'");
    echo "Users role updated.\n";
} catch (\Exception $e) {
    echo "Error updating users: " . $e->getMessage() . "\n";
}

try {
    echo "Updating cafes approval_status...\n";
    if (!Schema::hasColumn('cafes', 'approval_status')) {
        Schema::table('cafes', function (Blueprint $table) {
            $table->enum('approval_status', ['pending', 'approved', 'rejected'])->default('pending');
        });
        echo "Cafes approval_status added.\n";
    } else {
        echo "Cafes approval_status already exists.\n";
    }
} catch (\Exception $e) {
    echo "Error updating cafes: " . $e->getMessage() . "\n";
}
