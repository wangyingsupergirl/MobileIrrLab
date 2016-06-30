<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
class Form{
    protected $requiredFieldsName;
    
    public function check($input_arr){
        foreach($this->requiredFieldsName as $name){
           
        }
    }
    
    public static function createForm($btn_name){
        
    }
    public function validate($btn_name, $para_arr){
        if($btn_name=='eval_type_submit'){
            $eval_method = $para_arr['eval_method'];
            $eval_type = $para_arr['eval_type'];
            if(!is_numeric($eval_type)){
                echo 'Evaluation type is required.';
                exit;
            }
            if($eval_method!='irr'&&$eval_method!='firm'){
               echo 'Evaluation method is required.';
               exit;
            }
        }
        return true;
        
        
    }
}
?>
