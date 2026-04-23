<?php
require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;

function out($label, $value) { echo $label . $value . PHP_EOL; }

out('env=', config('app.env'));
out('db=', config('database.default'));
out('conn=', DB::connection()->getDatabaseName());
foreach (['kernel_prosses','kernel_mesins','kernel_calculations','kernel_dirt_moist_calculations','kernel_qwts','kernel_ripple_mills','kernel_destoners'] as $t) {
    try {
        out($t.':', DB::table($t)->count());
    } catch (Throwable $e) {
        out($t.':ERR:', $e->getMessage());
    }
}
