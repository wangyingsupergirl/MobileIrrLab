<?php
require_once '../includes/mil_init.php';
try{
$partners = MIL::doQuery('select * from contractor',MYSQL_ASSOC);
}catch(Exception $e){
	echo $e->getMessage();
}?>
<?php

$cnt = 0;
foreach($partners as $contractor){
	if($cnt==0){ 
	echo '<div class="radioFormSmall">';
	}
	
	echo '<div class="radioButtonWrap"><!--<input class="contractors" value ="'.$contractor['id'].'" type="checkbox" class="checkBox">-->';
	echo '<div class="contractors"><input type="hidden" value= "'.$contractor['id'].'"><label class="radioButt memberBasic">'.$contractor['name'].'</label></div>';
	try{
		$sql = "select * from mil_lab where contractor_id =".$contractor['id'];
		$mil_labs = MIL::doQuery($sql, MYSQL_ASSOC);
	}catch(Exception $e){
		exit();
	}
	foreach($mil_labs as $mil){
	
		
		echo '<div id="lab_div'.$contractor['id'].'" class="radioButtonWrap" style="display:none"><input name="lab_id:'.$mil['mil_id'].'" value ="'.$mil['mil_id'].'" type="checkbox" class="checkBox"';
		if($selected_labs!=null && array_key_exists($mil['mil_id'],$selected_labs)){
			echo 'checked';
		}
		echo '>';
		echo '<label>'.$mil['mil_type'].' - '.$mil['mil_name'].'</label></div>';
		
	
	}	
	echo '</div>';
	$cnt++;
	
	if($cnt==2)
	{
		$cnt=0;
		echo '</div>';
	}

}
if($cnt!=0){ // if number of mil lab is not 4x.
	echo '</div>';
}
?>

<script>
$(".contractors").click(
function(){

var contractor_id = $("input",this).val();
var div_id = 'lab_div'+contractor_id;
//alert(div_id);
$("#"+div_id).show('slow');
}
)
</script>