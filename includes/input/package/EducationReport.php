<?php

require_once dirname(__FILE__) . '/../Node.php';
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class EducationReport extends Node {

    protected $attrArr = array('id' => '',
        'presentation_date' => ''
        , 'presentation_types' => ''
        , 'group_name' => ''
        , 'attending_num' => ''
        , 'city' => ''
        , 'duration' => ''
        , 'package_id' => ''
        , 'status' => '');

    public function __construct($arr) {
        if (array_key_exists('id', $arr)) {
            $this->setProperty('id', $arr['id']);
        } else {
            $id = $this->generateId();
            $this->setProperty('id', $id);
        }


        foreach ($this->attrArr as $key => $value) {
            if (array_key_exists($key, $arr)) {
                $this->setProperty($key, $arr[$key]);
            }
        }
        $this->tableName = 'education_reports';
    }

    public function generateReadableId() {
        return $this->attrArr['presentation_types'] . $this->attrArr['group_name'] . $this->attrArr['city'];
    }
    public function getDisplayName($name){
        $val = $this->getProperty($name); //indexs '1,3,4'
        if($val==null){
            //if javascript is not working, $val could be null
            return 'NA';
        }
        if($name=='presentation_types'){
            $types = ""; 
            $table = Utility::getLookupTable('presentation_types', null);
            
             $indexs =explode(",", $val);
             foreach($indexs as $index){
                $row = $table[$index];
                $types .= $row->getProperty('name').",";
             }
              return substr($types, 0, strlen($types)-1);
             
        }else{
            return "Only accept presenation_types now";
        }
    }

}

?>
