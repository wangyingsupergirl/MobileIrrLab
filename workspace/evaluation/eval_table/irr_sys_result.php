<fieldset>
    <table id="irr_calculation" border="0" cellpadding="0" cellspacing="0" class="mainContactForm">
        <tr>
            <td colspan='2' >IRRIGATION SYSTEM EVALUATIONS: WATER SAVINGS RESULT
                <br />Water Saving(ac-ft) - Irrigation System Only
            </td>
        </tr>
        <!----Beginning of Improvement Type (Actual/Potential)---------------------->
        <tr>
            <td align="right">Type:</td>
            <td>
                <?php
               //$eval_type declare in eval_content.php
                $tuple = $_SESSION['eval_types_water_saving_types'][$eval_type];
                echo $tuple->getProperty('water_saving_type');

                ?>
            </td>
        </tr>
        <!------End of Improvement Type (Actual/Potential) ---------------------------------->
        
        <!------Beginning of DU/EU Improvement Result------------------------------------->
        <tr>
        <?php
            $du_eu_improv = $eval->getProperty('duEuImprov');
            $sched_imprv = $eval->getProperty('sched_imprv');
            $planned_repairs = $eval->getProperty('planned_repairs');
            $imm_repairs = $eval->getProperty('imm_repairs');
            if ($du_eu_improv!=false) {
                ?>
                <td align="right">DU or EU Improvement:</td>
                <td>
                    <span class="calResult"><?php echo round($du_eu_improv, 2);?></span>
                </td>
                <td>
                    <?php
                    $mg = Utility::getMillionGallon($du_eu_improv);
                    echo $mg;
                }
                ?>
            </td>
        </tr>
        <!----End of DU/EU Improvement Result------------------------------------>
        
        <!----Beginning of Scheduled&Planned&Immediate Repairs----------->
        <tr>
            <td align="right">Scheduled Improvements:</td>
            <td>
                <?php echo ($du_eu_improv !== false ? ' + ' : '') ?>
                <input name="sched_imprv" type="text" value="<?php echo $sched_imprv; ?>" size="32" maxlength="32">
            </td>
            <td><?php echo ($du_eu_improv !== false ? Utility::getMillionGallon($sched_imprv) : '') ?></td>
        </tr>
        
        <tr>
            <td align="right">Planned Repairs:</td>
            <td>
                <?php echo ($du_eu_improv !== false ? ' + ' : '') ?>
                <input name="planned_repairs" type="text" value="<?php echo $planned_repairs; ?>" size="32" maxlength="32">
            </td>
            <td><?php echo ($du_eu_improv !== false ? Utility::getMillionGallon($planned_repairs) : '') ?></td>
        </tr>
        
        <tr>
            <td align="right">Immediate Repairs:</td>
            <td><?php echo ($du_eu_improv !== false ? ' + ' : '') ?><input name="imm_repairs" type="text" value="<?php echo $imm_repairs; ?>" size="32" maxlength="32">
            </td>
            <td><?php echo ($du_eu_improv !== false ? Utility::getMillionGallon($imm_repairs) : '') ?></td>
        </tr>
        <!--------End of Scheduled&Planned&Immediate Repairs------------------------->
        
        <!--------Beginning of Calculate Button------------------------------------------------------>
        <?php
        if ($eval->isCalRequired()) {
            ?>
            <tr>
                <td></td>
                <td>
                    <input class="button" type="submit" id="calculate_water_saving" name="calculate_water_saving" value="Calculate Total Water Savings" onclick ="validateBeforeCalculation(); "/>
                </td>
            </tr>
        <!-----End of Calculate Button----------------------------------------------------------->   
            
        <!------Beginning of AWS or PWS Display Area ---------------------------->
        <?php
         }
        //AWS or PWS will be displayed below
        if ($du_eu_improv!==false) {
            if ($eval_type == 2 || $eval_type == 3) {
            // base on irrigation type calculate AWS or PWS
                ?>
                <tr>
                    <td align="right">
                        Total AWS: 
                    </td>
                    <td><span class="calResult">
                            <?php
                            $ws = $eval->getTotalWS();
                            $comment = false;
                            echo $ws;
                            if ($init_eval != false) {
                                 Utility::setLookupTableToSession('irr_sys_types', NULL);
                                $irr_sys_types_table =  $_SESSION['irr_sys_types'];
                                $init_eval->calculateDuEuImprov($irr_sys_types_table);
                                $pws = $init_eval->getTotalWS();
                                //$comments = $eval->getAutoComments($init_eval);
                                echo "/$pws";
                            }
                            ?></span>
                    </td>

                <?php } else if ($eval_type == 1) { ?>
                <tr>
                    <td align="right">
                        Total PWS: 
                    </td>
                    <td>
                        <span class="calResult">
                            <?php
                            $ws = $eval->getTotalWS();
                            echo $ws;
                            ?>
                        </span>
                        <?php
                    }
                    ?>

                </td>
                <td>
                    <?php
                    $mg = Utility::getMillionGallon($ws);
                    echo $mg;
               
                if ($init_eval != false) {
                    $pws_mg = Utility::getMillionGallon($pws);
                    echo "/$pws_mg";
                }
               } 
                ?>
            </td>
        </tr>
         <!------End of AWS or PWS Display Area ------------------------------------------------->
         
         <!------Beginning of Automatic Comments Display Area ---------------------------->
        <tr>
            <td colspan="3" >
             <div id="automatic_comments">
             <?php  if ($comments != false) {
                    foreach ($comments as $comment) {
                        echo $comment . '<br />';
                    }
                }?>
              </div>
            </td>
        </tr>
        <!--------End of Automatic Comments Display Area--------------------------------------->
    </table>
</fieldset>