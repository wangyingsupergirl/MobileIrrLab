var param=function(name,value){
 this.name=name;
 this.value=value;
}
var validateSearchForm = function(){
 $("#setting_form").validate({
  rules: {
   id:{
    digits:true,
    minlength: 13,
    maxlength: 13
   }, 
   root_depth:{
    digits:true,
    minlength: 13,
    maxlength: 13
   },
   soil_type:"required",
   lot_size:"required number",
   irr_tech:"required",
   zip_code:{
    required: true,
    digits: true,
    minlength: 5,
    maxlength: 5,
    min:32003,
    max:34997
   },
   active:"required"
  // ,irrigation_depth_method:"required"
  }

 });

}


////Now it is for replacement evaluation.
//when user clicks on the "calculate water saving" button in UI
var calculateWaterSaving = function(){
 //1. validate the form
 validateBeforeCalculation();
 var bool = $('#evalForm').valid();
 if(!bool) return false;
 //2.post a request to server 
 var data=new Array();
 var formElemList = $('#evalForm').serializeArray();
 for(var i=0;i<formElemList.length;i++){
  data[data.length]=new param(formElemList[i].name,formElemList[i].value);
 }
	 
 data[data.length]=new param('calculate_replacement_water_saving','');
 $.ajax({
  url : "control_evaluation.php",
  type : "POST",
  data: data,
  dataType:"json",
  success : function(resp) {
   //show result
   if(resp.hasOwnProperty("data")){
    var data = resp["data"];
    printWaterSavingResult(data);
   }else if(resp.hasOwnProperty("msg")){
               
  }
  },
  error : function(resp){
  //error message?
  }
 });
   
}
var printWaterSavingResult = function(data){
 for(var key in data){
  var val = data[key];
  $("#"+key).html(val);
 }
 $("#calculation_result").show("slow");
}

var canbeDeleted = function(entity, entityID){
 if(entity=='evaluation'){
  var parameter=new Array();
  parameter[parameter.length] = new param('eval_id',entityID);
  parameter[parameter.length] = new param('action','can_be_deleted');
  $.ajax({
   url : "../workspace_control.php",
   type : "POST",
   data : parameter,
   dataType : "json",
   success : function(resp) {
    showDeleteMessage(resp);
			
   },
   error : function(resp){
    showMessage(resp);
   }
  });
 }
}

var showDeleteMessage = function(resp){
 var inputbox;
 if(resp.hasOwnProperty("can_be_deleted")){
  if(resp["can_be_deleted"]==true){
   var name = "delete_evaluation:"+resp["eval_id"];
   inputbox = '<input class="button" type="submit" id="delete_yes" name="'+name+'" value="Yes"/>'+
   '<input class="button" type="button" id="delete_cancel" name="delete_cancel" value="Cancel" onclick=" disablePopup()"/>';
   $("#delete_form").html(inputbox);
  }else{
   inputbox ='<input class="button" type="button" id="delete_cancel" name="delete_cancel" value="Cancel" onclick=" disablePopup()"/>';
   $("#delete_form").html(inputbox);

  }
  $("#delete_msg_box").html(resp["msg"]);
  centerPopup();
  loadPopup(); 
 }
}