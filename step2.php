<?php
require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;

$date='2026-04-16';
$rows=DB::table('kernel_prosses')->whereDate('process_date',$date)->orderBy('id')->get([
'id','office','process_date','input_team','team_1_members','team_2_members',
'team_1_start_time','team_1_end_time','team_1_start_downtime','team_1_end_downtime',
'team_2_start_time','team_2_end_time','team_2_start_downtime','team_2_end_downtime','created_at'
]);

echo "kernel_prosses rows=".$rows->count()."\n";
foreach($rows as $r){
    echo json_encode($r, JSON_UNESCAPED_UNICODE)."\n";
}
