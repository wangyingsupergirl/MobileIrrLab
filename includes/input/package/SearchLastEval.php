<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class SearchLastEval extends Node{

    protected $attrArr = array(
        'id' => '',
        'eval_yr' => '',
        'eval_month' => '',
        'irr_sys_type' => '',
        'crop_category' => '',
        'county_id' => '',
        'acre_from' => '',
        'acre_to' => '',
        'zip_code' => '',
        //'mil_id' => '',
        'eval_method'=>''
    );
    protected $paraCount = 0;
    protected $evalList = array();
    protected $isReplacement;
    /*
     * Parameters:
     * 1. $paraArr: The criteria entered/selected by users(acre, crop...)
     * 2. $relatedEval: It could be follow up or replacement evaluation
     *    The underlying criteria:
     *    The evaluations should have the same value of below attributes as $relatedEval
     *    i.  MIL lab id
     *    ii. Evaluation Method
     *
     */
    public function __construct($paraArr,$relatedEval) {
        //$this->setProperty('mil_id', $relatedEval->getProperty('mil_id'));
        $this->setProperty('eval_method',$relatedEval->getProperty('eval_method'));
        $this->isReplacement = (($relatedEval->getProperty('eval_type')==3?true:false));
        foreach ($paraArr as $key => $val) {
            if (array_key_exists($key, $this->attrArr)) {
                if ($val != '') {
                    if ($val == 0) {
                        if ($key == 'acre_from' || $key == 'acre_to') {
                            
                        } else {
                            continue;
                        }
                    }
                    $this->attrArr[$key] = $val;
                   
                }
            }
        }
        
        if (is_numeric($paraArr['acre_from']) && is_numeric($paraArr['acre_to'])) {
            if ($paraArr['acre_from'] > $paraArr['acre_to']) {
                throw new Exception('acre from should be larger than acre to');
            }
        }
    }

    public function buildSql() {
        $sql = "select e.*
        from evaluation as e 
        join fl_county as c 
        join ag_urban_types_names t 
        where e.county_id = c.id  
        and t.id = e.crop_category  ";
        foreach ($this->attrArr as $key => $val) {
            if ($val != '') {
                $sql.=" and ";
                if ($key == 'acre_from') {
                    $sql.='acre >=' . $val;
                } else if ($key == 'acre_to') {
                    $sql.='acre <=' . $val;
                } else {
                    $sql.= 'e.'.$key . '="' . $val . '"';
                }
            }
        }
        if($this->isReplacement==false){
            $sql.=' and eval_type in (1,3)'; //only search initial evaluation
        }else{
            $sql .='';// Last evaluation of Replacement evaluation could be initial or follow up or replacement(initial)
        }

        return $sql;
    }


    public function isEvalList() {
        $sql = $this->buildSql();
        $eval_arr = MIL::doQuery($sql, MYSQL_ASSOC);
        if ($eval_arr == false) {//no waiting list
            return false;
        } else {
            // process waiting list; add to package waiting list array
            $evalList = array();
            foreach ($eval_arr as $key => $arr) {
                //$eval = new Evaluation($arr,$this);
                $eval = Evaluation::createEval('from_db', $arr);
                //Not sure
               $eval->setLastModifiedTime($arr['last_modified_time']);
                $id = $eval->getProperty('id');
                $evalList[$id] = $eval;
            }
            $this->evalList = $evalList;
            return true;
        }
    }

    public function getProperty($paraName){
        if($paraName=='evalList'){
            return $this->evalList;
        }else{
            return parent::getProperty($paraName);
        }
    }

}

?>
