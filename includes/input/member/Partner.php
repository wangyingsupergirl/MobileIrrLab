<?php
require_once dirname(__FILE__).'/Member.php';
class Partner extends Member {

 protected $attrArr = array(
     'mem_id' => ''
     , 'username' => ''
     , 'password' => ''
     , 'first_name' => ''
     , 'last_name' => ''
     , 'title' => ''
     , 'funded_labs_id' => ''
     , 'inkind_labs_id' => ''
     , 'labs_id' => ''
     , 'fiscal_standard' => ''
     , 'phone' => ''
     , 'role' => ''
     , 'partner_name' => ''
     , 'busi_addr' => ''
     , 'busi_city' => ''
     , 'busi_state' => ''
     , 'busi_zip' => ''
     , 'remit_addr' => ''
     , 'remit_city' => ''
     , 'remit_state' => ''
     , 'remit_zip' => ''
     , 'fax' => ''
     , 'status' => ''
     , 'approved_time' => ''
     , 'apply_time' => ''
     , 'admin_comments' => ''
     ,'reports_id'=>''
 );

 public function setProperty($paraName,$value){
      if(($paraName=='funded_labs_id'||$paraName=='inkind_labs_id')&&is_array($value)){
         $this->attrArr[$paraName] = implode(",",$value);
      }else{
          parent::setProperty($paraName,$value);
      }
  }
 public function approve($arr) {
  if (array_key_exists('admin_comments', $arr)) {
   $lab_ids = $this->getProperty('labs_id');
   $this->updateProperty(
           array(
       'admin_comments',
       'status',
       'approved_time',
       'labs_id'), array(
       $arr['admin_comments'],
       'approved',
       date('Y-m-d H:i:s'),
       $lab_ids)
   );
   return "{$this->getProperty('first_name')} {$this->getProperty('last_name')}'s membership has been approved!";
  } else {
   echo "Input field named comment is missing in member review page role id = {$this->getProperty('role')}";
   exit;
  }
 }

 public function getProperty($paraName) {
  if ($paraName == 'labs_id') {
   $funded=$this->getProperty("funded_labs_id");
   if($funded!=""){
   $raw = $funded .','. $this->getProperty("inkind_labs_id");
   }
   else{
    $raw=$this->getProperty("inkind_labs_id");
   }
   return $this->removeDup($raw);
  } else {
   $value = parent::getProperty($paraName);
   return $value;
  }
 }

 public function removeDup($str) {
  $arr = explode(",", $str);
  $n = count($arr);
  for ($i = 0; $i < $n; $i++) {
   for ($j = 0; $j < $i; $j++) {
    $cur = $arr[$i];
    if (!is_numeric($cur) || $arr[$i] == $arr[$j]) {
     unset($arr[$i]);
     break;
    }
   }
  }
  return implode(",", $arr);
 }
}

//$test = new Partner();
//echo $test->removeDup("1,3,3,4,2,1");
?>
