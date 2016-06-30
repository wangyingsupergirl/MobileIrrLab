<?php
require_once dirname(__FILE__).'/LookUpTable.php';
/*
* To change this template, choose Tools | Templates
* and open the template in the editor.
*/
class Lab extends LookupTuple{
protected $displayedAttrs;
protected $idName = 'mil_id';
protected $attrArr=array(
    'mil_id'=>''
    ,'mil_name'=>''
    ,'mil_type'=>''
    ,'contractor_id'=>''
    ,'billing_cycle'=>''
    ,'year_of_service'=>''
);
public function __construct($arr=array()){
    foreach($this->attrArr as  $key => $value){
        if(array_key_exists($key,$arr)){
        $this->attrArr[$key] = $arr[$key];
        }else{
        $this->attrArr[$key] = NULL;
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

public function getDisplayName($name=null){
    if($name == null){
        return $this->attrArr['mil_type'].'-'.$this->attrArr['mil_name'];
    }else if($name=='contractor_id'){
        $table_name = 'contractor';
        $val = $this->getProperty($name);
        $table = Utility::getLookupTable($table_name, null);
        if(array_key_exists($val,$table)){
            $row = $table[$val];
        }else{
            return 'NA';
        }
        return $row->getProperty('name');
    }
}

public function getID(){
    return $this->getProperty('mil_id');
}
}

?>
