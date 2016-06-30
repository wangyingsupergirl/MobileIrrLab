<fieldset>
<table border="0" cellpadding="0" cellspacing="0" class="mainContactForm">
   <!--Evaluation Type Starts Here-->
    <tr>
        <td align="right">Evaluation Type*:</td>
        <td>
        <?php
        $table_name = 'eval_types_water_saving_types';
        $related_eval = $_SESSION['eval_stack']->top(2);
        //var_dump($eval);
        if ($eval->isTypeDetermined() == true) {
            //If Evaluation Type and Method is assigned already, then display Evaluation Type here
            $eval_type = $eval->getProperty('eval_type');
            Utility::setLookupTableToSession($table_name, null);
            $tuple = $_SESSION[$table_name][$eval_type];
            echo $tuple->getProperty('evaluation_type');
         }else{
            /*If Evaluaton Type and Method is not assigned yet.
             * 1. Case I: This Evaluation is a single evaluation, which means it isn't entered in a package,
             *            i.  users enter a follow up evaluation & the initial evaluation of it hasn't been enter to db
             *                then, the evaluation type must be initial evaluation
             *            ii. users enter a replacement evaluation & the last evaluation of it hasn't been enter to db
             *                then, the evaluation type can be initial/follow up evaluation
             * 2. Case II: This Evaluation is entered in a package.
             */
            
            
             if($related_eval!=false){ //Case I
                if($related_eval->getProperty('eval_type')==FOLLOW_UP_EVALUATION){ //Case I i?>
                <select id="eval_type" name="eval_type" >
                    <option value="1" selected>Initial</option>
                </select>
            <?php
                }else if($related_eval->getProperty('eval_type')==REPLACEMENT_EVALUATION){// Case I ii
            ?>
                <select id="eval_type" name="eval_type">
                    <option value="1" selected>Initial</option>
                    <option value="2" selected>Follow Up</option>
                </select>
            <?php
                }
            }else{//Case II
                ?>
                <select id="eval_type" name="eval_type" onChange ="evalTypeOnChange()">
                <option value="" selected>Choose one</option>
                <?php Utility::printOptions($table_name, null); ?>
                </select>
            <?php }
         } ?>
    </td>
    </tr>
    <!--Evaluation Type Ends Here-->
    <!--Evaluation Method Starts Here-->
    <tr>
        <td align="right">Evaluation Method*:</td>
        <td>
        <?php
        if ($eval->isTypeDetermined() == true) {
            
            $eval_method = $eval->getProperty('eval_method');
            
            if (trim($eval_method) == 'irr') {
                echo 'Irrigation System Only';
            } else if (trim($eval_method) == 'firm') {
                echo 'Firm';
            }
        } else {
            if ($related_eval!=false) {
                $eval_method = $related_eval->getProperty('eval_method');
                
                if (trim($eval_method) == 'irr') {
                    echo 'Irrigation System Only <input type="hidden" name="eval_method" value="irr">';
                } else if (trim($eval_method) == 'firm') {
                    echo 'Firm <input type="hidden" name="eval_method" value="firm">';
                }

            }else{ ?>
            
            <select id="eval_method" name="eval_method">
                <option value="" selected>Choose one</option>
                <option value="irr">Irrigation System Only</option>
                <option value="firm">FIRM</option>
            </select>
        <?php }
        } ?>
        </td>
        <td></td>
    </tr>
    <!--Evaluation Method Ends Here-->
<tr>
<td></td>
<td> 
<?php if ($eval->isTypeDetermined() == true) { ?>
<input class="button" type="submit"  id ="eval_type_delete" name="eval_type_delete" value="Edit Type and Method" title='Click here to edit evaluation type and(or) method. System will keep everything you have now.'/>
<?php } else { ?>
<input class="button" type="submit" id ="eval_type_submit" name="eval_type_submit" value="Submit" onclick="validateEvalTypeMethod()"/>
<?php } ?></td>
</tr>
</table>
</fieldset>
