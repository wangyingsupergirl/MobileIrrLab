<?php

require_once dirname(__FILE__) . '/../Evaluation.php';
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class InitFirm extends Evaluation {

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
  return 'Potential';
 }

 public function getTotalWS() {
  return $this->getProperty('firm_pws');
 }

 public function delete() {
  if ($this->id == "" && array_key_exists('id', $this->attrArr)) {
   $this->id = $this->attrArr['id'];
  }
  /* There is no replacement firm, so only delete self */
  $sql = "delete from {$this->tableName}
          where id = '{$this->getProperty('id')}'";
  $result = MIL::doQuery($sql, MIL_DB_INSERT);
  //delete follow up or replacement which initial or last evaluation is $this
  $sql = "delete from {$this->tableName}
    where init_eval_id = '{$this->getProperty('id')}'  and eval_type in (2,3)";
  $result = MIL::doQuery($sql, MIL_DB_INSERT);
  return $result;
 }

 /*
   public function commitToDb() {
   $sql = "select * from {$this->tableName} where id='{$this->getProperty($this->idName)}'";
   $result = MIL::doQuery($sql, MYSQL_ASSOC);
   if ($result) {
   $return = $this->updateAllProperties();
   $replacement_eval = $this->existsAsReplacement();
   if ($replacement_eval) {
   $replacement_eval->copy($this);
   $replacement_eval->updateAllProperties();
   } else {
   //do nothing
   }
   return $return;
   } else {
   return $this->insertToDb();
   }
   }

   public function createReplacementEval(){
   $eval = new Evaluation();
   foreach($this->attrArr as $name => $val){
   if($name=='eval_type'){
   $eval->setProperty($name,3);
   }else if($name=='id'){
   $id = $eval->generateId();
   $eval->setProperty($name,$id);
   }else if($name=='init_eval_id'){
   continue;
   }else{
   $eval->setProperty($name,$val);
   }
   }
   return $eval;
   }
  */
 public function getPWS(){
 return $this->getTotalWS();
}
public function getAWS(){
 return 0;
}
 
  }

?>
