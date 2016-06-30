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


Class Report9 extends Report{


private $reportTitle;
private $reportTitleArr = array();
private $requestedArr;
private $tableContent;
private $attrArr = array(
     'fdacs_id'
    ,'reporting_period'
    ,'cal_start_yr'
    ,'mil_id'
    ,'mil_name'
    ,'evals_required'
    ,'followup_evals_required'
    ,'completed_initial_evals'
    ,'inital_eval_acres'
    ,'completed_followup_evals'
    ,'followup_eval_acres'
    ,'num_of_waiting_eval'
    ,'waiting_eval_acres'
);


public function printPDF($array){
    //Set $data
    $evals = $this->requestDBData($array);

}
public function requestDBData($array,$status){
    $this->pageOrientation = 'P';
    $mil_id = $array['mil_id'];
    /*
    $this->start_year = $array['cal_start_yr'];
    $this->end_year = $array['cal_end_yr']; 
    $this->start_month = $array['cal_start_month'];
    $this->end_month = $array['cal_end_month'];*/
    $start_year = $array['cal_start_yr'];  
    //$end_year = (string)(intval($star_year)+1);
    $quarter_from = $array['quarter9from'];
    $quarter_to = $array['quarter9to'];
    $arr1 = Utility::getStartEndMonth($start_year, $quarter_from, FDACS);
    $arr2 = Utility::getStartEndMonth($start_year, $quarter_to, FDACS);
    $eval_start_yr=$arr1['year'];
    $eval_end_yr=$arr2['year'];
    $total_start_month=$arr1['start_month'];
    $total_end_month=$arr2['end_month'];
    $quarter_num = intval($quarter_to)-intval($quarter_from)+1;
    //$quarter_name = "quarter{$quarter_to}_evals";
    //$followup_quarter_name = "quarter{$quarter_to}_followup_evals";
    $this->reportTitleArr =  $array;
    //Check package first
    if($eval_start_yr!=$eval_end_yr){
    $sql_00 = "select * from package where
                  mil_id = $mil_id
                  and ((eval_yr = $eval_start_yr
                  and eval_month >= $total_start_month)
                  or (eval_yr = $eval_end_yr
                  and eval_month <= $total_end_month)) and status='$status'";
    }
    else{
     $sql_00 = "select * from package where
                  mil_id = $mil_id
                  and eval_yr = $eval_start_yr
                  and eval_month >= $total_start_month
                  and eval_month <= $total_end_month and status='$status'";
        
    }
     $result = MIL::doQuery($sql_00, MYSQL_ASSOC);
     if(!$result){
         return false;
     }
    // if($eval_start_yr==$eval_end_yr){
    $sql_0 =
             "select
               package.id
              ,contract.fdacs_id
              ,contract.mil_id
              ,CONCAT(lab.mil_type, '-', lab.mil_name) mil_name
              ,";
    $tmp=intval($quarter_from);
    
    for($i=0;$i<$quarter_num;$i++){
        $quarter_name = "quarter{$tmp}_evals+";
        $sql_0 .= $quarter_name;
        $tmp++;
    }
    
    $sql_0 .="0";
    $sql_0 .=" evals_required
              ,";
    $tmp= intval($quarter_from);
        for($i=0;$i<$quarter_num;$i++){
        $followup_quarter_name = "quarter{$tmp}_followup_evals+";
        $sql_0 .= $followup_quarter_name;
        $tmp++;
    }
    $sql_0 .="0";
    if($eval_start_yr==$eval_end_yr){
    $sql_0 .=" followup_evals_required
             from
                (select * from package where
                  mil_id = $mil_id
                  and eval_yr = $eval_start_yr
                  and eval_month >= $total_start_month
                  and eval_month <= $total_end_month and status='$status') as package
                inner join contract
                inner join mil_lab as lab
                on
                package.contract_id = contract.id
                and package.mil_id = lab.mil_id
             ";
     }else{
         /*
          $sql_0 =
             "select
               package.id
              ,contract.fdacs_id
              ,contract.mil_id
              ,CONCAT(lab.mil_type, '-', lab.mil_name) mil_name
              ,$quarter_name evals_required
              ,$followup_quarter_name followup_evals_required
             from
                (select * from package where
                  mil_id = $mil_id
                  and eval_yr = $start_year
                  and eval_month >= $total_start_month
                  and eval_month <= $total_end_month and status='$status') as package
                inner join contract
                inner join mil_lab as lab
                on
                package.contract_id = contract.id
                and package.mil_id = lab.mil_id
             ";*/
             $sql_0 .=" followup_evals_required
             from
                (select * from package where
                  mil_id = $mil_id
                  and ((eval_yr = $eval_start_yr
                  and eval_month >= $total_start_month
                  or eval_yr = $eval_end_yr
                  and eval_month <= $total_end_month)) and status='$status') as package
                inner join contract
                inner join mil_lab as lab
                on
                package.contract_id = contract.id
                and package.mil_id = lab.mil_id
             ";
         
     }
    $result = MIL::doQuery($sql_0, MYSQL_ASSOC); 
    if($result){      
        $infoArr = $result[0];
        $latest=end($result);
        $latest_id=$latest['id'];
        $package_ids="(";
        foreach($result as $info){
        $package_ids .="'{$info['id']}',";      
        }
        $package_ids=substr($package_ids,0,-1);
        $package_ids.=")";
        
        $sql_1 = "select
                        count(*) completed_initial_evals
                         ,sum(acre) initial_eval_acres
                   from evaluation
                   where package_id in $package_ids and eval_type = 1
                   ";
      
        $sql_2 = "select
                        count(*) completed_followup_evals
                         ,sum(acre) followup_eval_acres
                    from evaluation
                    where package_id in $package_ids and eval_type = 2
                    ";
        $sql_3 = "select
                        sum(total_count) num_of_waiting_eval
                        ,sum(total_acres) waiting_eval_acres
                     from waiting_eval
                     where package_id = '$latest_id'";
        $sql_4 = "select
                        count(*) completed_replacement_evals
                       ,sum(acre) replacement_eval_acres
                    from evaluation
                    where package_id in $package_ids and eval_type = 3
                    ";
        $sql_5 = "select * from package where id in $package_ids";

        $result = MIL::doQuery($sql_1, MYSQL_ASSOC);
        if($result){
            $infoArr['completed_initial_evals'] = $result[0]['completed_initial_evals'];
            $infoArr['initial_eval_acres'] = $result[0]['initial_eval_acres'];
        }
        $result = MIL::doQuery($sql_2, MYSQL_ASSOC);
        if($result){
            $infoArr['completed_followup_evals'] = $result[0]['completed_followup_evals'];
            $infoArr['followup_eval_acres'] = $result[0]['followup_eval_acres'];
        }
       
        $result = MIL::doQuery($sql_3, MYSQL_ASSOC);
        if($result){
            $infoArr['num_of_waiting_eval'] = $result[0]['num_of_waiting_eval'];
            $infoArr['waiting_eval_acres'] = $result[0]['waiting_eval_acres'];
        }
        $result = MIL::doQuery($sql_4, MYSQL_ASSOC);
        if($result){
            $infoArr['completed_replacement_evals'] = $result[0]['completed_replacement_evals'];
            $infoArr[' replacement_eval_acres'] = $result[0][' replacement_eval_acres'];
        }
        $result = MIL::doQuery($sql_5, MYSQL_ASSOC);
        if($result){
            $count=0;
            $eval_comments=array();
            $package_comments=array();
            
            foreach($result as $arr){
                $package = new Package($arr);
                $package->retrievePackage($arr);
                $water_saving = $package->getWaterSaving();
                foreach($water_saving as $key => $val){
                    $key = 'water_saving_'.$key;
                    $infoArr[$key] += $val;
                }
                if($count==sizeof($result)-1){
                    $infoArr['waiting_list'] = ($package->getWaitingEvalList()!=null?true:false);
                    $infoArr['eval_list'] = ($package->getEvalList()!=null?true:false);
                    $infoArr['education_report'] = ($package->getEducationReportList()!=null?true:false); 
                    $infoArr['member'] = $package->getMember();
                    $infoArr['package_submitted_time'] = $package->getProperty('pack_submitted_time');   
                }
                $eval_comments=array_merge($eval_comments,$package->getEvaluationComments());
                $package_comments[$package->getProperty("id")]=$package->getProperty("comments");
                /*
                $infoArr['eval_comments'] = $package->getEvaluationComments();
                $infoArr['member'] = $package->getMember();
                $infoArr['package_submitted_time'] = $package->getProperty('pack_submitted_time');
                $infoArr['date_range'] = $package->getDateRange();
                $infoArr['package_comments'] = $package->getProperty("comments");*/
                $count++;
            }
            $infoArr['eval_comments']=$eval_comments;
            $infoArr['package_comments']= $package_comments;
            
        }

         
    }else{
        echo 'Report 9, contract is missing';
        exit;
    }

    $this->requestedArr = $infoArr;
    $this->requestedArr['reporting_period']='From: '.$total_start_month.'-'.$eval_start_yr.' To: '.$total_end_month.'-'.$eval_end_yr;
    return $infoArr;
}

public function getReport(){
    $html = '<body  width="70%" style="text-align:center;"><div style="margin:0 auto; width:800px;">'.$this->getReportTitle();
    $html .= $this->getTableContent().'<div style="margin-top: 10px; text-align:left;font-family:helvetica, arial, sans-serif;margin: 5px 0 15px 0;">Florida Department of Agriculture & Consumer Services</div></body>';
    //echo $html;exit;
    return $html;
    

}


public function getReportTitle(){
    $start_year = $this->getProperty('cal_start_yr');
    $end_year = $start_year +1;
    $this->reportTitle =
            "<h2 style='text-align:left;font-family:helvetica, arial, sans-serif;margin-bottom: 0;padding-bottom: 0;'>Report 9: CONDENSED REPORT FORM</h2>
            <h4 style='font-weight: 100; text-align:left;font-family:helvetica, arial, sans-serif;margin: 0 10px;color:#666;'>FLORIDA DEPARTMENT OF AGRICULTURE AND CONSUMER SERVICES<br /><br />
            FDACS FISCAL YEAR: $start_year - $end_year </h4> ";
    return $this->reportTitle;
}

public function getProperty($key){
    if(array_key_exists($key, $this->requestedArr)){
        return $this->requestedArr[$key];
    }else if(array_key_exists($key, $this->reportTitleArr)){
        return $this->reportTitleArr[$key];
    }else{
        return 'NA';
    }
}
public function getTableContent(){
$completed_initial_eval = $this->getProperty('completed_initial_evals');
$total_acre = $this->getProperty('initial_eval_acres')+$this->getProperty('followup_eval_acres')+$this->getProperty('replacement_eval_acres');
$total_irr_aws = $this->getProperty('water_saving_followup_irr')+$this->getProperty('water_saving_replacement_irr');
$total_firm_aws = $this->getProperty('water_saving_followup_firm')+$this->getProperty('water_saving_replacement_firm');
$total_aws = $total_irr_aws+$total_firm_aws;
//has replacement potential water saving already, check getWaterSaving() function.
$total_pws = $this->getProperty('water_saving_initial_irr')+$this->getProperty('water_saving_initial_firm');
$member = $this->getProperty('member');
if($member == 'NA'){
    echo 'No submission member information';
    exit;
}
$this->tableContent = 
//'<table style="margin:auto 0; line-height:20px; font-size:75%; font-weight:100; text-align:left; font-family:helvetica,arial,sans-serif; margin: 5px 0; border-collapse: collapse;" border="1" cellpadding="5" cellspacing="0" nobr="true" style="text-align:left;">
'<table  border="1" cellpadding="3" cellspacing="0" nobr="true" style="text-align:left ;font-weight:100; font-family:helvetica,arial,sans-serif; margin: 5px 0;line-height:20px; border-collapse: collapse;">
  <tr  nobr="true">
<th>FDACS Contract #: '.$this-> getProperty('fdacs_id').'</th>
<th>Reporting Period: '.$this-> getProperty('reporting_period').'</th>
</tr>
 <tr nobr="true">
<th>MIL #: '.$this-> getProperty('mil_id').'</th>
<th>MIL Name: '.$this-> getProperty('mil_name').'</th>
</tr>
 <tr nobr="true">
<th>Total Evaluations (Initial/Follow Up/Replacement) Required: '.$this-> getProperty('evals_required').'</th>
<th>Total Follow-Up Evaluations Required: '.$this-> getProperty('followup_evals_required').'</th>
</tr>
 <tr nobr="true">
<th>Completed Initial Evaluations: '.$completed_initial_eval.'</th>
<th>Completed Follow-Up Evaluations: '.$this->getProperty('completed_followup_evals').'</th>
</tr>
<tr nobr="true">
 <th>Completed Replacement Evaluations: '.$this->getProperty('completed_replacement_evals').'</th>
<th>Total Acres Evaluated: '.$total_acre.'</th>
</tr>
 <tr nobr="true">
<th>
Total Potential Annual Water Savings (Ac-ft/MG): '.$total_pws."/".Utility::getMillionGallonNum($total_pws).'<br />
Irrigation System Only: '.$this->getProperty('water_saving_initial_irr').'<br />
FIRM Only: '.$this->getProperty('water_saving_initial_firm').'<br />
</th>
<th>
Total &nbsp;Actual Annual Water Savings (Ac-ft/MG): '.$total_aws."/".Utility::getMillionGallonNum($total_aws).'<br />
Irrigation System Only: '.$this->getProperty('water_saving_followup_irr').'(Follow up) + '.$this->getProperty('water_saving_replacement_irr').'(Replacement) ='.$total_irr_aws.'<br />
FIRM Only: '.$this->getProperty('water_saving_followup_firm').'(Follow up) + '.$this->getProperty('water_saving_replacement_firm').'(Replacement) ='.$total_firm_aws.'<br />
</th>
</tr>
 <tr nobr="true">
<th colspan="2">&nbsp;</th>
</tr>
 <tr nobr="true">
<th colspan="2" style="text-align:left; background-color:#CCC;">WAITING LIST INFORMATION</th>
</tr>
 <tr nobr="true">
<th>Number of Evaluations: '.round($this->getProperty('num_of_waiting_eval')).'</th>
<th>Approximate Total Acres: '.round($this->getProperty('waiting_eval_acres')).'</th>
</tr>
 <tr nobr="true">
<th colspan="2">
Additional Information/Comments: <br/>
If total Actual Water Savings (AWS) is higher than total Potential Water Savings (PWS), it is likely 
because many more follow up or replacement evaluations were done than initial evaluations, during the 
reporting period.
<p>
The following Reports/Attachments
are also included under separate cover:<br />
';

if($this->getProperty('eval_list')){
    $this->tableContent .='Report No. 11a: IRRIGATION SYSTEM EVALUATIONS: WATER SAVINGS DATA AND RESULTS, PER MIL HANDBOOK<br />
Report No. 11b: IRRIGATION SYSTEM WATER SOURCE, PUMPING STATION, AND OTHER  INFO<br />';
    if($this->getProperty('completed_followup_evals')!=0||$this->getProperty('completed_replacement_evals')!=0){
       $this->tableContent .= 'Report No. 11c: TRACKING TABLE FOR INITIAL EVALUATIONS, FOLLOW UP EVALUATIONS, OR REPLACEMENTS<br />';
    }
}
if($this->getProperty('waiting_list')){
   $this->tableContent .='Report No. 7: MIL EVALUATION WAITING LIST<br />';
}
if($this->getProperty('education_report')){
    $this->tableContent .='Report No. 8: MIL CONSERVATION EDUCATION AND OUTREACH REPORT<br />';
}
$this->tableContent .= ' </p>';
//$this->tableContent .= " <p>Package Comments: <br />{$this->getProperty('package_comments')}</p>";

$this->tableContent .= " <p>Package Comments: <br />";
$size = count($this->getProperty('package_comments'));
$num = 0;
foreach($this->getProperty('package_comments') as $package_id => $comment){
	if($comment==''||$comment==null){ $num++; continue;}
		$this->tableContent .= "Package: $package_id: $comment;<br />";
	
}
if($num == $size){
	$this->tableContent .= "NONE";
}
$this->tableContent .='</p>';
$this->tableContent .='<p>Evaluation Comments:<br />';
$size = count($this->getProperty('eval_comments'));
$num = 0;
foreach($this->getProperty('eval_comments') as $eval_id => $comment){
	if($comment==''||$comment==null){ $num++; continue;}
                $this->tableContent .= "Evaluation {$eval_id}: $comment;<br />";
	
}
if($num == $size){
	$this->tableContent .= "NONE";
}
$timeString = date('m/d/Y');
$this->tableContent .='</p></th>
</tr>
 <tr nobr="true">'."
<th>Submitted by: {$member->getProperty('first_name')} {$member->getProperty('last_name')}</th>
<th>Title: {$member->getProperty('title')}</th>
</tr>".'
 <tr nobr="true">'."
<th>Email: {$member->getProperty('username')}</th>
<th>Date: {$this->getProperty('package_submitted_time')}</th>
</tr>".'
 <tr nobr="true">
<th colspan="2" style="background-color:#CCC;">Questions: 
Please contact Camilo Gaitan at   &nbsp;(850) 617-1715 or at camilo.gaitan@freshfrom.florida.com
</th>
</tr>
</table>' . "<br/><br/><br/><br/><br/><br/>{$timeString}"

;


    return $this->tableContent;
}

}
?>
