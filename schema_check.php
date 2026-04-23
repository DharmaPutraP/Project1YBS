<?php
require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;

$tables = ['kernel_prosses','kernel_mesin','kernel_calculations','kernel_dirt_moist_calculations','kernel_qwt','kernel_ripple_mill','kernel_destoner'];
foreach($tables as $t){
    echo "=== $t ===\n";
    foreach(DB::select("SHOW COLUMNS FROM `$t`") as $col){
        echo $col->Field."|".$col->Type."\n";
    }
}
