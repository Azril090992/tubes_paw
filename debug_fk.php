<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);

$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

$fks = DB::select("
    SELECT TABLE_NAME, COLUMN_NAME, CONSTRAINT_NAME, REFERENCED_TABLE_NAME
    FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE
    WHERE TABLE_SCHEMA = DATABASE() 
    AND REFERENCED_TABLE_NAME IS NOT NULL
    AND TABLE_NAME IN ('menu_categories', 'categories', 'menus', 'cafes')
");

$output = "";
foreach ($fks as $fk) {
    $output .= "Table: {$fk->TABLE_NAME} | Column: {$fk->COLUMN_NAME} | Constraint: {$fk->CONSTRAINT_NAME} | Ref Table: {$fk->REFERENCED_TABLE_NAME}\n";
}
file_put_contents('fks.txt', $output);
