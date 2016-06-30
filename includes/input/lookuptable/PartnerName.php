<?php
require_once dirname(__FILE__).'/LookUpTable.php';
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
class PartnerName extends LookupTuple{
    protected $attrArr = array(
        'id'=>''
        ,'name'=>''

    );
    protected $displayedAttrs;
    protected $displayCol='name';
    //protected $nameInEval = 'contractor_name';
    public function __construct($arr=array()){
        foreach($this->attrArr as $key => $value){
            if(array_key_exists($key,$arr)){
                $this->attrArr[$key] = $arr[$key];
            }else{
                $this->attrArr[$key] = NULL;
            }
        }
        $this->tableName = 'partner';
    }
    


}

?>
