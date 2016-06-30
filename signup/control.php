<?php
//Controller for Registration
//1. Select role
//2. Submit Membership
require_once '../includes/mil.php';
require_once '../includes/constant.php';
require_once'../includes/input/member/MemberPackage.php';
require_once '../includes/utility/Email.php';

if(array_key_exists('submit_role_selection',$_POST)){
    if(array_key_exists('role', $_POST)){
         $role = $_POST['role'];
         if($role == CONTRACTOR_ROLE){//Contractor
            header('Location: ./signup_contractor.php');
        }else if($role == EMPLOYEE_ROLE){//Employee
                header('Location: ./signup_employee.php');
        }else if($role == PARTNER_ROLE){ //Parnter
                header('Location: ./signup_partner.php');
        }else if($role==GUEST_ROLE){
                header('Location: ./signup_guest.php');
        }
    }else{
        exit;
    }
 }else if(array_key_exists('back_login',$_POST)){
     header('Location: ../login.php');
     exit;
 }else if(array_key_exists('is_valid_password',$_POST)){
     $member = new Member();
     $member->setProperty('password', $_POST['password']);
     $msg = $member->verifyProperty('password');
     if($msg===true){
         echo '';
     }else{
         echo $msg;
     }
     exit;
 }else if(array_key_exists('is_username_available',$_POST)){
     $member = new Member();
     $member->setProperty('username',$_POST['username'] );
     $bool = $member->exists();
     if($bool){
         echo 'This username is not available any more, try another one';
     }else{
         echo 'Available username';
     }
 }else if(array_key_exists('submit_membership_application',$_POST)){
    if(array_key_exists('role',$_POST)){
        $role = $_POST['role'];
    }
    $member = Member::createMember($role);
    $member->init($_POST);
    //If sign up doesn't sucess, then jump back to signup page($back_page).
    $back_page = '';
    if($role == CONTRACTOR_ROLE){
        //Parse the string to feed 'lab_county' property
        $labs_counties = array();
        foreach ($_POST as $paraName => $paraVal) {
            if (strstr($paraName, 'county')) {
                $pieces = explode(":", $paraName);
                $lab_id= $pieces[1];
                $counties = $_POST[$paraName];
                $labs_counties[$lab_id] = $counties;
            }
        }
        $member->setProperty('lab_county', $labs_counties);
        $back_page = 'signup_contractor.php';
     }else if($role==EMPLOYEE_ROLE){
        $labs_id = '';
        foreach ($_POST as $paraName => $paraVal) {
            if (strstr($paraName, 'lab_id')) {
                $pieces = explode(":", $paraName);
                $lab_id = $pieces[1];
                $labs_id .= ','.$lab_id;
            }
        }
        //remove the ; at the beginning of the string
        $labs_id = substr($labs_id, 1);
        $member->setProperty('labs_id', $labs_id);
        $back_page = 'signup_employee.php';
     }else if($role==GUEST_ROLE){
        $labs_id = '';
        foreach ($_POST as $paraName => $paraVal) {
            if (strstr($paraName, 'lab_id')) {
                $pieces = explode(":", $paraName);
                $lab_id = $pieces[1];
                $labs_id .= ','.$lab_id;
            }
        }
        //remove the ; at the beginning of the string
        $labs_id = substr($labs_id, 1);
        $member->setProperty('labs_id', $labs_id);
        $back_page = 'signup_guest.php';
     }else if($role==PARTNER_ROLE){
       $back_page = 'signup_partner.php';
     }
     
    
    $rtn = $member->exists();
    if($rtn){
        
         $return_member = $rtn;
         if($return_member->getProperty('status')=='disapproved'){
           $now = date('Y-m-d H:i:s');
           $member->setProperty('apply_time',$now);
           $member->setProperty('status','submitted');
           //use previous user id to update
           $id = $return_member->getProperty('mem_id');
           $member->setProperty('mem_id',$id);
            $result = $member->updateAllProperties();
            if($result){
                 $email = Email::getInstance();
                   $rtn = $email->send(ADMIN_EMAIL, 'New membership application', 'An membership application is received. Login into MIL to review!'); 
                  header('Location:./signup_suc.php');
            }else{
                header("Location:./$back_page?err=Member record can not be insert successfully");
            }
         }else{
             //MemberServed stores the member's infomation who is currently served by system.
             session_start();   
             $_SESSION['MemberServed'] = $member;
             header("Location:./$back_page?err=Username is not available");
         }
    }else{
        $now = date('Y-m-d H:i:s');
        $member->setProperty('apply_time',$now);
        $member->setProperty('status','submitted');
        $result = $member->insertToDb();
        if($result){
             $email = Email::getInstance();
             $rtn = $email->send(ADMIN_EMAIL, 'New membership application', 'As subject'); 
            header('Location:./signup_suc.php');
        }else{
            header("Location:./$back_page?err=Member record can not be insert successfully");
        }
       
    }
    
    exit;
 }