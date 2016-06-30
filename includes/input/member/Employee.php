<?php
require_once dirname(__FILE__).'/Member.php';
class Employee extends Member{
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
    ,'partners_id'=>''
    ,'status'=>''
    ,'approved_time'=>''//review_time
    ,'apply_time'=>''
    ,'admin_comments'=>''
  );
}

?>
