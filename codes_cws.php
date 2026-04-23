<?php
require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;
$tables=['kernel_calculations','kernel_dirt_moist_calculations','kernel_qwt','kernel_ripple_mill','kernel_destoner'];
foreach($tables as $t){
  $codes=DB::table($t)->where('kode','like','CWS%')->distinct()->orderBy('kode')->pluck('kode')->toArray();
  echo $t.':'.json_encode($codes, JSON_UNESCAPED_UNICODE)."\n";
}
