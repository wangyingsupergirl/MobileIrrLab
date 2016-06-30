<?php
require_once dirname(__FILE__).'/../Node.php';
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
class Contract extends Node{
protected $arr_names = array('id'
         ,'fdacs_id'
         ,'fdacs_yr'
         ,'mil_id'
         ,'quarter1_evals'
         ,'quarter2_evals'
         ,'quarter3_evals'
         ,'quarter4_evals'
         ,'quarter1_followup_evals'
         ,'quarter2_followup_evals'
         ,'quarter3_followup_evals'
         ,'quarter4_followup_evals');
protected $displayedAttrs;
public function __construct($arr=array()){
if(array_key_exists('id',$arr)&&$arr['id']!=null){
  $this->id = $arr['id'];
}else{
  $this->id = $this->generateId();
  $arr['id'] = $this->id;
}

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
$this->tableName = 'contract';
}

public function getProperty($paraName){
   if($paraName=='status'){
       return 'enteredByAdmin';
   }else{
       return parent::getProperty($paraName);
   }
}

public function getTotalEvals(){
    return $this->getProperty('quarter1_evals')+$this->getProperty('quarter2_evals')+$this->getProperty('quarter3_evals')+$this->getProperty('quarter4_evals');
}

public function getEvalNumDetails(){
   return "({$this->displayedAttrs['quarter1_evals']}+{$this->displayedAttrs['quarter2_evals']}+{$this->displayedAttrs['quarter3_evals']}+{$this->displayedAttrs['quarter4_evals']})";

}
public function getTotalFollowupEvals(){
   return $this->getProperty('quarter1_followup_evals')+$this->getProperty('quarter2_followup_evals')+$this->getProperty('quarter3_followup_evals')+$this->getProperty('quarter4_followup_evals');
}
public function getFollowupEvalNumDetails(){
   return "({$this->displayedAttrs['quarter1_followup_evals']}+{$this->displayedAttrs['quarter2_followup_evals']}+{$this->displayedAttrs['quarter3_followup_evals']}+{$this->displayedAttrs['quarter4_followup_evals']})";

}
}
?>
