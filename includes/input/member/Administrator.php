<?php
require_once dirname(__FILE__).'/Member.php';
class Administrator extends Member{
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
    , 'partners_id'=>''
    , 'status'=>''
    , 'approved_time'=>''
    , 'apply_time'=>''
     ,'admin_comments'=>''
    ,'company'=> ''
  );
  
}
?>
