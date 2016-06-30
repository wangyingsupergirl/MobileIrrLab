<?php
require_once dirname(__FILE__).'/../Evaluation.php';
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
class FollowFirm extends Evaluation{
protected $requiredArr=array(
'id'
,'display_id'
,'eval_funding_sources'
,'eval_type'
,'eval_method'
,'package_id'
,'mil_id'
,'irr_sys_type'

,'acre'
,'crop_category'

,'nir_water_use'
,'actual_water_use'
,'irr_sys_problems'

,'county_id'
,'zip_code'
,'farm_id'
,'soil_type'
,'water_source'
,'tds'
,'ph'
,'pump_type'
,'has_flow_meter'
,'gpm'
,'device_gpm'
,'motor_type'
,'from_flow_meter'
,'from_device' 

,'firm_aws'
,'firm_pws'
,'firm_iws'

,'init_eval_id'
,'eval_yr'
,'eval_month'
     ,'comments'
);

public function isCalRequired(){
return false;
}
public function calculateDuEuImprov(){
    return false;
}
public function getWaterSavingType(){
    return 'Actual';
}
public function getTotalWS() {
    return $this->getProperty('firm_aws')+$this->getProperty('firm_iws');
}
public function getPWS(){
 return 0;
}
public function getAWS(){
 return $this->getTotalWS();
}


}
?>
