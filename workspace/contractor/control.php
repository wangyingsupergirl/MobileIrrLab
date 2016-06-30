<?php
require_once dirname(__FILE__) . '/../../includes/constant.php';
require_once dirname(__FILE__) . '/../../includes/utility.php';
session_start();
$_SESSION['tab'] = CONTRACTOR_TAB;

if(array_key_exists('save_contractor',$_POST)){
    
    $contractor = new ContractorName($_POST);
    $contractor->updateAllProperties();
    header("Location:../workspace.php");
    exit;
}else if(array_key_exists('add_contractor',$_POST)){
    $contractor = new ContractorName($_POST);
    $contractor->insertToDb();
    header("Location:../workspace.php");
    exit;
}else if(array_key_exists('add_new_contractor',$_POST)){
   $_SESSION['contractor'] = false;
   header("Location: add_new_contractor.php");
   exit;
}else if(array_key_exists('cancel',$_POST)){
   header("Location:../workspace.php");
   exit;
}
foreach($_POST as $paraName=>$paraVal){
    if(strstr($paraName,'edit_contractor')){
        $pieces = explode(":", $paraName);
        $contractor_id = $pieces[1];
        $contractor = $_SESSION['contractors'][$contractor_id];
        $_SESSION['contractor'] = $contractor;
        header('Location: add_new_contractor.php');
        exit;
    }
 
}
?>
