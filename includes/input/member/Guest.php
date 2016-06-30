<?php
require_once dirname(__FILE__).'/Member.php';
class Guest extends Member{
   protected $attrArr = array(
    'mem_id'=> ''
    ,'username'=> ''
    ,'password'=> ''
    ,'first_name'=> ''
    ,'last_name'=> ''
    ,'title'=> ''
    ,'company'=> ''
    ,'labs_id'=> ''
    , 'busi_city' => ''
    , 'busi_state' => ''
    , 'busi_zip' =>''
    ,'phone'=> ''
    ,'role'=> ''
    ,'status'=>''
    ,'approved_time'=>''//review_time
    ,'apply_time'=>''
    ,'admin_comments'=>''
  );
}

?>
