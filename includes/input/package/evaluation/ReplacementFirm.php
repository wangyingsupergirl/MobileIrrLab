<?php

require_once dirname(__FILE__) . '/../Evaluation.php';
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class ReplacementFirm extends Evaluation {

 protected $requiredArr = array(
     'id'
     , 'display_id'
     , 'eval_funding_sources'
     , 'eval_type'
     , 'eval_method'
     , 'package_id'
     , 'mil_id'
     , 'irr_sys_type'
     , 'acre'
     , 'crop_category'
     , 'nir_water_use'
     , 'actual_water_use'
     , 'irr_sys_problems'
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
     , 'firm_aws'
     , 'firm_pws'
     , 'firm_iws'
     , 'init_eval_id'
     , 'eval_yr'
     , 'eval_month'
     , 'comments'
 );

 public function isCalRequired() {
  return false;
 }

 public function calculateDuEuImprov() {
  return false;
 }

 public function getWaterSavingType() {
  return 'Actual';
 }

 public function getTotalWS() {
  return $this->getProperty('firm_aws') + $this->getProperty('firm_iws');
 }
 public function getPWS(){
 return $this->getTotalPWS();
}
 public function getTotalPWS() { //intial copy
      return $this->getProperty('firm_pws');
 }
public function getAWS(){
 return $this->getTotalWS();
}
 public function createInitialEval() {
  $eval = new Evaluation();
  foreach ($this->attrArr as $name => $val) {
   if ($name == 'eval_type') {
    $eval->setProperty($name, 1);
   } else if ($name == 'id') {
    $id = $eval->generateId();
    $eval->setProperty($name, $id);
   } else if ($name == 'init_eval_id') {
    $eval->setProperty($name, null);
   } else {
    $eval->setProperty($name, $val);
   }
  }
  return $eval;
 }

// public function commitToDb() {
//  $sql = "select * from {$this->tableName} where id='{$this->getProperty($this->idName)}'";
//  $result = MIL::doQuery($sql, MYSQL_ASSOC);
//  if ($result) {
//   return $this->updateAllProperties();
//  } else {
//   $initial_eval = $this->existsAsInitial();
//   if ($initial_eval) {
//    //may be update that initial copy also based on the new data
//   } else {
//    $initial_eval = $this->createInitialEval();
//    $initial_eval->insertToDb();
//   }
//
//   return $this->insertToDb();
//  }
// }

}

?>
