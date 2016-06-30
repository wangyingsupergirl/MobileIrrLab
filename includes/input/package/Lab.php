<?php
require_once dirname(__FILE__).'/Node.php';
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
class Lab extends Node{
protected $arr_names = array(
         'mil_id'
         ,'mil_name'
         ,'mil_type'
         ,'year_of_service'
         ,'billing_cycle'
         ,'contractor_id'
);
protected $displayedAttrs;
public function __construct($arr=array()){

foreach($this->arr_names as  $value){
    if(array_key_exists($value,$arr)){
        $this->attrArr[$value] = $arr[$value];
    }else{
        $this->attrArr[$value] = NULL;
    }
}
foreach($this->attrArr as $key => $val){
    if($val===NULL){
        $val = 'NA';
    }
     $this->displayedAttrs[$key]=$val;

}
$this->tableName = 'mil_lab';
}
public function getLabName(){
    return $this->getProperty('mil_type').'-'.$this->getProperty('mil_name');
}

}

?>
