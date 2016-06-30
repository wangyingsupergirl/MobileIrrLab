<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
require_once dirname(__FILE__) . '/../utility.php';
require_once dirname(__FILE__) . '/../input/package/Package.php';

class Report {

    protected $pageOrientation = 'L';
    protected $userInput = false;
    protected $eval_method_array = array('irr' => 'Irrigation System Only', 'firm' => 'Farm Irrigation Rating Method (FIRM/FIRI)', 'both' => 'Irrigation System Only AND Farm Irrigation Rating Method (FIRM/FIRI)'); 
    protected $eval_method = "";
    public function getPageOrientation() {
        return $this->pageOrientation;
    }

    public static function createReport($report_num) {
        
        if ($report_num == REPORT11A) {
            $report = new Report11A();
        } else if ($report_num == REPORT11B) {
            $report = new Report11B();
        } else if ($report_num == REPORT11C) {
            $report = new Report11C();
        } else if ($report_num == REPORT7) {
            $report = new Report7();
        } else if ($report_num == REPORT8) {
            $report = new Report8();
        } else if ($report_num == REPORT9) {
            $report = new Report9();
        } else if ($report_num == REPORT1A) {
            $report = new Report1A();
        } else if ($report_num == REPORT2) {
            $report = new Report2();
        } else if ($report_num == REPORT3) {
            $report = new Report3();
        } else if ($report_num == REPORT4B) {
            $report = new Report4B();
        } else if ($report_num == REPORT6A) {
            $report = new Report6A();
        } else if ($report_num == REPORT6B) {
            $report = new Report6B();
        } else if ($report_num == REPORT6C) {
            $report = new Report6C();
        } else if ($report_num == REPORT6D) {
            //echo "sdf";
            $report = new Report6D();
        } else if ($report_num == REPORT12A) {
            $report = new Report12A();
        } else if ($report_num == REPORT12B) {
            $report = new Report12B();
        } else if ($report_num == REPORT12C) {
            $report = new Report12C();
        }
        else if($report_num == REPORT12D){
            $report = new Report12D();
        }
        else if($report_num == REPORT13A){
            $report = new Report13A();
        }
        else if($report_num == REPORT13B){
            $report = new Report13B();
        }else if($report_num == REPORT13C){
            $report = new Report13C();
        }
        else if($report_num == REPORT13D){
            $report = new Report13D();
        }
        else if($report_num == REPORT14A){
            $report = new Report14A();
        }
        else if($report_num == REPORT14B){
            $report = new Report14B();
        }else if($report_num == REPORT14C){
            
            $report = new Report14C();
        }
        else if($report_num == REPORT14D){
            $report = new Report14D();
        }

        return $report;
    }

    public function changeObj2Arr($obj, $report_id) {
        $mil_id = $obj->getProperty('mil_id');
        $eval_yr = $obj->getProperty('eval_yr');
        $eval_month = $obj->getProperty('eval_month');
        $rtn = Utility::getReportYearQuarter($eval_yr, $eval_month);
        $rtn['cal_start_yr'] = $eval_yr;
        $rtn['cal_end_yr'] = $eval_yr;
        $rtn['cal_start_month'] = $eval_month;
        $rtn['cal_end_month'] = $rtn['end_month'];
        $rtn['mil_id'] = $obj->getProperty('mil_id');
        if ($report_id === '9') {
            $rtn['cal_start_yr'] = $rtn['fdacs_start_yr'];
            $rtn['quarter9from'] = $rtn['fdacs_quarter'];
            $rtn['quarter9to'] = $rtn['fdacs_quarter'];
        }
        $rtn['mil_id'] = $mil_id;
        $fq = new FiscalQuarter($eval_yr, $eval_month, FED);
        $rtn['fed_start_yr'] = $fq->getFiscalSYr();
        $rtn['fed_quater'] = $fq->getFiscalQtr();
        $fq = new FiscalQuarter($eval_yr, $eval_month, FDACS);
        $rtn['fdacs_start_yr'] = $fq->getFiscalSYr();
        $rtn['fdacs_quarter'] = $fq->getFiscalQtr();
        return $rtn;
    }

}

?>
