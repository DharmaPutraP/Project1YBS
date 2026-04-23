<?php
require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;

$start='2026-04-16 07:00:00';
$end='2026-04-17 06:59:59';
$codes=['CWS','CWS1','CWS2','CWS3'];
$tables=['kernel_calculations','kernel_dirt_moist_calculations','kernel_qwt','kernel_ripple_mill','kernel_destoner'];
$all=[];
foreach($tables as $t){
  $rows=DB::table($t)
   ->whereBetween('rounded_time',[$start,$end])
   ->whereIn('kode',$codes)
   ->orderBy('rounded_time')
   ->get(['kode','rounded_time','created_at','sampel_boy','pengulangan','office']);
  echo "=== $t rows=".$rows->count()." ===\n";
  foreach($rows as $r){
    $arr=(array)$r;
    $arr=['source_table'=>$t]+$arr;
    $all[]=$arr;
    echo json_encode($arr, JSON_UNESCAPED_UNICODE)."\n";
  }
}
echo "TOTAL=".count($all)."\n";
