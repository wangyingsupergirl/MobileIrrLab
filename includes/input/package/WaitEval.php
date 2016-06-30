<?php
require_once dirname(__FILE__).'/../Node.php';
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
class WaitEval extends Node{
public function __construct($arr){

if(array_key_exists('id',$arr))
{
  $this->attrArr['id'] = $arr['id'];
  $this->attrArr['status'] = $arr['status'];
}else
{
  $this->attrArr['id'] = $this->generateId();
  $this->attrArr['status'] = 'pending';
  
}

$this->attrArr['category_id'] = $arr['category_id'];
$this->attrArr['county_id'] = $arr['county_id'];
$this->attrArr['total_count'] = $arr['total_count'];
$this->attrArr['total_acres'] = $arr['total_acres'];
$this->attrArr['package_id'] = $arr['package_id'];
$this->tableName = 'waiting_eval';
}

//insert into database;
//add to package

public function generateReadableId(){
return $this->attrArr['package_id'].$this->attrArr['county_id'].$this->attrArr['category_id'];
} 


}
?>
