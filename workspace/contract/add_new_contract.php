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

if(array_key_exists('contract',$_SESSION)){
    $contract = $_SESSION['contract'];
   // echo $contract;
}else{
    $contract = false;
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
                <h2>Contract</h2>
                <form id="new_package_form" action="./control.php" method="post">
                    <table border="0" cellpadding="0" cellspacing="0" class="mainContactForm">
                        <tr>
                        <td></td>
                        <td><input type="hidden" name="id" value="<?php echo ($contract?$contract->getProperty('id'):"");?>"/></td>
                        </tr>
                        <tr>
                            <td align="right">FDACS Contract #: </td>
                            <td>
                                <input type="input" name="fdacs_id" value="<?php echo ($contract?$contract->getProperty('fdacs_id'):"");?>"/>
                            </td>
                        </tr>
                        <tr>
                            <td align="right">FDACS Year: </td>
                            <td>
                                
                                <select id="fdacs_yr" name="fdacs_yr">
                                    <option value="selected">Choose one</option>
                                    <?php
                                    $begin = BEGINNING_OF_CALENDAR_YEAR_OF_EVALUATION;
                                    $end = END_OF_CALENDAR_YEAR_OF_EVALUATION;
                                    for ($i = $begin; $i <= $end; $i++) {
                                        $j = $i+1;
                                        echo "<option value='$i' ";
                                        if($contract){
                                            $fdacs_yr = $contract->getProperty('fdacs_yr');
                                            if($i==$fdacs_yr){
                                                echo "selected";
                                             }
                                        }
                                        echo">$i - $j </option>";
                                    }
                                    ?>
                                </select>
                            </td>
                        </tr>
                         
                        <tr>
                        <td align="right">MIL Lab Name: </td>
                            <td>
                                 <select name="mil_id" id="mil_id">
                                    <!--JQuery Form Validation default is "" not 0 -->
                                    <option value="selected">Choose one</option>
                                    <?php
                                    foreach ($labs_arr as $key => $val) {
                                        echo '<option value="' . $val->getProperty('mil_id') . '" ';
                                        if($contract){
                                            $mil_id = $contract->getProperty('mil_id');
                                            if($val->getProperty('mil_id') ==$mil_id){
                                                echo 'selected';
                                            }
                                        }
                                        echo '>';

                                        echo $val->getDisplayName();
                                        echo '</option>';
                                    }
                                    ?>
                                </select>
                            </td>
                        </tr>
                       
                        <tr>
                            <td colspan="2">
                                 Required Evaluation (Initial/Follow up/Replacement)
                            </td>
                        </tr>
                        <tr>
                            <td align="right">+ Quarter 1: </td>
                            <td>
                             <input type="input" name="quarter1_evals" value="<?php echo ($contract?$contract->getProperty('quarter1_evals'):"");?>"/>
                            </td>
                        </tr>
                        <tr>
                            <td align="right">+ Quarter 2: </td>
                            <td>
                             <input type="input" name="quarter2_evals" value="<?php echo ($contract?$contract->getProperty('quarter2_evals'):"");?>" />
                            </td>
                        </tr>
                        <tr>
                            <td align="right">+ Quarter 3: </td>
                            <td>
                             <input type="input" name="quarter3_evals" value="<?php echo ($contract?$contract->getProperty('quarter3_evals'):"");?>"/>
                            </td>
                        </tr>
                        <tr>
                            <td align="right">+ Quarter 4: </td>
                            <td>
                             <input type="input" name="quarter4_evals" value="<?php echo ($contract?$contract->getProperty('quarter4_evals'):"");?>"/>
                            </td>
                        </tr>
                        <tr>
                            <td align="right">Total: </td>
                            <td>
                               
                            </td>
                        </tr>

                         <tr>
                            <td colspan="2">
                                 Required Follow up Evaluation
                            </td>
                        </tr>
                       <tr>
                            <td align="right">+ Quarter 1: </td>
                            <td>
                             <input type="input" name="quarter1_followup_evals" value="<?php echo ($contract?$contract->getProperty('quarter1_followup_evals'):"");?>"/>
                            </td>
                        </tr>
                        <tr>
                            <td align="right">+ Quarter 2: </td>
                            <td>
                             <input type="input" name="quarter2_followup_evals"  value="<?php echo ($contract?$contract->getProperty('quarter2_followup_evals'):"");?>" />
                            </td>
                        </tr>
                        <tr>
                            <td align="right">+ Quarter 3: </td>
                            <td>
                             <input type="input" name="quarter3_followup_evals"  value="<?php echo ($contract?$contract->getProperty('quarter3_followup_evals'):"");?>"/>
                            </td>
                        </tr>
                        <tr>
                            <td align="right">+ Quarter 4: </td>
                            <td>
                             <input type="input" name="quarter4_followup_evals" value="<?php echo ($contract?$contract->getProperty('quarter4_followup_evals'):"");?>"/>
                            </td>
                        </tr>
                        <tr>
                            <td align="right">Total: </td>
                            <td>
                                
                            </td>
                        </tr>
                       


                        <tr>
                            <td align="right"></td>
                            <td>
                                <div class="form-btns">
                                    <input  class="button" name="<?php echo($contract?"save":"add")?>_contract"  type="submit" value="<?php echo($contract?"Save":"Add")?>" tabindex="3" />
                                </div>
                            </td>
                        </tr>
                    </table>
                </form>
                <span class="clearing"></span>
            </div>
            <div id="sponsorLogos">
                <p>Sponsored by the State of Florida, The St. Johns Water Management District, and The Florida Department of Agriculture</p>
            </div>
        </div>
      
    </body>
</html>