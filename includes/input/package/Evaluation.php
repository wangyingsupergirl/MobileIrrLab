<?php
error_reporting(E_ALL & ~E_STRICT & ~E_NOTICE & ~E_DEPRECATED);
require_once dirname(__FILE__) . '/../Node.php';

/*
 * Class Evaluation
 * 
 */

class Evaluation extends Node {

 protected $id; // to be deprecated
 protected $attrArr = array(
     'id' => ''
     , 'display_id' => ''
     , 'eval_funding_sources' => ''
     , 'eval_type' => ''
     , 'eval_method' => ''
     , 'package_id' => ''
     , 'mil_id' => ''
     , 'irr_sys_type' => ''
     , 'irr_sys_du' => ''
     , 'acre' => ''
     , 'crop_category' => ''
     , 'nir_water_use' => NULL
     , 'actual_water_use' => NULL
     , 'irr_sys_problems' => ''
     , 'sched_imprv' => ''
     , 'planned_repairs' => ''
     , 'imm_repairs' => ''
     , 'county_id' => ''
     , 'zip_code' => ''
     , 'farm_id' => ''
     , 'soil_type' => ''
     , 'water_source' => ''
     , 'tds' => ''
     , 'ph' => ''
     , 'pump_type' => ''
     , 'has_flow_meter' => ''
     , 'gpm' => ''
     , 'device_gpm' => ''
     , 'motor_type' => ''
     , 'firm_aws' => ''
     , 'firm_pws' => ''
     , 'firm_iws' => ''
     , 'from_flow_meter' => ''
     , 'from_device' => ''
     , 'init_eval_id' => ''
     , 'eval_yr' => ''
     , 'eval_month' => ''
     , 'comments' => ''
     , 'status' => ''
     , 'eval_created_time' => ''
     ,'pws'=>''
     ,'aws'=>''
     ,'eval_yr_month'=>''
 );
 protected $tableName = 'evaluation';
 protected $lastModifiedTime;
 protected $package;
 protected $isSingleEval = false; //enter from package or from follow up evaluation
//True, Only if evaluation type is initial and input it manually when input its follow up evaluation
 protected $duEuImprov = false;
 protected $initialDuEuImprov = false; //replacement only
 protected $waterSaving = false;
 protected $initEval = false;

 public function commitToDb() {
  //check if this eval is new eval or not
  $sql = "select *  from {$this->tableName} where id='{$this->getProperty('id')}'";
  $eval_arr = MIL::doQuery($sql, MYSQL_ASSOC);
  $eval_created_time = date('Y-m-d H:i:s');//If it is the first enter, 
  if ($eval_arr != false) {
   /* The evaluation entered before.
    * 1. get old copy's create time
    * 2. set create time to new copy 
    */
  foreach ($eval_arr as $key => $arr) {
    $eval = Evaluation::createEval('from_db', $arr);
    $eval_created_time = $eval->getProperty('eval_created_time');
   }
   $sql = "delete from {$this->tableName} where id='{$this->getProperty('id')}'";
   $result = MIL::doQuery($sql, MIL_DB_INSERT);
  }
  $this->setProperty("eval_created_time", $eval_created_time);
  $this->setProperty("aws", $this->getAWS());
  $this->setProperty("pws", $this->getPWS());
  $this->setProperty("eval_yr_month", $this->concatEvalYrMonth());
  $sql = $this->buildInsertSql();
  $result = MIL::doQuery($sql, MIL_DB_INSERT);
 }

 public function validateInput() {
  /*
   * Server side validation
   * setWaterSaving() requires this function
   * I. NIR and AWU validation
   *  1. Valid NIR is in the range  (0,100), AWU is in the range [0, 100), or blank/space
   *  2. NIR and AWU are not both blanks/spaces
   *  3. Change space or blank to NULL
   */
  if ($this->getProperty('eval_type') != 1 && $this->initEval != 1) {
   if ($this->getProperty('eval_type') == 2) {
    throw new Exception("Please enter initial evaluation first!");
   } else {
    throw new Exception("Please enter last evaluation first!");
   }
  }
  $nir = $this->getProperty('nir_water_use');
  $awu = $this->getProperty('actual_water_use');
  $numeric_num = 0;
  if (is_numeric($nir)) {
   if ($nir <= 0 || $nir >= 100) {
    throw new Exception('NIR must be greater than 0 and less than 100.');
   }
   $numeric_num++;
  } else {
   if (trim($nir) != '') {
    throw new Expection('NIR must be number or blank/space');
   } else {
    $this->setProperty('nir_water_use', null);
   }
  }
  if (is_numeric($awu)) {
   if ($awu < 0 || $awu >= 100) {
    throw new Exception('AWU must equals to or be greater than 0 and less than 100');
   }
   $numeric_num++;
  } else {
   if (trim($awu) != '') {
    throw new Exception('AWU must equals to or be greater than 0 and less than 100');
   } else {
    // in DB actual water use is decimal, actual water use can be 0, so if it is blank, should be store as null
    $this->setProperty('actual_water_use', null);
   }
  }
  if ($numeric_num == 0) {
   throw new Exception('For AWU or NIR, at least enter one valid number');
  }
  return true;
 }

//factory
 public static function createEval($msg, $paraArr) {
  if ($msg == 'new_eval') {
   if (!array_key_exists('package_id', $paraArr)) {
    echo 'package_id is missing';
    exit;
   }
   $eval = new Evaluation();
   $eval->init($paraArr);


   // have id and parameter array.
  } else if ($msg == 'new_init_eval') {//no package_id
   $eval = new Evaluation();
   $eval->init($paraArr);
   $eval->setProperty('isSingleEval', true);
  } else if ($msg == 'from_db') {
   $eval = self::createEvalByTypeMethod($paraArr);
   $eval->setInitEval();
  }
  return $eval;
 }

 public static function createEvalByTypeMethod($paraArr) {
  $eval_type = '';
  $eval_method = '';
  if (array_key_exists('eval_type', $paraArr)) {
   $eval_type = $paraArr['eval_type'];
  }
  if (array_key_exists('eval_method', $paraArr)) {
   $eval_method = $paraArr['eval_method'];
  }
  if ($eval_type == '1' && $eval_method == 'firm') {
   $eval = new InitFirm();
  } else if ($eval_type == '1' && $eval_method == 'irr') { //1=initial
   $eval = new InitIrrSys();
  } else if ($eval_type == '2' && $eval_method == 'firm') { //1=initial
   $eval = new FollowFirm();
  } else if ($eval_type == '2' && $eval_method == 'irr') { //1=initial
   $eval = new FollowIrrSys();
  } else if ($eval_type == '3' && $eval_method == 'firm') { //1=initial
   //Replacement and firm is not supported by system.
   //This type of evaluation hasn't happened so far in 6 years.
   $eval = new ReplacementIrrSys();
   $paraArr['eval_method'] = 'firm';
  } else if ($eval_type == '3' && $eval_method == 'irr') { //1=initial
   $eval = new ReplacementIrrSys();
  } else {
   echo 'Invalid eval type or eval method';
   exit;
  }
  $eval->init($paraArr);

  return $eval;
 }

//
 public static function transformEval($paraArr, $previousEval) {
//$paraArr only has method and type
  $eval = self::createEvalByTypeMethod($paraArr);
  $eval->setPropertiesByObj($previousEval);
  if ($eval->getProperty('eval_type') == '1') {
   $eval->setProperty('farm_id', $eval->generateId());
  } else {
   $eval->setProperty('farm_id', '');
   $eval->setInitEval();
  }
  return $eval;
 }

 public function init($paraArr) {
  foreach ($this->attrArr as $name => $val) {
   if (array_key_exists($name, $paraArr)) {
    $this->attrArr[$name] = $paraArr[$name];
   }
  }

  if (!$this->attrArr['id']) {
   $this->attrArr['id'] = $this->generateId();
   $this->attrArr['status'] = 'pending';
  }

  $this->tableName = 'evaluation';
 }

 /*
  * Determine whether entered evaluation ($eval) is in the same package as this evaluation object
  */

 public function inSamePackage($eval) {
  if ($this->getProperty('eval_yr') == $eval->getProperty('eval_yr')) {
   $month = $this->getProperty('eval_month');
   $range_a = FiscalQuarter::getQuarterRange($month);
   $month = $eval->getProperty('eval_month');
   $range_b = FiscalQuarter::getQuarterRange($month);
   if ($range_a == $range_b) {
    return true;
   } else {
    return false;
   }
  }
 }

 public function getTotalWS() {
  if ($this->isFirm()) {
   return false;
  }
  $sched_imprv = $this->getProperty('sched_imprv');
  $planned_repairs = $this->getProperty('planned_repairs');
  $imm_repairs = $this->getProperty('imm_repairs');
  $total = $this->duEuImprov + ($sched_imprv == '' ? 0 : $sched_imprv) + ($planned_repairs == '' ? 0 : $planned_repairs) + ($imm_repairs == '' ? 0 : $imm_repairs);
  return round($total, 2);
 }

 public function isFarmIDSet() {
  $farm_id = $this->getProperty('farm_id');
  if ($farm_id == '' || $farm_id == 0)
   return false;
  else
   return true;
 }

 public function isFirm() {
  if ($this->getProperty('eval_method') == 'firm')
   return true;
  else
   return false;
 }

 public function isIrrSys() {
  if ($this->getProperty('eval_method') == 'irr')
   return true;
  else
   return false;
 }

 public function isTypeDetermined() {
  /* if ($this->innerStatus == 'type_undetermined')
    return false;
    else
    return true; */
  $type = $this->getProperty('eval_type');
  $method = $this->getProperty('eval_method');
  if (is_numeric($type) && $method != '') {
   return true;
  } else {
   return false;
  }
 }

 //used by createEval
 public function setInitEval() { // from database
  if ($this->getProperty('eval_type') == 1) {
   return;
  } else if ($this->getProperty('eval_type') == 2 || $this->getProperty('eval_type') == 3) {
   $init_id = $this->getProperty('init_eval_id');
   $sql = 'select * from evaluation where id="' . $init_id . '"';
   $eval_arr = MIL::doQuery($sql, MYSQL_ASSOC);
   if ($eval_arr != false) {
    foreach ($eval_arr as $key => $paraArr) {
     $init_eval = Evaluation::createEval('from_db', $paraArr);
     $init_eval->setLastModifiedTime($paraArr['eval_created_time']);
    }
    $this->initEval = $init_eval;
    return;
   } else {
    return;
   }
  }
 }

 public static function createEvalByType($paraArr, $package) {
  $eval_type = $paraArr['eval_type'];
  $eval_method = $paraArr['eval_method'];
  if ($eval_type == '1' && $eval_method == 'firm') {
   $eval = new InitFirm($paraArr, $package);
  } else if ($eval_type == '1' && $eval_method == 'irr') { //1=initial
   $eval = new InitIrrSys($paraArr, $package);
  } else {
   //to be continue;
   $eval = null;
  }
  return $eval;
 }

 public function setLastModifiedTime($time) {
  if ($time == null)
   $time = 'N/A';
  $this->lastModifiedTime = $time;
 }

 public function getLastModifiedTime() {

  return $this->lastModifiedTime;
 }

 public function setAttrArr($paraArr) {
  foreach ($this->attrArr as $key => $val) {
   if (array_key_exists($key, $paraArr)) {
    $this->setProperty($key, $paraArr[$key]);
   }
  }
  $this->setProperty("aws", $this->getAWS());
  $this->setProperty("pws", $this->getPWS());
  $this->setProperty("eval_yr_month", $this->concatEvalYrMonth());
 }

 public function generateDisplayId($package) {
  $mil_id = $package->getLabId();
  $eval_yr = $package->getEvalYr();
  $eval_month = $package->getEvalMonth();
  $index = $package->getEvalNum();
  return $mil_id . '_' . $eval_yr . '_' . $eval_month . '_' . $index;
 }

 public function getProperty($paraName) {

  if ($paraName == 'isSingleEval') {
   return $this->isSingleEval;
  }
  if ($paraName == 'duEuImprov') {
   return $this->duEuImprov;
  }
  if ($paraName == 'initEval') {
   return $this->initEval;
  }

  return parent::getProperty($paraName);
 }
public function concatEvalYrMonth(){
  return parent::getProperty('eval_yr').'-'.parent::getProperty('eval_month').'-1';
}
 public function setProperty($paraName, $paraVal) {
  if ($paraName == 'isSingleEval') {
   $this->isSingleEval = $paraVal;
  } else if ($paraName == 'initEval') {
   $this->initEval = $paraVal;
  } else if ($paraName == 'duEuImprov') {
   $this->duEuImprov = $paraVal;
  } else if($paraName == 'eval_yr' || $paraName == 'eval_month') {
      parent::setProperty($paraName, $paraVal);
      parent::setProperty('eval_yr_month', $this->concatEvalYrMonth());
  }else{
   parent::setProperty($paraName, $paraVal);
  }
 }

 public function getPackage() {
  return $this->package;
 }

 /*
   public function insertToDb() {
   try {

   $sql = 'select * from evaluation where id="' . $this->getProperty('id') . '"';
   $result = MIL::doQuery($sql, MYSQL_ASSOC);
   if ($result != false) {
   $sql = 'delete from evaluation where id="' . $this->getProperty('id') . '"';
   $result = MIL::doQuery($sql, MIL_DB_INSERT);

   }
   $sql = $this->buildInsertSql();
   $result = MIL::doQuery($sql, MIL_DB_INSERT); // may throw db exception

   } catch (Expection $e) {
   echo $e->getMessage();
   }
   } */

 public function setPropertiesByObj($previousEval) { //$previousEval is previous evaluation object
  foreach ($this->attrArr as $attrName => $val) { // according requiredArr initialize evaluation
   if ($attrName == 'eval_type' || $attrName == 'eval_method')
    continue;
   $val = $previousEval->getProperty($attrName);
   $this->setProperty($attrName, $val);
  }
  if ($previousEval->getProperty('isSingleEval')) {
   $this->setProperty('isSingleEval', true);
  }
 }

 public function exists() {
  $sql = "select * from $this->tableName where ";
  //Get all the properties which is not '';
  $arr = array();
  foreach ($this->attrArr as $name => $value) {
   if ($value == '') {
    continue;
   }
   $arr[$name] = $value;
  }
  $i = 0;
  $length = count($arr);
  foreach ($arr as $name => $value) {
   $sql .= "$name = '$value' ";
   if ($i != $length - 1) {
    $sql.="and ";
   }
   $i++;
  }
  $rtn = MIL::doQuery($sql, MYSQLI_ASSOC);
  if ($rtn) {
   if (count($rtn)) {
    $arr = $rtn[0];
    $eval = Evaluation::createEval('from_db', $arr);
    return $eval;
   }
  }
  return false;
 }

 public function existsAsInitial() {
  if ($this->getProperty('eval_type') == 3) {
   $sql = "select * from $this->tableName
                where eval_type=1
                  and display_id='{$this->getProperty('display_id')}' ";

   $rtn = MIL::doQuery($sql, MYSQLI_ASSOC);
   if ($rtn) {
    if (count($rtn)) {
     $arr = $rtn[0];
     $eval = Evaluation::createEval('from_db', $arr);
     return $eval;
    }
   }
   return false;
  } else {
   return false;
  }
 }

 public function existsAsReplacement() {
  if ($this->getProperty('eval_type') == 1) {
   $sql = "select * from $this->tableName
                where eval_type=3
                  and display_id='{$this->getProperty('display_id')}' ";

   $rtn = MIL::doQuery($sql, MYSQLI_ASSOC);
   if ($rtn) {
    if (count($rtn)) {
     $arr = $rtn[0];
     $eval = Evaluation::createEval('from_db', $arr);
     return $eval;
    }
   }
   return false;
  } else {
   return false;
  }
 }

 public function copy($eval) {
  if ($this->getProperty('eval_type') == '3' || $eval->getProperty('eval_type') == '1') {
   foreach ($eval->attrArr as $name => $val) {
    if ($name != 'eval_type' && $name != 'id' && $name != 'init_eval_id') {
     $this->setProperty($name, $val);
    }
   }
  } else {
   echo "evaluation->copy($eval) invalid eval_type";
  }
 }

 public function getDisplayName($name) {
  $val = $this->getProperty($name);
  if ($name == 'eval_type') {
   if ($val == INITIAL_EVALUATION) {
    return 'Initial';
   } else if ($val == FOLLOW_UP_EVALUATION) {
    return 'Follow up';
   } else if ($val == REPLACEMENT_EVALUATION) {
    return 'Replacement';
   } else {
    return "Invalid evaluation type{$val}";
   }
  } else if ($name == 'eval_method') {
   if ($val == 'firm') {
    return 'FIRM';
   } else if ($val == 'irr') {
    return 'Irrigation System Only';
   } else {
    return "Invalid evaluation method {$val}";
   }
  } else if ($name == 'crop_category') {
   $table = Utility::getLookupTable('ag_urban_types_names', null);
   $row = $table[$val];
   return $row->getProperty('name');
  } else if ($name == 'county_id') {
   $table = Utility::getLookupTable('fl_county', null);
   $row = $table[$val];
   return $row->getProperty('name');
  } else {
   return "Only accept eval_type and eval_method now";
  }
 }

 public function isInitOrLastToOthers() {
  $sql = "select * from {$this->tableName}
 where init_eval_id = '{$this->getProperty('id')}'";
  $result = MIL::doQuery($sql, MYSQL_ASSOC);
  if ($result) {
   return true;
  } else {
   return false;
  }
 }

 public function getRelatedFollowupReplacementEvals() {
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
}
?>
