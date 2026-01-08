<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$columns = Schema::getColumnListing('bookings');
echo "Columns: " . implode(', ', $columns) . "\n";

$type = Schema::getColumnType('bookings', 'payment_status');
echo "Payment Status Type: " . $type . "\n";

// Raw describe for more detail (length)
$details = DB::select("DESCRIBE bookings");
foreach ($details as $col) {
    if ($col->Field === 'payment_status') {
        echo "Details for payment_status: " . json_encode($col) . "\n";
    }
}
