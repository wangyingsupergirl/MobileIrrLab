<?php
require_once dirname(__FILE__).'/Node.php';
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
class ContractorName extends Node{
protected $arr_names = array(
         'id'
         ,'name'
         
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

$this->tableName = 'contractor';
}


}

?>
