<?php
require_once dirname(__FILE__) . '/../../includes/utility.php';
require_once dirname(__FILE__) . '/../../includes/constant.php';
require_once dirname(__FILE__) . '/../../includes/input/package/evaluation/FollowIrrSys.php';
require_once dirname(__FILE__) . '/../../includes/input/package/evaluation/InitIrrSys.php';
require_once dirname(__FILE__) . '/../../includes/input/package/evaluation/FollowFirm.php';
require_once dirname(__FILE__) . '/../../includes/input/package/evaluation/InitFirm.php';

if (array_key_exists('msg', $_GET)) {
    $msg = $_GET['msg'];
}
?>
<div class="content" dojotype="dijit.layout.ContentPane" title="MIL Contracts"
<?php echo(!array_key_exists('tab', $_SESSION) || $_SESSION['tab'] ==3 ? "selected" : "")  ?>>
<fieldset>

    <div>
   <div id="msg"><?php echo $msg ?></div>
     <form action="<?php echo MIL_SERVER_ROOT.'workspace/contract/';?>control.php" method="post">
      <table border="0" cellpadding=0 cellspacing=0 class="subForms">
        <thead>
            <th>&nbsp;</th>
            <th>FDACS Contract #</th>
            <th>FDACS Year</th>
            <th>MIL ID</th>
            <th>MIL Name</th>
            <th>Evaluation<br/>(Initial/Follow up/Replacement)<br/>Required </th>
            <th>Follow up Evaluation Required</th>
            <th>Operations</th>
         </thead>
         <?php
         $contracts = Utility::getAllContract($MemberServed);
         $_SESSION['contracts'] = $contracts;
         $i = 1;
         foreach ($contracts as $id => $contract) {
            $mil_id = $contract->getProperty('mil_id');
            $mil_name = $Labs[$mil_id]->getDisplayName();
            $total_evals = $contract->getTotalEvals();
            $total_followup_evals = $contract->getTotalFollowupEvals();
            $btns = new Buttons($contract, $MemberServed->getProperty('role'));
            echo
            '<tr>
                <td>' . $i . '</td>
                <td>' . $contract->getProperty('fdacs_id'). '</td>
                <td>' . $contract->getProperty('fdacs_yr') . '</td>
                <td>' . $mil_id . '</td>
                <td>' . $mil_name. '</td>
                <td>' .$total_evals . $contract->getEvalNumDetails()."</td>
                <td>" .$total_followup_evals . $contract->getFollowupEvalNumDetails()."</td>
                <td>"  . $btns->getString() . '</td>
              </tr>';
             $i++;
        }
         ?>
         
         
         
       </table>  
         <?php if($MemberServedRole==ADMIN_ROLE){?>
        <input class="button" type="submit" name="add_new_contract" value="Add New Contract"/>
         <?php } ?>
     </form>
  
      </div>   
    </fieldset>

</div>