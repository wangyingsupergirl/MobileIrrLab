<?php
require_once dirname(__FILE__) . '/../../includes/input/package/Contract.php';
require_once dirname(__FILE__) . '/../../includes/input/package/Package.php';
require_once dirname(__FILE__) . '/../../includes/utility.php';
session_start();
$package = $_SESSION['PackageObject'];
$contracts = $package->getContracts();
$labs_arr = $_SESSION['MILLabs'];
$selected_contract= $package->getProperty('contract_id',$contract_id);
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
        <?php if($contracts){?>
            <h2> Select Contract</h2>
            <form action='control_evaluation.php' method='post'>
                <table border="0" cellpadding="0" cellspacing="0" class="subForms" style="margin-left: 0;">
                    <thead>
                  
                    <th>FDACS Contract #</th>
                    <th>FDACS Year</th>
                    <th>MIL ID</th>
                    <th>MIL Name</th>
                    <th>Total Evaluation(Initial/Follow up/Replacement) Required</th>
                    <th>Total Follow up Evaluation Required</th>
                    <th>Operations</th>
                    </thead>
                <?php foreach($contracts as $contract){ ?>
                    <tr>
                        <td><?php echo $contract->getProperty('fdacs_id');?></td>
                        <td><?php echo $contract->getProperty('fdacs_yr');?></td>
                        <td><?php echo $contract->getProperty('mil_id');?></td>
                        <td><?php $mil_id = $contract->getProperty('mil_id');
                                  $mil_name = $labs_arr[$mil_id]->getDisplayName();
                                  echo $mil_name; ?></td>
                        <td><?php echo $contract->getTotalEvals().$contract->getEvalNumDetails();?></td>
                        <td><?php echo $contract->getTotalFollowupEvals().$contract->getFollowupEvalNumDetails();?></td>
                        <td> <input class="button" type="submit" name="add_contract_to_package:<?php echo $contract->getProperty('id');?>" value="Select"/> </td>
                     </tr>
                <?php } ?>
                </table>
                <div  style="margin: 5px 10px 0 0; float:left;">
               
                </div>
            </form>
        <?php }else{
            echo "No contract is available for this package. Send Email to Adminstractor.";
        } ?>
         <?php 
        $selected_contract_id= $package->getProperty('contract_id',$contract_id); 
        if($selected_contract_id!=''){
            if(array_key_exists($selected_contract_id, $contracts)){
                echo "Selected Contract FDACS # {$contracts[$selected_contract_id]->getProperty('fdacs_id')}";
            }
        }
        ?>
        </div>
        
        <span class="clearing"></span>
        <div id="sponsorLogos">
        <p>Sponsored by the State of Florida, The St. Johns Water Management District, and The Florida Department of Agriculture</p>
        </div>

    </div>
</body>
</html>