
<?php
//Controller for MIL Membership Management
require_once dirname(__FILE__) . '/../../includes/constant.php';
require_once dirname(__FILE__) . '/../../includes/input/member/MemberPackage.php';
require_once  dirname(__FILE__) .'/../../includes/utility/Email.php';
session_start();
$_SESSION['tab'] = MEMBERMENAGEMENT_TAB;

if(array_key_exists('approve_membership',$_POST)){
  $member = $_SESSION['memberReviewed'];
  $rtn = $member->approve($_POST);
  $email = Email::getInstance();
  $email->send($member->getProperty('username'), "Your MIL membership has been approved", "Login to Access your workspace"); 
  $_SESSION['members'][$memberID] = $member;
  header("Location:../workspace.php?msg=$rtn");
}else if(array_key_exists('disapprove_membership',$_POST)){
  $member = $_SESSION['memberReviewed'];
  $rtn = $member->disapprove($_POST);
  $email = Email::getInstance();
  $email->send($member->getProperty('username'), "Your MIL membership has been disapproved", "{$member->getProperty('username')} can login to system to review the result"); 
  header("Location:../workspace.php?msg=$rtn");
}else if(array_key_exists('save_partner_report_list',$_POST)){
    $member = $_SESSION['memberReviewed']; 
    $report_list_str = "";
     foreach ($_POST as $paraName => $paraVal) {
            if (strstr($paraName, 'report_id')) {
                $pieces = explode(":", $paraName);
                $report_id = $pieces[1];
                $report_list_str .= ",".$report_id;
            }
        }
        $report_list_str = substr($report_list_str, 1);
        //$member->setProperty('reports_id', $report_list_str);
        $result = $member->updateProperty('reports_id', $report_list_str);
        if($result){
            header('Location:update_report_list.php?msg=Updated sucessfully');
        }else{
             header('Location:update_report_list.php?msg=Not updated');
        }
        return;
}else if(array_key_exists('back_to_member_list',$_POST)){
   header('Location:../workspace.php');
}

foreach ($_POST as $paraName => $paraVal) {
    if (strstr($paraName, 'review_contractor')) {
        $pieces = explode(":", $paraName);
        $memberID = $pieces[1];
        $_SESSION['memberReviewed'] = $_SESSION['members'][$memberID];
        header("Location: contractor_review.php");
        exit;
    }else if(strstr($paraName, 'review_employee')){
        $pieces = explode(":", $paraName);
        $memberID = $pieces[1];
        $_SESSION['memberReviewed'] = $_SESSION['members'][$memberID];
        header("Location: employee_review.php");

    }else if(strstr($paraName,'review_administrator')){
       echo 'no page yet';
       exit;
    }else if(strstr($paraName,'review_partner')){
       $pieces = explode(":", $paraName);
        $memberID = $pieces[1];
        $_SESSION['memberReviewed'] = $_SESSION['members'][$memberID];
        header("Location: partner_review.php");
    }else if(strstr($paraName,'review_guest')){
       $pieces = explode(":", $paraName);
        $memberID = $pieces[1];
        $_SESSION['memberReviewed'] = $_SESSION['members'][$memberID];
        header("Location: guest_review.php");
    }else if(strstr($paraName,'report_partner')){
        $pieces = explode(":", $paraName);
        $memberID = $pieces[1];
        $_SESSION['memberReviewed'] = $_SESSION['members'][$memberID];
         header("Location: update_report_list.php");
     }else{
        $dir = dirname(__FILE__);
        echo "no response page at $dir/control.php";
        exit;
    }
}
?>
