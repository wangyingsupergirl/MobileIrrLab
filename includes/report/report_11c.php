<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
require_once dirname(__FILE__) . '/../mil_init.php';
require_once dirname(__FILE__) . '/../utility.php';
require_once dirname(__FILE__) . '/../input/package/evaluation/FollowIrrSys.php';
require_once dirname(__FILE__) . '/../input/package/evaluation/InitIrrSys.php';
require_once dirname(__FILE__) . '/../input/package/evaluation/FollowFirm.php';
require_once dirname(__FILE__) . '/../input/package/evaluation/InitFirm.php';

Class Report11C extends Report {

    private $evals;
    private $initEvals;
    private $reportTitle;
    private $reportTitleArr = array();
    private $tableHeader;
    private $tableContent;
    private $start_year;
    private $end_year;
    private $start_month;
    private $end_month;

    public function printPDF($array) {
        //Set $data
        $evals = $this->requestDBData($array);
    }

    /*
     * @function requestDBData: 
     *    1. Get all the follow up and replace evaluation of the package 
     *    2. Get all the corresponding initial/last evaluations.
     * @param $array: parameter array
     * @param $status:  current status of the package
     *                $status could be "approved"(Report generation) 
     *                                     or "submitted"(evaluations reviewed by admin)
     *                                     or "pending"(evaluations previewed by employee or contractor)
     * @return $eval: follow up and replacement evaluations, if the package has any.
     *                         if not return false;
     */

    public function requestDBData($array, $status) {
        $mil_id = $array['mil_id'];
        $this->start_year = $array['cal_start_yr'];
        $this->end_year = $array['cal_end_yr'];
        $this->start_month = $array['cal_start_month'];
        $this->end_month = $array['cal_end_month'];
        // $start_year = $array['fed_start_yr'];
        // $quarter = $array['fed_quater'];
        $this->reportTitleArr = $array;
        $this->reportTitleArr['start_date'] = $this->start_year . '-' . $this->start_month;
        $this->reportTitleArr['end_date'] = $this->end_year . '-' . $this->end_month;
        //  $arr = Utility::getStartEndMonth($start_year, $quarter, FED);
        // $start_month = $arr['start_month'];
        // $end_month = $arr['end_month'];
        // $eval_yr = $arr['year'];
        if ($this->start_year === $this->end_year) {
            $sql = "select
        eval.id
        ,eval.eval_yr
        ,eval.eval_month
        ,eval.eval_method
        ,eval.eval_type
        ,crop.name crop_name
        ,eval.eval_funding_sources
        ,eval.acre
        ,eval.irr_sys_type
        ,eval.actual_water_use
        ,eval.nir_water_use
        ,eval.irr_sys_du
        ,eval.firm_aws
        ,eval.firm_pws
        ,eval.firm_iws
        ,eval.display_id
        ,eval.sched_imprv 
        ,eval.planned_repairs
         ,eval.imm_repairs
    from
        evaluation as eval
        inner join ag_urban_types_names as crop
    on
        eval.crop_category = crop.id
    where 
       (
          eval_yr = $this->start_year and
          eval_month >= $this->start_month and
          eval_month <= $this->end_month) and
          eval.status = '$status'and
          mil_id = $mil_id
              and eval_type in (2,3)
          order by eval.display_id
    ";
        } else {
            $sql = "select
        eval.id
        ,eval.eval_yr
        ,eval.eval_month
        ,eval.eval_method
        ,eval.eval_type
        ,crop.name crop_name
        ,eval.eval_funding_sources
        ,eval.acre
        ,eval.irr_sys_type
        ,eval.actual_water_use
        ,eval.nir_water_use
        ,eval.irr_sys_du
        ,eval.firm_aws
        ,eval.firm_pws
        ,eval.firm_iws
        ,eval.display_id
        ,eval.sched_imprv 
        ,eval.planned_repairs
         ,eval.imm_repairs
    from
        evaluation as eval
        inner join ag_urban_types_names as crop
    on
        eval.crop_category = crop.id
    where 
       (eval_yr > $this->start_year and
          eval_yr < $this->end_year or
          eval_yr = $this->start_year and
          eval_month >= $this->start_month or
          eval_yr =$this->end_year and
          eval_month <= $this->end_month) and
          eval.status = '$status'and
          mil_id = $mil_id
              and eval_type in (2,3)
          order by eval.display_id
    ";
        }
        $evals = MIL::doQuery($sql, MYSQL_ASSOC);
        if ($evals) {
            $this->evals = $evals;
            $this->requestInitEvals();
        }
        return $evals;
    }

    /*
     * @function requestInitEvals: 
     *     Get all the corresponding initial/last evaluations of $this->evals
     * @return $eval: initial or last evaluation, if the package has any.
     *                         if not return false;
     */

    public function requestInitEvals() {
        $eval_ids = array();
        foreach ($this->evals as $eval) {
            $eval_id = "'{$eval['id']}'";
            array_push($eval_ids, $eval_id);
        }
        $eval_ids = implode(",", $eval_ids);

        $sql = "select
        eval.id followup_eval_id
        ,init_eval.id id
        ,init_eval.eval_yr
        ,init_eval.eval_month
        ,init_eval.eval_method
        ,init_eval.eval_type
        ,crop.name crop_name
        ,init_eval.eval_funding_sources
        ,init_eval.irr_sys_type
        ,init_eval.acre
        ,init_eval.actual_water_use
        ,init_eval.nir_water_use
        ,init_eval.irr_sys_du
        ,init_eval.firm_aws
        ,init_eval.firm_pws
        ,init_eval.firm_iws
          ,init_eval.sched_imprv 
         ,init_eval.planned_repairs
         ,init_eval.imm_repairs
     from
        (select * from evaluation where id in ($eval_ids)) as eval
        inner join(select * from evaluation where eval_type in (1,2,3)) as init_eval
        inner join ag_urban_types_names as crop
     on
        eval.init_eval_id = init_eval.id
        and init_eval.crop_category = crop.id
     ";
        $evals = MIL::doQuery($sql, MYSQL_ASSOC);
        $this->initEvals = $evals;
        return $evals;
    }

    public function getReport() {
        $html = $this->getReportTitle();
        $html .= $this->getTableHeader();
        $html .= $this->getTableContent();
        return $html;
    }

    public function getTableHeader() {
        $this->tableHeader = //'<table  border="1" cellpadding="5" cellspacing="0" nobr="true" style="font-size:85%; font-weight:100; text-align:left; font-family:helvetica,arial,sans-serif; margin: 5px 10px; border-collapse: collapse;">
                '<table  border="1" cellpadding="2" style="text-align:center;font-weight:100; font-family:helvetica,arial,sans-serif; margin: 5px 10px;border-collapse: collapse;">
  <tr style="background-color:#ccc;"  nobr="true" >
  <th></th>
  <th>Calendar Year</th>
  <th>Calendar Month</th>
  <th>Eval ID No#</th>
  <th>Name or Crop<br>
    No.</th>
  <th>Cost Share</th>
  <th>Acres</th>
  <th>EU or DU (%)</th>
  <th>NIR (in)</th>
  <th>Actual (in)</th>
  <th>Initial Eval TOTAL PWS (ac-ft)</th>
  <th>Follow Up Eval OR Replacement DU/EU Imprv AWS (ac-ft)*</th>
  <th>TOTAL AWS (ac-ft)*</th>
  </tr>';
        return $this->tableHeader;
    }

    public function getReportTitle() {
        $mil_id = $this->reportTitleArr['mil_id'];
        $mils = Utility::getLookupTable('mil_lab', null);
        $mil_name = $mils[$mil_id]->getProperty('mil_name');
        $start_year = $this->reportTitleArr['fed_start_yr'];
        $end_year = $start_year + 1;
        $this->reportTitle = " <h2 style='text-align:left;font-family:helvetica, arial, sans-serif;margin: 25px 10px 0 10px; padding-bottom: 0;'>Report No. 11c
        TRACKING TABLE FOR INITIAL EVALUATIONS, FOLLOW UP EVALUATIONS, OR REPLACEMENTS  </h2>
        <h4 style='font-weight: 100; text-align:left;font-family:helvetica, arial, sans-serif;margin: 0 10px;color:#666;'>MIL ID: $mil_id&nbsp;&nbsp; MIL Name: $mil_name&nbsp;&nbsp; Calendar Year From: {$this->reportTitleArr['start_date']} To {$this->reportTitleArr['end_date']} &nbsp;&nbsp;</h4> ";
        return $this->reportTitle;
    }

    public function getTableContent() {
        $this->tableContent = "";
        $n = count($this->evals);
        for ($i = 0; $i < $n; $i++) {
            $eval = $this->evals[$i];
            foreach ($eval as $key => $val) {
                if ($val === false || $val === null | trim($val) === '') {
                    $eval[$key] = 'NA';
                }
            }
            $cur_init_eval = null;
            foreach ($this->initEvals as $init_eval) {
                if ($init_eval['followup_eval_id'] == $eval['id']) {
                    $cur_init_eval = $init_eval;
                    foreach ($cur_init_eval as $key => $val) {
                        if ($val === false || $val === null | trim($val) === '') {
                            $cur_init_eval[$key] = 'NA';
                        }
                    }
                }
            }
            if ($cur_init_eval == null) {
                echo "Can not find corresponding initial or last evaluation for evaluation" . $eval['id'];
            }
            if ($cur_init_eval['eval_method'] == 'irr') {
                $init_eval_obj = Evaluation::createEval('from_db', $cur_init_eval);
                $irr_sys_types = Utility::getLookupTable('irr_sys_types', null);
                $eval_type = $init_eval_obj->getProperty('eval_type');
                if ($eval_type == 1) { //initial evaluation
                    $pws = $init_eval_obj->getTotalWS();
                    $du_eu = 'NA';
                    $total = 'NA';
                } else if ($eval_type == 2) {//follow up evaluation, last evaluation of the replacement evaluation
                    $pws = 'NA';
                    $du_eu = $init_eval_obj->calculateDuEuImprov($irr_sys_types);
                    $total = $init_eval_obj->getTotalWS();
                }
            } else if ($cur_init_eval['eval_method'] == 'firm') {
                $pws = $cur_init_eval['firm_pws'];
            }
            $funding_sources = Utility::getNamesByIDs($cur_init_eval['eval_funding_sources'], 'eval_funding_sources');
            // $fq = new FiscalQuarter($cur_init_eval['eval_yr'], $cur_init_eval['eval_month'], FED);
            $first_row_name = "";
            $second_row_name = "";
            if ($eval['eval_type'] == 2) {
                $first_row_name = "Initial Eval";
                $second_row_name = "Follow Up Eval";
            } else if ($eval['eval_type'] == 3) {
                $first_row_name = "Last Eval of Old System";
                $second_row_name = "Replacement Eval";
            }
            $this->tableContent .= '<tr  nobr="true" >' .
                    "<td>{$first_row_name}</td>
      <td>{$cur_init_eval['eval_yr']}</td>
      <td>{$cur_init_eval['eval_month']}</td>
      <td>{$cur_init_eval['id']}</td>
      <td>{$cur_init_eval['crop_name']}</td>
      <td>{$funding_sources}</td>
      <td>{$cur_init_eval['acre']}</td>
      <td>{$cur_init_eval['irr_sys_du']}</td>
      <td>{$cur_init_eval['nir_water_use']}</td>
      <td>{$cur_init_eval['actual_water_use']}</td>
      <td>{$pws}</td>
      <td>{$du_eu}</td>
      <td>{$total}</td>
     </tr>";
            if ($eval['eval_method'] == 'irr') {
                $eval_obj = Evaluation::createEval('from_db', $eval);
                $eval_obj->setProperty('initEval', $init_eval_obj);
                $irr_sys_types = Utility::getLookupTable('irr_sys_types', null);
                $aws = $eval_obj->calculateDuEuImprov($irr_sys_types);
                $total = $eval_obj->getTotalWS();
            } else if ($eval['eval_method'] == 'firm') {
                $aws = $eval['firm_aws'];
                $total = $eval['firm_iws'];
            }
            $funding_sources = Utility::getNamesByIDs($eval['eval_funding_sources'], 'eval_funding_sources');
            // $fq = new FiscalQuarter($eval['eval_yr'], $eval['eval_month'], FED);
            $timeString = date('m/d/Y');
            $this->tableContent .= '<tr style="background-color:#EEE;"  nobr="true" >' . "
      <td>{$second_row_name}</td>
      <td>{$eval['eval_yr']}</td>
      <td>{$eval['eval_month']}</td>
      <td>{$eval['id']}</td>
      <td>{$eval['crop_name']}</td>
      <td>{$funding_sources}</td>
      <td>{$eval['acre']}</td>
      <td>{$eval['irr_sys_du']}</td>
      <td>{$eval['nir_water_use']}</td>
      <td>{$eval['actual_water_use']}</td>
      <td>NA</td>
      <td>$aws</td>
      <td>$total</td>
     </tr><tr></tr>";
        }
        $this->tableContent .= "</table>
    <div style='margin-top: 10px; text-align:left;font-family:helvetica, arial, sans-serif;margin: 5px 0 15px 10px;'> * From DE/EU Improvements, Irrigation Schedule Improvements, Planned Repairs, and/or Immediate Repairs; See Also Report 11a.</div>
    <div style='margin-top: 3px; text-align:left;font-family:helvetica, arial, sans-serif;margin: 2px 0 15px 10px;'>Florida Department of Agriculture & Consumer Services</div><br/><br/><br/><br/><br/><br/>{$timeString}";
        return $this->tableContent;
    }

}

?>
