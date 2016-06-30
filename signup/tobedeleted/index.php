<?php

////process array $_POST
//$lab_ids = array();
//session_start();
//$pre_lab_county = null;
//$post_lab_county = array();
//
//if(array_key_exists('add_county',$_POST)){
//	
//	
//	if(array_key_exists('lab_county',$_SESSION)){
//	$pre_lab_county = $_SESSION['lab_county'];
//	}
//	
//	foreach($_POST as $key => $value){
//		if(strstr($key,'county_list_')){
//			$pieces = explode("_",$key);
//			$lab_id = $pieces[2];
//		
//			$county_id = $value;
//			if($county_id==0)
//			continue;
//			if($pre_lab_county==null){
//				$post_lab_county[$lab_id] = $county_id;
//			}else{
//				if(array_key_exists($lab_id,$pre_lab_county)){
//				$county_list = $pre_lab_county[$lab_id].';'.$county_id;
//				$post_lab_county[$lab_id]=$county_list;
//				}else{
//				$post_lab_county[$lab_id] = $county_id;
//				}
//			}			
//		}
//	}
//	$_SESSION['lab_county'] = $post_lab_county;
//	header('Location: ./signup_contractor.php');
//	exit;
//}

if(isset($_POST['role'])){
	$role=$_POST['role'];
	
}else{
 	header('Location: ./error.php');
}
	

if(isset($role)){
	$role=(int)$role;
	if($role==1){//Contractor
		header('Location: ./signup_contractor_new.php');
	}else if($role==2){//Employee
		header('Location: ./signup_employee.php');
	}else if($role==3){ //Parnter
		header('Location: ./signup_partner.php');
	}else if($role==4){
		header('Location: ./signup_guest.php');
	}
}