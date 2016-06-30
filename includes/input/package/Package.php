<?php

/*
 * After package is created,
 * the package won't be deleted or updated,
 * so it don't need special method 
 */
require_once dirname(__FILE__) . '/evaluation/ReplacementFirm.php';
require_once dirname(__FILE__) . '/evaluation/ReplacementIrrSys.php';
require_once dirname(__FILE__) . '/evaluation/FollowIrrSys.php';
require_once dirname(__FILE__) . '/evaluation/InitIrrSys.php';
require_once dirname(__FILE__) . '/evaluation/FollowFirm.php';
require_once dirname(__FILE__) . '/evaluation/InitFirm.php';
require_once dirname(__FILE__) . '/EducationReport.php';
require_once dirname(__FILE__) . '/WaitEval.php';
require_once dirname(__FILE__) . '/Contract.php';
require_once dirname(__FILE__) . '/../../constant.php';
require_once dirname(__FILE__) . '/../../utility.php';
require_once dirname(__FILE__) . '/../../mil_init.php';
require_once dirname(__FILE__) . '/../../report/report.php';
require_once dirname(__FILE__) . '/../../report/report_11a.php';
require_once dirname(__FILE__) . '/../../report/report_11b.php';
require_once dirname(__FILE__) . '/../../report/report_11c.php';
require_once dirname(__FILE__) . '/../../report/report_7.php';
require_once dirname(__FILE__) . '/../../report/report_8.php';
require_once dirname(__FILE__) . '/../../report/report_9.php';

class Package extends Node {

    protected $attrArr = array(
        'id' => ''
        , 'mil_id' => ''
        , 'eval_yr' => ''
        , 'eval_month' => ''
        , 'num_of_eval' => 0
        , 'status' => ''
        , 'end_eval_month' => ''
        , 'contract_id' => ''
        , 'pack_submitted_time' => ''
        , 'pack_created_time' => ''
        , 'pack_approved_time' => ''
        , 'comments' => ''
        , 'admin_comments' => ''
        , 'submitted_by_member_id' => ''
    );
    protected $evalList = null;
    protected $waitingEvalList = null;
    protected $educationReportList = null;
//protected $presentationList = null;
    protected $fiscalArr;

//get package submit cycle 1 month, 3months
    public function getCycle() {
        if ($this->attrArr['eval_month'] == $this->attrArr['end_eval_month']) {
            return 1;
        } else {
            return 3;
        }
    }

    public function getMember() {
        $id = $this->getProperty('submitted_by_member_id');
        if ($id == 'NA') {
            return null;
        } else {
            $sql = "select * from member where mem_id = '$id'";
            $rtn = MIL::doQuery($sql, MYSQLI_ASSOC);
            if ($rtn) {
                if (count($rtn)) {
                    $arr = $rtn[0];
                    $member = Member::createMemberFromDB($arr);
                    $rtn = $member;
                }
            }
            return $rtn;
        }
    }

    public function __construct($para_array) { // maybe from $_POST (New Package), or from database (Edit)
        $this->attrArr['id'] = $this->generateId(); //need to reconsiderate
        $this->attrArr['mil_id'] = $para_array['mil_id'];
        $this->attrArr['eval_yr'] = $para_array['eval_yr'];

        if (array_key_exists('eval_months', $para_array)) {
//from new package page user input
            $months = explode('-', $para_array['eval_months']);
            $this->attrArr['eval_month'] = $months[0];
            $this->attrArr['end_eval_month'] = $months[1];
         
        } else {
//get package from db
            $this->attrArr['eval_month'] = $para_array['eval_month'];
            $this->attrArr['end_eval_month'] = $para_array['end_eval_month'];
        }
        $this->id = $this->attrArr['id'];
        $this->tableName = 'package';
    }

    public function retrievePackage($para_array) {
        foreach ($this->attrArr as $key => $val) {
            if (array_key_exists($key, $para_array)) {
                $this->attrArr[$key] = $para_array[$key];
            }
            if ($key == 'id') {
                $this->id = $para_array['id'];
            }
        }
    }

    public function preview() {
        $result = "";
        $report_ids = array('11a', '11b', '11c', '7', '8', '9');
        //$report_ids = array('9');
        foreach ($report_ids as $report_id) {
            $report = Report::createReport($report_id);
            $month = $this->getProperty('eval_month');
            $yr = $this->getProperty('eval_yr');
            $dataArr = Utility::getReportYearQuarter($yr, $month);
            $dataArr['cal_start_yr']=$yr;
            $dataArr['cal_end_yr']= $yr;
            $dataArr['cal_start_month']=$month;
            $dataArr['cal_end_month']=$dataArr['end_month'];
            $dataArr['mil_id'] = $this->getProperty('mil_id');
            if($report_id==='9'){
                $dataArr['cal_start_yr']=$dataArr['fdacs_start_yr'];
                $dataArr['quarter9from']= $dataArr['fdacs_quarter'];
                $dataArr['quarter9to']= $dataArr['fdacs_quarter'];
            }
            $result = $report->requestDBData($dataArr, 'pending');
            echo $report->getReport();
        }
        $result = "end";
        return $result;
    }

    public function newPackage() {
//Get the quarter range by month
        $month = $this->getProperty('eval_month');
        $end_month = $this->getProperty('end_eval_month');
        if ($month == $end_month) {
            $range = array('min' => $month, 'max' => $end_month);
        } else {
            $range = FiscalQuarter::getQuarterRange($month);
        }
        $range['name'] = 'eval_month';
        $paraArr = array('mil_id' => $this->getProperty('mil_id'), 'eval_yr' => $this->getProperty('eval_yr'), 'range' => $range);
//check this package exists in db or not 
        $result = $this->exists($paraArr);
        if (!$result) {
            $this->setProperty('status', 'pending');
            $package_id = $this->insertToDb();
            return true;
        } else {
            $this->attrArr['id'] = $result[0]['id'];
            return false;
        }
    }

    public function submit() {
        //delete package from DB
        $boolean = $this->hasEvals();
        if ($boolean == true) {
            foreach ($this->evalList as $eval) {
                $eval->updateProperty('status', 'submitted');
            }
        }
        $boolean = $this->isWaitingEvalListEmpty();
        if ($boolean == true) {
            foreach ($this->waitingEvalList as $waitingEval) {
                $waitingEval->updateProperty('status', 'submitted');
            }
        }
        $boolean = $this->isEducationReportListEmpty();
        if ($boolean == true) {
            foreach ($this->educationReportList as $educationReport) {
                $educationReport->updateProperty('status', 'submitted');
            }
        }
        $result = $this->updateProperty('status', 'submitted');
        $now = date('Y-m-d H:i:s');
        $this->updateProperty('pack_submitted_time', $now);
        $this->updateProperty('submitted_by_member_id', $this->getProperty('submitted_by_member_id'));
        return $result;
    }
        public  function existsFarmId($arr,$tableName){
           $sql = "select * from $tableName where ";
           $farm_id=$arr['farm_id'];
           $sql.="farm_id='$farm_id'";
        $bool = MIL::doQuery($sql, MYSQL_ASSOC);
        return $bool;
    }
           public  function existsId($arr,$tableName){
           $sql = "select * from $tableName where ";
           $id=$arr['id'];
           $sql.="id='$id'";
        $bool = MIL::doQuery($sql, MYSQL_ASSOC);
        return $bool;
    }
    public function insert_first_followup($array) {
        $result = false;
        $tableName = 'first_followup_evaluation';
        foreach ($array as $eval) {
            $result = $this->existsFarmId($eval, $tableName);
            if (!$result) {
                $sql = "";
                $attrName = "";
                $attrVal = "";
                foreach ($eval as $key => $eva) {
                    $attrName.=',' . $key . '';
                    if ($eva != null) {
                        $eva = addslashes($eva);
                        $attrVal .= ',"' . $eva . '"';
                    } else {
                        //if val = null, do not add ""
                        $attrVal .= ",null";
                    }
                }
                $attrName = '(' . substr($attrName, 1) . ')';
                $attrVal = '(' . substr($attrVal, 1) . ')';
                $sql = 'insert into ' . $tableName . ' ' . $attrName . ' values ' . $attrVal;
                $result = MIL::doQuery($sql, MIL_DB_INSERT); // may throw db exception
            }
        }
        return $result;
    }
    public function delete_first_followup($array) {
        $result = false;
        $tableName = 'first_followup_evaluation';
        foreach ($array as $eval) {
            $result = $this->existsId($eval, $tableName);
            $attrName='id';
            $attrVal=$eval['id'];
            $attrVal="'".$attrVal."'";
            if ($result) {
                $sql = "delete from $tableName where ";
                $sql.= "$attrName = $attrVal";
                $result = MIL::doQuery($sql, MIL_DB_INSERT); // may throw db exception
            }
        }
        return $result;
    }
    public function approve() {
        //delete package from DB
        $boolean = $this->hasEvals();
        $followup_eval = $this->getFirstEvals();
        if ($boolean == true) {
            foreach ($this->evalList as $eval) {
                $eval->updateProperty('status', 'approved');
            }
            $this->insert_first_followup($followup_eval);
        }
        $boolean = $this->isWaitingEvalListEmpty();
        if ($boolean == true) {
            foreach ($this->waitingEvalList as $waitingEval) {
                $waitingEval->updateProperty('status', 'approved');
            }
        }
        $boolean = $this->isEducationReportListEmpty();
        if ($boolean == true) {
            foreach ($this->educationReportList as $educationReport) {
                $educationReport->updateProperty('status', 'approved');
            }
        }
        $result = $this->updateProperty('status', 'approved');
        $now = date('Y-m-d H:i:s');
        $this->updateProperty('pack_approved_time', $now);
        return $result;
    }

    public function disapprove($comments) {
        //delete package from DB
        $boolean = $this->hasEvals();
        $followup_eval = $this->getFirstEvals();
        if ($boolean == true) {
            foreach ($this->evalList as $eval) {
                $eval->updateProperty('status', 'pending');
            }
            $this->delete_first_followup($followup_eval);
        }
        $boolean = $this->isWaitingEvalListEmpty();
        if ($boolean == true) {
            foreach ($this->waitingEvalList as $waitingEval) {
                $waitingEval->updateProperty('status', 'pending');
            }
        }
        $boolean = $this->isEducationReportListEmpty();
        if ($boolean == true) {
            foreach ($this->educationReportList as $educationReport) {
                $educationReport->updateProperty('status', 'pending');
            }
        }
        $result = $this->updateProperty('status', 'pending');
        $this->updateProperty('admin_comments', $comments);
        return $result;
    }

    public function delete() {
        //delete package from DB
        $boolean = $this->hasEvals();
        if ($boolean == true) {
            foreach ($this->evalList as $eval) {
                $eval->delete();
            }
        }
        $boolean = $this->isWaitingEvalListEmpty();
        if ($boolean == true) {
            foreach ($this->waitingEvalList as $waitingEval) {
                $waitingEval->delete();
            }
        }
        $boolean = $this->isEducationReportListEmpty();
        if ($boolean == true) {
            foreach ($this->educationReportList as $educationReport) {
                $educationReport->delete();
            }
        }
        $result = parent::delete();
        return $result;
        //delete all the evaluations in this package form DB
    }

    public function getEvalList() {
        if ($this->evalList == null) {
            $this->hasEvals();
        }
        return $this->evalList;
    }

    public function getPackageName() { //display to user package id
        return $this->attrArr['mil_id'] . $this->attrArr['eval_yr'] . $this->attrArr['eval_month'];
    }

    public function getWaitingEvalList() {
        if ($this->waitingEvalList == null) {
            $this->isWaitingEvalListEmpty();
        }
        return $this->waitingEvalList;
    }

    public function isWaitingEvalListEmpty() {
//$sql = 'select * from waiting_eval where package_id ="'.$this->getProperty('id').'"';
        $package_id = $this->getProperty('id');
        $sql = "select w.id id, c.name county_id,  t.name category_id, w.total_count,w.total_acres,w.package_id
        from waiting_eval as  w 
        join fl_county as c 
        join ag_urban_types_names t 
        where w.package_id = '$package_id' and w.county_id = c.id  and t.id = w.category_id;";
        $waiting_arr = MIL::doQuery($sql, MYSQL_ASSOC);

        if ($waiting_arr == false) {//no waiting list
            return false;
        } else {
// process waiting list; add to package waiting list array
            $this->waitingEvalList = array();
            foreach ($waiting_arr as $key => $arr) {
                $wait_eval = new WaitEval($arr);
                $id = $wait_eval->getId();
                $this->waitingEvalList[$id] = $wait_eval;
            }
            return true;
        }
    }

    public function getFirstEvals() {
        $package_id = $this->getProperty('id');
        $sql = 'select * from evaluation where package_id ="' . $this->getProperty('id') . '"';
        $eval_arr = MIL::doQuery($sql, MYSQL_ASSOC);
        if ($eval_arr == false) {//no eval list
            return false;
        } else {
            $first_eval_arr = array();
            $i = 0;
            foreach ($eval_arr as $key => $arr) {
                if($arr['eval_type']==2){
                $first_eval_arr[$i]['farm_id'] = $arr['farm_id'];
                $first_eval_arr[$i]['id'] = $arr['id'];
                $first_eval_arr[$i]['eval_yr_month'] = $arr['eval_yr_month'];
                $i++;              
                }
            }
            return $first_eval_arr;
        }
    }

    public function hasEvals() {
        /*
         * If packages has evaluations return true
         * Else return false
         * Assign  $this->evalList 
         */
        $sql = 'select * from evaluation where package_id ="' . $this->getProperty('id') . '"';
        $eval_arr = MIL::doQuery($sql, MYSQL_ASSOC);

        if ($eval_arr == false) {//no eval list
            return false;
        } else {
// process  eval list; add to package eval list array
            $this->evalList = array();
            foreach ($eval_arr as $key => $arr) {
//$eval = new Evaluation($arr,$this);
                $eval = Evaluation::createEval('from_db', $arr);
                $eval->setLastModifiedTime($arr['last_modified_time']);
                $id = $eval->getProperty('id');
                $this->evalList[$id] = $eval;
            }
            return true;
        }
    }

    public function getWaterSaving() {
        //Get Potential Water Saving
        $water_saving = array(
            'initial_firm' => 0
            , 'initial_irr' => 0
            , 'followup_firm' => 0
            , 'followup_irr' => 0
            , 'replacement_firm' => 0
            , 'replacement_irr' => 0);
        if ($this->evalList == null) {
            $rtn = $this->hasEvals();
            if ($rtn == false) {
                echo 'No eval in this package';
                exit;
            }
        }
        foreach ($this->evalList as $eval) {
            $saving = $eval->getTotalWS();
            $eval_method = $eval->getProperty('eval_method');
            $eval_type = $eval->getProperty('eval_type');
            if ($eval_method == 'firm' && $eval_type == 1) {
                $water_saving['initial_firm'] += $saving;
            } else if ($eval_method == 'firm' && $eval_type == 2) {
                $water_saving['followup_firm'] += $saving;
            } else if ($eval_method == 'firm' && $eval_type == 3) {
                $water_saving['replacement_firm'] += $saving;
            } else if ($eval_method == 'irr' && $eval_type == 1) {
                $water_saving['initial_irr'] += $saving;
            } else if ($eval_method == 'irr' && $eval_type == 2) {
                $water_saving['followup_irr'] += $saving;
            } else if ($eval_method == 'irr' && $eval_type == 3) {
                $water_saving['replacement_irr'] += $saving;
                //treat replacement as initial two
                $saving = $eval->getTotalPWS();
                $water_saving['initial_irr'] += $saving;
            }
        }
        return $water_saving;
    }

    public function getEvaluationComments() {
        $comments = array();
        if ($this->evalList == null) {
            $rtn = $this->hasEvals();
            if ($rtn == false) {
                echo 'No eval in this package';
                exit;
            }
        }
        foreach ($this->evalList as $eval) {
            $id = $eval->getProperty('id');
            $comment = $eval->getProperty('comments');
            $comments[$id] = $comment;
        }
        return $comments;
    }

    public function getEducationReportList() {
        if ($this->educationReportList == null) {
            $this->isEducationReportListEmpty();
        }
        return $this->educationReportList;
    }

    public function isEducationReportListEmpty() {
        $sql = 'select * from education_reports where package_id ="' . $this->getProperty('id') . '"';
        $result = MIL::doQuery($sql, MYSQL_ASSOC);

        if ($result == false) {//no educationReportList
            return false;
        } else {
            // process waiting list; add to package educationReportList array
            $this->educationReportList = array();
            foreach ($result as $key => $arr) {
                $node = new EducationReport($arr);
                $this->educationReportList[$node->getId()] = $node;
            }
            return true;
        }
    }

    public function getContracts() {
        $mil_id = $this->getProperty('mil_id');
        $fq = new FiscalQuarter($this->getProperty('eval_yr'), $this->getProperty('eval_month'), FDACS);
        $fdacs_yr = $fq->getFiscalSYr();
        $sql = "select * from contract where mil_id = {$mil_id} and fdacs_yr = {$fdacs_yr}";
        $contract_arr = MIL::doQuery($sql, MYSQL_ASSOC);
        $contracts = array();

        if ($contract_arr == false) {
            return false;
        } else {

            foreach ($contract_arr as $key => $arr) {

                $contract = new Contract($arr);
                $id = $contract->getProperty('id');
                $contracts[$id] = $contract;
            }
            return $contracts;
        }
    }

    public function getDateRange() {
        $fq = new FiscalQuarter($this->getProperty('eval_yr'), $this->getProperty('eval_month'));
        $months = $fq->getFiscalDQtr();
        $range = "$months {$this->getProperty('eval_yr')}";
        return $range;
    }

}
/*
$para_array=array();

$package=new Package($para_array);
$array=array();
$array[0]['farm_id']='4f1035acd8535';
$array[0]['id']='4f1035acd8530';
$array[0]['eval_yr_month']='2010-10-30';
$result=$package->delete_first_followup($array);
echo $result;*/
?>
