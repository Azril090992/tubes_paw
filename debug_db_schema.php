<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== CAFES ===\n";
$columns = Schema::getColumnListing('cafes');
echo "Columns: " . implode(', ', $columns) . "\n";
// Check if status exists and its type if possible, though getColumnListing is usually enough to see existence.
$cafeCols = DB::select("DESCRIBE cafes");
foreach ($cafeCols as $col) {
    echo $col->Field . " (" . $col->Type . ")\n";
}

echo "\n=== USERS ===\n";
$userCols = DB::select("DESCRIBE users");
foreach ($userCols as $col) {
    echo $col->Field . " (" . $col->Type . ")\n";
}
