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


Class Report7 extends Report{

private $waitingEvals;
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
    $start_year = $array['cal_start_yr'];
    $quarter = $array['fed_quarter'];
    $this->reportTitleArr =  $array;
    $arr = Utility::getStartEndMonth($start_year, $quarter, FED);
    $start_month = $arr['start_month'];
    $end_month = $arr['end_month'];
    $eval_yr = $start_year;
    $this->reportTitleArr['fed_start_yr']=$eval_yr;
    $this->reportTitleArr['start_month']=$start_month;
    $this->reportTitleArr['end_month']=$end_month;
    $sql =
     "select
      county.name county_name
      ,crop.name crop_name
      ,waiting_eval.total_count count
      ,waiting_eval.total_acres acre
     from
        (select * from package where
          mil_id = $mil_id
          and eval_yr = $eval_yr
          and eval_month >= $start_month
          and eval_month <= $end_month ) as package
        inner join waiting_eval
        inner join ag_urban_types_names as crop
        inner join fl_county as county
     on
        package.id = waiting_eval.package_id
        and waiting_eval.category_id = crop.id
        and waiting_eval.county_id = county.id
        and package.status = '$status'
     ";

    $evals = MIL::doQuery($sql, MYSQL_ASSOC);
    $this->waitingEvals = $evals;
   
    return $evals;
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
      <th></th>
      <th>County</th>
      <th>Land Use</th>
      <th>Total Count</th>
      <th>Approx Total Acres</th>
  </tr>';
  return $this->tableHeader;
}

public function getReportTitle(){
$mil_id = $this->reportTitleArr['mil_id'];
$mils = Utility::getLookupTable('mil_lab', null);
$mil_name = $mils[$mil_id]->getProperty('mil_name');
$start_year = $this->reportTitleArr['fed_start_yr'];
$start_month= $this->reportTitleArr['start_month'];
$end_month = $this->reportTitleArr['end_month'];
//$end_year = $start_year+1;
$this->reportTitle ="<div style='margin:0 auto; width:800px;'> <h2 style='text-align:left;font-family:helvetica, arial, sans-serif;margin-bottom: 0;padding-bottom: 0;'>Report 7:MIL EVALUATION WAITING LIST</h2>
        <h4 style='font-weight: 100; text-align:left;font-family:helvetica, arial, sans-serif;margin: 0 10px;color:#666;'>MIL ID: $mil_id&nbsp;&nbsp; MIL Name: $mil_name&nbsp;&nbsp; Calendar Month Period: $start_month-$end_month  &nbsp;&nbsp; Calendar Year: $start_year  &nbsp;&nbsp;</h4> ";
return $this->reportTitle;
}

public function getTableContent(){
    $this->tableContent = "";
    $n = count($this->waitingEvals);
    $total_count = 0;
    $total_acre = 0;
    for($i = 0; $i < $n; $i++){
      $waitings = $this->waitingEvals[$i];
      foreach($waitings as $key => $val){
          if($val===false||$val===null|trim($val)===''){
              $waitings[$key] = 'NA';
          }
      }
      $total_count += $waitings['count'];
      $total_acre += $waitings['acre'];
      $j = $i + 1;
      $this->tableContent .=  '<tr  nobr="true">'.
     "<td style='font-weight:bold;'>$j</td>
      <td>{$waitings['county_name']}</td>
      <td>{$waitings['crop_name']}</td>
      <td>{$waitings['count']}</td>
      <td>{$waitings['acre']}</td>
     </tr>";
     }
     $timeString = date('m/d/Y');
    $this->tableContent .= "<tr><td style='font-weight:bold;'>Total</td>
      <td></td>
      <td></td>
      <td>$total_count</td>
      <td>$total_acre</td>
     </tr></table><div style='margin-top: 10px; text-align:left;font-family:helvetica, arial, sans-serif;margin: 5px 0 15px 0;'>Florida Department of Agriculture & Consumer Services</div></div><br/><br/><br/><br/><br/><br/>{$timeString}";
    return $this->tableContent;
}

}
?>
