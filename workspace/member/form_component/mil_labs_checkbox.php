<?php

$mil_labs =Utility::getAllLab($member);
$selected_labs = null;

if($member){
    $selected_labs = $member->getPropertyByType('labs_id','array');
}
$cnt = 0;

foreach($mil_labs as $mil){
    if($cnt==0){
        echo '<div class="radioFormSmall">';
    }
    echo '<div class="radioButtonWrap">
        <input name="lab_id:'.$mil->getProperty('mil_id').'" value ="'.$mil->getProperty('mil_id').'" type="checkbox" class="checkBox"';
    if($selected_labs!=null && in_array($mil->getProperty('mil_id'),$selected_labs)){
            echo 'checked';
    }
    echo '>';
    echo '<label>'.$mil->getDisplayName().'</label></div>';
    $cnt++;

    if($cnt==4){
        $cnt=0;
        echo '</div>';
    }
}

if($cnt!=0){ // if number of mil lab is not 4x.
	echo '</div>';
}
			
?>