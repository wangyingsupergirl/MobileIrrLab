<?php

require_once dirname(__FILE__) . '/../Evaluation.php';
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class FollowIrrSys extends Evaluation {

 protected $requiredArr = array(
     'id'
     , 'display_id'
     , 'eval_funding_sources'
     , 'eval_type'
     , 'eval_method'
     , 'package_id'
     , 'mil_id'
     , 'irr_sys_type'
     , 'irr_sys_du'
     , 'acre'
     , 'crop_category'
     , 'nir_water_use'
     , 'actual_water_use'
     , 'irr_sys_problems'
     , 'sched_imprv'
     , 'planned_repairs'
     , 'imm_repairs'
     , 'county_id'
     , 'zip_code'
     , 'farm_id'
     , 'soil_type'
     , 'water_source'
     , 'tds'
     , 'ph'
     , 'pump_type'
     , 'gpm'
     , 'device_gpm'
     , 'has_flow_meter'
     , 'motor_type'
     , 'from_flow_meter'
     , 'from_device'
     , 'init_eval_id'
     , 'eval_yr'
     , 'eval_month'
     , 'comments'
 );

 public function isCalRequired() {
  return true;
 }



 /*
  * Function calculateDuEuImprov()
  * 1. Calculates DuEuImprovement
  * 2. Assigns to the member variable $duEuImprov
  * 3. Returns the value of DuEuImprovement
  * Case A,B,C,D,E,F refer google doc MIL logic
  * throw exception FOLLOW_INIT_EVAL_NIR_AWU_NOT_MATCH
  */

 public function calculateDuEuImprov($irr_sys_types_table=null) {
  //irr_sys_types_table won't be used by follow up irrgation system evaluation. It is for inital irr system evaluation.
  $nir = $this->getProperty('nir_water_use');
  $awu = $this->getProperty('actual_water_use');
  $du = $this->getProperty('irr_sys_du');
  $acre = $this->getProperty('acre');
  $init_eval = $this->getProperty('initEval');
  if ($init_eval == false) {
   $init_eval = $this->getInitialEval();
   if($init_eval==false){
    echo "Please enter the inital evaluation first!'";
   }
  } 
   $init_nir = $init_eval->getProperty('nir_water_use');
   $init_awu = $init_eval->getProperty('actual_water_use');
   $init_acre = $init_eval->getProperty('acre');
   $init_du = $init_eval->getProperty('irr_sys_du');
  

  if (is_numeric($nir)) {
   //follow up evaluation satisify case A or C
   if (is_numeric($init_nir)) {
    //initial up evaluation satifiy case D or F
    $aws = $this->getAWSByNIR($init_nir, $init_du, $init_acre, $nir, $du, $acre);
   }
  } else {
   //$nir is not a number
   //follow up evaluation satisify case B or C
   if (is_numeric($init_awu)) {
    //initial up evaluation satifiy case E or F
    $aws = $this->getAWSByAWU($init_awu, $init_du, $init_acre, $awu, $du, $acre);
   }
  }

  if (is_numeric($nir) && !is_numeric($awu)) {
   //follow up evaluation satisify case A
   if (!is_numeric($init_nir) && is_numeric($awu)) {
    //initial evaluation satisify case D
    throw new Exception(FOLLOW_INIT_EVAL_NIR_AWU_NOT_MATCH);
   }
  }

  if (is_numeric($awu) && !is_numeric($nir)) {
   //case B
   if (!is_numeric($awu) && is_numeric($nir)) {
    //case D
    throw new Exception(FOLLOW_INIT_EVAL_NIR_AWU_NOT_MATCH);
   }
  }
  $this->duEuImprov = round($aws, 2);
  $this->duEuImprov = ($this->duEuImprov > 0 ? $this->duEuImprov : 0);
  return $this->duEuImprov;
 }

 //inner class， called by calculateDuEuImprov()
 private function getAWSByNIR($init_nir, $init_du, $init_acre, $nir, $du, $acre) {
  return $init_nir / $init_du * 100 * $init_acre / 12 - $nir / $du * 100 * $acre / 12;
 }

 private function getAWSByAWU($init_awu, $init_du, $init_acre, $awu, $du, $acre) {
  return $init_awu / $init_du * 100 * $init_acre / 12 - $awu / $du * 100 * $acre / 12;
  ;
 }

 public function getWaterSavingType() {
  return 'Actual';
 }

 public function getTotalWS() {
  $sched_imprv = $this->getProperty('sched_imprv');
  $planned_repairs = $this->getProperty('planned_repairs');
  $imm_repairs = $this->getProperty('imm_repairs');
  $this->duEuImprov = $this->calculateDuEuImprov();
  $total = $this->duEuImprov + ($sched_imprv == '' ? 0 : $sched_imprv) + ($planned_repairs == '' ? 0 : $planned_repairs) + ($imm_repairs == '' ? 0 : $imm_repairs);
  return round($total, 2);
 }
/*
 * @function getInitialEval: Get the initial evaluation of current follow up evaluation
 * @return $result: if the initial evaluation has been enter, return evaluation object, 
 *                           else return false;
 */
 public function getInitialEval(){
  $sql = 
  "select * 
   from {$this->tableName} as eval1
   inner join 
   (select 
    init_eval_id as id 
   from {$this->tableName}
   where id = '{$this->getProperty('id')}') as eval2
   on eval1.id = eval2.id"; //try to elimate the comfusion
  $result = MIL::doQuery($sql, MYSQL_ASSOC);
  if ($result) {
   foreach ($result as $eval) {
    $eval = Evaluation::createEval('from_db', $eval);
    $result = $eval;
    break;
   }
 }
  return $result;
  
 }
 public function delete() {
  if ($this->id == "" && array_key_exists('id', $this->attrArr)) {
   $this->id = $this->attrArr['id'];
  }
  //delete follow up or replacement which initial or last evaluation is $this
  $sql = "select from {$this->tableName}
    where init_eval_id = '{$this->getProperty('id')}'  and eval_type = 3";
  $result = MIL::doQuery($sql, MIL_DB_INSERT);
  if ($result)
  /* There is no replacement firm, so only delete self */
   $sql = "delete from {$this->tableName}
          where id = '{$this->getProperty('id')}'";
  $result = MIL::doQuery($sql, MIL_DB_INSERT);
  return $result;
 }
 
 public function getPWS(){
 return 0;
}
public function getAWS(){
 return $this->getTotalWS();
}

}


?>
