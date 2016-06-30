<?php

require_once dirname(__FILE__) . '/InitIrrSys.php';

class ReplacementIrrSys extends InitIrrSys {
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

 public function calculateInitialCopyDuEuImprov($irr_sys_types_table) {
  return parent::calculateDuEuImprov($irr_sys_types_table);
 }

 public function calculateDuEuImprov($irr_sys_types_table=null) {
  //irr_sys_types_table won't be used by follow up irrgation system evaluation. It is for inital irr system evaluation.
  $nir = $this->getProperty('nir_water_use');
  $awu = $this->getProperty('actual_water_use');
  $du = $this->getProperty('irr_sys_du');
  $acre = $this->getProperty('acre');
  $init_eval = $this->getProperty('initEval');
  if ($init_eval == false) {
   throw new exception('Please enter the inital evaluation first!');
  } else {
   $init_nir = $init_eval->getProperty('nir_water_use');
   $init_awu = $init_eval->getProperty('actual_water_use');
   $init_acre = $init_eval->getProperty('acre');
   $init_du = $init_eval->getProperty('irr_sys_du');
  }

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

 public function commitToDb() {
  $sql = "select * from {$this->tableName} where id='{$this->getProperty($this->idName)}'";
  $result = MIL::doQuery($sql, MYSQL_ASSOC);
  if ($result) {
   return $this->updateAllProperties();
  } else {
   return $this->insertToDb();
  }
 }

 public function getTotalPWS() { //intial copy
  $irr_sys_types_table = Utility::getLookupTable('irr_sys_types', null);
  $sched_imprv = $this->getProperty('sched_imprv');
  $planned_repairs = $this->getProperty('planned_repairs');
  $imm_repairs = $this->getProperty('imm_repairs');
  $du = $this->calculateInitialCopyDuEuImprov($irr_sys_types_table);
  $total = $du + ($sched_imprv == '' ? 0 : $sched_imprv) + ($planned_repairs == '' ? 0 : $planned_repairs) + ($imm_repairs == '' ? 0 : $imm_repairs);
  return round($total, 2);
 }

 public function getTotalWS() {
  $sched_imprv = $this->getProperty('sched_imprv');
  $planned_repairs = $this->getProperty('planned_repairs');
  $imm_repairs = $this->getProperty('imm_repairs');
  $this->duEuImprov = $this->calculateDuEuImprov();
  $total = $this->duEuImprov + ($sched_imprv == '' ? 0 : $sched_imprv) + ($planned_repairs == '' ? 0 : $planned_repairs) + ($imm_repairs == '' ? 0 : $imm_repairs);
  return round($total, 2);
 }
 public function getPWS(){
 return $this->getTotalPWS();
}
public function getAWS(){
 return $this->getTotalWS();
}
}

?>
