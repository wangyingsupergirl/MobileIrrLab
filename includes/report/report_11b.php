<?php

require_once dirname(__FILE__).'/../mil_init.php';
require_once dirname(__FILE__).'/../utility.php';
require_once dirname(__FILE__) . '/../input/package/evaluation/FollowIrrSys.php';
require_once dirname(__FILE__) . '/../input/package/evaluation/InitIrrSys.php';
require_once dirname(__FILE__) . '/../input/package/evaluation/FollowFirm.php';
require_once dirname(__FILE__) . '/../input/package/evaluation/InitFirm.php';


Class Report11B extends Report{

private $evals;
private $reportTitle;
private $reportTitleArr = array();
private $tableHeader;
private $tableContent;
private $start_year;
private $end_year;
private $start_month;
private $end_month;


public function printPDF($array){
    //Set $data
    $evals = $this->requestDBData($array);

}
public function requestDBData($array, $status){
    $mil_id = $array['mil_id'];
    $this->start_year = $array['cal_start_yr'];
    $this->end_year = $array['cal_end_yr'];
    //$quarter = $array['fed_quater'];
    $this->reportTitleArr['mil_id'] = $mil_id;
    //$arr = Utility::getStartEndMonth($start_year, $quarter, FED);
    $this->start_month = $array['cal_start_month'];
    $this->end_month = $array['cal_end_month'];
    //$eval_yr = $arr['year'];
    if($this->start_year===$this->end_year){
         $sql = 
    "select
        e.id
        ,e.eval_method
        ,c.name county_name
        ,e.zip_code zip_code
        ,e.soil_type soil_type
        ,w.type water_source
        ,water_saving.evaluation_type eval_type_name
        ,e.tds, e.ph
        ,p.type pump_type
        ,e.has_flow_meter
        ,d.name device_gpm
        ,e.from_flow_meter
        ,e.from_device
        ,m.type motor_type
        ,e.firm_aws
        ,e.firm_pws
        ,e.firm_iws
     , e.display_id 
    from
        evaluation as e
        inner join fl_county as c
        inner join water_source_types as w
        inner join eval_types_water_saving_types as water_saving
        inner join pump_types as p
        inner join motor_types as m
        inner join device_gpm as d
    on
        water_saving.id=e.eval_type
        and c.id = e.county_id
        and e.water_source = w.id
        and p.id= e.pump_type
        and m.id = e.motor_type
        and d.id = e.device_gpm
    where    (
          eval_yr = $this->start_year and
          eval_month >= $this->start_month and
          eval_month <= $this->end_month) and
          e.status = '$status'and
          mil_id = $mil_id
    order by e.display_id
    ";   
    }
    else{
    $sql = 
    "select
        e.id
        ,e.eval_method
        ,c.name county_name
        ,e.zip_code zip_code
        ,e.soil_type soil_type
        ,w.type water_source
        ,water_saving.evaluation_type eval_type_name
        ,e.tds, e.ph
        ,p.type pump_type
        ,e.has_flow_meter
        ,d.name device_gpm
        ,e.from_flow_meter
        ,e.from_device
        ,m.type motor_type
        ,e.firm_aws
        ,e.firm_pws
        ,e.firm_iws
     , e.display_id 
    from
        evaluation as e
        inner join fl_county as c
        inner join water_source_types as w
        inner join eval_types_water_saving_types as water_saving
        inner join pump_types as p
        inner join motor_types as m
        inner join device_gpm as d
    on
        water_saving.id=e.eval_type
        and c.id = e.county_id
        and e.water_source = w.id
        and p.id= e.pump_type
        and m.id = e.motor_type
        and d.id = e.device_gpm
    where    (eval_yr > $this->start_year and
          eval_yr < $this->end_year or
          eval_yr = $this->start_year and
          eval_month >= $this->start_month or
          eval_yr =$this->end_year and
          eval_month <= $this->end_month) and
          e.status = '$status'and
          mil_id = $mil_id
    order by e.display_id
    ";
    }
    
    $evals = MIL::doQuery($sql, MYSQL_ASSOC);
   
    $this->evals = $evals;
    return $evals;
}

public function requestDataAllYearAllLabs($eval_yr, $status) {
    $sql =
    "select
    e.id eval_id
   , e.farm_id  irrig_sys_id
    ,e.mil_id
    ,e.eval_yr
    ,e.eval_month
    ,e.eval_type
    ,water_saving.evaluation_type eval_type_name
    ,e.eval_method
    ,c.name county_name
    ,e.zip_code zip_code
    ,e.soil_type soil_type
    ,w.type water_source
    ,e.tds, e.ph
    ,p.type pump_type
    ,e.has_flow_meter
    ,d.name device_gpm
    ,e.from_flow_meter
    ,e.from_device
    ,m.type motor_type
    ,e.firm_aws
    ,e.firm_pws
    ,e.firm_iws
    , e.display_id 
    from
    evaluation as e
    inner join fl_county as c
    inner join water_source_types as w
    inner join eval_types_water_saving_types as water_saving
    inner join pump_types as p
    inner join motor_types as m
    inner join device_gpm as d
    on
    c.id = e.county_id
    and e.water_source = w.id
    and p.id= e.pump_type
    and m.id = e.motor_type
    and d.id = e.device_gpm
    where eval_yr >= $this->start_year and eval_yr <= $this->end_year
    e.status = '$status'
    order by e.mil_id, e.eval_yr, e.eval_month, e.display_id
    ";
     $evals = MIL::doQuery($sql, MYSQL_ASSOC);
    $this->evals = $evals;
    return $evals;
}
public function getReport(){
    $html = $this->getReportTitle();
    $html .= $this->getTableHeader();
    $html .= $this->getTableContent();
   // echo $html;exit;
    return $html;

}

public function getTableHeader(){
$this->tableHeader =
 //'<table  border="1" cellpadding="5" cellspacing="0" nobr="true" style="font-size:85%(can not be used); font-weight:100; text-align:left; font-family:helvetica,arial,sans-serif; margin: 5px 10px; border-collapse: collapse;">
  '<table  border="1" cellpadding="1" style="text-align:center; font-family:helvetica,arial,sans-serif; margin: 5px 10px;border-collapse: collapse;">
   <tr style="background-color:#ccc;" nobr="true">
      <th rowspan="2" width="8%">Eval ID #</th>
      <th rowspan="2">Evaluation Type</th>
      <th rowspan="2" >Evaluation Method</th>
      <th rowspan="2">County Name</th>
      <th rowspan="2">Zip Code</th>
      <th rowspan="2">Soil Type<br>
        No.</th>
      <th rowspan="2">Water Source</th>
      <th rowspan="2">TDS</th>
      <th rowspan="2">pH</th>
      <th rowspan="2">Pump Type</th>
      <th rowspan="2">Has Flow Meter?</th>
      <th rowspan="2">Device Used to Measure GPM</th>
      <th colspan="2">Gallons per Minutes</th>
      <th rowspan="2">Motor Type</th>
      <th colspan="3"><sub>Savings From Irrig Sys &amp; Mgmt, per FIRM (ac-ft)</sub></th>
    </tr>
    <tr style="font-family: helvetica, arial, verdana;font-weight:bold;font-size:7;" nobr="true">
      <th>From Permanent Flow Meter</th>
      <th>From Device used to verify GPM</th>
      <th>Potential</th>
      <th>Actual</th>
      <th>Immediate</th>
    </tr>';
  return $this->tableHeader;
}

public function getReportTitle(){
$mil_id = $this->reportTitleArr['mil_id'];
$mils = Utility::getLookupTable('mil_lab', null);
$mil_name = $mils[$mil_id]->getProperty('mil_name');
//$start_year = $this->reportTitleArr['fed_start_yr'];
//$end_year = $start_year+1;
$this->reportTitle =" <h2 style='text-align:left;font-family:helvetica, arial, sans-serif;margin: 25px 10px 0 10px; padding-bottom: 0;'>Report No. 11b
       IRRIGATION SYSTEM WATER SOURCE, PUMPING STATION, AND OTHER  INFO </h2>".
        //<h4 style='font-weight: 100; text-align:left;font-family:helvetica, arial, sans-serif;margin: 0 10px;color:#666;'>MIL ID: $mil_id&nbsp;&nbsp; MIL Name: $mil_name&nbsp;&nbsp; Federal Quarter: {$this->reportTitleArr['fed_quater']}&nbsp;&nbsp; Federal Fiscal Year: $start_year-$end_year  &nbsp;&nbsp;</h4> ";
       " <h4 style='font-weight: 100; text-align:left;font-family:helvetica, arial, sans-serif;margin: 0 10px;color:#666;'>MIL ID: $mil_id&nbsp;&nbsp; MIL Name: $mil_name&nbsp;&nbsp;REQUESTED PERIOD From: $this->start_year - $this->start_month To: $this->end_year - $this->end_month &nbsp;&nbsp;</h4> ";
return $this->reportTitle;
}

public function fillFields() {
        $this->tableContent = "";
        $n = count($this->evals);
        $rows = array();
        for ($i = 0; $i < $n; $i++) {
            $eval = $this->evals[$i];
            foreach ($eval as $key => $val) {
                if ($val === false || $val === null | trim($val) === '') {
                    $eval[$key] = 'NA';
                }
            }
            $eval['eval_method'] = ($eval['eval_method'] == 'irr' ? 'Irrigation System Only' : 'FIRM');
            $eval['has_flow_meter'] = ucfirst($eval['has_flow_meter']);
            $eval['from_flow_meter'] = (  $eval['has_flow_meter'] == 'No' ? 'NA' : $eval['from_flow_meter']);
            $eval['from_device']= ($eval['device_gpm'] == 'None' ? 'NA' : $eval['from_device']);
            $eval['firm_aws'] = ($eval['eval_method'] == 'irr' ? 'NA' : $eval['firm_aws']);
            $eval['firm_pws'] = ($eval['eval_method'] == 'irr' ? 'NA' : $eval['firm_pws']);
            $eval['firm_iws'] = ($eval['eval_method'] == 'irr' ? 'NA' : $eval['firm_iws']);
            array_push($rows, $eval);
        }
        return $rows;
    }

    public function getTableContent(){
    $this->tableContent = "";
    $n = count($this->evals);
    for($i = 0; $i < $n; $i++){
      $eval = $this->evals[$i];
      $color = ($i % 2 == 0 ? "#FFF" : "#EEE");
      foreach($eval as $key => $val){
          if($val===false||$val===null|trim($val)===''){
              $eval[$key] = 'NA';
          }
      }
      $eval_method = ($eval['eval_method'] == 'irr' ? 'Irrigation System Only' : 'FIRM');
      $eval_type_name=$eval['eval_type_name'];
      $county_name = $eval['county_name'];
      $zip_code = $eval['zip_code'];
      $soil_type = $eval['soil_type'];
      $water_source= $eval['water_source'];
      $tds = $eval['tds'];
      $ph = $eval['ph'];
      $pump_type  = $eval['pump_type'];
      $has_flow_meter = ucfirst($eval['has_flow_meter']);
      $device_gpm = $eval['device_gpm'];
      $from_flow_meter = ($has_flow_meter=='No'?'NA':$eval['from_flow_meter']);
      $from_device = ($device_gpm=='None'?'NA':$eval['from_device']);
      $motor_type_id = $eval['motor_type'];
      $firm_aws = ($eval_method=='irr'?'NA':$eval['firm_aws']);
      $firm_pws = ($eval_method=='irr'?'NA':$eval['firm_pws']);
      $firm_iws = ($eval_method=='irr'?'NA':$eval['firm_iws']);
      $index = $eval['id'];
      
     $timeString = date('m/d/Y');
       $this->tableContent .= '<tr style="background-color:'.$color.';"  nobr="true" >'."
	  <td>$index</td>
             <td>$eval_type_name</td>
                 <td>$eval_method</td>
             <td>$county_name</td>
              <td>$zip_code</td>
              <td>$soil_type</td>
              <td>$water_source</td>
              <td>$tds</td>
              <td>$ph</td>
              <td>$pump_type</td>
              <td>$has_flow_meter</td>
              <td>$device_gpm </td>
              <td>$from_flow_meter</td>
              <td>$from_device</td>
              <td>$motor_type_id</td>
              <td>$firm_pws</td>
              <td>$firm_aws</td>             
              <td>$firm_iws</td>
            </tr>";
    }
    $this->tableContent .= "</table><div style='margin-top: 10px; text-align:left;font-family:helvetica, arial, sans-serif;margin: 5px 0 15px 10px;'>Florida Department of Agriculture & Consumer Services</div><br/><br/><br/><br/><br/><br/>{$timeString}";
    return $this->tableContent;
}

}
?>
