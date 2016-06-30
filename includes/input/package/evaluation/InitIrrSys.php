<?php
error_reporting(E_ALL & ~E_STRICT & ~E_NOTICE & ~E_DEPRECATED);
require_once dirname(__FILE__) . '/../Evaluation.php';
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 * 
 */

class InitIrrSys extends Evaluation {

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
     , 'has_flow_meter'
     , 'gpm'
     , 'device_gpm'
     , 'motor_type'
     , 'from_flow_meter'
     , 'from_device'
     , 'init_eval_id'
     , 'eval_yr'
     , 'eval_month'
     , 'comments'
 );


 /*
  * Function calculateDuEuImprov()
  * 1. Calculates DuEuImprovement
  * 2. Assigns to the member variable $duEuImprov
  * 3. Returns the value of DuEuImprovement(named as pws, actually not pws )
  * Case A,B,C  refer google doc MIL logic
  * throw exception FOLLOW_INIT_EVAL_NIR_AWU_NOT_MATCH
  * Refer to:
  * Server side validation named as Evaluation->validateInput() 
  * setWaterSaving() requires this function
  * I. NIR and AWU validation
  *  1. Valid NIR is in the range  (0,100), AWU is in the range [0, 100), or blank/space
  *  2. NIR and AWU are not both blanks/spaces
  *  3. Change space or blank to NULL
  */

 public function calculateDuEuImprov($irr_sys_types_table) {
  $nir = $this->getProperty('nir_water_use');
  $awu = $this->getProperty('actual_water_use');
  $du = $this->getProperty('irr_sys_du');
  $acre = $this->getProperty('acre');
  $irr_sys_type_id = $this->getProperty('irr_sys_type');
  $du_max = $irr_sys_types_table[$irr_sys_type_id]->getProperty('max_du_eu');
  if (is_numeric($nir) && !is_numeric($awu)) {
   $pws = $this->getPWSbyNIR($nir, $awu, $du, $acre, $du_max);
  } else if (is_numeric($awu) && !is_numeric($nir)) {
   $pws = $this->getPWSbyAWU($nir, $awu, $du, $acre, $du_max);
  } else if (is_numeric($awu) && is_numeric($nir)) {
   $pws = $this->getPWSbyNIRAWU($nir, $awu, $du, $acre, $du_max);
  } else {
   //Shouldn't happen.
   //If happened, check whether called Evaluation->validateInput() first
   echo INIT_NIR_AWU_BOTH_NULL;
   exit;
  }
  $this->duEuImprov = round($pws, 2);
    $this->duEuImprov = ($this->duEuImprov > 0 ? $this->duEuImprov : 0);
  return $this->duEuImprov;
 }

 public function getPWSbyNIR($nir, $awu, $du, $acre, $du_max) {
  return ($nir / $du * 100 - $nir / $du_max * 100) * ($acre / 12);
 }

 public function getPWSbyAWU($nir, $awu, $du, $acre, $du_max) {
  return ($awu / $du * 100 - $awu / $du_max * 100) * ($acre / 12);
 }

 public function getPWSbyNIRAWU($nir, $awu, $du, $acre, $du_max) {
  return ($awu / $du * 100 - $nir / $du_max * 100) * ($acre / 12);
 }

 public function isCalRequired() {
  return true;
 }

 public function getWaterSavingType() {
  return 'Potential';
 }

 public function getTotalWS() {
  $irr_sys_types_table = Utility::getLookupTable('irr_sys_types', null);
  $sched_imprv = $this->getProperty('sched_imprv');
  $planned_repairs = $this->getProperty('planned_repairs');
  $imm_repairs = $this->getProperty('imm_repairs');
  $this->duEuImprov = $this->calculateDuEuImprov($irr_sys_types_table);
  $total = $this->duEuImprov + ($sched_imprv == '' ? 0 : $sched_imprv) + ($planned_repairs == '' ? 0 : $planned_repairs) + ($imm_repairs == '' ? 0 : $imm_repairs);
  return round($total, 2);
 }
//
// public function commitToDb() {
//  $sql = "select * from {$this->tableName} where id='{$this->getProperty($this->idName)}'";
//  $result = MIL::doQuery($sql, MYSQL_ASSOC);
//  if ($result) {
//   $return = $this->updateAllProperties();
//   $replacement_eval = $this->existsAsReplacement();
//   if ($replacement_eval) {
//    $replacement_eval->copy($this);
//    $replacement_eval->updateAllProperties();
//   } else {
//    //do nothing
//   }
//   return $return;
//  } else {
//   return $this->insertToDb();
//  }
// }

 public function createReplacementEval() {
  $eval = new Evaluation();
  foreach ($this->attrArr as $name => $val) {
   if ($name == 'eval_type') {
    $eval->setProperty($name, 3);
   } else if ($name == 'id') {
    $id = $eval->generateId();
    $eval->setProperty($name, $id);
   } else if ($name == 'init_eval_id') {
    continue;
   } else {
    $eval->setProperty($name, $val);
   }
  }
  return $eval;
 }

 public function delete() {
  if ($this->id == "" && array_key_exists('id', $this->attrArr)) {
   $this->id = $this->attrArr['id'];
  }
  //delete self and replacement copy, if it exists
  $sql = "delete from {$this->tableName}
          where display_id = '{$this->getProperty('display_id')}' and package_id = '{$this->getProperty('package_id')}' and eval_type in (1,3)";
  $result = MIL::doQuery($sql, MIL_DB_INSERT);
  //delete follow up or replacement which initial or last evaluation is $this
  $sql = "delete from {$this->tableName}
    where init_eval_id = '{$this->getProperty('id')}'  and eval_type in (2,3)";
  $result = MIL::doQuery($sql, MIL_DB_INSERT);
  return $result;
 }

 public function getRelatedFollowupReplacementEvals($package_id) {
  /* $sql = "select * from {$this->tableName}
    where init_eval_id = '{$this->getProperty('id')}' and package_id='$package_id' and eval_type in (2,3)";
   * The related evaluation could also in other package.
   * Eg, input initial evaluation of follow up evaluation, this initial eval is in previous package, delete initial will affect this follow up in this package too
   */
  $sql = "select * from {$this->tableName}
    where init_eval_id = '{$this->getProperty('id')}'  and eval_type in (2,3)";
  $result = MIL::doQuery($sql, MYSQL_ASSOC);
  if ($result) {
   $eval_arr = array();
   foreach ($result as $eval) {
    $eval = Evaluation::createEval('from_db', $eval);
    array_push($eval_arr, $eval);
   }
   $result = $eval_arr;
  }
  return $result;
 }
 public function getPWS(){
 return $this->getTotalWS();
}
public function getAWS(){
 return 0;
}
}
?>