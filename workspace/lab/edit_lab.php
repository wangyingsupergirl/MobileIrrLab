<?php
/*
 * Users can create a new package in this page
 * Different forms for 2 kinds of billing cycle MIL Labs
 * Form Validation on both client and server side.
 */
require_once dirname(__FILE__) . '/../../includes/utility.php';
require_once dirname(__FILE__) . '/../../includes/constant.php';
session_start();
$labs_arr = $_SESSION['MILLabs'];
/*Get all the lab id concated by "," of which the billing cycle is 1 month*/
$labs_1month = '';
foreach ($labs_arr as $key => $val) {
    if ($val->getProperty('billing_cycle') == 1) {
        $labs_1month .= ',' . $val->getProperty('mil_id');
    }
}
$labs_1month = substr($labs_1month, 1);
if(array_key_exists('lab',$_SESSION)){
    $lab = $_SESSION['lab'];
}else{
    $lab = false;
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <title>State of Florida Mobile Irrigation Lab (MIL) Program - Add More Packages</title>
        <script src="../../js/jquery-validate/lib/jquery.js" type="text/javascript"></script>
        <script src="../../js/jquery-validate/jquery.validate.js" type="text/javascript"></script>
        <link rel="stylesheet" type="text/css" href="../../styles/milStylesheet.css" />
        <meta name="robots" content="nofollow" />
    </head>

    <body id="login">
        <div id="Wrapper">
            <div id="header">
                <h1>State of Florida</h1>
                <p>Mobile Irrigation Laboratory Program</p>
            </div>
            <div id="contentWrap">
                <h2>MIL Lab</h2>
                <form id="new_package_form" action="./control.php" method="post">
                    <table border="0" cellpadding="0" cellspacing="0" class="mainContactForm">
                        <tr>
                            <td align="right">MIL Lab ID: </td>
                            <td>
                                <?php echo ($lab?$lab->getProperty('mil_id'):"");?>
                                <input type="hidden" name="mil_id" value="<?php echo ($lab?$lab->getProperty('mil_id'):"");?>"/>
                            </td>
                        </tr>
                        <tr>
                            <td align="right">MIL Lab Name: </td>
                            <td>
                                <input type="input" name="mil_name" value="<?php echo ($lab?$lab->getProperty('mil_name'):"");?>"/>
                            </td>
                        </tr>
                         <tr>
                            <td align="right">MIL Lab Type: </td>
                            <td>
                                <input type="input" name="mil_type" value="<?php echo ($lab?$lab->getProperty('mil_type'):"");?>"/>
                            </td>
                        </tr>
                        <tr>
                        <td align="right">Year of Service: </td>
                            <td>
                                <input type="input" name="year_of_service" value="<?php echo ($lab?$lab->getProperty('year_of_service'):"");?>"/>
                            </td>
                        </tr>
                        <tr>
                            <td align="right">Contractor Name:</td>
                            <td>
                                <select id="contractor_id" name="contractor_id">
                                <option value="selected">Choose one</option>
                                <?php
                                    $table_name = 'contractor';
                                    Utility::printOptions($table_name,null,$lab);
                                ?>
                                </select>
                            </td>
                        </tr>
                         <tr>
                            <td align="right">Billing Cycle:</td>
                            <td>
                                <select name="billing_cycle">
                                    <option value="1" <?php echo ($lab&&$lab->getProperty('billing_cycle')==1?"selected":"")?>>1 month</option>
                                    <option value="3" <?php echo ($lab&&$lab->getProperty('billing_cycle')==3?"selected":"")?>>3 months</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td align="right">Counties:</td>
                            <td>
                                 <div>
                                   <?php 
                                  
                                   if($lab){
                                       $mil_id = $lab->getProperty('mil_id');
                                       $countyLab = new CountyLab($mil_id);
                                       echo $countyLab->displayCounties();
                                   }
                                   ?>
                                </div>
                            </td>
                        </tr>


                        <tr>
                            <td align="right"></td>
                            <td>
                                <div class="form-btns">
                                    <input  class="button" name="<?php echo($lab?"save":"add")?>_lab"  type="submit" value="<?php  echo ($lab?"Save":"Add")?>" tabindex="3" />
                                    <input  class="button" name="cancel" type="submit" value="Cancel" tabindex="3" />
                     
                                </div>
                            </td>
                        </tr>
                    </table>
                </form>
                <span class="clearing"></span>
            </div>
            <div id="sponsorLogos">
                <p>Sponsored by the State of FLorida, The St. Johns Water Management District, and The Florida Department of Agriculture</p>
            </div>
        </div>

   
  
    </body>
</html>