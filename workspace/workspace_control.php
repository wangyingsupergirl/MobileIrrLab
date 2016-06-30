<?php

//For Delete operation
require_once dirname(__FILE__) . '/../includes/input/package/Package.php';
require_once dirname(__FILE__) . '/../includes/input/package/evaluation/FollowIrrSys.php';
require_once dirname(__FILE__) . '/../includes/input/package/evaluation/InitIrrSys.php';
require_once dirname(__FILE__) . '/../includes/input/package/evaluation/FollowFirm.php';
require_once dirname(__FILE__) . '/../includes/input/package/evaluation/InitFirm.php';
require_once dirname(__FILE__) . '/../includes/constant.php';
require_once dirname(__FILE__) . '/../includes/utility/forms_validation.php';
require_once dirname(__FILE__) . '/../includes/input/package/WaitEval.php';
require_once dirname(__FILE__) . '/../includes/input/package/EducationReport.php';
require_once dirname(__FILE__) . '/../includes/input/package/SearchLastEval.php';
require_once dirname(__FILE__) . '/../includes/input/lookuptable/ContractorName.php';
require_once dirname(__FILE__) . '/../includes/input/lookuptable/Lab.php';
session_start();
if(array_key_exists('action',$_POST)){
 if($_POST['action']=='can_be_deleted'){
  if(array_key_exists('eval_id',$_POST)){
    $eval = new Evaluation();
    $eval->init(array('id'=>$_POST['eval_id']));
    $bool = !$eval->isInitOrLastToOthers();
    $response = array("can_be_deleted"=>$bool,"eval_id"=>$_POST['eval_id']);
    if($bool){
     $response['msg'] = "Are you sure to delete this evaluation?";
    }else{
     $response['msg'] = "This evaluation can not be deleted, since other evaluations have been entered require data from this one.";
    }
    $json = json_encode($response);
    echo $json;
    exit;
  }
 }
}
foreach ($_POST as $paraName => $paraVal) {
    if (strstr($paraName, 'delete_lab')) {
        $pieces = explode(":", $paraName);
        $lab_id = $pieces[1];
        $lab = $_SESSION['MILLabs'][$lab_id];
        $result = $lab->delete();
        if ($result) {
            $_SESSION['tab'] = LAB_TAB;
            header("Location:workspace.php?msg=MIL Lab $lab_id has been deleted.");
        } else {
            echo $result;
        }
    } else if (strstr($paraName, 'delete_contractor')) {
        $pieces = explode(":", $paraName);
        $contractor_id = $pieces[1];
        $contractor = $_SESSION['contractors'][$contractor_id];
        $result = $contractor->delete();
        if ($result) {
            $_SESSION['tab'] = CONTRACTOR_TAB;
            header("Location:workspace.php?msg=Contractor $contractor_id has been deleted.");
        } else {
            echo $result;
        }
    } else if (strstr($paraName, 'delete_contract')) {
        $pieces = explode(":", $paraName);
        $contract_id = $pieces[1];
        $contract = $_SESSION['contracts'][$contract_id];
        $result = $contract->delete();
        if ($result) {
            $_SESSION['tab'] = CONTRACT_TAB;
            header("Location:workspace.php?msg=Contract $contract_id has been deleted.");
        } else {
            echo $result;
        }

        exit;
    } else if (strstr($paraName, 'delete_package')) {

        $pieces = explode(":", $paraName);
        $pack_id = $pieces[1];
        $package = $_SESSION['packages'][$pack_id];
        $result = $package->delete();
        if ($result) {
            $_SESSION['tab'] = EVALUATION_TAB;
            header("Location:workspace.php?msg=package $pack_id has been deleted.");
        } else {
            echo $result;
        }

        exit;
    } else if (strstr($paraName, 'delete_eval')) {
        $pieces = explode(":", $paraName);
        $eval_id = $pieces[1];
        $eval = new Evaluation();
        $eval->init(array('id'=>$eval_id));
        $bool = !$eval->isInitOrLastToOthers();
        if($bool){
         header("Location: evaluation/package_content_list.php?msg=Evaluation $eval_id has been deleted.");
        }else{
         header("Location: evaluation/package_content_list.php?msg=Evaluation $eval_id can not be deleted. ");
        }
        $result = $eval->delete();
        if ($result) {
            $_SESSION['tab'] = EVALUATION_TAB;
            header("Location: evaluation/package_content_list.php?msg=Evaluation $eval_id has been deleted.");
        } else {
            echo $result;
        }

        exit;
    }
}
?>
