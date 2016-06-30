<?php
require_once dirname(__FILE__) . '/../../includes/input/member/Administrator.php';
require_once  dirname(__FILE__) .'/../../includes/input/member/Contractor.php';
require_once dirname(__FILE__) . '/../../includes/input/member/Employee.php';
require_once  dirname(__FILE__) . '/../../includes/input/member/Partner.php';
require_once  dirname(__FILE__) . '/../../includes/input/member/Guest.php';
session_start();
$memberServed = $_SESSION['MemberServed'];
if(array_key_exists('save_profile',$_POST)){
    /*unset($_POST['save_profile']);
    if(array_key_exists('same',$_POST)){
     unset($_POST['same']);
    }*/
    $bool = $memberServed->updateProperties($_POST);
    $_SESSION['MemberServed'] = $memberServed;
    header("Location:../workspace.php?accountUpdate=$bool");
}else if(array_key_exists('save_username', $_POST)){
    if(array_key_exists('username',$_POST)){
        $new_username = $_POST['username'];
        $rtn = $memberServed->changeUsername($new_username);
        if($rtn===true){
            echo 'Username is updated successfully';
            $_SESSION['MemberServed'] = $memberServed;
            return;
        }else{
            echo $rtn;
            return;
        }
    }else{
        echo 'user name is required';
        return;
    }
}else if(array_key_exists('sync_username', $_POST)){
    // update displayed username to be the latest one.
    session_start();
    echo $_SESSION['MemberServed']->getProperty('username');
    return;
    
}else if(array_key_exists('save_password',$_POST)){
    if(array_key_exists('new_password',$_POST)){
        $new_pwd = $_POST['new_password'];
    }else{
        return 'new_password is missing';
    }
    if(array_key_exists('retyped_new_password',$_POST)){
        $retyped_new_pwd = $_POST['retyped_new_password'];
    }else{
        return 'retyped_new_password is missing';
    }
    if($retyped_new_pwd!=$new_pwd){
        return  'You must enter the same new password twice in order to comfirm it.';
    }
    if(array_key_exists('old_password',$_POST)){
        $typed_old_pwd = $_POST['old_password'];
    }else{
        return 'old password is missing';
    }
    $rtn = $memberServed->changePwd($typed_old_pwd, $new_pwd);
    if($rtn===true){
        echo 'Password is updated successfully';
        $_SESSION['MemberServed'] = $memberServed;
        return;
    }else{
        echo $rtn;
        return;
    }
    
 }
 

?>