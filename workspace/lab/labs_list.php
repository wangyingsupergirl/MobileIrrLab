<?php
require_once dirname(__FILE__) . '/../../includes/utility.php';
require_once dirname(__FILE__) . '/../../includes/constant.php';
require_once dirname(__FILE__) . '/../../includes/input/package/evaluation/FollowIrrSys.php';
require_once dirname(__FILE__) . '/../../includes/input/package/evaluation/InitIrrSys.php';
require_once dirname(__FILE__) . '/../../includes/input/package/evaluation/FollowFirm.php';
require_once dirname(__FILE__) . '/../../includes/input/package/evaluation/InitFirm.php';


?>
<div class="content" dojotype="dijit.layout.ContentPane" title="MIL Labs"
<?php echo(!array_key_exists('tab', $_SESSION) || $_SESSION['tab'] ==4 ? "selected" : "")  ?>>
<fieldset>
<div>
     <form action="lab/control.php" method="post">
      <table border="0" cellpadding=0 cellspacing=0 class="subForms">
        <thead>
            <th>&nbsp;</th>
            <th>MIL Lab #</th>
            <th>MIL Name</th>
           
            <th>Years of Service</th>
            <th>Contractor</th>
            <th>Billing Cycle</th>
            <th>Operations</th>
         </thead>
          <?php
        
         $i = 1;
         foreach ($Labs as $id => $lab) {
            $btns = new Buttons($lab, $MemberServed->getProperty('role'));
            echo
            '<tr>
                <td>' . $i . '</td>
                <td>' . $lab->getProperty('mil_id'). '</td>
                <td>' . $lab->getDisplayName() . '</td>
                <td>' . $lab->getProperty('year_of_service'). '</td>
                <td>' .$lab->getDisplayName('contractor_id') ."</td>
                <td>" .$lab->getProperty('billing_cycle')."</td>
                <td>"  .$btns->getString(). '</td>
              </tr>';
             $i++;
        }
         ?>
         <tr>
        

       </table>
        <?php if($MemberServedRole==ADMIN_ROLE){?>
       <input class="button" type="submit" name="add_new_lab" value="Add New MIL Lab"/>
        <?php }?>
     </form>
      </div>
    </fieldset>

</div>