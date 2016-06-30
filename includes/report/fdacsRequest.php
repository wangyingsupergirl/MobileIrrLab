<?php

require_once "report_11a.php";
require_once "report_11b.php";
$start_year = 2010;
$end_year = 2012;
for($year = $start_year; $year <= $end_year; $year++){
$report = new Report11A();
$rez = $report->requestDataAllYearAllLabs($year, 'approved');
if(!$rez) {echo "no eval"; exit;}
$rows = $report->fillFields();
$fp = fopen("{$year}Report11a.csv","w");
if(count($rows)>0){
    $keys = array();
    foreach($rows[0] as $key => $val){
        array_push($keys, $key);
    }
     fputcsv($fp, $keys);
}
foreach($rows as $row){
    
    fputcsv($fp, $row);
}
fclose($fp);

$report = new Report11B();
$rez = $report->requestDataAllYearAllLabs($year, 'approved');
if(!$rez) {echo "no eval"; exit;}
$rows = $report->fillFields();
$fp = fopen("{$year}Report11b.csv","w");
if(count($rows)>0){
    $keys = array();
    foreach($rows[0] as $key => $val){
        array_push($keys, $key);
    }
     fputcsv($fp, $keys);
}
foreach($rows as $row){
    
    fputcsv($fp, $row);
}
fclose($fp);
}
?>
