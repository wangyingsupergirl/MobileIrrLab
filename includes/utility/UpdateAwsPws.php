
<?php
/*
 *  1. Initial update pws
 * 2. Follow up update aws
 * 3. Replace update pws & aws
 */
require_once dirname(__FILE__) . '/../mil_init.php';
require_once dirname(__FILE__) . '/../utility.php';
require_once dirname(__FILE__) . '/../input/package/evaluation/FollowIrrSys.php';
require_once dirname(__FILE__) . '/../input/package/evaluation/InitIrrSys.php';
require_once dirname(__FILE__) . '/../input/package/evaluation/FollowFirm.php';
require_once dirname(__FILE__) . '/../input/package/evaluation/InitFirm.php';

function getEvalsByYrMonth($eval_yr, $eval_month) {
    $sql =
            "select
                e.id eval_id
                ,e.eval_type
                ,water_saving.evaluation_type eval_type_name
                ,e.eval_method
                ,e.irr_sys_type
                ,irr_sys.common_name irr_sys_name
                ,irr_sys.max_du_eu 
                ,e.irr_sys_du
                ,e.acre
                ,crop.type crop_type
                ,crop.name crop_name
                ,e.nir_water_use
                ,e.actual_water_use
                ,e.irr_sys_problems
                ,water_saving.water_saving_type
                ,e.sched_imprv 
                ,e.planned_repairs
                ,e.imm_repairs
                ,e.init_eval_id
                , e.display_id 
                ,e.firm_pws
                ,e.firm_aws
                ,e.firm_iws
            from 
            evaluation as e
            inner join  eval_types_water_saving_types as water_saving
            inner join  irr_sys_types as irr_sys
            inner join ag_urban_types_names as crop
            on
            water_saving.id=e.eval_type
            and irr_sys.id = e. irr_sys_type
            and crop.id = e.crop_category
            where  eval_month = $eval_month and
              eval_yr = $eval_yr 
              and eval_type = 3
            order by e.display_id ";
    $evals = MIL::doQuery($sql, MYSQL_ASSOC);
    if($evals == null || $evals[0]==null) $evals = null;
    return $evals;
}

function getEvalsByID($id) {
    $sql =
            "select
                e.id eval_id
                ,e.eval_type
                ,water_saving.evaluation_type eval_type_name
                ,e.eval_method
                ,e.irr_sys_type
                ,irr_sys.common_name irr_sys_name
                ,irr_sys.max_du_eu 
                ,e.irr_sys_du
                ,e.acre
                ,crop.type crop_type
                ,crop.name crop_name
                ,e.nir_water_use
                ,e.actual_water_use
                ,e.irr_sys_problems
                ,water_saving.water_saving_type
                ,e.sched_imprv 
                ,e.planned_repairs
                ,e.imm_repairs
                ,e.init_eval_id
                , e.display_id 
                ,e.firm_pws
                ,e.firm_aws
               ,e.firm_iws
            from 
            evaluation as e
            inner join  eval_types_water_saving_types as water_saving
            inner join  irr_sys_types as irr_sys
            inner join ag_urban_types_names as crop
            on
            water_saving.id=e.eval_type
            and irr_sys.id = e. irr_sys_type
            and crop.id = e.crop_category
            where e.id = '$id'
            order by e.display_id ";
    $evals = MIL::doQuery($sql, MYSQL_ASSOC);
    if($evals == null || $evals[0]==null) $evals = null;
    return $evals;
}



//For each Replacement Irrigation System Only evaluation add initial copy
//Put initial copy next to replacement copy
function addInitialForReplacement($evals) {
    $length = count($evals);
    $updated_evals = array();
    for ($i = 0; $i < $length; $i++) {
        array_push($updated_evals, $evals[$i]);
        $eval = $evals[$i];
        if ($eval['eval_type'] == 3) {//replacement evaluation
            $eval['eval_type'] = 1;
            $eval['eval_type_name'] = "Initial";
            $eval['water_saving_type'] = 'Potential';
            array_push($updated_evals, $eval);
        }
    }
    $evals = $updated_evals;
    return $evals;
}
function fillFields($evals){
   //$evals =  addInitialForReplacement($evals);
    $length = count($evals);
    $rows = array();
    for ($i = 0; $i < $length; $i++) {
        $eval = $evals[$i];
        $irr_sys_types = Utility::getLookupTable('irr_sys_types', null);
        $eval_obj = Evaluation::createEval('from_db', $eval);
        foreach ($eval as $key => $val) {
            if ($val === false || $val === null | trim($val) === '') {
                $eval[$key] = 'NA';
            }
        }
        $eu_du_improv = $eval_obj->calculateDuEuImprov($irr_sys_types);
        $ws = $eval_obj->getTotalWS();
        $eval['aws'] = ($eval['water_saving_type'] == 'Actual' ? $ws : 0);
        $eval['pws'] = ($eval['water_saving_type'] == 'Potential' ? $ws : 0);
        if($eval['eval_type']==3) {
            $eval['pws'] = $eval_obj->getTotalPWS();
        }
        $rows[count($rows)] = $eval;
    }
    return $rows;
}

function updateDB($evals){
    $length = count($evals);
    $rows = array();
    foreach ($evals as $i => $eval) {
        $sql =  "update evaluation set aws = {$eval['aws']}, pws =  {$eval['pws']} where id ='{$eval['eval_id']}' ";
        $result = MIL::doQuery($sql, MIL_DB_INSERT); 
        $rows[count($rows)] = $sql;
        echo $sql. "\r\n";
    }
    return $rows;
}
/* $evals = getEvalsByID('5064a32c48cfd');
$total = count($evals);
echo "$i-$j total: {$total}\r\n";
$evals = fillFields($evals);
updateDB($evals);
*/
function start(){
    for($i = 2000; $i <= 2014; $i++){
        for($j = 0; $j <=11 ; $j++){
            $evals = getEvalsByYrMonth($i,$j);
            $total = count($evals);
            echo "$i-$j total: {$total}\r\n";
            $evals = fillFields($evals);
            updateDB($evals);
        }
    }
     
}
start();

?>

