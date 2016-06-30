<?php

Class CountyLab{
    private $milID; 
    private $countyList;
    public function __construct($mil_id){
        $milID = $mil_id;
        $countyList = $this->setCountyList($mil_id);
     }
     public function setCountyList($mil_id){
         $sql = "select 
             county_id
             , name 
             from lab_county 
             inner join fl_county 
             where 
             lab_county.county_id = fl_county.id 
             and  mil_id = $mil_id;";
         $table = MIL::doQuery($sql, MYSQL_ASSOC);
         $list = array();
         for($i = 0; $i <  count($table); $i++){
             $item = $table[$i];
             $list[$item['county_id']] = $item;
         }
        $this->countyList = $list;
    }
    public function getCounties(){
        return json_encode($this->countyList);
    }
    
    public function displayCounties(){
        $list = "";
        foreach($this->countyList as $county){
            $list .= $county['name']. ", ";
        }
        return $list;
    }
}
?>
