<?php 
require_once '../includes/mil_init.php';
require_once '../includes/utility.php'; //required constant.php in utility.php
session_start();
$member = false;
if(array_key_exists('MemberServed',$_SESSION)){
    $member = $_SESSION['MemberServed'];
}
$mil_labs = Utility::getLookupTable('mil_lab',null);
$contractor_id2lab_id = array();
foreach($mil_labs as $lab_id => $mil_lab){
    $contractor_id = $mil_lab->getProperty('contractor_id');
    if($contractor_id == NULL){
        continue;
    }
    if(array_key_exists($contractor_id,$contractor_id2lab_id)){
        $contractor_id2lab_id[$contractor_id] .= ",$lab_id";
    }else{
        $contractor_id2lab_id[$contractor_id] = "$lab_id";
    }
}
$contractor_name_js = "{";
foreach($contractor_id2lab_id as $key => $val){
    $contractor_name_js .="$key:'$val',";
}
$contractor_name_js = substr($contractor_name_js,0, strlen($contractor_name_js)-1);
$contractor_name_js .= "}";
$mil_lab_js = "{";
foreach($mil_labs as $key => $mil_lab){
  $mil_lab_name = $mil_lab->getDisplayName();
  $mil_lab_js .= "$key:' $mil_lab_name',";
}
$mil_lab_js = substr($mil_lab_js,0, strlen($mil_lab_js)-1);

$mil_lab_js .= "}";
$county_options = Utility::getOptions('fl_county',null);
if($member!=false){
$labs_counties = $member->getProperty('lab_county');
$lab_counties_js  = '{';
foreach($labs_counties as $lab_id => $counties){
  $lab_counties_js .= "$lab_id:' $counties',";
}
$lab_counties_js = substr($lab_counties_js,0, strlen($lab_counties_js)-1);
$lab_counties_js  .= '}';
}else{
    $lab_counties_js = '{}';
}
?>

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<title>State of Florida Mobile Irrigation Lab (MIL) Program - Contractor Sign Up</title>
<link rel="stylesheet" type="text/css" href="../styles/milStylesheet.css" />
<script src="../js/jquery-validate/lib/jquery.js" type="text/javascript"></script>
<script src="../js/jquery-validate/jquery.validate.js" type="text/javascript"></script>
<script src="../js/multiple_choices_list.js" type="text/javascript"></script>
<script type="text/javascript">
var mil_labs = <?php echo $mil_lab_js;?>;
var contractor_name2mil_labs = <?php echo $contractor_name_js;?>;
var labs_counties = <?php echo $lab_counties_js;?>;
var county_multiple_choices =[];
$().ready(function() {
    var contractor_name_id;
    contractor_name_id = $('#contractor_name').val();
      ids = getLabIDs(contractor_name_id);
      var html = getLabHtml(ids);
      $('#responsibleCounties').html(html);
      for(var i=0; i<ids.length; i++){
          var lab_id = ids[i];
          var name = lab_id + '_county';
          county_multiple_choices[i] = new MultipleChoicesList(name,'../images/iDelete.gif');
          county_multiple_choices[i].load();
      }

     $('#contractor_name').change(function() {
      contractor_name_id = $('#contractor_name').val();
      ids = getLabIDs(contractor_name_id);
      var html = getLabHtml(ids);
      $('#responsibleCounties').html(html);
      for(var i=0; i<ids.length; i++){
          var lab_id = ids[i];
          var name = lab_id + '_county';
          county_multiple_choices[i] = new MultipleChoicesList(name,'../images/iDelete.gif');
          county_multiple_choices[i].load();
      }
     
    });
});
function getLabIDs(contractor_name_id){
      if(contractor_name_id==""){
          return false;
      }
      var lab_ids = contractor_name2mil_labs[contractor_name_id];
      var lab_ids_arr = lab_ids.split(",");
      return lab_ids_arr;
}
function getLabHtml(lab_ids_arr){
    if(!lab_ids_arr){
        return 'Select contractor first.';
    }
    var html = '';
    var labs_id = '';
    for(var i=0; i< lab_ids_arr.length;i++){
        labs_id +=','+lab_ids_arr[i];
    }
    labs_id = labs_id.substring(1);
    var html = '<input name="labs_id" value="'+labs_id+'" type="hidden">';
    for(var i=0; i< lab_ids_arr.length;i++){
      var lab_id = lab_ids_arr[i];
      html += '<tr><td>' + mil_labs[lab_id] + ':&nbsp; </td>' ;
      html += '<td><select id="'+lab_id+'_county_dropdownlist"><option value="">Choose one</option>';
      html +=           '<?php echo $county_options;?>';
      html +=      '</select>'
             +'</td>';
      html += '<td><input type="button" id="'+lab_id+'_county_addbutton" name="'+lab_id+'_county_addbutton" value="Add County"/></td>'
              +'</tr>';
      if(lab_id in labs_counties){
         $input_value = labs_counties[lab_id];
      }else{
          $input_value = '';
      }
      html +='<tr>'+
              '<td>'
               +'<div class ="err_msg" id="'+lab_id+'_county_errmsgfield"> <!--Component IV display list-->'
               +'</div>'
               +'</td>'
              +'<td>'
               +'<div id="'+lab_id+'_county_displaylist">'
               +'</div>'
               +'<input id="'+lab_id+'_county" name="county:'+lab_id+'" type="hidden" value="'+$input_value+'" class="required" /><br /> '+"If counties are added, but this error message still doesn't go away, simply click submit button.<br />"
              +'<td>'+'</tr>';
    }
      return html;
}
</script>
</head>

<body id='login'>
    <div id="largeWrapper">
        <div id="header">
        <h1>State of Florida</h1>
        <p>Mobile Irrigation Laboratory Program</p>
    </div>

<div id="largeContentWrap">
<h2>MIL Contractor Sign Up</h2>
<div class="errMsg">
<?php
    if(array_key_exists('err',$_GET)){
        echo $_GET['err'];
    }
?>
</div>

<form action="./control.php" id ="contractor_signup_form" method="post" name="Login">
<fieldset>	
<table>
    <tr>
        <td>*Contractor Name: &nbsp;</td>
        <td>
        <select id="contractor_name" name="contractor_name">
        <option value="" selected>Choose one</option>
        <?php
            $table_name = 'contractor';
            Utility::printOptions($table_name,null);
        ?>
        </select>
        </td>
    </tr>
    <tr><td><input type="hidden" id="role" name="role" value="<?php echo CONTRACTOR_ROLE ?>"/></td></tr>
</table>
</fieldset>

<fieldset id="responsibleLabs">
    <h4>Responsible for MIL Lab(s):</h4>
    <!--Don't change name of div below!Used by the Javascript above-->
    <div id="responsibleCounties">
    </div>
</fieldset>

<fieldset>
    <h4>Contact Information</h4>
    <table border="0" cellpadding="0" cellspacing="0" class="mainContactForm">
        <tr>
        <td>*Business Address:</td>
        <td><input id="busi_addr" name="busi_addr" type="text" <?php if($member) echo "value='{$member->getProperty('busi_addr')}'";?> size="32" maxlength="32" /></td>
        <td>*Remittance Address:</td>
        <td><input id="remit_addr" name="remit_addr" <?php if($member) echo "value='{$member->getProperty('remit_addr')}'";?>  type="text" value="" size="32" maxlength="32" /></td>
        </tr>

        <tr>
        <td>* City:</td>
        <td><input id="busi_city" name="busi_city" type="text" <?php if($member) echo "value='{$member->getProperty('busi_city')}'";?> size="32" maxlength="32"/></td>
        <td>* City:</td>
        <td><input id="remit_city" name="remit_city" type="text" <?php if($member) echo "value='{$member->getProperty('remit_city')}'";?> size="32" maxlength="32"/></td>
        </tr>
        <tr>
        <td>* State:</td>
        <td><input id="busi_state" name="busi_state" type="text" <?php if($member) echo "value='{$member->getProperty('busi_state')}'";?> size="32" maxlength="32"/></td>
        <td>* State:</td>
        <td><input id="remit_state" name="remit_state" type="text" <?php if($member) echo "value='{$member->getProperty('remit_state')}'";?> size="32" maxlength="32"/></td>
        </tr>
        <tr>
        <td>* Zip:</td>
        <td><input id="busi_zip" name="busi_zip" type="text" <?php if($member) echo "value='{$member->getProperty('busi_zip')}'";?> size="5" maxlength="5"/></td>
        <td>* Zip:</td>
        <td><input id="remit_zip" name="remit_zip" type="text" <?php if($member) echo "value='{$member->getProperty('remit_zip')}'";?> size="5" maxlength="5"/></td>
        </tr>
        <tr>
        <td>Is business address<br /> the same with<br /> remittance address?</td>
        <td>
        <div class="radioButt memberBasic">
        <input type="radio" id = "same" name="same"/><label style="float:left;">Yes</label>
        </div>
        <div class="radioButt memberBasic">
        <input type="radio" id = "not_same" name="same"/> <label style="float:left;">No</label>
        </div>
        </td>
        </tr>
        <tr>
        <td>&nbsp;</td>
        <td colspan="3">&nbsp;</td>
        </tr>
        <?php require_once 'form_component/mem_basic_info.php';?>
        <?php require_once 'form_component/username_pwd.php';?>
        <tr>
        <td></td>
        <td colspan="3"></td>
        </tr>
    </table>
</fieldset>
<?php
if($member) 
    if($member->getProperty('admin_comments')!=''){
     echo "Disapprove Reason: {$member->getProperty('admin_comments')}";
    }
?>
<div class="form-btns" style="margin-top: 20px">
<input class="button" type="submit" id="submit_membership_application"  name="submit_membership_application" value="Submit"/>
<input class="button" type="submit" name="back_login" value="Cancel"/>
</div>

</form>

</div>
<span class="clearing"></span>
<div id="sponsorLogos">
<p>Sponsored by the State of FLorida, The St. Johns Water Management District, and The Florida Department of Agriculture</p>
</div>

</div>
<script type="text/javascript">
    $("#same").click(
        function(){
            $('#remit_addr').val( $('#busi_addr').val());
            $('#remit_city').val( $('#busi_city').val());
            $('#remit_state').val( $('#busi_state').val());
            $('#remit_zip').val( $('#busi_zip').val());
        }
    );
    $("#not_same").click(
        function(){
            $('#remit_addr').val("");
            $('#remit_city').val("");
            $('#remit_state').val("");
            $('#remit_zip').val("");
        }
    );
        
        $('#submit_membership_application').click(function(){
            $.validator.messages.required = "This field is required.";
            $.validator.messages.number = "{1} must be a number.";
            $.validator.messages.min = "The field should be greater or equal to {0}.";
            $.validator.messages.max = "The field should be less or equal to {0}.";
            $.validator.messages.equalTo = "The Re-enter password doesn't match password";
          
           var names = {
                contractor_name: 'Contractor Name'
                ,busi_addr: 'Business Address'
                ,remit_addr: 'Remittance Address'
                ,busi_city:'City in Business Address'
                ,remit_city: 'City in Remittance Address'
                ,busi_state:'State in Business Address'
                ,remit_state: 'State in Remittance Address'
                ,busi_zip:'Zip Code in Business Address'
                ,remit_zip:'Zip Code in Business Address'
                ,first_name:'First Name'
                ,last_name:'Last Name'
                ,title:'Title'
                ,phone:'Phone'
                ,fiscal_standard:'Fiscal Standard'
                ,username: 'User Name'
                ,password: 'Password'
                ,re_password:'Re-enter password'
                ,'county:18':'Counties'
             };
             
            $("#contractor_signup_form").validate({
                rules: {
                    contractor_name: {   
                        required:[true,names.contractor_name]
                    },
                    busi_addr: {   
                        required:[true,names.busi_addr]
                    },
                     remit_addr: {   
                        required:[true,names.remit_addr]
                    },
                    busi_city: {   
                        required:[true,names.busi_city]
                    },
                    remit_city: {   
                        required:[true,names.remit_city]
                    },
                    busi_state:{
                         required:[true,names.busi_state]
                    },
                    remit_state:{
                       required:[true,names.remit_state]
                    }, 
                    busi_zip: {   
                        required:[true,names.busi_zip],
                        digits:true,
                        minlength: 5,
                        maxlength: 5
                    },
                    remit_zip: {   
                        required:[true,names.remit_zip],
                        digits:true,
                        minlength: 5,
                        maxlength: 5
                    },
                    first_name:{
                        required:[true,names.first_name]
                    },
                    last_name:{
                        required:[true,names.last_name]
                    },
                    title:{
                        required:[true,names.title]
                    },
                    phone:{
                        required:[true,names.phone],
                        digits:true,
                        minlength: 10,
                        maxlength: 10
                    },
                    fiscal_standard:{
                        required:[true,names.fiscal_standard]
                    },
                    username:{
                        required:[true,names.username]
                        ,email:[true,names.username]
                    },
                    password:{
                        required:[true, names.password]
                    },
                    re_password:{
                        required:[true, names.re_password]
                        ,equalTo: "#password"
                    }
                   
                }
            });
        })
        
          
      $('#password').change(
        function(){
            var password = $('#password').val();
             $.post(
            "control.php",
            { password: password,  is_valid_password:"" },
            function(msg) {
            $('#password_err').html(msg);
            //jump to top of browser page, display error/suc msg (#profile_notification)to users
          
            
            });
        }
      )
        $('#username').change(
        function(){
            var username = $('#username').val();
           $.post(
            "control.php",
            { username: username,  is_username_available:"" },
            function(msg) {
            $('#username_err').html(msg);
            //jump to top of browser page, display error/suc msg (#profile_notification)to users
          
            
            });
        }
      )
  </script>
</script>
</body>
</html>
