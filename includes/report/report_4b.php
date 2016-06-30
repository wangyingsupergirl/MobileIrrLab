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


Class Report4b extends Report{

private $params = array(
    'mil_labs'=>array()
    ,'cal_start_yr'=>''
    ,'cal_start_month'=>''
    ,'cal_end_yr'=>''
    ,'cal_end_month'=>''
    ,'eval_method' => ''
);

private $results = array();
private $irrSysTypeCategory;
private  $counties;
private $reportTitle;
private $reportTitleArr = array();
private $tableHeader;
private $tableContent;


public function init($array){
 //initialize request params $this->params
 foreach($this->params as $key=>$val){
  if(array_key_exists($key,$array)){
    if($key=='mil_labs')$array[$key] = explode(",",$array[$key]); 
   $this->params[$key] = $array[$key];
  }else{
   throw new Exception("Parameter $key is required.");
  }
 }
 $this->irrSysTypeCategory = Utility::getLookupTable('irr_sys_type_category');
 $this->counties = Utility::getLookupTable("fl_county");
}

public function requestDBData($array){
    $this->init($array);
    $this->getTypeofIrrSys();
    if(count($this->results)<1)return false;
    return true;
    //No. Evaluation, total acre,  Start Date, End Date
   
}

public function getTypeofIrrSys() {
  $labs_id = $this->params['mil_labs'];
  $start_yr = $this->params['cal_start_yr'];
  $end_yr = $this->params['cal_end_yr'];
  $start_month = $this->params['cal_start_month'];
  $end_month = $this->params['cal_end_month'];
  $this->eval_method = $this->params['eval_method'];
        if ($start_month <= 9) {
            $start_yr_month = $start_yr . '-0' . $start_month ;
        } else {
            $start_yr_month = $start_yr . '-' . $start_month ;
        }
   $start_yr_month="'".date('Y-m-01', strtotime($start_yr_month))."'";
  if ($end_month <= 9) {
            $end_yr_month = $end_yr . '-0' . $end_month;
        } else {
            $end_yr_month = $end_yr . '-' . $end_month;
   }
   $end_yr_month="'".date('Y-m-t', strtotime($end_yr_month))."'";
  if($labs_id!=null && count($labs_id)!=0){
   $labs_str = implode(",", $labs_id);
  }else{
   return;
  }
  if ($this->eval_method != 'both'){
    $sql = "select 
    eval.county_id
   , irr.category_id
   , min(eval.eval_yr_month) start_date
   , max(eval.eval_yr_month) end_date
   , sum(eval.acre)  eval_acre
   from 
    (select 
        mil_id
        , county_id
        , irr_sys_type
        , id
        , eval_yr_month
        , acre  from evaluation 
    where 
        eval_yr_month >= $start_yr_month 
        and eval_yr_month <= $end_yr_month 
        and mil_id in ($labs_str)
        and eval_method = '"."$this->eval_method"."' 
        and status='approved') as eval
   inner join 
    (select  
        a.id irr_sys_type_id
       , a.category category_id 
     from irr_sys_types a 
     inner join irr_sys_type_category b 
     on a.category = b.id) irr 
   on eval.irr_sys_type= irr.irr_sys_type_id
   group by county_id, category_id
   order by county_id"; 
   }
   else{
   $sql = "select 
    eval.county_id
   , irr.category_id
   , min(eval.eval_yr_month) start_date
   , max(eval.eval_yr_month) end_date
   , sum(eval.acre)  eval_acre
   from 
    (select 
        mil_id
        , county_id
        , irr_sys_type
        , id
        , eval_yr_month
        , acre  from evaluation 
    where 
        eval_yr_month >= $start_yr_month 
        and eval_yr_month <= $end_yr_month 
        and mil_id in ($labs_str)
        and status='approved') as eval
   inner join 
    (select  
        a.id irr_sys_type_id
       , a.category category_id 
     from irr_sys_types a 
     inner join irr_sys_type_category b 
     on a.category = b.id) irr 
   on eval.irr_sys_type= irr.irr_sys_type_id
   group by county_id, category_id
   order by county_id"; 
   }
  $irr_sys_types = MIL::doQuery($sql, MYSQL_ASSOC);
  if($irr_sys_types!=false){
   foreach($irr_sys_types  as $key => $record){
    $county_id = $record['county_id'];
    $irr_sys_category_id = $record['category_id'];
    $eval_acre = $record['eval_acre'];
    if(!isset($this->results[$county_id])){
     $this->results[$county_id] =  array();
    }
    $this->results[$county_id][$irr_sys_category_id] = $eval_acre;
    if(!isset($this->results[$county_id]['start_date'])||$this->results[$county_id]['start_date']>$record['start_date'])
    $this->results[$county_id]['start_date'] = $record['start_date'];
    if(!isset($this->results[$county_id]['end_date'])||$this->results[$county_id]['end_date']<$record['end_date'])
    $this->results[$county_id]['end_date'] = $record['end_date'];
    
    
   }
  }
 
}
 

public function getReport(){
 $html="
	<style>
		body{
			font-size: 120%;	
		}

		table {
			margin: 0;
			padding: 0;
			width: 100%;
			border-collapse: collapse;
		}

		table th,
		table td {
			padding: 10px 20px;
			text-align: left;
		}

		table th {
			border-width: 2px;
		}

		table td {
			color: #666;
		}

		table tr:last-child th,

		table tr:last-child td {
			border-bottom: none;
		}

		table tr:nth-child(even) {
			background: #eee;
		}	
	</style>";
    $html = $this->getReportTitle();
    $html .= $this->getTableHeader();
    $html .= $this->getTableContent();
    return $html;
}

public function getTableHeader(){
$this->tableHeader =
 '<table border="1" cellspacing="0" cellpadding="5" width="100%">
  <tr  nobr="true">
  <th width="10%">County</th>
  ';
foreach($this->irrSysTypeCategory as $key => $obj){
 $this->tableHeader .= "<th width='10%'>{$obj->getProperty("name")}</th>";
}
$this->tableHeader .='<th>TOTAL AG ACRES</th>';
$this->tableHeader .='<th>Available Start Date</th><th>Available End Date</th></tr>';
  return $this->tableHeader;
}

public function getReportTitle(){
$start_year = $this->params['cal_start_yr'];
$end_year = $this->params['cal_end_yr'];
$this->reportTitle =
        " <h2>
          Report 4B: Ag Acres Evaluated - By County and Type of Irrigation System<br />
          WITH {$this->eval_method_array[$this->eval_method]} <br />
         </h2>
         <h4>
          REQUESTED PERIOD From: {$this->params['cal_start_yr']}-{$this->params['cal_start_month']} To: {$this->params['cal_end_yr']}-{$this->params['cal_end_month']}
        </h4>";
return $this->reportTitle;
}

public function getTableContent(){
    $this->tableContent = "";
   //total for all
    $totals = 0;
     // the total in the last line
    $totals_by_category = array();
    foreach($this->irrSysTypeCategory as $category_id => $obj){
      $totals_by_category[$category_id] = 0;
    }
    foreach($this->results as $county_id => $row){
     $total_by_county = 0;//display in the end of each line
     $this->tableContent .=  '<tr  nobr="true">'.
     "<td>{$this->counties[$county_id]->getProperty("name")}</td> ";
     foreach($this->irrSysTypeCategory as $key => $obj){
       $category_id = $obj->getProperty("id");
       if(array_key_exists($category_id, $row)){
        $this->tableContent .="<td>{$row[$category_id]}</td>";
        $total_by_county += $row[$category_id];
        $totals_by_category[$category_id] += $row[$category_id];
       }else{
        $this->tableContent .="<td>0</td>";
       }
     }
     $this->tableContent .="<td>$total_by_county</td>";
     $this->tableContent .="<td>{$row['start_date']}</td>";
     $this->tableContent .="<td>{$row['end_date']}</td>";
     $totals += $total_by_county;
     $this->tableContent .= "</tr>";
    }/*
    foreach($this->counties as $county_id => $county_obj){
     if(!array_key_exists($county_id,$this->results)){
      continue;
     }
     $row = $this->results[$county_id];
     $total_by_county = 0;//display in the end of each line
     $this->tableContent .= "<tr><td>{$county_obj->getProperty("name")}</td> ";
     foreach($this->irrSysTypeCategory as $key => $obj){
       $category_id = $obj->getProperty("id");
       if(array_key_exists($category_id, $row)){
        $this->tableContent .="<td>{$row[$category_id]}</td>";
        $total_by_county += $row[$category_id];
        $totals_by_category[$category_id] += $row[$category_id];
       }else{
        $this->tableContent .="<td>0</td>";
       }
     }
     $this->tableContent .="<td>$total_by_county</td>";
     $this->tableContent .="<td>{$this->params['cal_start_yr']}-{$row['start_month']}</td>";
     $this->tableContent .="<td>{$this->params['cal_start_yr']}-{$row['end_month']}</td>";
     $totals += $total_by_county;
     $this->tableContent .= "</tr>";
    }*/
    $timeString = date('m/d/Y');
    $this->tableContent .= '<tr  nobr="true">'.
     "<td>Total Ag Acres: </td>";
    foreach($totals_by_category as $id => $num){
       $this->tableContent .="<td>$num</td>";
    }
     $this->tableContent .="<td>$totals</td><td></td><td></td></tr>";
    $this->tableContent .= "</table><div>Florida Department of Agriculture & Consumer Services</div><br/><br/><br/><br/><br/><br/>{$timeString}";
    return $this->tableContent;
}

}
?>
