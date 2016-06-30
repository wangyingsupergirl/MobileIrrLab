<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
require_once dirname(__FILE__).'/../mil_init.php';
require_once dirname(__FILE__).'/../utility.php';
require_once dirname(__FILE__) . '/../input/package/evaluation/FollowIrrSys.php';
require_once dirname(__FILE__) . '/../input/package/evaluation/InitIrrSys.php';
require_once dirname(__FILE__) . '/../input/package/evaluation/FollowFirm.php';
require_once dirname(__FILE__) . '/../input/package/evaluation/InitFirm.php';


Class Report8 extends Report{

private $presentations;
private $reportTitle;
private $reportTitleArr = array();
private $tableHeader;
private $tableContent;



public function printPDF($array){
    //Set $data
    $evals = $this->requestDBData($array);

}
public function requestDBData($array,$status){
    $mil_id = $array['mil_id'];
    $this->start_year = $array['cal_start_yr'];
    $this->end_year = $array['cal_end_yr']; 
    $this->start_month = $array['cal_start_month'];
    $this->end_month = $array['cal_end_month'];
    //$start_year = $array['fed_start_yr'];
    //$quarter = $array['fed_quater'];
    $this->reportTitleArr =  $array;
    //$arr = Utility::getStartEndMonth($start_year, $quarter, FED);
    $this->reportTitleArr["start_yr_mon"]=$this->start_year.'-'.$this->start_month;
    $this->reportTitleArr["end_yr_mon"]=$this->end_year.'-'.$this->end_month;
    //$start_month = $arr['start_month'];
   // $end_month = $arr['end_month'];
   // $eval_yr = $arr['year'];
    if($this->start_year===$this->end_year){
     $sql =
     "select
       er.presentation_date date
      ,er.presentation_types types
      ,er.group_name
      ,er.attending_num
      ,er.city
      ,er.duration
     from
        (select * from package where
          mil_id = $mil_id
          and ( eval_yr = $this->start_year and
          eval_month >= $this->start_month and
          eval_month <= $this->end_month) and status='$status') as package
        inner join education_reports as er
     on
        package.id = er.package_id
       
     ";
    }
    else{
    $sql =
     "select
       er.presentation_date date
      ,er.presentation_types types
      ,er.group_name
      ,er.attending_num
      ,er.city
      ,er.duration
     from
        (select * from package where
          mil_id = $mil_id
          and (eval_yr > $this->start_year and
          eval_yr < $this->end_year or
          eval_yr = $this->start_year and
          eval_month >= $this->start_month or
          eval_yr =$this->end_year and
          eval_month <= $this->end_month) and status='$status') as package
        inner join education_reports as er
     on
        package.id = er.package_id
       
     ";
    }

    $result = MIL::doQuery($sql, MYSQL_ASSOC);
    $this->presentations =  $result;

    return $result;
}

public function getReport(){
    $html = $this->getReportTitle();
    $html .= $this->getTableHeader();
    $html .= $this->getTableContent();
    return $html;

}
public function getTableHeader(){
$this->tableHeader =
 '<table  border="1" cellpadding="5" cellspacing="0" nobr="true" style="font-size:85%; font-weight:100; text-align:left; font-family:helvetica,arial,sans-serif; margin: 5px 0; border-collapse: collapse;">
  <tr style="background-color:#ccc;">
    <th>Date</th>
    <th>Type of Presentation</th>
    <th>Name of Group</th>
    <th>Number Attending</th>
    <th>City or Town</th>
    <th>Duration  (hrs)</th>
  </tr>';
  return $this->tableHeader;
}

public function getReportTitle(){
$mil_id = $this->reportTitleArr['mil_id'];
$mils = Utility::getLookupTable('mil_lab', null);
$mil_name = $mils[$mil_id]->getProperty('mil_name');
$start_year = $this->reportTitleArr['fed_start_yr'];
$end_year = $start_year+1;
$this->reportTitle ="<div style='margin:0 auto; width:800px;'> <h2 style='text-align:left;font-family:helvetica, arial, sans-serif;margin-bottom: 0;padding-bottom: 0;'>Report 8:MIL Conservation Education and Outreach Report</h2>
        <h4 style='font-weight: 100; text-align:left;font-family:helvetica, arial, sans-serif;margin: 0 10px;color:#666;'>MIL ID: $mil_id&nbsp;&nbsp; MIL Name: $mil_name&nbsp;&nbsp; Federal Fiscal Start Date: {$this->reportTitleArr["start_yr_mon"]} &nbsp;&nbsp;Federal Fiscal End Date: {$this->reportTitleArr["end_yr_mon"]} &nbsp;&nbsp;</h4> ";
return $this->reportTitle;
}

public function getTableContent(){
    $this->tableContent = "";
    $n = count($this->presentations);
    $total_count = 0;
    $total_duration = 0;
    for($i = 0; $i < $n; $i++){
      $pres = $this->presentations[$i];
      foreach($pres as $key => $val){
          if($val===false||$val===null|trim($val)===''){
              $pres[$key] = 'NA';
          }
      }
      $total_count += $pres['attending_num'];
      $total_duration +=$pres['duration'];
      $j = $i + 1;
      $types = Utility::getNamesByIDs($pres['types'],'presentation_types');
      $this->tableContent .= '<tr  nobr="true">'.
     "<td>{$pres['date']}</td>
      <td>$types</td>
      <td>{$pres['group_name']}</td>
      <td>{$pres['attending_num']}</td>
      <td>{$pres['city']}</td>
      <td>{$pres['duration']}</td>
     </tr>";
     }
     $timeString = date('m/d/Y');
    $this->tableContent .= "<tr>
       <td style='font-weight:bold;'>Totals</td>
      <td></td>
      <td></td>
       <td> $total_count</td>
      <td></td>
      <td>$total_duration</td>
     </tr></table><div style='margin-top: 10px; text-align:left;font-family:helvetica, arial, sans-serif;margin: 5px 0 15px 0;'>Florida Department of Agriculture & Consumer Services</div></div><br/><br/><br/><br/><br/><br/>{$timeString}";
    return $this->tableContent;
}

}
?>
