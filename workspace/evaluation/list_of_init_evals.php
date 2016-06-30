<?php 
require_once dirname(__FILE__) . '/../../includes/input/package/Package.php';
require_once dirname(__FILE__) . '/../../includes/input/package/SearchLastEval.php';
require_once dirname(__FILE__) . '/../../includes/input/package/evaluation/FollowIrrSys.php';
require_once dirname(__FILE__) . '/../../includes/input/package/evaluation/InitIrrSys.php';
require_once dirname(__FILE__) . '/../../includes/input/package/evaluation/FollowFirm.php';
require_once dirname(__FILE__) . '/../../includes/input/package/evaluation/InitFirm.php';

session_start();
if(array_key_exists('search',$_SESSION)){
$search = $_SESSION['search'];

}
?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>State of Florida Mobile Irrigation Lab (MIL) Program - Contractor Sign In</title>
    <link rel="stylesheet" type="text/css" href="../../styles/milStylesheet.css" />
    <meta name="robots" content="nofollow" />
</head>

<body id="login">
    <div id="largeWrapper">
        <div id="header">
            <h1>State of Florida</h1>
            <p>Mobile Irrigation Laboratory Program</p>
        </div>

        <div id="largeContentWrap">
            <h2> Select Right Evaluation</h2>
            <form action='control_evaluation.php' method='post'>
                <?php
                if($search->isEvalList()){
                ?>
                    <table border="0" cellpadding="0" cellspacing="0" class="subForms" style="margin-left: 0;">
                    <tr>
                   
                    <th>Evaluation ID</th>
                    <th>Evaluation Type</th>
                    <th>Evaluation Method</th>
                    <th>MIL ID</th>
                    <th>Fiscal Year</th>
                    <th>Month</th>
                    <th>Crop Name</th>
                    <th>County</th>
                    <th>Acre</th>
                    <th>Zip Code</th>
                     <th>Status</th>
                    <th>Operations</th>
                    </tr>
                        <?php
                        $evalList = $search->getProperty('evalList');
                        //$crop_type = Utility::getLookupTable();
                        foreach($evalList as $id => $eval){?>
                        <tr>
                       
                        <td> <?php echo $id;?></td>
                        <td><?php echo ($eval->getDisplayName('eval_type')=='Replacement'?'Initial':$eval->getDisplayName('eval_type'));?></td>
                        <td><?php echo $eval->getDisplayName('eval_method');?></td>
                        <td><?php echo $eval->getProperty('mil_id');?></td>
                        <td><?php echo $eval->getProperty('eval_yr');?></td>
                        <td><?php echo $eval->getProperty('eval_month');?></td>
                        <td><?php echo $eval->getDisplayName('crop_category'); ?></td>
                        <td><?php echo $eval->getDisplayName('county_id');?></td>
                        <td><?php echo $eval->getProperty('acre');?></td>
                        <td><?php echo $eval->getProperty('zip_code');?></td>
                        <td><?php echo $eval->getProperty('status');?></td>
                       <td> <input class="button" type="submit" name="choose_inital_eval_id:<?php echo $id;?>" value="Select"/></td>
                        </tr>
                        <?php }?>
                    </table>
                   

                <?php }else{ ?>
                
                    No Evaluation Satisfies the Search Criteria.
                    <div class="form-btns" style="margin: 5px 10px 0 0; float:left;">
                    <input class="button" type="submit" name="refine_search_criteria" value="Back to Refine"/>
                    </div>
                    
                <?php
                }
                ?>
            </form>
        </div>
            <span class="clearing"></span>
            <div id="sponsorLogos">
            <p>Sponsored by the State of FLorida, The St. Johns Water Management District, and The Florida Department of Agriculture</p>
            </div>
        </div>
    </body>
</html>