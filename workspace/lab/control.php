<?php
require_once dirname(__FILE__) . '/../../includes/constant.php';
require_once dirname(__FILE__) . '/../../includes/utility.php';
session_start();
$_SESSION['tab'] = LAB_TAB;

if(array_key_exists('save_lab',$_POST)){
    $lab = new Lab($_POST);
    $lab->updateProperties($_POST);
    header("Location:../workspace.php");
    exit;
}else if(array_key_exists('add_lab',$_POST)){
    $lab = new Lab($_POST);
    $lab->insertToDb();
    Utility::addNewLabToAdmin($lab->getProperty("mil_id"));
    header("Location:../workspace.php");
    exit;
}else if(array_key_exists('add_new_lab',$_POST)){
   $_SESSION['lab'] = false;
   header("Location: add_new_lab.php");
   exit;
}else if(array_key_exists('cancel',$_POST)){
   header("Location:../workspace.php");
   exit;
}else if(array_key_exists('get_counties',$_POST)){
    header('Content-type: application/json');   
    if(array_key_exists('mil_id', $_POST)){ 
           $mil_id = $_POST['mil_id'];
           $countyLab = new CountyLab($mil_id);
           $countyList = $countyLab->getCounties();
           echo json_encode($countyList);
       }else{
           echo json_encode(array());
       }
}
foreach($_POST as $paraName=>$paraVal){
    if(strstr($paraName,'edit_lab')){
        $pieces = explode(":", $paraName);
        $lab_id = $pieces[1];
        $lab = $_SESSION['MILLabs'][$lab_id];
        $_SESSION['lab'] = $lab;
        header('Location: edit_lab.php');
        exit;
    }

}
?>
