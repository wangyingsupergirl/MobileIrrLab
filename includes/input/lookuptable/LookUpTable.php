<?php
////////////////
require_once dirname(__FILE__) . '/../Node.php';
class LookupTuple extends Node{
private static $tablesName = array(
 0=>'eval_types_water_saving_types'
,1=>'irr_sys_types'
,2=>'water_source_types'
,3=>'ag_urban_types_names'
,4=>'pump_types'
,5=>'motor_types'
,6=>'device_gpm'
,7=>'eval_funding_sources'
,8=>'contractor'
,9=>'irr_sys_problems'
,10=>'fl_county'
,11=>'presentation_types'
,12=>'mil_lab'
,13=>'partner'
,14=>'irr_sys_type_category'
,15=>'report'
);
protected $attrArr;
protected $displayCol;
public function getDisplayCol(){
return $this->displayCol;
}
public function getNameInEval(){
return $this->nameInEval;
}

public static function createLookupTuple($tableName,$arr){
	if($tableName==self::$tablesName[0]){
		$obj = new EvalTypeWaterSavingType($arr);
	}else if($tableName==self::$tablesName[1]){
		$obj = new IrrSysType($arr);
	}else if($tableName==self::$tablesName[2]){
		$obj = new WaterSource($arr);
	}else if($tableName==self::$tablesName[3]){
		$obj = new CropCategory($arr);
	}else if($tableName==self::$tablesName[4]){
		$obj = new PumpType($arr);
	}else if($tableName==self::$tablesName[5]){
		$obj = new MotorType($arr);
	}else if($tableName==self::$tablesName[6]){
		$obj = new DeviceGPM($arr);
	}else if($tableName==self::$tablesName[7]){
		$obj = new EvalFundingSource($arr);
	}else if($tableName==self::$tablesName[8]){
		$obj = new ContractorName($arr);
	}else if($tableName==self::$tablesName[9]){
		$obj = new IrrProblem($arr);
	}else if($tableName==self::$tablesName[10]){
		$obj = new FLCounty($arr);
	}else if($tableName==self::$tablesName[11]){
                $obj = new PresentationType($arr);
        }else if($tableName==self::$tablesName[12]){
                $obj = new Lab($arr);
        }else if($tableName==self::$tablesName[13]){
		$obj = new PartnerName($arr);
        }else if($tableName==self::$tablesName[14]){
		$obj = new IrrSysTypeCategory($arr);
        }else if($tableName==self::$tablesName[15]){
		$obj = new ReportName($arr);
        }
        return $obj;
}
public function __construct($arr){
	$this->attrArr = $arr;
}
public function getProperty($paraName){
   if($paraName=='status'){
       return 'enteredByAdmin';
   }else{
       return parent::getProperty($paraName);
   }
}
 public function buildInsertSql(){
         $sql = "";
         $attrName = "";
         $attrVal = "";
         foreach($this->attrArr as $key => $val){
             if($key=='id'){
                 continue;
             }
          $attrName.=','.$key.'';
          if($val!=null){
             $val = addslashes($val);
             $attrVal .= ',"'.$val.'"';
          }else{
              //if val = null, do not add ""
              $attrVal .= ",null";
          }
          }
          $attrName= '('.substr($attrName,1).')';
          $attrVal= '('.substr($attrVal,1).')';
          $sql = 'insert into '.$this->tableName.' '.$attrName.' values '.$attrVal;
          return $sql;
    }
}

class PresentationType extends LookupTuple{
 protected $attrArr = array(
 'id'=>''
 ,'name'=>''
 );
 protected $displayCol = 'name';
 protected $nameInEval = 'presentation_types';
}

class FLCounty extends LookupTuple{
protected $attrArr = array(
'id'=>''
,'name'=>''
);
protected $displayCol='name';
protected $nameInEval = 'county_id';

}
class IrrProblem extends LookupTuple{
protected $attrArr = array(
'id'=>''
,'name'=>''
);
protected $displayCol='name';
protected $nameInEval = 'irr_sys_problems';
public function getProperty($name){
    if($name=='name'){
        return  $this->attrArr['id'].'      '.$this->attrArr['name'];
    }else{ 
        return  $this->attrArr[$name];;
 }
}
}
/*class ContractorName extends LookupTuple{
protected $attrArr = array(
'id'=>''
,'name'=>''
);
protected $displayCol='name';
protected $nameInEval = 'contractor_name';
}*/
class EvalFundingSource extends LookupTuple{
protected $attrArr = array(
'id'=>''
,'name'=>''
);
protected $displayCol='name';
protected $nameInEval = 'eval_funding_sources';
}
class DeviceGPM extends LookupTuple{
protected $attrArr = array(
'id'=>''
,'name'=>''
);
protected $displayCol='name';
protected $nameInEval = 'device_gpm';
}
class EvalTypeWaterSavingType extends LookupTuple{
protected $attrArr = array(
'id'=>''
,'evaluation_type'=>''
,'water_saving_type'=>''
);
protected $displayCol='evaluation_type';
protected $nameInEval = 'eval_type';
}
///////////////////////////////////////
class IrrSysType extends LookupTuple{
protected $attrArr = array(
'id'=>''
,'common_name'=>''
,'technical_name'=>''
,'max_du_eu'=>''
,'category'=>''
);
protected $displayCol='common_name';
protected $nameInEval = 'irr_sys_type';
}
//////////////////////////////////////
/*
class Lab extends LookupTuple{
protected $attrArr=array(
'mil_id'=>''
,'mil_name'=>''
,'mil_type'=>''
,'contractor_id'=>''
 ,' billing_cycle '=>''
,'year_of_service'
);
public function getDisplayName(){
return $this->attrArr('mil_type').'-'.$this->attrArr('mil_name');
}
}*/
///////////////////////////
class WaterSource extends LookupTuple{
protected $attrArr = array(
'id'=>''
,'type'=>'');
protected $displayCol='type';
protected $nameInEval = 'water_source';
}
//////////////////////////

class CropCategory extends LookupTuple{
protected $attrArr = array(
'id'=>'',
'type'=>'',
'name'=>''
);
protected $displayCol='name';
protected $nameInEval = 'crop_category';
public function getProperty($name){
    if($name=='name'){
        return  $this->attrArr['name'];
    }else{ 
        return  $this->attrArr[$name];;
 }
}
}
////////////////////////////////////
class PumpType extends LookupTuple{
protected $attrArr = array(
'id'=>'',
'type'=>'',
);
protected $displayCol='type';
protected $nameInEval = 'pump_type';
}
//////////////////////////////////////////
class MotorType extends LookupTuple{
protected $attrArr = array(
'id'=>'',
'type'=>'',
);
protected $displayCol='type';
protected $nameInEval = 'motor_type';
}
///////////////////////////////////////
class IrrSysTypeCategory extends LookupTuple{
protected $attrArr = array(
'id'=>'',
'name'=>''
);
protected $displayCol='name';
protected $nameInEval = 'irr_sys_type_category';
}