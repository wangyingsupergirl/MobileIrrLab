<?php
/*
 * Required by page "/eval_table/eval_type_method.php". Use class EvalTypeWaterSavingType in package.php. Use getETWSTList() in utility.php
 */
require_once dirname(__FILE__) . '/../../includes/input/package/Package.php';
require_once dirname(__FILE__) . '/../../includes/utility.php';
require_once dirname(__FILE__) . '/../../includes/constant.php'; //used by att1a_required.php
session_start();
$msg = false;
if (!array_key_exists('eval_stack', $_SESSION)) {
 head('Location:./error.php?err=There is no evaluation to display.');
 exit;
} else {
 $eval = $_SESSION['eval_stack']->peek();
}
if(array_key_exists('PackageObject',$_SESSION)){
    $package = $_SESSION['PackageObject'];
}else{
    echo 'Session miss Package Object';
    exit;
}


?>
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <title>State of Florida Mobile Irrigation Lab (MIL) Program - Evaluation</title>
        <link rel="stylesheet" type="text/css" href="../../styles/milStylesheet.css" />
        <meta name="robots" content="nofollow" />
        <script src="../../js/jquery-validate/lib/jquery.js" type="text/javascript"></script>
        <script src="../../js/jquery-validate/jquery.validate.js" type="text/javascript"></script>
        <script src="../../js/eval_form_validate.js" type="text/javascript"></script>
         <script src="../../js/util.js" type="text/javascript"></script>
         <script src="../../js/multiple_choices_list.js" type="text/javascript"></script>
        <script type="text/javascript">
            var irr_sys_max = <?php
Utility::setLookupTableToSession('irr_sys_types', null);
$maxes = Utility::getMaxEuDuJsArray('irr_sys_types', 'max_du_eu');
echo $maxes;
?> ;
                      
                                              //var irr_sys_max = {
                                              //4:85,5:94,6:95,7:90,8:60,9:95,10:75,11:80,12:80,13:80,14:85,15:90,16:86,17:75,18:87,19:65};
                                              $().ready(function() {
                                                  //even handler
                                                  var index;
                                                  $('#irr_sys_type').change(function() {
                                                      index = $('#irr_sys_type').val();
                                                      $('#max_du_eu').html(irr_sys_max[index]);
                                                  });
	
                                                  index = $('#irr_sys_type').val();
                                                  if(index!=""){
                                                      $('#max_du_eu').html(irr_sys_max[index]);
                                                  }
                                              });
        </script>
    </head>

    <body id="login">
        <div id="largeWrapper"> 
            <div id="header">
                <h1>State of Florida</h1>
                <p>Mobile Irrigation Laboratory Program</p>
            </div>
            <div id="largeContentWrap">
                <div id="mainIndex">
                    <form id="evalForm" action="./control_evaluation.php" method="post" name="Login">
                        <h2 style="margin-bottom: 0;">Irrigation System Evaluation</h2>
                        <span>* indicates required field</span>	
                        <?php
                        if (array_key_exists('err', $_GET)) {
                            $msg = $_GET['err'];
                           
                        }
                        echo "<p style='color:red'>$msg</p>";
                        //Evaluation Type Method
                        require_once dirname(__FILE__) . '/eval_table/eval_type_method.php';
                       
                        if ($eval->isTypeDetermined()) {
                            $eval_type = $eval->getProperty('eval_type');
                            $eval_method = $eval->getProperty('eval_method');

                            require_once dirname(__FILE__) . '/eval_table/att1a_required.php';

                            if ($eval->isIrrSys()) {
                                if ($eval->getProperty('eval_type') != '3') {
                                 require_once dirname(__FILE__) . '/eval_table/irr_sys_result.php';
                                } else {
                                 require_once dirname(__FILE__) . '/eval_table/replacement_irr_sys_result.php';
                                }
                            }
                            
                            require_once dirname(__FILE__) . '/eval_table/att1b_required.php';

                            if ($eval->isFirm()) {
                                require_once dirname(__FILE__) . '/eval_table/firm_result.php';
                            }

                             require_once dirname(__FILE__) . '/eval_table/comment.php';
                        } 
                        ?>
                        <?php if ($eval->isTypeDetermined()) { ?>
                           <div class="form-btns" style="margin-top: 20px;">
                                <input class="button" type="submit" id="eval_submit" name="eval_submit" value="Submit" onclick="validateBeforeSubmit();"/>
                                <input class="button" type="submit" name="eval_cancel" value="Cancel"/>
                            </div>
                        <?php } ?>
                    </form>
                </div>
            </div>

            <span class="clearing"></span>
            <div id="sponsorLogos">
                <p>Sponsored by the State of FLorida, The St. Johns Water Management District, and The Florida Department of Agriculture</p>
            </div>
        </div>
      
    </body>
</html>
