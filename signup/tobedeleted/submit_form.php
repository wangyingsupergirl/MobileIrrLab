<?php
//Below one only for signup_contractor.php
require_once '../includes/mil_init.php';
require_once 'constant.php';
define('UPDATELAB', 1);

define('ADDCOUNTY', 2);
define('DELCOUNTY', 3);

// Below 2 only for  signup_contractor_checkboxbutton.php
define('ADDLAB',4);
define('DELLAB',5);

define('SUBMEMAPP',6);
define('BACK',7);





$operation = NULL; 
function setRole(&$post){
if(array_key_exists('role',$post)&& $post['role']!=""){
$role = $post['role'];
 if($role == CONTRACTOR_EMPLOYEE){
   if(array_key_exists('employee_type',$post)){
   $role = $post['employee_type'];
   $post['role'] = $role;
 }
}
}
}
function setLabsSession($post,$exists_counties){
$selected_labs = array();
foreach($post as $paraName => $paraVal){
		if(strstr($paraName,'lab_id:')){
			$pieces = explode(":",$paraName);
			$lab_id = $pieces[1];
			$selected_labs[$lab_id] = 1;
			if($exists_counties){
				//if delete mil labs, corresponding mil counties added to that lab will be deleted from $_SESSION too.
				$labs_counties = $_SESSION['labs_counties'];
				if(array_key_exists($lab_id,$labs_counties)){
					unset($_SESSION['labs_counties'][$lab_id]);
				}
			}
		}
}
$_SESSION['labs_id'] = $selected_labs;
}

function updateSessionPara($_POST){
	foreach($_POST as $paraName => $paraVal){
	$_SESSION[$paraName] = $paraVal;
	}
}

/* Two kind of sign up page for contractor*/
if(array_key_exists("mode",$_POST)){
	$mode = $_POST['mode'];
	if($mode==0){
		define('SIGNUPPAGE','signup_contractor_new.php');
	}else if($mode==1){
		define('SIGNUPPAGE','signup_contractor_checkboxbutton.php');
	}
}else{
	define('SIGNUPPAGE','signup_employee.php');
	
}

/*
 * According submit button name, get the type of operations. 
 */
foreach($_POST as $paraName => $paraVal){
	if($paraName=='update_labs'){
		$operation = UPDATELAB;
		break;
	}else if(strstr($paraName,'add_county')){
		$operation = ADDCOUNTY;
		$pieces = explode(":",$paraName);
		$lab_id = $pieces[1];
		break;
	}else if(strstr($paraName,'del_county')){
		$operation = DELCOUNTY;
		$pieces = explode(":",$paraName);
		$lab_id = $pieces[1];
		$county_id = $pieces[2];
		$pieces = explode("_",$county_id);//remove x and y
		$county_id = $pieces[0];
		break;
	}else if(strstr($paraName,'add_mil_lab')){
		$operation = ADDLAB;
		$pieces = explode(':',$paraName);
		$lab_id = $pieces[1];
		$pieces = explode("_",$lab_id);//remove x and y
		$lab_id = $pieces[0];
		break;
	}else if(strstr($paraName,'del_mil_lab')){
		$operation = DELLAB;
		$pieces = explode(':',$paraName);
		$lab_id = $pieces[1];
		$pieces = explode("_",$lab_id);//remove x and y
		$lab_id = $pieces[0];
		break;
	}else if($paraName == 'sub_mem_app'){
		$operation = SUBMEMAPP;
		break;
	}else if($paraName =='back_login'){
		header('Location: ../login.php');
		exit;
	}
}


session_start();

if(array_key_exists('labs_counties',$_SESSION)){
	$exists_counties = true;
}else{
	$exists_counties = false;
}

/*
 * Below are the details of operation.
 */
 
if($operation==UPDATELAB){ // include add and delete
	foreach($_POST as $paraName => $paraVal){
		if(strstr($paraName,'lab_id:')){
			$pieces = explode(":",$paraName);
			$lab_id = $pieces[1];
			$selected_labs[$lab_id] = 1;
			if($exists_counties){
				//if delete mil labs, corresponding mil counties added to that lab will be deleted from $_SESSION too.
				$labs_counties = $_SESSION['labs_counties'];
				if(array_key_exists($lab_id,$labs_counties)){
					unset($_SESSION['labs_counties'][$lab_id]);
				}
			}
		}
	}
	$_SESSION['labs_id'] = $selected_labs;
	header('Location: ./'.SIGNUPPAGE);
	

}else if($operation==ADDCOUNTY){
	$county = array();
	foreach($_POST as $paraName => $paraVal){
		if(strstr($paraName,'county_list_'.$lab_id)){
			$pieces = explode(":",$paraVal);
			$county_id = $pieces[0];
			$county_name = $pieces[1];
			$county[$county_id] = $county_name;
			$query = 'insert into lab_county values('.$lab_id.','.$county_id.')';
			try{
			MIL::doQuery( $query ,MYSQL_ASSOC);
			}
      catch(Exception $e){
        //echo $e->getMessage();
      }	
			break;
		}
	}
	/*
	 * The structure of $_SESSION['labs_counties']
	 * array(26=>array(1=>"Marson",2=>" "))
	*/
	if(array_key_exists('labs_counties',$_SESSION)){
		$labs_counties = $_SESSION['labs_counties'];
		if(array_key_exists($lab_id,$labs_counties)){
			$lab_counties = $labs_counties[$lab_id];
			if(array_key_exists($county_id,$lab_counties)){
				//Duplicated County, should end error message later
			}else{
				$lab_counties[$county_id] = $county_name;
			}
			$_SESSION['labs_counties'][$lab_id] =$lab_counties;;
		}else{
			$_SESSION['labs_counties'][$lab_id] = $county;
		}
	}else{
		$_SESSION['labs_counties'] = array($lab_id => $county);
	}
	header('Location: ./'.SIGNUPPAGE);
	

}else if($operation == DELCOUNTY){
	
	unset($_SESSION['labs_counties'][$lab_id][$county_id]);
	$query = 'delete from lab_county where mil_id = '.$lab_id.' and county_id = '.$county_id;
	try{
	MIL::doQuery( $query ,MYSQL_ASSOC);
	}
  catch(Exception $e){
    //echo $e->getMessage();
  }	
	header('Location: ./'.SIGNUPPAGE);
	

}else if($operation == ADDLAB){
	
	$_SESSION['labs_id'][$lab_id] = 1;
	header('Location: ./'.SIGNUPPAGE);
	
	
}else if($operation == DELLAB){
	
	unset($_SESSION['labs_id'][$lab_id]);
	if($exists_counties){
		$labs_counties = $_SESSION['labs_counties'];
		if(array_key_exists($lab_id,$labs_counties)){
			unset($_SESSION['labs_counties'][$lab_id]);
		}
	}
	header('Location: ./'.SIGNUPPAGE);
	
	
}else if($operation == SUBMEMAPP){
	//require_once '../includes/input/member_info.php';
	require_once '../includes/input/member.php';
	updateSessionPara($_POST);
	try{
		if(array_key_exists('role',$_POST)){
			$role = $_POST['role'];
			setRole($_POST);
			if($role == CONTRACTOR_EMPLOYEE){
				setLabsSession($_POST,$exists_counties);
				$mem = new Employee();
				$mem->registerInit($_POST,$_SESSION);
			}else if($role == CONTRACTOR ){
				$mem = new Contractor();
				$mem->registerInit($_POST,$_SESSION);
			}
		}else{
			echo 'error in submit_form 200';
		}
		//$mem = new contractor($_POST,$_SESSION);// may throw "" exception
		if($mem->exists('username',$mem->getProperty('username'))!=false){
			$error = SIGNUPPAGE.'?err=the username is not available';
	        header('Location:./'.$error);
	        exit;
		}
		$sql = $mem->buildInsertSql();
		$result = MIL::doQuery($sql,MIL_DB_INSERT); // may throw db exception
		
	}catch(Exception $e){
	$error = SIGNUPPAGE.'?err='.$e->getMessage();
	//echo $error;
	
	header('Location:./'.$error);
	exit;
	}
	header('Location:./signup_suc.php');
}
updateSessionPara($_POST);// for contractor, keep previous entry before submit
