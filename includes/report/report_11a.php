<?php

require_once dirname(__FILE__) . '/../mil_init.php';
require_once dirname(__FILE__) . '/../utility.php';
require_once dirname(__FILE__) . '/../input/package/evaluation/FollowIrrSys.php';
require_once dirname(__FILE__) . '/../input/package/evaluation/InitIrrSys.php';
require_once dirname(__FILE__) . '/../input/package/evaluation/FollowFirm.php';
require_once dirname(__FILE__) . '/../input/package/evaluation/InitFirm.php';

Class Report11A extends Report {

    private $evals;
    private $reportTitle;
    private $reportTitleArr = array();
    private $tableHeader;
    private $tableContent;
    private $start_year;
    private $end_year;
    private $start_month;
    private $end_month;
    public function printPDF($array) {
        $evals = $this->requestDBData($array);
    }

    public function requestDBData($array, $status) {
        $mil_id = $array['mil_id'];
        $this->start_year = $array['cal_start_yr'];
        $this->end_year = $array['cal_end_yr'];    
        //$quarter = $array['fed_quater'];
        $this->reportTitleArr = $array;
        $this->reportTitleArr['mil_id'] = $mil_id;
        //$arr = Utility::getStartEndMonth($start_year, $quarter, FED);
        $this->start_month = $array['cal_start_month'];
        $this->end_month = $array['cal_end_month'];
       // $eval_yr = $arr['year'];
        if($this->start_year===$this->end_year){
            $sql=
                          $sql =
                "select
      e.id eval_id
     ,e.eval_type
     ,water_saving.evaluation_type eval_type_name
     ,e.eval_method
     ,e.irr_sys_type
     ,irr_sys.common_name irr_sys_name
     ,irr_sys.max_du_eu 
     ,e.irr_sys_du
     ,e.acre
     ,crop.type crop_type
     ,crop.name crop_name
     ,e.nir_water_use
     ,e.actual_water_use
     ,e.irr_sys_problems
     ,water_saving.water_saving_type
     ,e.sched_imprv 
     ,e.planned_repairs
     ,e.imm_repairs
     ,e.init_eval_id
    , e.display_id 
    ,e.firm_pws
    ,e.firm_aws
    ,e.firm_iws
     from 
      evaluation as e
      inner join  eval_types_water_saving_types as water_saving
      inner join  irr_sys_types as irr_sys
      inner join ag_urban_types_names as crop
     on
      water_saving.id=e.eval_type
      and irr_sys.id = e. irr_sys_type
      and crop.id = e.crop_category
      where
          (
          eval_yr = $this->start_year and
          eval_month >= $this->start_month and
          eval_month <= $this->end_month) and
          e.status = '$status'and
          mil_id = $mil_id
   order by e.display_id ";
            
        }else{
        $sql =
                "select
      e.id eval_id
     ,e.eval_type
     ,water_saving.evaluation_type eval_type_name
     ,e.eval_method
     ,e.irr_sys_type
     ,irr_sys.common_name irr_sys_name
     ,irr_sys.max_du_eu 
     ,e.irr_sys_du
     ,e.acre
     ,crop.type crop_type
     ,crop.name crop_name
     ,e.nir_water_use
     ,e.actual_water_use
     ,e.irr_sys_problems
     ,water_saving.water_saving_type
     ,e.sched_imprv 
     ,e.planned_repairs
     ,e.imm_repairs
     ,e.init_eval_id
    , e.display_id 
    ,e.firm_pws
    ,e.firm_aws
    ,e.firm_iws
     from 
      evaluation as e
      inner join  eval_types_water_saving_types as water_saving
      inner join  irr_sys_types as irr_sys
      inner join ag_urban_types_names as crop
     on
      water_saving.id=e.eval_type
      and irr_sys.id = e. irr_sys_type
      and crop.id = e.crop_category
      where
          (eval_yr > $this->start_year and
          eval_yr < $this->end_year or
          eval_yr = $this->start_year and
          eval_month >= $this->start_month or
          eval_yr =$this->end_year and
          eval_month <= $this->end_month) and
          e.status = '$status'and
          mil_id = $mil_id
   order by e.display_id ";
        }
        $evals = MIL::doQuery($sql, MYSQL_ASSOC);
        $this->evals = $evals;
        return $evals;
    }
    
    public function requestDataAllYearAllLabs($eval_yr, $status) {
        $sql =
                "select
      e.id eval_id
      ,e.farm_id  irrig_sys_id
     ,e.mil_id
     ,e.eval_yr
     ,e.eval_month
     ,e.eval_type
     ,water_saving.evaluation_type eval_type_name
     ,e.eval_method
     ,e.irr_sys_type
     ,irr_sys.common_name irr_sys_name
     ,irr_sys.max_du_eu 
     ,e.irr_sys_du
     ,e.acre
     ,crop.type crop_type
     ,crop.name crop_name
     ,e.nir_water_use
     ,e.actual_water_use
     ,e.irr_sys_problems
     ,water_saving.water_saving_type
     ,e.sched_imprv 
     ,e.planned_repairs
     ,e.imm_repairs
     ,e.init_eval_id
    ,e.firm_pws
    ,e.firm_aws
    ,e.firm_iws
    from 
      evaluation as e
      inner join  eval_types_water_saving_types as water_saving
      inner join  irr_sys_types as irr_sys
      inner join ag_urban_types_names as crop
     on
      water_saving.id=e.eval_type
      and irr_sys.id = e. irr_sys_type
      and crop.id = e.crop_category
      where  eval_yr = $eval_yr
           e.status = '$status'
   order by mil_id, eval_yr,eval_month, e.display_id ";
        $evals = MIL::doQuery($sql, MYSQL_ASSOC);
        $this->evals = $evals;
        return $evals;
    }

    public function getReport() {
        $html = $this->getReportTitle();
        $html .= $this->getTableHeader();
        $html .= $this->getTableContent();

        return $html;
    }

    public function getTableHeader() {
        $this->tableHeader =
//'<table  border="1" cellpadding="5" cellspacing="0" nobr="true" style="font-size:85%; font-weight:100; text-align:left; font-family:helvetica,arial,sans-serif; margin: 5px 10px; border-collapse: collapse;">
                '<table  border="1" cellpadding="2" cellspacing="0" style="text-align:center;font-weight:100; font-family:helvetica,arial,sans-serif; margin: 5px 10px;border-collapse: collapse;">
      <tr style="background-color:#ccc;"  nobr="true" >
      <th width="8%" rowspan="2">Eval ID #</th>
      <th width="7%"rowspan="2">Evaluation Type</th>
      <th width="6%" rowspan="2" >Evaluation Method</th>
      <th width="6%" rowspan="2">Irrigation System Type</th>
      <th width="11%" colspan="2" >Irrig System Distrib or Emiss Unif (%)</th>
      <th width="5%"  rowspan="2">Irrig Sys Ac</th>
      <th width="9%"  colspan="2">Land Use</th>
      <th width="8%"  colspan="2">Annual Water Use (in.)</th>
      <th width="6%"  rowspan="2">Irrigation System Problems</th>
      <th width="34%"  colspan="7">Water Savings (ac-ft) - Irrigation System Only</th>
      
  </tr>
  <tr style="font-family: helvetica, arial, verdana;"  nobr="true">
      <th>Max</th>
      <th>Per Eval</th>
      <th>Type</th>
      <th>Name or Crop</th>
      <th>NIR</th>
      <th>Actual</th>
      <th>Type</th>
      <th>DU or EU Imprv</th>
      <th>Sched. Imprv</th>
      <th>Planned Repairs</th>
      <th>Imm Repairs</th>
      <th>Total AWS</th>
      <th>Total PWS</th>
  </tr>';
        return $this->tableHeader;
    }

    public function getReportTitle() {
        $mil_id = $this->reportTitleArr['mil_id'];
        $mils = Utility::getLookupTable('mil_lab', null);
        $mil_name = $mils[$mil_id]->getProperty('mil_name');
        //$start_year = $this->reportTitleArr['fed_start_yr'];
       // $end_year = $start_year + 1;

        $this->reportTitle = "<h2 style='text-align:left;font-family:helvetica, arial, sans-serif;margin: 25px 10px 0 10px; padding-bottom: 0;'>Report No. 11a:
        IRRIGATION SYSTEM EVALUATIONS: WATER SAVINGS DATA AND RESULTS, PER MIL Handbook</h2>" .
        
        //<h4 style='font-weight: 100; text-align:left;font-family:helvetica, arial, sans-serif;margin: 0 10px;color:#666;'>MIL ID: $mil_id&nbsp;&nbsp; MIL Name: $mil_name&nbsp;&nbsp; Federal Quarter: {$this->reportTitleArr['fed_quater']}&nbsp;&nbsp; Federal Fiscal Year: $start_year-$end_year &nbsp;&nbsp;</h4> 
        "<h4 style='font-weight: 100; text-align:left;font-family:helvetica, arial, sans-serif;margin: 0 10px;color:#666;'>MIL ID: $mil_id&nbsp;&nbsp; MIL Name: $mil_name&nbsp;&nbsp; REQUESTED PERIOD From: $this->start_year - $this->start_month To: $this->end_year - $this->end_month &nbsp;&nbsp;</h4>  "      . "";
        return $this->reportTitle;
    }

    //For each Replacement Irrigation System Only evaluation add initial copy
    //Put initial copy next to replacement copy
    public function addInitialForReplacement() {
        $length = count($this->evals);
        $evals = array();
        for ($i = 0; $i < $length; $i++) {
            array_push($evals, $this->evals[$i]);
            $eval = $this->evals[$i];
            if ($eval['eval_type'] == 3) {//replacement evaluation
                $eval['eval_type'] = 1;
                $eval['eval_type_name'] = "Initial";
                $eval['water_saving_type'] = 'Potential';
                array_push($evals, $eval);
            }
        }
        $this->evals = $evals;
    }
   public function fillFields(){
        $this->addInitialForReplacement();
        $n = count($this->evals);
        $rows = array();
        for ($i = 0; $i < $n; $i++) {
            $eval = $this->evals[$i];
            $irr_sys_types = Utility::getLookupTable('irr_sys_types', null);
            $eval_obj = Evaluation::createEval('from_db', $eval);
            foreach ($eval as $key => $val) {
                if ($val === false || $val === null | trim($val) === '') {
                    $eval[$key] = 'NA';
                }
            }
            $eval['eval_method'] = ($eval['eval_method'] == 'irr' ? 'Irrigation System Only' : 'FIRM');
            $eu_du_improv = $eval_obj->calculateDuEuImprov($irr_sys_types);
            $eval['eu_du_improv'] = ($eu_du_improv === false ? 'NA' : $eu_du_improv);
            $ws = $eval_obj->getTotalWS();
            $eval['aws'] = ($eval['water_saving_type'] == 'Actual' ? $ws : 'NA');
            $eval['pws'] = ($eval['water_saving_type'] == 'Potential' ? $ws : 'NA');
            $eval['irr_sys_problems'] = str_replace(",", ";", $eval['irr_sys_problems']);
            unset($eval['eval_type']);
            unset($eval['display_id']);
            $rows[count($rows)] = $eval;
         }
         return $rows;
   }
  
    public function getTableContent() {
        $this->tableContent = "";
        $this->addInitialForReplacement();
        $n = count($this->evals);
        for ($i = 0; $i < $n; $i++) {
          
            $eval = $this->evals[$i];
            $irr_sys_types = Utility::getLookupTable('irr_sys_types', null);
            $eval_obj = Evaluation::createEval('from_db', $eval);
            foreach ($eval as $key => $val) {
                if ($val === false || $val === null | trim($val) === '') {
                    $eval[$key] = 'NA';
                }
            }
            $eval_method = ($eval['eval_method'] == 'irr' ? 'Irrigation System Only' : 'FIRM');
            $eu_du_improv = $eval_obj->calculateDuEuImprov($irr_sys_types);
            $eu_du_improv = ($eu_du_improv === false ? 'NA' : $eu_du_improv);
            $ws = $eval_obj->getTotalWS();
            $aws = ($eval['water_saving_type'] == 'Actual' ? $ws : 'NA');
            $pws = ($eval['water_saving_type'] == 'Potential' ? $ws : 'NA');
             $color = ($i % 2 == 0 ? "#FFF" : "#EEE");
             $timeString = date('m/d/Y');
            $this->tableContent .= '<tr style="background-color:' . $color . ';"  nobr="true">' . "
        <td>{$eval['eval_id']}</td>
        <td>{$eval['eval_type_name']}</td>
        <td>$eval_method</td>
        <td>{$eval['irr_sys_name']}</td>
        <td>{$eval['max_du_eu']}</td>
        <td>{$eval['irr_sys_du']}</td>
        <td>{$eval['acre']}</td>
        <td>{$eval['crop_type']}</td>
        <td>{$eval['crop_name']}</td>
        <td>{$eval['nir_water_use']}</td>
        <td>{$eval['actual_water_use']}</td>
        <td>{$eval['irr_sys_problems']}</td>
        <td>{$eval['water_saving_type']}</td>
        <td>$eu_du_improv</td>
        <td>{$eval['sched_imprv']}</td>
        <td>{$eval['planned_repairs']}</td>
        <td>{$eval['imm_repairs']}</td>
        <td>$aws</td>
        <td>$pws</td>
            </tr>";
        }
        $this->tableContent .= "</table><div style='margin-top: 10px; text-align:left;font-family:helvetica, arial, sans-serif;margin: 5px 0 15px 10px;'>Florida Department of Agriculture & Consumer Services</div><br/><br/><br/><br/><br/><br/>{$timeString}";

        return $this->tableContent;
    }

}

?>
