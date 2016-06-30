<?php
require_once dirname(__FILE__).'/Member.php';
class Contractor extends Member{
  protected $attrArr = array(
    'mem_id'=> ''
    ,'username'=> ''
    ,'password'=> ''
    ,'first_name'=> ''
    ,'last_name'=> ''
    ,'title'=> ''
    ,'labs_id'=> ''
    ,'fiscal_standard'=> ''
    ,'phone'=> ''
    ,'role'=> ''
    ,'contractor_name' => ''
    , 'busi_addr' => ''
    , 'busi_city' => ''
    , 'busi_state' => ''
    , 'busi_zip' =>''
    , 'remit_addr' => ''
    , 'remit_city' => ''
    , 'remit_state' => ''
    , 'remit_zip' => ''
    , 'fax' =>''
    ,'lab_county'=>'' //array(county_id=>"lab_id1,lab_id2")
    ,'status'=>''
    ,'approved_time'=>''
    ,'apply_time'=>''
    ,'admin_comments'=>''
  );
  public function getProperty($paraName){
      $lab_county = $this->attrArr['lab_county'];
      if($paraName=='lab_county'&&is_string($lab_county)){
          $labs_counties = unserialize($this->attrArr['lab_county']);
          return $labs_counties;
      }else{
          $value = parent::getProperty($paraName);
          return $value;
      }
  }

  public function setProperty($paraName,$value){
      if($paraName=='lab_county'&&is_array($value)){
         $this->attrArr['lab_county'] = serialize($value);
      }else{
          parent::setProperty($paraName,$value);
      }
  }
  
  public function approve($arr){
        if(array_key_exists('admin_comments',$arr)){
            $lab_county = $this->getProperty('lab_county');
            foreach($lab_county as $lab_id => $counties){
                $counties = explode(",",$counties);
                foreach($counties as $county_id){
                    $lab_id = trim($lab_id);
                    $county_id = trim($county_id);
                    $sql = "replace lab_county values($lab_id, $county_id)";
                    $result = MIL::doQuery($sql,MIL_DB_INSERT);
                }
            }
            $this->updateProperty(array('admin_comments','status','approved_time'),array($arr['admin_comments'],'approved',date('Y-m-d H:i:s')));
            
            return "{$this->getProperty('first_name')} {$this->getProperty('last_name')}'s membership has been approved!";

        }else{
            echo "Input field named comment is missing in member review page role id = {$this->getProperty('role')}";
            exit;
        }
       }
}
?>
