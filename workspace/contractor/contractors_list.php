<?php
require_once dirname(__FILE__) . '/../../includes/utility.php';
require_once dirname(__FILE__) . '/../../includes/constant.php';
require_once dirname(__FILE__) . '/../../includes/input/package/evaluation/FollowIrrSys.php';
require_once dirname(__FILE__) . '/../../includes/input/package/evaluation/InitIrrSys.php';
require_once dirname(__FILE__) . '/../../includes/input/package/evaluation/FollowFirm.php';
require_once dirname(__FILE__) . '/../../includes/input/package/evaluation/InitFirm.php';


?>
<div class="content" dojotype="dijit.layout.ContentPane" title="MIL Contractors"
<?php echo(!array_key_exists('tab', $_SESSION) || $_SESSION['tab'] ==CONTRACTOR_TAB ? "selected" : "")  ?>>
<fieldset>

    <div>
     <form action="<?php echo MIL_SERVER_ROOT.'workspace/contractor/';?>control.php" method="post">
      <table border="0" cellpadding=0 cellspacing=0 class="subForms">
        <thead>
            <th>Contractor ID</th>
            <th>Contractor Name</th>
            <th>Operations</th>
         </thead>
         <?php
         $partners = Utility::getAllContractor($MemberServed);
         $_SESSION['contractors'] = $partners;
         $i = 1;
         foreach ($partners as $id => $contractor) {
            $btns = new Buttons($contractor, $MemberServed->getProperty('role'));
            echo
            '<tr>
                <td>' . $contractor->getProperty('id'). '</td>
                <td>' .  $contractor->getProperty('name') . '</td>
                <td>'  .$btns->getString(). '</td>
             </tr>';
             $i++;
        }
         ?>

       </table>
         <?php if($MemberServedRole==ADMIN_ROLE){?>
       <input class="button" type="submit" name="add_new_contractor" value="Add New Contractor Name"/>
         <?php } ?>
     </form>
      </div>
    </fieldset>

</div>