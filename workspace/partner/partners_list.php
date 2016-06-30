<?php
require_once dirname(__FILE__) . '/../../includes/utility.php';
require_once dirname(__FILE__) . '/../../includes/constant.php';
require_once dirname(__FILE__) . '/../../includes/input/package/evaluation/FollowIrrSys.php';
require_once dirname(__FILE__) . '/../../includes/input/package/evaluation/InitIrrSys.php';
require_once dirname(__FILE__) . '/../../includes/input/package/evaluation/FollowFirm.php';
require_once dirname(__FILE__) . '/../../includes/input/package/evaluation/InitFirm.php';
?>
<div class="content" dojotype="dijit.layout.ContentPane" title="MIL Partners"
<?php echo(!array_key_exists('tab', $_SESSION) || $_SESSION['tab'] ==CONTRACTOR_TAB ? "selected" : "")  ?>>
<fieldset>

    <div>
     <form action="<?php echo MIL_SERVER_ROOT.'workspace/partner/';?>control.php" method="post">
      <table id="partner_name_table" border="0" cellpadding=0 cellspacing=0 class="subForms">
        <thead>
            <th>Partner ID</th>
            <th>Partner Name</th>
            <th>Operations</th>
         </thead>
         <?php
         $partners = Utility::getAllPartner();
         $i = 1;
         foreach ($partners as $id => $partner) {
            $btns = new Buttons($partner, $MemberServed->getProperty('role'));
            echo
            '<tr>
                <td>' . $partner->getProperty('id'). '</td>
                <td>' .  $partner->getProperty('name') . '</td>
                <td>'  .$btns->getString(). '</td>
             </tr>';
             $i++;
        }
         ?>

       </table>
         <?php if($MemberServedRole==ADMIN_ROLE){?>
       <input id="add_new_partner" class="button" type="button" name="add_new_contractor" value="Add New Partner Name"/>
         <?php } ?>
     </form>
      </div>
    </fieldset>
<script>
 //to be refactor
 $("#add_new_partner").click(
 function(){
  $("#partner_name_table").append("<tr><td></td><td>"
+'<input type="input" name="name" value=""/>'
+"</td><td>"+
 ' <input  class="button" name="add_partner" type="button" value="Add"/>'+
'<input  class="button" name="delete_partner" type="button" value="Delete"/>'
 +"</td></tr>");
 }
);
$("#partner_name_table").on('click','tr',
 function(event){
  var target = $(event.target);
  var btnType = target.attr("value");
  if(btnType=='Delete'){
   $(this).remove(); //this <tr>
  }else if(btnType=='Add'){
   //ajax save;
   var input = $(this).find("input[type='input']");
   var val = input.attr("value");
   //simple validation
   if(val.trim()==""){
    input.after("<div class='err'>This field shouldn't be blank</div>");
   }else{
    //save
    var action = target.attr("name");
    var array = {};
    array[action]='';
    array['name']=val;
    save(array);
   }
   
  }else if(btnType=='Edit'){
   target.type =  "button";
   var $tds = $(this).children();
   if($tds.length==3){
    var td1Val = $tds.eq(1).html();
    var td2Val = $tds.eq(2).html();
    $tds.eq(1).html('<input type="input" name="name" value="'+td1Val+'"/>')
    $tds.eq(2).html('<input  class="button" name="save_partner" type="button" value="Save"/>'+
    '<input  class="button" name="cancel" type="button" value="Cancel"/>');
    $("#partner_name_table").on('click','tr',function(event){
        target = $(event.target);
        btnType = target.attr("value");
        if(btnType=='Cancel'){
         $tds = $(this).children();
         if($tds.length==3){
          $tds.eq(1).html(td1Val);
           $tds.eq(2).html(td2Val);
         }
       }
    })
   }
 }else if(btnType=='Save'){
   var input = $(this).find("input[type='input']");
    var $tds = $(this).children();
   if($tds.length==3){
    var id = $tds.eq(0).html();
   }
  var val = input.attr("value");
   //simple validation
   if(val.trim()==""){
    input.after("<div class='err'>This field shouldn't be blank</div>");
   }else{
    //save
    var action = target.attr("name");
    var array = {};
    array[action]='';
    array['name']=val;
    array['id']=id;
    save(array);
   }
 }
 
 });
//function to save an entity
var save = function(inputArr) {
var data=inputArr;
//making the ajax call
$.ajax({
 url : "partner/control.php",
 type : "POST",
 data:data,
 dataType:"json",
 success : function(data) {
  //success reload table
  populateList(data);
 }
});


}

//function to populate the list of an entity

var populateList=function(resp){
 var htm = '';
 var data;
 if(resp==null){
  $('#partner_name_table').html("no response");
  return;
}
 if(resp.result==false){
  htm = resp.data;
 }else{
  data=resp.data;
   htm+=' <thead><th>Partner ID</th><th>Partner Name</th><th>Operations</th></thead>';
  for(var index in data){
   //creating a row
   htm+='<tr>';
   htm+='<td>'+data[index]['id']+'</td><td>'+data[index]['name']+'</td>';
   htm+='<td>'+ ' <input  class="button" name="edit_partner" type="button" value="Edit"/></td>'+'</tr>';
 }
   $('#partner_name_table').html(htm);
 }
}


</script>
</div>