<?php
require_once dirname(__FILE__) . '/../../includes/utility.php';
require_once dirname(__FILE__) . '/../../includes/input/package/evaluation/FollowIrrSys.php';
require_once dirname(__FILE__) . '/../../includes/input/package/evaluation/InitIrrSys.php';
require_once dirname(__FILE__) . '/../../includes/input/package/evaluation/FollowFirm.php';
require_once dirname(__FILE__) . '/../../includes/input/package/evaluation/InitFirm.php';
$msg = false;
if (array_key_exists('msg', $_GET)) {
 $msg = $_GET['msg'];
}
?>
<div class="content" dojotype="dijit.layout.ContentPane" title="MIL Evaluations"
     <?php echo(!array_key_exists('tab', $_SESSION) || $_SESSION['tab'] == 1 ? "selected" : "") ?>>
 <div id="subcontent_evaluation" dojotype="dijit.layout.TabContainer" tabposition="top" region="center">
  <div class="content" dojotype="dijit.layout.ContentPane" title="Pending">
   <div id="eval_list">
   </div>

   <form action="./evaluation/control_evaluation.php" method="post">
    <input class="button" type="submit" name="add_new_eval_package" value="Add New Evaluation Package" />
    <div id="msg"><?php echo $msg ?></div>   
    <?php if ($Packages != false) {
     ?>
     <table border="0" cellpadding=0 cellspacing=0 class="subForms">
      <thead>
      <th>&nbsp;</th>
      <th>MIL Name</th>
      <th>Eval Package ID</th>
      <th>MIL ID</th>
      <th>Your Fiscal Yr <?php echo FiscalQuarter::getFiscalEndMonth($FiscalStandard); ?></th>
      <th>Quarter</th>
      <th>From Month to Month</th>
      <th>Current Num of Evals</th>
      <th>Date Created</th>
      <th>&nbsp;</th>
      </thead>
      <?php
      $i = 1;
      foreach ($Packages as $id => $package) {
       if ($package->getProperty('status') != 'pending')
        continue;
       $fq = new FiscalQuarter($package->getProperty('eval_yr'), $package->getProperty('eval_month'), $FiscalStandard);
       $mil_id = $package->getProperty('mil_id');
       $mil_display_name = $Labs[$mil_id]->getDisplayName();
       // $btn = new Button($package,$_SESSION['role']);
       $btns = new Buttons($package, $MemberServed->getProperty('role'));
       echo
       '<tr>
    <td>' . $i . '</td>
    <td>' . $mil_display_name . '</td>
    <td>' . $package->getPackageName() . '</td>
    <td>' . $mil_id . '</td>
    <td>' . $fq->getFiscalYr() . '</td>
    <td>' . $fq->getFiscalQtr() . '</td>
    <td>' . $fq->getFiscalDQtr() . '</td>
    <td>' . $package->getProperty('num_of_eval') . '</td>
    <td>' . $package->getProperty('pack_created_time') . '</td>
    <td>' . $btns->getString() . '</td>
    </tr>';
       $i++;
      }
      ?>

     </table>
    </form>

   </div>
   <div class="content" dojotype="dijit.layout.ContentPane" title="Submitted">
    <div id="eval_list">
    </div>

    <form action="./evaluation/control_evaluation.php" method="post">
     <table border="0" cellpadding=0 cellspacing=0 class="subForms">
      <thead>
      <th>&nbsp;</th>
      <th>MIL Name</th>
      <th>Eval Package ID</th>
      <th>MIL ID</th>
      <th>Your Fiscal Yr </th>
      <th>Quarter</th>
      <th>From Month to Month</th>
      <th>Num of Evals</th>
      <th>Date Submitted</th>
      <th>&nbsp;</th>
      </thead>
      <?php
      $i = 1;
      foreach ($Packages as $id => $package) {
       if ($package->getProperty('status') != 'submitted')
        continue;
       $fq = new FiscalQuarter($package->getProperty('eval_yr'), $package->getProperty('eval_month'), $FiscalStandard);
       $mil_id = $package->getProperty('mil_id');
       $mil_display_name = $Labs[$mil_id]->getDisplayName();
       // $btn = new Button($package,$_SESSION['role']);
       $btns = new Buttons($package, $MemberServed->getProperty('role'));

       echo
       '<tr>
    <td>' . $i . '</td>
    <td>' . $mil_display_name . '</td>
    <td>' . $package->getPackageName() . '</td>
    <td>' . $mil_id . '</td>
    <td>' . $fq->getFiscalYr() . '</td>
    <td>' . $fq->getFiscalQtr() . '</td>
    <td>' . $fq->getFiscalDQtr() . '</td>
    <td>' . $package->getProperty('num_of_eval') . '</td>
    <td>' . $package->getProperty('pack_submitted_time') . '</td>
         <td>' . $btns->getString() . '</td>
    
    </tr>';
       $i++;
      }
      ?>


     </table>
    </form>

   </div>
   <div class="content" dojotype="dijit.layout.ContentPane" title="Approved">
    <div id="eval_list">
    </div>

    <form action="./evaluation/control_evaluation.php" method="post">
     <table border="0" cellpadding=0 cellspacing=0 class="subForms">
      <thead>
      <th>&nbsp;</th>
      <th>MIL Name</th>
      <th>Eval Package ID</th>
      <th>MIL ID</th>
      <th>Your Fiscal Yr </th>
      <th>Quarter</th>
      <th>From Month to Month</th>
      <th>Num of Evals</th>
      <th>Date Approved</th>
      <th>&nbsp;</th>
      </thead>
 <?php
 $i = 1;
 foreach ($Packages as $id => $package) {
  if ($package->getProperty('status') != 'approved')
   continue;
  $fq = new FiscalQuarter($package->getProperty('eval_yr'), $package->getProperty('eval_month'), $FiscalStandard);
  $mil_id = $package->getProperty('mil_id');
  $mil_display_name = $Labs[$mil_id]->getDisplayName();
  // $btn = new Button($package,$_SESSION['role']);
  $btns = new Buttons($package, $MemberServed->getProperty('role'));

  echo
  '<tr>
    <td>' . $i . '</td>
    <td>' . $mil_display_name . '</td>
    <td>' . $package->getPackageName() . '</td>
     <td>' . $mil_id . '</td>
    <td>' . $fq->getFiscalYr() . '</td>
    <td>' . $fq->getFiscalQtr() . '</td>
    <td>' . $fq->getFiscalDQtr() . '</td>
    <td>' . $package->getProperty('num_of_eval') . '</td>

    <td>' . $package->getProperty('pack_approved_time') . '</td>
         <td class="tableTD">' . $btns->getString() . '</td>

    </tr>';
  $i++;
 }
}
?>


    </table>
   </form>
  </div>
 </div>
</div>