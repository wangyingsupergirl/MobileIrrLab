<?php

/*
 * @File control_login.php : the controller of login module.
 * 
 */
require_once dirname(__FILE__) . '/includes/utility.php';
require_once dirname(__FILE__) . '/includes/KLogger.php';
// Initialize the session.
// If you are using session_name("something"), don't forget it now!
session_start();
// Unset all of the session variables.
$_SESSION = array();
// Finally, destroy the session.
session_destroy();
session_start();
$_SESSION = array();
$sid = session_id();

if (array_key_exists('logout', $_GET) && $_GET['logout'] == 1) {
 session_unset();
 session_destroy();
 header('Location: login.php');
 exit;
} else if (array_key_exists('login', $_POST)) {

 if (array_key_exists('username', $_POST)) {
  if ($_POST['username'] == '') {
   header('Location: login.php?err=Sorry, user name can not be blank.');
   exit;
  }
 }
 if (array_key_exists('password', $_POST)) {
  if ($_POST['password'] == '') {
   header('Location: login.php?err=Sorry, password can not be blank.');
   exit;
  }
 }
 $member = new Member();
 $member = $member->verifyUsernamePwd($_POST['username'], $_POST['password']);
 if ($member) {
  $_SESSION['MemberServed'] = $member;
  $status = $member->getProperty('status');
  $role = $member->getProperty('role');
  if ($status == 'disapproved') {
   if ($role == CONTRACTOR_ROLE) {
    header("Location: signup/signup_contractor.php");
   } else if ($role == EMPLOYEE_ROLE) {
    header("Location: signup/signup_employee.php");
   } else if ($role == ADMIN_ROLE) {
    header('Location: login.php?err=Sorry, your membership as admin has been disapproved.');
   } else if($role==PARTNER_ROLE){
     header("Location: signup/signup_partner.php");
   }else if($role==GUEST_ROLE){
      header("Location: signup/signup_guest.php");
   }else{
    header('Location: login.php?err=Sorry, your role does not exists in the system.');
   }
  } else if ($status == 'submitted') {
   header('Location: login.php?err=Sorry, your membership is still under review.');
  } else if ($status == 'approved') {
   //var_dump($_SESSION['MemberServed']);
   header("Location: workspace/workspace.php");
  } else {
   header('Location: login.php?err=Sorry, your membership status is not valid.');
  }
 } else {
  header('Location: login.php?err=Sorry, user name or password is not correct');
 }
 exit;
}
