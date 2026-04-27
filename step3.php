<?php
require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;

$date='2026-04-16';
$proses=DB::table('kernel_prosses')->whereDate('process_date',$date)->orderBy('id')->get(['id','office','input_team']);
foreach($proses as $p){
    echo "=== kernel_prosses_id={$p->id} office={$p->office} input_team={$p->input_team} ===\n";
    $mesins=DB::table('kernel_mesin')
      ->where('kernel_prosses_id',$p->id)
      ->where('machine_name','like','CLAYBATH WET SHELL%')
      ->orderBy('id')
      ->get(['id','machine_name','team_name','production_start_time','production_end_time','total_produsction_hours','is_spare_input']);
    echo "rows=".$mesins->count()."\n";
    foreach($mesins as $m){ echo json_encode($m, JSON_UNESCAPED_UNICODE)."\n"; }
}
