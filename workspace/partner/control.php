<?php
require_once dirname(__FILE__) . '/../../includes/constant.php';
require_once dirname(__FILE__) . '/../../includes/utility.php';
session_start();
$_SESSION['tab'] = CONTRACTOR_TAB;
$response = array();
if(array_key_exists('add_partner',$_POST)){
    $partner = new PartnerName($_POST);
    $result =  $partner->insertToDb();
    
    if($result===false){
     $response['result'] = false;
     $response['data'] = "Save new partner name failed";
    }else{
     $partners = Utility::getAllPartner();
     if($partners){
      $response['result'] = true;
      $data =array();
       foreach($partners as $partner){
       array_push($data,$partner->getProperties());
      }
      $response['data'] = $data;
     }else{
      $response['result'] = false;
      $response['data'] = "Retrieve all the partner failed";
     }
    }
   
}else if(array_key_exists('save_partner',$_POST)){
    $partner = new PartnerName($_POST);
    $result =$partner->updateAllProperties();
   
    if($result===false){
     $response['result'] = false;
     $response['data'] = "Save new partner name failed";
    }else{
     $partners = Utility::getAllPartner();
     if($partners){
      $response['result'] = true;
      $data =array();
      foreach($partners as $partner){
       array_push($data,$partner->getProperties());
      }
      $response['data'] = $data;
     
     }else{
      $response['result'] = false;
      $response['data'] = "Retrieve all the partner failed";
     }
    }
    
}else{
 $response = array();
 $response['result'] = false;
 $response['data'] = "invalid action";
}
echo json_encode($response);
    exit;
?>
