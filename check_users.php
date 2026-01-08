<?php
use Illuminate\Support\Facades\DB;
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$cols = DB::select("DESCRIBE users");
foreach ($cols as $col) {
    if ($col->Field == 'role') {
        echo "Role Type: " . $col->Type . "\n";
    }
}
