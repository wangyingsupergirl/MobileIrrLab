<?php
require_once dirname(__FILE__) . '/../../includes/mil_init.php';
require_once dirname(__FILE__) . '/../../includes/utility.php'; //required constant.php in utility.php
session_start();
$member = false;
if(array_key_exists('memberReviewed',$_SESSION)){
    $member = $_SESSION['memberReviewed'];
}else{
    echo 'Error no memberReviewed para in session';
    exit;
}

?>

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<title>State of Florida Mobile Irrigation Lab (MIL) Program - Membership Review</title>
<link rel="stylesheet" type="text/css" href="../../styles/milStylesheet.css" />
<script src="../../js/jquery-validate/lib/jquery.js" type="text/javascript"></script>
<script src="../../js/multiple_choices_list.js" type="text/javascript"></script>

</head>

<body id='login'>
    <div id="largeWrapper">
        <div id="header">
        <h1>State of Florida</h1>
        <p>Mobile Irrigation Laboratory Program</p>
    </div>

<div id="largeContentWrap">
<h2>MIL Contractor Membership Review</h2>
<div class="errMsg">
<?php
    if(array_key_exists('err',$_GET)){
        echo $_GET['err'];
    }
?>
</div>

<form action="./control.php" method="post" name="Login">
<fieldset>
<table>
    <tr>
        <td>*Contractor Name: &nbsp;</td>
        <td>
        <select id="contractor_name" name="contractor_name" disabled>
        <option value="0" selected>Choose one</option>
        <?php
            $table_name = 'contractor';
            Utility::printOptions($table_name,null);
        ?>
        </select>
        </td>
    </tr>
    <tr><td><input type="hidden" name="role" value="<?php echo CONTRACTOR_ROLE ?>"/></td></tr>
</table>
</fieldset>

<fieldset id="responsibleLabs">
    <h4>Responsible for MIL Lab(s):</h4>
    <?php 
        $lab_county = $member->getProperty('lab_county');
        $labs = Utility::getLookupTable('mil_lab',null);
        $counties = Utility::getLookupTable('fl_county',null);
        foreach($lab_county as $lab_id => $county_str){
            $lab_name = $labs[$lab_id]->getDisplayName();
            echo "<div>$lab_name:";
            if(trim($county_str)!=''){
                 $county_arr = explode(',',$county_str);
                foreach($county_arr as $county_id){
                   $county_id = trim($county_id);
                   if(array_key_exists($county_id,$counties)){
                        $county= $counties[$county_id];
                        $county_name = $county->getProperty('name');
                        echo "$county_name&nbsp;";
                    }
                }
            }else{
                echo "No Counties are added.";
            }
            echo "</div>";

        }
    ?>
</fieldset>

<fieldset>
    <h4>Contact Information</h4>
    <table border="0" cellpadding="0" cellspacing="0" class="mainContactForm">
        <tr>
        <td>*Business Address:</td>
        <td><input id="busi_addr" name="busi_addr" type="text" <?php if($member) echo "value='{$member->getProperty('busi_addr')}'";?> size="32" maxlength="32" /></td>
        <td>*Remittance Address:</td>
        <td><input id="remit_addr" name="remit_addr" <?php if($member) echo "value='{$member->getProperty('remit_addr')}'";?>  type="text" value="" size="32" maxlength="32" /></td>
        </tr>

        <tr>
        <td>* City:</td>
        <td><input id="busi_city" name="busi_city" type="text" <?php if($member) echo "value='{$member->getProperty('busi_city')}'";?> size="32" maxlength="32"/></td>
        <td>* City:</td>
        <td><input id="remit_city" name="remit_city" type="text" <?php if($member) echo "value='{$member->getProperty('remit_city')}'";?> size="32" maxlength="32"/></td>
        </tr>
        <tr>
        <td>* State:</td>
        <td><input id="busi_state" name="busi_state" type="text" <?php if($member) echo "value='{$member->getProperty('busi_state')}'";?> size="32" maxlength="32"/></td>
        <td>* State:</td>
        <td><input id="remit_state" name="remit_state" type="text" <?php if($member) echo "value='{$member->getProperty('remit_state')}'";?> size="32" maxlength="32"/></td>
        </tr>
        <tr>
        <td>* Zip:</td>
        <td><input id="busi_zip" name="busi_zip" type="text" <?php if($member) echo "value='{$member->getProperty('busi_zip')}'";?> size="5" maxlength="5"/></td>
        <td>* Zip:</td>
        <td><input id="remit_zip" name="remit_zip" type="text" <?php if($member) echo "value='{$member->getProperty('remit_zip')}'";?> size="5" maxlength="5"/></td>
        </tr>
       
        <tr>
        <td>&nbsp;</td>
        <td colspan="3">&nbsp;</td>
        </tr>
        <?php require_once 'form_component/mem_basic_info.php';?>
       
        <tr>
        <td></td>
        <td colspan="3"></td>
        </tr>
    </table>
</fieldset>
<fieldset>
    <table border="0" cellpadding="0" cellspacing="0">
        <tr><td colspan='2'></td></tr>
        <tr>
        <td align="right">Comment Box: </td>
        <td>
        <textarea id="admin_comments" name="admin_comments" COLS=80 ROWS=2 maxlength="500"></textarea>
        <br />Maximum characters: 500<br />
        You have <input type="text" id="countdown" size="3" value="500"/> characters left.
        </td>
        </tr>
        <tr>
            <td></td>
            <td><input class="button" name="approve_membership" value="Approve" type="submit"/>
            <input class="button" name="disapprove_membership" value="Disapprove" type="submit"/>
            <input class="button" type="submit" name="back_to_member_list" value="Cancel"/>
            </td>
        </tr>
    </table>
</fieldset>

</form>

</div>
<span class="clearing"></span>
<div id="sponsorLogos">
<p>Sponsored by the State of FLorida, The St. Johns Water Management District, and The Florida Department of Agriculture</p>
</div>

</div>

</body>
</html>
