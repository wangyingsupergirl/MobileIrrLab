<?php
function getLabs($member, $tag_name){ //'labs_id'
$mil_labs = Utility::getAllLab();
$selected_labs = null;
$html = '';
if($member){
    $selected_labs = $member->getPropertyByType($tag_name,'array');
}
$cnt = 0;

foreach($mil_labs as $mil){
    if($cnt==0){
        $html .= '<div class="radioFormSmall">';
    }
    $mil_id = $mil->getProperty('mil_id');
    $html .=  "<div class='radioButtonWrap'><input name='{$tag_name}[]' value ='$mil_id' type='checkbox' class='checkbox'";
    if($selected_labs!=null && in_array($mil_id,$selected_labs)){
           $html.='checked';
    }
    $html .= '>';
    $html .= '<label>'.$mil->getDisplayName().'</label></div>';
    $cnt++;

    if($cnt==4){
        $cnt=0;
        $html .= '</div>';
    }
}

if($cnt!=0){ // if number of mil lab is not 4x.
	$html .= '</div>';
}
return $html;
}		
?>
