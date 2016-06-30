<?php
//report 1a start here

require_once "report_1a.php";
$report = new Report1A();
$params = array(
    'mil_labs'=>'16,18'
    ,'cal_start_yr'=>2011
    ,'cal_start_month'=>1
    ,'cal_end_yr'=>2011
    ,'cal_end_month'=>12
);
$report->init($params);
$report->getEvals();
$report->getWaitEvals();
$report->calculateData();
echo $report->getReport();

//report 2 start here
require_once "report_2.php";
$report  = new Report2();
$params = array(
    'mil_labs'=>'16,18'
    ,'cal_start_yr'=>2011
    ,'cal_start_month'=>1
    ,'cal_end_yr'=>2011
    ,'cal_end_month'=>12
);
$report->init($params);
$report->getEvals();
$report->getWaitEvals();
$report->calculateData();
echo $report->getReport();


//report 4b start here

require_once "report_4b.php";
$report  = new Report4b();
$params = array(
    'mil_labs'=>'16,18'
    ,'cal_start_yr'=>2011
    ,'cal_start_month'=>1
    ,'cal_end_yr'=>2011
    ,'cal_end_month'=>12
);
$report->init($params);
$report->getTypeofIrrSys();
echo $report->getReport();

//report 6b start here
require_once "report_6b.php";
$report  = new Report6b();
$params = array(
    'mil_labs'=>'16,18'
    ,'cal_start_yr'=>2011
    ,'cal_start_month'=>1
    ,'cal_end_yr'=>2011
    ,'cal_end_month'=>12
);
$report->init($params);
$report->getEvals();
$report->calculateData();
echo $report->getReport();
?>
