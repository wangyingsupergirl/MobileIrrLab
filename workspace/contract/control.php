<?php
require_once dirname(__FILE__) . '/../../includes/constant.php';
require_once dirname(__FILE__) . '/../../includes/input/package/Contract.php';
session_start();
$_SESSION['tab'] = CONTRACT_TAB;

if(array_key_exists('save_contract',$_POST)){
    
    $contract = new Contract($_POST);
    $contract->updateAllProperties();
    header("Location:../workspace.php");
    exit;
}else if(array_key_exists('add_contract',$_POST)){
    $contract = new Contract($_POST);
    $contract->insertToDb();
    header("Location:../workspace.php");
}else if(array_key_exists('add_new_contract',$_POST)){
   $_SESSION['contract'] = false;
   header("Location: add_new_contract.php");
}else if(array_key_exists('cancel',$_POST)){
   header("Location:../workspace.php");
   exit;
}
foreach($_POST as $paraName=>$paraVal){
    if(strstr($paraName,'edit_contract')){
        $pieces = explode(":", $paraName);
        $contract_id = $pieces[1];
        $contract = $_SESSION['contracts'][$contract_id];
        $_SESSION['contract'] = $contract;
        header('Location: add_new_contract.php');
        exit;
    }
 
}
?>
