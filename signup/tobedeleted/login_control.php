<?php
require_once '../includes/mil_init.php';
if(array_key_exists('username',$_POST)&&$_POST['username']!=""){
	if(array_key_exists('password',$_POST)&&$_POST['password']!=""){
		$password = md5($_POST['password']);
		$memArr = MIL::doQuery('select * from freeze_alert_system_users where username="'.$_POST['username'].'" and password="'.$password.'"',MYSQL_ASSOC);
		if($memArr==false){
			header('Location: login.php?err='.MEMBERNOTEXIST);
			exit;
		}else{
			//to do add member's attribute to session
			$_SESSION = array();
			session_start();
			$sid = session_id();

			foreach($memArr as $key => $valArr){
				if(array_key_exists('role',$valArr)){
					$_SESSION['role']= $valArr['role'];
				}
				if(array_key_exists('labs_id',$valArr)){
					$labs = explode(';',$valArr['labs_id']);
					$size = count($labs);
					if($labs[$size-1]==""){
						unset($labs[$size-1]);
					}
					
					$labs_arr = Utility::getLabsInfo($labs);
					$_SESSION['labs_arr'] = $labs_arr;
					$_SESSION['labs_id_arr'] = $labs;
				}
				if(array_key_exists('fiscal_standard',$valArr)){
					$_SESSION['fiscal_standard'] = $valArr['fiscal_standard'];
				}
			}
			
			//header('Location: workspace/workspace.php?sid='.$sid);
			header('Location: prefs.php');
			exit;
		}
	}else{
		header('Location: login.php?err='.PASSWORDREQUIRED);
		exit;
	}
}else{
	header('Location: login.php?err='.USERNAMEREQUIRED);
	exit;
}

