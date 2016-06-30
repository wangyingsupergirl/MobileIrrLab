<?php
require_once dirname(__FILE__).'/../constant.php';
require_once dirname(__FILE__).'/../mil_init.php';
class Invoice{
 protected $attrArr = array(
      'period' => ''
     , 'contractor_name' =>''
     ,'contractor_remit_addr'=>''
      ,'contractor_remit_city'=>''
      ,'contractor_remit_state'=>''
     ,'contractor_remit_zip'=>''
     ,'contractor_phone'=>''
     ,'customer_name'=>' '
     ,'customer_addr'=>''
     ,'customer_city'=>''
     ,'customer_state'=>''
     ,'customer_zip'=>''
     ,'customer_phone'=>''
     ,'contract_id'=>''
     ,'mil_name'=>''//below are user input
     ,'date' => ''
     ,'order_id' => ''
     ,'rep' => ''
     ,'FOB' => ''
    ,'total'=>''
    ,'Comment' =>''
     ,'payment'=>''
     ,'FEID'=>''
     ,'id' => '');
 protected $package;
 protected $tasks =  array(); //,'tasks' => array("task_id","mil_name","cost","sub_total")
 function __construct($package){
  $this->package = $package;
 }
 function setInitialValue(){
  if($this->package==null){
   echo "package shouldn't be null";
   exit;
   }
   $mil_id = $this->package->getProperty("mil_id");
   $this->setProperty("period",  $this->package);
   $system_contract_id = $this->package->getProperty('contract_id');
   $sql = "select fdacs_id  from  contract where id =  '$system_contract_id' ";
   $rtn = MIL::doQuery($sql, MYSQLI_ASSOC);
    if(count($rtn)){
          $contract_id = $rtn[0]['fdacs_id'];
          $this->setProperty('contract_id',   $contract_id );
    }
    
   $sql  = "select 
    mil_lab.mil_name
    , mil_lab.mil_type
    , contractor.name contractor_name
    , member.remit_addr contractor_remit_addr
    , member.remit_city contractor_remit_city
    , member.remit_state contractor_remit_state
    , member.remit_zip contractor_remit_zip
    , member.phone contractor_phone
    from mil_lab 
    inner join contractor on mil_lab.contractor_id = contractor.id  
    inner join member on member.contractor_name = mil_lab.contractor_id
    where mil_lab.mil_id = '$mil_id'; ";
    $rtn = MIL::doQuery($sql, MYSQLI_ASSOC);
    if($rtn){
      if(count($rtn)){
          $info = $rtn[0];
          //set mil_name "Ag-Floridan", contractor_name,  contractor_remit_addr, contractor_phone
          $info['mil_name'] =  $info['mil_type']."-".$info['mil_name'];
          unset($info['mil_type']);
          foreach($info as $key => $val){
            $this->setProperty($key, $val);
          }
      }
     }
      $sql  = "select company customer_name
       , busi_addr customer_addr
       , busi_city customer_city
       , busi_state customer_state
       , busi_zip customer_zip 
       , phone customer_phone
       from member 
       where username = 'camilo.gaitan@freshfromflorida.com';";
    $rtn = MIL::doQuery($sql, MYSQLI_ASSOC);
    if($rtn){
      if(count($rtn)){
          $info = $rtn[0];
          //set mil_name "Ag-Floridan", contractor_name,  contractor_remit_addr, contractor_phone
          foreach($info as $key => $val){
            $this->setProperty($key, $val);
          }
      }
     }
 }
 function setUserInput($arr){
  foreach($arr as $key => $val){
   if(array_key_exists($key, $this->attrArr)){
    $this->attrArr[$key] = $val;
    //console.log($val);
   }else if(strstr($key,'task_id')){
     $pieces = explode(":", $key);
     $index = $pieces[1];
     if(!isset($this->tasks[$index])){
      $this->tasks[$index] = array();
     }
     $this->tasks[$index]['task_id'] = $val;
   }else if(strstr($key,'cost')){
    $pieces = explode(":", $key);
     $index = $pieces[1];
     if(!isset($this->tasks[$index])){
      $this->tasks[$index] = array();
     }
      $this->tasks[$index]['cost'] = $val;
   }else if(strstr($key,'sub_total')){
    $pieces = explode(":", $key);
     $index = $pieces[1];
     if(!isset($this->tasks[$index])){
      $this->tasks[$index] = array();
     }
      $this->tasks[$index]['sub_total'] = $val;
   }
  }
 }
 function setProperty($name, $val){
  if($name=="period"){
    $start_month = $val->getProperty("eval_month");
    $end_month = $start_month + 2;
    $this->attrArr[$name] = Utility::getMonthName($start_month)."-".Utility::getMonthName($end_month)."/".$val->getProperty("eval_yr");
   
  }else{
   $this->attrArr[$name] = $val;
  }
}

function getProperty($name){
 if($name == 'tasks')return $this->tasks; 
 if($name == 'contractor_remit_full_addr'){
     return $this->attrArr['contractor_remit_addr']
             .", ".$this->attrArr['contractor_remit_city']
             .", ".$this->attrArr['contractor_remit_state']
             .", ".$this->attrArr['contractor_remit_zip'];
 }
 if($name =='contractor_phone'){
     $phone =  $this->attrArr['contractor_phone'];
     $phone = substr($phone, 0, 3)." ".substr($phone, 3, 3)." ".substr($phone, 6, 4);
     return $phone;
 }
 return $this->attrArr[$name];
 }
}
?>
