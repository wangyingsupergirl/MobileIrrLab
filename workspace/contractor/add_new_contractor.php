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
if(array_key_exists('contractor',$_SESSION)){
    $contractor = $_SESSION['contractor'];
}else{
    $contractor = false;
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
                <h2>Contractor Name</h2>
                <form action="./control.php" method="post">
                    <table border="0" cellpadding="0" cellspacing="0" class="mainContactForm">
                        <tr>
                        <td></td>
                        <td><input type="hidden" name="id" value="<?php echo ($contractor?$contractor->getProperty('id'):"");?>"/></td>
                        </tr>
                        <tr>
                            <td align="right">Contractor Name: </td>
                            <td>
                                <input type="input" name="name" value="<?php echo ($contractor?$contractor->getProperty('name'):"");?>"/>
                            </td>
                        </tr>
                        <tr>
                            <td align="right"></td>
                            <td>
                               <input  class="button" name="<?php  echo ($contractor?"save":"add")?>_contractor" type="submit" value="<?php  echo ($contractor?"Save":"Add")?>"/>
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